<?php

namespace Moveon\Setting\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Moveon\Setting\Repositories\UserSettingRepository;

class UserSettingService
{
    private UserSettingRepository $userSettingRepository;

    public function __construct(UserSettingRepository $userSettingRepository)
    {
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * Get user setting
     * @return Authenticatable|null
     */
    public function getUserSetting(): ?Authenticatable
    {
        return $this->userSettingRepository->userAccount();
    }

    /**
     * Update user setting
     * @param $request
     * @return mixed
     */
    public function updateUserSetting($request): mixed
    {
        $data = $request->safe()->only('first_name', 'last_name', 'user_name', 'email');

        return $this->userSettingRepository->updateAccount($data);
    }

    /**
     * Create OTP
     * @return mixed
     */
    public function createOtp(): mixed
    {
        # Check OTP if already exist
        $otpExist = $this->userSettingRepository->getOtp();

        if ($otpExist) {
            return null;
        }

        $data = [
            'medium'     => Auth::user()->email,
            'token'      => rand(1000, 9999),
            'type'       => 'otp',
            'created_at' => now()
        ];

        return $this->userSettingRepository->createOtp($data);
    }

    /**
     * Set password
     * @param $request
     * @return false|mixed
     */
    public function setPassword($request): mixed
    {
        # Check OTP valid or not
        $otp = $this->userSettingRepository->getOtp();

        if ($otp && $otp->token === $request->input('otp')) {
            $data = [
                'password' => Hash::make($request->input('password')),
            ];

            return $this->userSettingRepository->updatePassword(Auth::user(), $data);
        }

        return false;
    }
}
