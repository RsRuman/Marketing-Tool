<?php

namespace Moveon\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Token;
use Laravel\Sanctum\PersonalAccessToken;
use Moveon\Platform\Http\Requests\ShopifyInstallRequest;
use Moveon\Platform\Http\Requests\ShopifyStoreRequest;
use Moveon\Platform\Http\Resources\TempDataResource;
use Moveon\Platform\Services\ShopifyService;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class ShopifyController extends Controller
{
    private ShopifyService $shopifyService;

    public function __construct(ShopifyService $shopifyService)
    {
        $this->shopifyService = $shopifyService;
    }

    /**
     * Install shopify
     * @param ShopifyInstallRequest $request
     * @return RedirectResponse
     */
    public function generateInstallLink(ShopifyInstallRequest $request): RedirectResponse
    {
        $scopes     = 'read_customers,read_orders,read_products,read_cart_transforms,read_discounts,write_discounts,read_draft_orders,read_gift_cards,write_gift_cards,read_locations';

        $redirectUri = env('SHOPIFY_REDIRECT_URI');

        $shopDomain = $request->input('shop');

        $installUrl = "https://{$shopDomain}/admin/oauth/authorize?client_id=" . env('SHOPIFY_API_KEY') . "&scope=$scopes&redirect_uri=$redirectUri";

        return redirect()->away($installUrl);
    }

    /**
     * Handling callback after install
     * @param ShopifyStoreRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function handleCallback(ShopifyStoreRequest $request): JsonResponse|RedirectResponse
    {
        $unInstallToken = '';

        try {
            DB::beginTransaction();

            $callback = $this->shopifyService->callback($request);

            if (isset($callback['error'])) {
                $unInstallToken = $callback['token'];
                throw new Exception($callback['error']);
            }

            if (!isset($callback['success']) && !$callback['data']) {
                throw new Exception('Could not set user');
            }

            if ($callback['success'] !== 'EXIST_USER') {
                $access_token = $callback['data']->user->createToken('MoveonMarketingTool')->accessToken;

                $data = [
                    'user_id' => $callback['data']->user->id,
                    'token' => $access_token
                ];

                $tempData = $this->shopifyService->createTempData($data);

                if (!$tempData) {
                    throw new Exception('Temp data not created');
                }
            }

            DB::commit();

            $tempData = $this->shopifyService->getTempData($callback['data']->user->id);

            $tempData = [
                'access_token' => $tempData->token
            ];

            # On success
            # TODO: return redirect to dashboard
            return redirect()->away(
                sprintf(
                    "%s?%s",
                    env('MARKETING_TOOL_SIGN_IN_URL'),
                    http_build_query($tempData)
                )
            );
        } catch (Exception $ex) {
            DB::rollBack();

            # UnInstall if eny error
            $this->uninstall($request->input('shop'), $unInstallToken);
            return redirect()->away(
                sprintf(
                    "%s?%s",
                    env('MARKETING_TOOL_SIGN_IN_URL'),
                    http_build_query(['error' => $ex->getMessage()])
                )
            );
        }
    }

    /**
     * Uninstall app
     * @param $shop
     * @param $shopToken
     * @return void
     */
    public function uninstall($shop, $shopToken): void
    {
        $uninstallUrl = "https://$shop/admin/apps/".env('SHOPIFY_API_KEY')."/uninstall";

        Http::withHeaders([
            'X-Shopify-Access-Token' => $shopToken,
        ])->delete($uninstallUrl);
    }
}


