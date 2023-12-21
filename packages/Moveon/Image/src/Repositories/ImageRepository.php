<?php

namespace Moveon\Image\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Moveon\Image\Models\Image;

class ImageRepository
{
    public function all($request): LengthAwarePaginator
    {
        $originId = Auth::user()->origin->id;
        $perPage  = $request->input('per_page', 10);

        return Image::query()
            ->where('user_id', $originId)
            ->when($request->input('name'), function ($query) use($request) {
                $query->where('name', 'like', '%'. $request->input('name'). '%');
            })
            ->when($request->input('category'), function ($query) use($request) {
                $query->whereHas('categories', function ($query) use($request) {
                    $query->where('categories.id', $request->input('category'));
                });
            })
            ->when($request->input('sort_by'), function ($query) use($request) {
                if ($request->input('sort_by') === 'name') {
                    $query->orderBy('name', 'ASC');
                }

                if ($request->input('sort_by') === 'date') {
                    $query->orderBy('created_at', 'ASC');
                }
            })
            ->with(['categories', 'createdBy', 'updatedBy'])
            ->latest()
            ->paginate($perPage);
    }

    public function create($data): mixed
    {
        return Image::create($data);
    }

    public function find($id): Model|Collection|Builder|array|null
    {
        $originId = Auth::user()->origin->id;
        return Image::query()
            ->where('id', $id)
            ->where('user_id', $originId)
            ->with(['categories', 'createdBy', 'updatedBy'])
            ->first();
    }

    public function update($image, $data) {
        $image->update(['updated_by_id' => Auth::user()->id]);
        return $image->categories()->sync($data);
    }

    public function delete($image) {
        $categoryIds = $image->categories()->pluck('categories.id')->toArray();
        $image->categories()->detach($categoryIds);
        Storage::delete('public/'.$image->link);
        return $image->delete();
    }
}
