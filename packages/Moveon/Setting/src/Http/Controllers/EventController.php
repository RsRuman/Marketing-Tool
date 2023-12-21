<?php

namespace Moveon\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\Setting\Http\Requests\EventRequest;
use Moveon\Setting\Services\EventService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EventController extends Controller
{
    private EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Event store
     * @param EventRequest $request
     * @return JsonResponse
     */
    public function store(EventRequest $request): JsonResponse
    {
        $xSecreteToken = $request->header('x-secrete-token');

        $user = $this->eventService->checkAuthorization($xSecreteToken);

        if (!$user) {
            return Response::json([
                "message" => 'Unauthorized'
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $event = $this->eventService->storeEvent($request);

        if (!$event) {
            return Response::json([
                "message" => 'Could not initiate event'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            "message" => 'Event initiated successfully'
        ], ResponseAlias::HTTP_OK);
    }
}
