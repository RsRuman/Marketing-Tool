<?php

namespace Moveon\Customer\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Moveon\Customer\Repositories\CustomerRepository;

class CustomerService
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get all customers
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getCustomers($request): LengthAwarePaginator
    {
        return $this->customerRepository->all($request);
    }

    public function createCustomer($data) {
        $this->customerRepository->create($data);
    }

    public function getCustomer($id): Model|null
    {
        return $this->customerRepository->find($id);
    }
}
