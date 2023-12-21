<?php

namespace Moveon\Setting\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Moveon\Setting\Repositories\IntegrationRepository;

class IntegrationService
{
    private IntegrationRepository $integrationRepository;

    public function __construct(IntegrationRepository $integrationRepository)
    {
        $this->integrationRepository = $integrationRepository;
    }

    /**
     * Get all integrations
     * @return Collection|array
     */
    public function getIntegrations(): Collection|array
    {
        return $this->integrationRepository->all();
    }

    /**
     * Get an integration
     * @param $slug
     * @return Model|null
     */
    public function getIntegration($slug): ?Model
    {
        return $this->integrationRepository->find($slug);
    }
}
