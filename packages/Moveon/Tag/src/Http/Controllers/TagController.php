<?php

namespace Moveon\Tag\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Moveon\Lead\Http\Resources\LeadResource;
use Moveon\Tag\Http\Requests\AttachTagRequest;
use Moveon\Tag\Http\Requests\TagStoreRequest;
use Moveon\Tag\Http\Requests\TagUpdateRequest;
use Moveon\Tag\Http\Resources\TagResource;
use Moveon\Tag\Models\Tag;
use Moveon\Tag\Services\TagService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TagController extends Controller
{
    private TagService $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * List of tags
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Tag::class);

        $tags = $this->tagService->getTags();

        # Transform tag
        $tags = TagResource::collection($tags);

        return Response::json([
            'data' => $tags
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Get tag
     * @throws AuthorizationException
     */
    public function show($id): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Tag::class);

        $tag = $this->tagService->getTag($id);

        if (!$tag) {
            return Response::json([
                'error' => 'Could not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Transform data
        $tag = new TagResource($tag);

        return Response::json([
            'data' => $tag
        ], ResponseAlias::HTTP_OK);

    }

    /**
     * Create tag
     * @throws AuthorizationException
     */
    public function store(TagStoreRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('create', Tag::class);

        $tag = $this->tagService->createTag($request);

        if (!$tag) {
            return Response::json([
                'error' => 'Could not create tag. Please try later!'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Transform data
        $tag = new TagResource($tag);

        return Response::json([
            'data' => $tag
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Update tag
     * @throws AuthorizationException
     */
    public function update(TagUpdateRequest $request, $id): JsonResponse
    {
        # Check authorization
        $this->authorize('create', Tag::class);

        $tag = $this->tagService->updateTag($request, $id);

        if (!$tag) {
            return Response::json([
                'error' => 'Could not update tag. Please try later!'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Transform data
        $tag = new TagResource($tag->fresh());

        return Response::json([
            'data' => $tag
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Delete tag
     * @throws AuthorizationException
     */
    public function destroy($id): JsonResponse
    {
        # Check authorization
        $this->authorize('delete', Tag::class);

        $tag = $this->tagService->deleteTag($id);

        if (!$tag) {
            return Response::json([
                'error' => 'Could not delete tag.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        return Response::json([
            'message' => 'Tag deleted successfully.'
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Attach tags with lead
     * @param AttachTagRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function attachLeadWithTag(AttachTagRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('attachTag', Tag::class);

        $lead = $this->tagService->attachTags($request);

        if (!$lead) {
            return Response::json([
                'error' => 'Lead not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Transform data
        $lead = new LeadResource($lead->load('tags'));

        return Response::json([
            'data' => $lead
        ], ResponseAlias::HTTP_OK);
    }
}
