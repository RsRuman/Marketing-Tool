<?php

namespace Moveon\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Moveon\Customer\Jobs\CustomerImportJob;
use Moveon\Customer\Jobs\OrderImportJob;
use Moveon\Customer\Jobs\ProductImportJob;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ImportController extends Controller
{
    private $platform;
    private $shopToken;
    private $shopDomain;
    private int $perPage;
    private string $url;
    private bool $next;
    private $endCursor;

    /**
     * Set default data to request shopify
     * @param $request
     * @return void
     */
    public function setDefaultVariables($request): void
    {
        $this->platform   = $request->user()->platforms()->where('type', 'shopify')->first();
        $this->shopToken  = $this->platform->access_token;
        $this->shopDomain = $this->platform->domain;
        $this->perPage    = 5;
        $this->url        = "https://{$this->shopDomain}/admin/api/2023-07/graphql.json";
        $this->next       = true;
        $this->endCursor  = null;
    }

    /**
     * Customers query
     * @param $next
     * @param $endCursor
     * @return array
     */
    public function setCustomersQuery($next, $endCursor): array
    {
        $this->next      = $next;
        $this->endCursor = $endCursor;
        $variables       = [
            'perPage' => $this->perPage,
        ];

        if ($endCursor) {
            $variables['cursor'] = $endCursor;
        }

        return [
            'query'     => <<<'GRAPHQL'
            query($perPage: Int!, $cursor: String) {
            customers(first: $perPage, after: $cursor) {
            pageInfo {hasNextPage endCursor}
            edges {
            node {
            id
            email
            firstName
            lastName
            tags
            phone
            lastOrder {name}
            amountSpent {amount currencyCode}
            numberOfOrders
            averageOrderAmount
            }
            }
            }
            }
            GRAPHQL,
            'variables' => $variables,
        ];
    }

    /**
     * Orders query
     * @param $next
     * @param $endCursor
     * @return array
     */
    public function setOrdersQuery($next, $endCursor): array
    {
        $this->next      = $next;
        $this->endCursor = $endCursor;
        $variables       = [
            'perPage' => $this->perPage,
        ];

        if ($endCursor) {
            $variables['cursor'] = $endCursor;
        }

        return [
            'query'     => <<<'GRAPHQL'
            query($perPage: Int!, $cursor: String) {
                orders(first: $perPage, after: $cursor) {
                    pageInfo {hasNextPage endCursor}
                    edges {
                        node {
                            id
                            name
                            createdAt
                            currencyCode
                            discountCodes
                            displayFulfillmentStatus
                            fullyPaid
                            originalTotalAdditionalFeesSet {presentmentMoney {amount, currencyCode}, shopMoney {amount, currencyCode}}
                            tags
                            subtotalLineItemsQuantity
                            subtotalPriceSet {presentmentMoney {amount, currencyCode}, shopMoney {amount, currencyCode}}
                            totalDiscountsSet {presentmentMoney {amount, currencyCode}, shopMoney {amount, currencyCode}}
                            totalPriceSet {presentmentMoney {amount, currencyCode}, shopMoney {amount, currencyCode}}
                            totalReceivedSet {presentmentMoney {amount, currencyCode}, shopMoney {amount, currencyCode}}
                            totalShippingPriceSet {presentmentMoney {amount, currencyCode}, shopMoney {amount, currencyCode}}
                            totalTaxSet {presentmentMoney {amount, currencyCode}, shopMoney {amount, currencyCode}}
                            totalWeight
                            unpaid
                            customer {id}
                            customerLocale
                        }
                    }
                }
            }
            GRAPHQL,
            'variables' => $variables,
        ];
    }

    /**
     * Products query
     * @param $next
     * @param $endCursor
     * @return array
     */
    public function setProductsQuery($next, $endCursor): array
    {
        $this->next      = $next;
        $this->endCursor = $endCursor;
        $variables       = [
            'perPage' => $this->perPage,
        ];

        if ($endCursor) {
            $variables['cursor'] = $endCursor;
        }

        return [
            'query'     => <<<'GRAPHQL'
            query($perPage: Int!, $cursor: String) {
                products(first: $perPage, after: $cursor) {
                    pageInfo {hasNextPage endCursor}
                    edges {
                        node {
                            id
                            title
                            totalInventory
                            totalVariants
                            vendor
                            createdAt
                            description
                            isGiftCard
                            productType
                            productCategory {
                                productTaxonomyNode {
                                    id
                                    name
                                }
                            }
                            priceRangeV2 {
                                maxVariantPrice {
                                    amount
                                    currencyCode
                                }
                                minVariantPrice {
                                    amount
                                    currencyCode
                                }
                            }
                            publishedAt
                            onlineStoreUrl
                            onlineStorePreviewUrl
                            options {
                                id
                                name
                                position
                            }
                            featuredImage {
                                id
                                altText
                                height
                                url
                            }
                        }
                    }
                }
            }
            GRAPHQL,
            'variables' => $variables,
        ];
    }

    /**
     * Import customer
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function importCustomers(Request $request): JsonResponse
    {
        $this->setDefaultVariables($request);

        while ($this->next) {
            [$data, $this->next, $this->endCursor] = $this->__makeGlobRequest($this->url, $this->shopToken, $this->setCustomersQuery($this->next, $this->endCursor), 'customers');
            CustomerImportJob::dispatch($data);
        }

        return Response::json([
            'message' => 'Customer importing.'
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Import customer
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function importOrders(Request $request): JsonResponse
    {
        $this->setDefaultVariables($request);

        while ($this->next) {
            [$data, $this->next, $this->endCursor] = $this->__makeGlobRequest($this->url, $this->shopToken, $this->setOrdersQuery($this->next, $this->endCursor), 'orders');
            OrderImportJob::dispatch($data);
        }

        return Response::json([
            'message' => 'order importing'
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Import product
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function importProducts(Request $request): JsonResponse
    {
        $this->setDefaultVariables($request);

        while ($this->next) {
            [$data, $this->next, $this->endCursor] = $this->__makeGlobRequest($this->url, $this->shopToken, $this->setProductsQuery($this->next, $this->endCursor), 'products');
            ProductImportJob::dispatch($data, auth()->user()->origin->id);
        }

        return Response::json([
            'message' => 'Product importing'
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Make request to shopify admin graphql endpoint
     * @throws Exception
     */
    private function __makeGlobRequest($url, $shopToken, $query, $type): array
    {
        $response = Http::withHeaders([
            'Content-Type'           => 'application/json',
            'X-Shopify-Access-Token' => $shopToken,
        ])->post($url, $query);

        if ($response->successful()) {
            $response = $response->json()['data'][$type];
            return [$response['edges'], $response['pageInfo']['hasNextPage'], $response['pageInfo']['endCursor']];
        }
        throw new Exception('Failed');
    }

}
