<?php

namespace Moveon\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Moveon\Setting\Events\SendOtpEvent;
use Moveon\Setting\Http\Requests\PasswordResetRequest;
use Moveon\Setting\Http\Requests\UserSettingUpdateRequest;
use Moveon\Setting\Http\Resources\UserSettingResource;
use Moveon\Setting\Services\UserSettingService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserSettingController extends Controller
{
    private UserSettingService $userSettingService;

    public function __construct(UserSettingService $userSettingService)
    {
        $this->userSettingService = $userSettingService;
    }

    /**
     * Get user setting
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
    {
        # Check authorization
        $this->authorize('viewUserSetting', User::class);

        # Get data
        $user = $this->userSettingService->getUserSetting();

        # Transform data
        $user = new UserSettingResource($user);

        return Response::json([
            'data' => $user
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Update account
     * @param UserSettingUpdateRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UserSettingUpdateRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('editUserSetting', User::class);

        # Update data
        $user = $this->userSettingService->updateUserSetting($request);

        if (!$user) {
            return Response::json([
                'error' => 'Could not update. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = Auth::user();

        # Transform data
        $user = new UserSettingResource($user);

        return Response::json([
            'data' => $user
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Send OTP
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function sendOtp(Request $request): JsonResponse
    {
        # Check authorization
        $this->authorize('resetPassword', User::class);

        $otp = $this->userSettingService->createOtp();

        if (!$otp) {
            return Response::json([
                'error' => 'Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Send OTP
        SendOtpEvent::dispatch(Auth::user(), $otp->token);

        # Return response
        return Response::json([
            'message' => 'OTP send successfully. Please check your mail.'
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Password reset
     * @param PasswordResetRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('resetPassword', User::class);

        # Update password
        $passwordU = $this->userSettingService->setPassword($request);

        if (!$passwordU) {
            return Response::json([
                'error' => 'Please provide valid OTP and try again later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Password reset successful.'
        ], ResponseAlias::HTTP_OK);
    }
}
