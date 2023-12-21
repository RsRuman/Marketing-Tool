<?php

namespace Moveon\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\Customer\Models\Customer;
use Moveon\Customer\Services\ExportService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ExportController extends Controller
{
    private ExportService $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Export customers
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function exportCustomer(): JsonResponse
    {
        # Check authorization
        $this->authorize('exportCustomer', Customer::class);

        try {
            $output = $this->exportService->exportCustomers();

            $path = asset('storage/exports/' . $output);

            return Response::json([
                'path' => $path
            ], ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return Response::json([
                'error' => $ex->getMessage()
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Import customers
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function importCustomer(Request $request): JsonResponse
    {
        # Check authorization
        $this->authorize('importCustomer', Customer::class);

        try {
            $this->exportService->importCustomers($request->file('customers'));

            return Response::json([
                'message' => 'User imported successfully.'
            ], ResponseAlias::HTTP_OK);
        } catch (Exception $ex) {
            return Response::json([
                'error' => $ex->getMessage()
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
