<?php

namespace Moveon\Conversation\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ConversationService
{
    /**
     * Get conversations
     * @param $request
     * @return PromiseInterface|Response
     */
    public function getConversations($request): PromiseInterface|Response
    {
        $perPage = $request->input('per_page', 20);
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations?PageSize=' . $perPage);
    }

    /**
     * Create conversation to twilio
     * @param $request
     * @return PromiseInterface|Response
     */
    public function createConversation($request): PromiseInterface|Response
    {
        $data                 = [];
        $data['FriendlyName'] = $request->input('friendly_name');

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->post(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations', $data);
    }

    /**
     * Fetch conversation from twilio
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function fetchConversation($conversationSid): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid);
    }

    /**
     * Update a conversation
     * @param $request
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function updateConversation($request, $conversationSid): PromiseInterface|Response
    {
        $data                 = [];
        $data['FriendlyName'] = $request->input('friendly_name');

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->post(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid, $data);
    }

    /**
     * Delete a conversation
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function deleteConversation($conversationSid): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->delete(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid);
    }

    public function getOrCreateConversation($from, $accountSid, $authToken, $twilioBaseURL)
    {
        # Check if a conversation exists for the sender's phone number
        # Logic to retrieve or create conversation SID

        # Implement logic to retrieve or create a conversation SID based on the sender's phone number
        # This might involve checking a database or using Twilio Conversations API

        # return conversation id
    }

    public function sendMessageToConversation($conversationSid, $from, $body, $accountSid, $authToken, $twilioBaseURL): PromiseInterface|Response
    {
        // Send the received message to the conversation using Twilio REST API
        return Http::withBasicAuth($accountSid, $authToken)
            ->post("$twilioBaseURL/Conversations/$conversationSid/Messages.json", [
                'From' => $from,
                'Body' => $body,
            ]);
    }
}
