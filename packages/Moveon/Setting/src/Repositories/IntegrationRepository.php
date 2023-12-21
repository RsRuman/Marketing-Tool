<?php

namespace Moveon\Setting\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Moveon\Setting\Models\Integration;

class IntegrationRepository
{
    public function all(): Collection|array
    {
        return Integration::query()
            ->where('status', Integration::STATUS_ACTIVE)
            ->get();
    }

    public function find($slug): Model|null
    {
        return Integration::query()
            ->where('slug', $slug)
            ->first();
    }
}
