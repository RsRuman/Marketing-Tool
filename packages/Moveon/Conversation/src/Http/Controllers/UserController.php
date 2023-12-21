<?php

namespace Moveon\Conversation\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Moveon\Conversation\Http\Requests\NotificationLevelRequest;
use Moveon\Conversation\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * List of all a user conversations
     * @param Request $request
     * @param $userId
     * @return PromiseInterface|Response
     */
    public function index(Request $request, $userId): PromiseInterface|Response
    {
        return $this->userService->listOfAllConversations($request, $userId);
    }

    /**
     * Fetch a specific conversation
     * @param $userId
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function show($userId, $conversationSid): PromiseInterface|Response
    {
        return $this->userService->fetchConversation($userId, $conversationSid);
    }

    /**
     * Set notification level
     * @param NotificationLevelRequest $request
     * @param $userId
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function setNotificationLevel(NotificationLevelRequest $request, $userId, $conversationSid): PromiseInterface|Response
    {
        return $this->userService->setConversationNotificationLevel($request, $userId, $conversationSid);
    }

    /**
     * Remove a User from one of their Conversations
     * @param $userId
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function destroy($userId, $conversationSid): PromiseInterface|Response
    {
        return $this->userService->removeUserFromConversation($userId, $conversationSid);
    }
}
