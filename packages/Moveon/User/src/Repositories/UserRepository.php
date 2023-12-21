<?php

namespace Moveon\User\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Moveon\User\Models\TempData;

class UserRepository
{
    # All users
    public function all(): Collection
    {
        $originId = Auth::user()->origin ? Auth::user()->origin->id : auth()->user()->id;
        return User::query()
            ->where('origin_id', $originId)
            ->with('roles')
            ->get();
    }

    # Create user
    public function create($data) {
        return User::create($data);
    }

    # Generate token
    public function token($data):mixed {
        if (Auth::attempt($data)) {
            $user        = Auth::user();
            $accessToken = $user->createToken('MoveonMarketingTool')->accessToken;

            # Saving token to temp data
            $tempData = TempData::query()->where('user_id', $user->id)->first();
            $tempData?->update([
                'access_token' => $accessToken
            ]);

            return $accessToken;
        }

        return false;
    }

    # Find user
    public function find($id) {
        return User::query()->where('id', $id)->where('origin_id', auth()->user()->id)->first();
    }

    # Update user
    public function update($data, $user) {
        return $user->update($data);
    }

    # Sync roles
    public function syncRoles($data, $user) {
        return $user->syncRoles($data);
    }
}
