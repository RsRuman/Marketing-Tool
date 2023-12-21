<?php

namespace Moveon\Setting\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Moveon\Platform\Models\Platform;
use Moveon\Setting\Repositories\AccountRepository;

class AccountService
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Account details
     * @return Model|null
     */
    public function getAccountDetail(): ?Model
    {
        return $this->accountRepository->accountDetail();
    }

    /**
     * Update account
     * @param $request
     * @return false|mixed
     */
    public function updateAccountDetail($request): mixed
    {
        $originId = Auth::user()->origin->id;

        $platform =  Platform::query()->where('user_id', $originId)->first();

        if ($platform->type === 'shopify') {
            return false;
        }

        $data            = [];
        $data['name']    = $request->input('company_name');
        $data['email']   = $request->input('email');
        $data['domain']  = $request->input('store');
        $data['website'] = $request->input('website');

        return $this->accountRepository->updateAccount($platform, $data);
    }
}
