<?php

namespace Moveon\Conversation\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Moveon\Conversation\Http\Requests\ConversationCreateRequest;
use Moveon\Conversation\Services\ConversationService;

class ConversationController extends Controller
{
    private ConversationService $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    /**
     * List of conversations
     * @param Request $request
     * @return PromiseInterface|Response
     */
    public function index(Request $request): PromiseInterface|Response
    {
        # Get conversations
        return $this->conversationService->getConversations($request);
    }

    /**
     * Create conversation
     * @param ConversationCreateRequest $request
     * @return PromiseInterface|Response
     */
    public function create(ConversationCreateRequest $request): PromiseInterface|Response
    {
        # Create conversation
        return $this->conversationService->createConversation($request);
    }

    /**
     * Get a specific conversation
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function show($conversationSid): PromiseInterface|Response
    {
        return $this->conversationService->fetchConversation($conversationSid);
    }

    /**
     * Update a conversation
     * @param ConversationCreateRequest $request
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function update(ConversationCreateRequest $request, $conversationSid): PromiseInterface|Response
    {
        return $this->conversationService->updateConversation($request, $conversationSid);
    }

    /**
     * Delete a specific conversation
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function destroy($conversationSid): PromiseInterface|Response
    {
        # Delete a conversation
        return $this->conversationService->deleteConversation($conversationSid);
    }
}
