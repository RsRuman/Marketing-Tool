<?php

namespace Moveon\EmailTemplate\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Moveon\EmailTemplate\Models\EmailTemplate;

class EmailTemplateRepository
{
    public function all($request): LengthAwarePaginator
    {
        $originId = Auth::user()->origin->id;

        $perPage = $request->input('per_page', 10);
        return EmailTemplate::query()
            ->where('user_id', $originId)
            ->when($request->input('name'), function ($query) use($request) {
                $query->where('name', 'like', '%'. $request->input('name') .'%');
            })
            ->when($request->input('sort_by'), function ($query) use($request) {
                if ($request->input('sort_by') === 'name') {
                    $query->orderBy('name', 'ASC');
                }

                if ($request->input('sort_by') === 'date') {
                    $query->orderBy('created_at', 'ASC');
                }
            })
            ->with(['createdBy', 'updatedBy'])
            ->paginate($perPage);
    }

    public function find($id): object|null
    {
        $originId = Auth::user()->origin->id;
        return EmailTemplate::query()
            ->where('id', $id)
            ->where('user_id', $originId)
            ->with('createdBy', 'updatedBy')
            ->first();
    }

    public function create($data): mixed
    {
        return EmailTemplate::create($data);
    }

    public function update($data, $template)
    {
        return $template->update($data);
    }

    public function delete($id): bool|null
    {
        return $this->find($id)->delete();
    }
}
