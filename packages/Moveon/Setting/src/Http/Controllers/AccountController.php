<?php

namespace Moveon\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Moveon\Platform\Models\Platform;
use Moveon\Setting\Http\Requests\AccountUpdateRequest;
use Moveon\Setting\Http\Resources\AccountResource;
use Moveon\Setting\Services\AccountService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AccountController extends Controller
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Get account details
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Platform::class);

        # Get data
        $account = $this->accountService->getAccountDetail();

        # Transform data
        $account = new AccountResource($account);

        return Response::json([
            'data' => $account
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Update account
     * @param AccountUpdateRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(AccountUpdateRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('edit', Platform::class);

        # Update data
        $platform = $this->accountService->updateAccountDetail($request);

        if (!$platform) {
            return Response::json([
                'error' => 'Could not update account. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $platform = Auth::user()->platform;

        # Transform data
        $platform = new AccountResource($platform);

        # Return response
        return Response::json([
            'data' => $platform
        ], ResponseAlias::HTTP_OK);
    }
}
