<?php

namespace Moveon\Customer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Moveon\Customer\Repositories\CustomerRepository;

class CustomerImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $shopifyCustomers;

    /**
     * Create a new job instance.
     */
    public function __construct($shopifyCustomers)
    {
        $this->shopifyCustomers = $shopifyCustomers;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->shopifyCustomers) {
            foreach ($this->shopifyCustomers as $customer) {
                $data               = [
                    'customer_id'          => $customer['node']['id'],
                    'email'                => $customer['node']['email'],
                    'first_name'           => $customer['node']['firstName'],
                    'last_name'            => $customer['node']['lastName'],
                    'tags'                 => $customer['node']['tags'],
                    'phone'                => $customer['node']['phone'],
                    'last_order'           => $customer['node']['lastOrder'],
                    'amount_spent'         => $customer['node']['amountSpent'],
                    'number_of_orders'     => $customer['node']['numberOfOrders'],
                    'average_order_amount' => $customer['node']['averageOrderAmount'],
                    'customer_type'        => 'shopify',
                ];
                $customerRepository = new CustomerRepository();
                $customerRepository->create($data);
            }
        }
    }
}
