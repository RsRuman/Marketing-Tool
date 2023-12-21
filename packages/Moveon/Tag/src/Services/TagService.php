<?php

namespace Moveon\Tag\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Moveon\Tag\Repositories\TagRepository;

class TagService
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Get all tags
     * @return Collection|array
     */
    public function getTags(): Collection|array
    {
        return $this->tagRepository->all();
    }

    public function getTag($id): ?Model
    {
        return $this->tagRepository->find($id);
    }

    public function createTag($request)
    {
        # Sanitize data
        $data            = $request->safe()->only('name');
        $data['slug']    = $request->input('name');
        $data['user_id'] = Auth::user()->origin->id;

        return $this->tagRepository->create($data);
    }

    public function updateTag($request, $tagId) {
        $tag = $this->tagRepository->find($tagId);

        if (!$tag) {
            return null;
        }

        $data = $request->safe()->only('name');
        $data['slug'] = $request->input('name');

        $this->tagRepository->update($tag, $data);

        return $tag;
    }

    /**
     * Delete tag
     * @param $id
     * @return mixed|null
     */
    public function deleteTag($id): mixed
    {
        $tag = $this->tagRepository->find($id);

        if (!$tag) {
            return null;
        }

        return $this->tagRepository->delete($tag);
    }

    /**
     * Attach tags
     * @param $request
     * @return Model|null
     */
    public function attachTags($request): ?Model
    {
        $lead = $this->tagRepository->findLead($request->input('lead_id'));

        if (!$lead) {
            return null;
        }

        $this->tagRepository->updateTags($lead, $request->input('tag_ids'));

        return $lead;
    }
}
