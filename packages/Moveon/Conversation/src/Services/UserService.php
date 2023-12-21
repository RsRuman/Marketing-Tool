<?php

namespace Moveon\Conversation\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class UserService
{
    /**
     * List of all user conversations
     * @param $request
     * @param $userId
     * @return PromiseInterface|Response
     */
    public function listOfAllConversations($request, $userId): PromiseInterface|Response
    {
        $perPage = $request->input('per_page', 20);

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Users/' . $userId . '/' . 'Conversations' . '?PageSize=' . $perPage);
    }

    /**
     * Fetch a specific conversation
     * @param $userId
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function fetchConversation($userId, $conversationSid): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Users/' . $userId . '/' . 'Conversations/' . $conversationSid);
    }

    /**
     * Set conversation notification level
     * @param $request
     * @param $userId
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function setConversationNotificationLevel($request, $userId, $conversationSid): PromiseInterface|Response
    {
        $data = [];
        $data['NotificationLevel'] = $request->input('notification_level');

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->post(env('TWILIO_CONVERSATION_BASE_URL') . '/Users/' . $userId . '/' . 'Conversations/' . $conversationSid, $data);
    }

    /**
     * Remove a User from one of their Conversations
     * @param $userId
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function removeUserFromConversation($userId, $conversationSid): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->delete(env('TWILIO_CONVERSATION_BASE_URL') . '/Users/' . $userId . '/' . 'Conversations/' . $conversationSid);
    }
}
