<?php

namespace Moveon\Setting\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserSettingRepository
{
    public function userAccount(): ?Authenticatable
    {
        return Auth::user();
    }

    public function updateAccount($data)
    {
        return Auth::user()->update($data);
    }

    public function getOtp()
    {
        return DB::table('password_resets')
            ->where('medium', Auth::user()->email)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->first();
    }

    public function createOtp($data)
    {
        $id = DB::table('password_resets')->insertGetId($data);

        return DB::table('password_resets')->find($id);
    }

    public function updatePassword($user, $data) {
        return $user->update($data);
    }
}
