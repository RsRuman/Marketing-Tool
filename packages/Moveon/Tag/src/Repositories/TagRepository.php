<?php

namespace Moveon\Tag\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Moveon\Setting\Models\Lead;
use Moveon\Tag\Models\Tag;

class TagRepository
{
    public function all(): Collection|array
    {
        return Tag::query()->where('user_id', auth()->user()->origin->id)->get();
    }

    public function find($id): Model|null
    {
        return Tag::query()->where('user_id', auth()->user()->origin->id)->where('id', $id)->first();
    }

    public function create($data)
    {
        return Tag::create($data);
    }

    public function update($tag, $data) {
        return $tag->update($data);
    }

    public function delete($tag) {
        DB::table('lead_tag')->where('lead_id', $tag->id)->delete();
        return $tag->delete();
    }

    public function findLead($id): Model|null
    {
        $originId = Auth::user()->origin->id;
        return Lead::query()
            ->where('user_id', $originId)
            ->where('_id', $id)
            ->first();
    }

    public function updateTags($lead, $data) {
        return $lead->tags()->sync($data);
    }
}
