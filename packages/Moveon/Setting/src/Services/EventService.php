<?php

namespace Moveon\Setting\Services;

use Illuminate\Database\Eloquent\Model;
use Moveon\Setting\Repositories\EventRepository;

class EventService
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Check authorization
     * @param $xSecreteToken
     * @return Model|null
     */
    public function checkAuthorization($xSecreteToken): ?Model
    {
        return $this->eventRepository->find($xSecreteToken);
    }

    /**
     * Store event
     * @param $request
     * @return mixed
     */
    public function storeEvent($request): mixed
    {
        return $this->eventRepository->store($request->input('type'), $request->input('event'));
    }
}
