<?php

namespace Moveon\Platform\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Moveon\Platform\Models\Platform;
use Moveon\Platform\Repositories\PlatformRepository;
use Moveon\Platform\Repositories\TempDataRepository;
use Moveon\User\Events\CreateAdminRoleEvent;
use Moveon\User\Events\SignUpUserUpdateEvent;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ShopifyService
{
    private PlatformRepository $platformRepository;

    public function __construct(PlatformRepository $platformRepository)
    {
        $this->platformRepository = $platformRepository;
    }

    /**
     * After shopify app install process
     * @param $request
     * @return array
     */
    public function callback($request): array
    {
        # Assigning data
        $code         = $request->input('code');
        $shop         = $request->input('shop');
        $receivedHmac = $request->input('hmac');
        $redirectUri  = env('SHOPIFY_REDIRECT_URI');

        # Calculate HMAC
        $calculatedHmac = hash_hmac('sha256', http_build_query($request->except('hmac')), env('SHOPIFY_API_SECRET'));

        if ($calculatedHmac !== $receivedHmac) {
            return [
                'error' => 'HMAC_VERIFICATION_FAILED',
                'code'  => ResponseAlias::HTTP_BAD_REQUEST
            ];
        }

        # Perform token exchange using the authorization code
        $response = Http::post("https://$shop/admin/oauth/access_token", [
            'client_id' => env('SHOPIFY_API_KEY'),
            'client_secret' => env('SHOPIFY_API_SECRET'),
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ]);

        $platformData = $response->json();

        # Get shopify store info
        $shopDetails  = $this->getShopDetails($shop, $platformData['access_token']);

        if (!$shopDetails) {
            return [
                'error' => 'SHOP_DETAILS_FAILED',
                'code'  => ResponseAlias::HTTP_BAD_REQUEST,
                'token' => $platformData['access_token']
            ];
        }

        $existUser = $this->platformRepository->findByEmail($shopDetails['shop']['email']);

        # Check exist user
        if ($existUser) {
            return [
                'success' => 'EXIST_USER',
                'data'    => $existUser->load('user'),
                'code'    => ResponseAlias::HTTP_OK
            ];
        }

        $platformData['type']         = Platform::SHOPIFY;
        $platformData['email']        = $shopDetails['shop']['email'];
        $platformData['domain']       = $shopDetails['shop']['domain'];
        $platformData['shop_id']      = $shopDetails['shop']['id'];
        $platformData['website']      = 'https://' . $shopDetails['shop']['domain'];
        $platformData['name']         = $shopDetails['shop']['name'];

        $data = [
            'user_name' => $platformData['name'],
            'email'     => $platformData['email'],
            'password'  => Hash::make(Str::random(16))
        ];

        # Create user
        $user = $this->platformRepository->createUser($data);

        if (!$user) {
            return [
                'error' => 'CREATE_USER_FAILED',
                'code'  => ResponseAlias::HTTP_BAD_REQUEST,
                'token' => $platformData['access_token']
            ];
        }

        $data = [
            'name'       => 'admin',
            'guard_name' => 'api',
            'user_id'    => $user->id
        ];

        # Create role
        $role = $this->platformRepository->createUserRole($data);

        if (!$role) {
            return [
                'error' => 'CREATE_USER_ROLE_FAILED',
                'code'  => ResponseAlias::HTTP_BAD_REQUEST,
                'token' => $platformData['access_token']
            ];
        }

        # Assign role
        $user->assignRole($role);

        # Assign permissions to this user role
        event(new CreateAdminRoleEvent($user));
        # Update user
        event(new SignUpUserUpdateEvent($user));

        $platformData['user_id'] = $user->id;

        # Create platform
        $platform = $this->platformRepository->create($platformData);

        if (!$platform) {
            return [
                'error' => 'CREATE_PLATFORM_FAILED',
                'code'  => ResponseAlias::HTTP_BAD_REQUEST,
                'token' => $platformData['access_token']
            ];
        }

        return [
            'success' => 'successfully platform created.',
            'data' => $platform,
            'code' => ResponseAlias::HTTP_CREATED
        ];
    }

    /**
     * Get shop details
     * @param $shop
     * @param $accessToken
     * @return array|false|mixed
     */
    private function getShopDetails($shop, $accessToken): mixed
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get("https://$shop./admin/api/2023-07/shop.json");

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }

    /**
     * Create temp data
     * @param $data
     * @return mixed
     */
    public function createTempData($data): mixed
    {
        $tempDataRepository = new TempDataRepository();
        return $tempDataRepository->create($data);
    }

    /**
     * Get temp data by user id
     * @param $userId
     * @return Model|Builder|null
     */
    public function getTempData($userId): Model|Builder|null
    {
        $tempDataRepository = new TempDataRepository();
        return $tempDataRepository->findByUserId($userId);
    }
}
