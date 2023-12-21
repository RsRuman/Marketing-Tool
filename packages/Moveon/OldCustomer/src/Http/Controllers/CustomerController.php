<?php

namespace Moveon\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\Customer\Http\Resources\CustomerResource;
use Moveon\Customer\Models\Customer;
use Moveon\Customer\Services\CustomerService;
use Moveon\Setting\Models\Lead;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CustomerController extends Controller
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * List of customers
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Lead::class);

        $customers = $this->customerService->getCustomers($request);

        # Transform data
        $customers = CustomerResource::collection($customers);

        # Prepare collection response for pagination
        $customers = $this->collectionResponse($customers);

        # Return response
        return Response::json($customers, ResponseAlias::HTTP_OK);
    }

    /**
     * Show customer with tags
     * @throws AuthorizationException
     */
    public function show($id): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Customer::class);

        $customer = $this->customerService->getCustomer($id);

        if (!$customer) {
            return Response::json([
                'error' => 'Could not found customer.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $customer = $customer->load('tags');

        $customer = new CustomerResource($customer);

        return Response::json([
            'data' => $customer
        ], ResponseAlias::HTTP_OK);
    }
}
