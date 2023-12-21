<?php

namespace Moveon\Conversation\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Moveon\Conversation\Http\Requests\CreateMessageRequest;
use Moveon\Conversation\Services\MessageService;

class MessageController extends Controller
{
    private MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * List of all conversation messages
     * @param Request $request
     * @return PromiseInterface|Response
     */
    public function index(Request $request, $conversationSid): PromiseInterface|Response
    {
        return $this->messageService->fetchAllConversationMessage($request, $conversationSid);
    }

    /**
     * Create a conversation message
     * @param CreateMessageRequest $request
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function create(CreateMessageRequest $request, $conversationSid): PromiseInterface|Response
    {
        return $this->messageService->createConversationMessage($request, $conversationSid);
    }

    /**
     * Fetch a conversation message
     * @param $conversationSid
     * @param $messageId
     * @return PromiseInterface|Response
     */
    public function show($conversationSid, $messageId): PromiseInterface|Response
    {
        return $this->messageService->fetchConversationMessage($conversationSid, $messageId);
    }

    /**
     * Update a conversation message
     * @param CreateMessageRequest $request
     * @param $conversationSid
     * @param $messageId
     * @return PromiseInterface|Response
     */
    public function update(CreateMessageRequest $request, $conversationSid, $messageId): PromiseInterface|Response
    {
        return $this->messageService->updateConversationMessage($request, $conversationSid, $messageId);
    }

    /**
     * Delete a conversation message
     * @param $conversationSid
     * @param $messageId
     * @return PromiseInterface|Response
     */
    public function destroy($conversationSid, $messageId): PromiseInterface|Response
    {
        return $this->messageService->deleteConversationMessage($conversationSid, $messageId);
    }
}
