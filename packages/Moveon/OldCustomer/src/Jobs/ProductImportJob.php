<?php

namespace Moveon\Customer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Moveon\Customer\Repositories\ProductRepository;

class ProductImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $shopifyProducts;
    private mixed $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($shopifyProducts, $userId)
    {
        $this->shopifyProducts = $shopifyProducts;
        $this->userId          = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->shopifyProducts) {
            foreach ($this->shopifyProducts as $product) {
                $data            = [
                    'user_id'                  => $this->userId,
                    'product_id'               => $product['node']['id'],
                    'title'                    => $product['node']['title'],
                    'total_inventory'          => $product['node']['totalInventory'],
                    'total_variants'           => $product['node']['totalVariants'],
                    'vendor'                   => $product['node']['vendor'],
                    'created_at'               => $product['node']['createdAt'],
                    'description'              => $product['node']['description'],
                    'is_gift_card'             => $product['node']['isGiftCard'],
                    'product_type'             => $product['node']['productType'],
                    'product_category'         => $product['node']['productCategory'],
                    'price_range'              => $product['node']['priceRangeV2'],
                    'published_at'             => $product['node']['publishedAt'],
                    'online_store_url'         => $product['node']['onlineStoreUrl'],
                    'online_store_preview_url' => $product['node']['onlineStorePreviewUrl'],
                    'options'                  => $product['node']['options'],
                    'feature_image'            => $product['node']['featuredImage'],
                ];
                $productRepository = new ProductRepository();
                $productRepository->create($data);
            }
        }
    }
}
