<?php

namespace Moveon\Customer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Moveon\Customer\Repositories\OrderRepository;

class OrderImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $shopifyOrders;

    /**
     * Create a new job instance.
     */
    public function __construct($shopifyOrders)
    {
        $this->shopifyOrders = $shopifyOrders;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->shopifyOrders) {
            foreach ($this->shopifyOrders as $order) {
                $data            = [
                    'order_id'                           => $order['node']['id'],
                    'name'                               => $order['node']['name'],
                    'order_created_at'                   => $order['node']['createdAt'],
                    'currency_code'                      => $order['node']['currencyCode'],
                    'discount_code'                      => $order['node']['discountCodes'],
                    'display_fulfillment_status'         => $order['node']['displayFulfillmentStatus'],
                    'fully_paid'                         => $order['node']['fullyPaid'],
                    'original_total_additional_fees_set' => $order['node']['originalTotalAdditionalFeesSet'],
                    'tags'                               => $order['node']['tags'],
                    'sub_total_line_items_quantity'      => $order['node']['subtotalLineItemsQuantity'],
                    'subtotal_price_set'                 => $order['node']['subtotalPriceSet'],
                    'total_discounts_set'                => $order['node']['totalDiscountsSet'],
                    'total_price_set'                    => $order['node']['totalPriceSet'],
                    'total_received_set'                 => $order['node']['totalReceivedSet'],
                    'total_shipping_price_set'           => $order['node']['totalShippingPriceSet'],
                    'total_tax_set'                      => $order['node']['totalTaxSet'],
                    'total_weight'                       => $order['node']['totalWeight'],
                    'unpaid'                             => $order['node']['unpaid'],
                    'customer_id'                        => $order['node']['customer']['id'],
                    'customer_locale'                    => $order['node']['customerLocale'],
                ];
                $orderRepository = new OrderRepository();
                $orderRepository->create($data);
            }
        }
    }
}
