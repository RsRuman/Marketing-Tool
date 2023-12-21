<?php

namespace Moveon\Conversation\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class MessageService
{
    /**
     * Create a message to a conversation
     * @param $request
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function createConversationMessage($request, $conversationSid): PromiseInterface|Response
    {
        $data           = [];
        $data['Author'] = $request->input('author');
        $data['Body']   = $request->input('body');

        # TODO: Upload media file to s3 or any other file storage
        # TODO: Then get that url and assign to $mediaUrl
        # TODO: For twilio media service, sending media message is different. Follow doc
        # TODO: $data['MediaUrl'] = mediaUrl1,mediaUrl2;
        # TODO: $data['To'] = 'participantIdentity1,participantIdentity2';

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->post(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Messages', $data);
    }

    /**
     * Fetch a conversation message
     * @param $conversationSid
     * @param $messageId
     * @return PromiseInterface|Response
     */
    public function fetchConversationMessage($conversationSid, $messageId): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Messages/' . $messageId);
    }

    /**
     * Fetch all conversation message
     * @param $request
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function fetchAllConversationMessage($request, $conversationSid): PromiseInterface|Response
    {
        $perPage = $request->input('per_page', 20);
        $orderBy = $request->input('order_by', 'desc');

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Messages' . '?Order=' . $orderBy . '&PageSize=' . $perPage);
    }

    /**
     * Update a conversation message
     * @param $request
     * @param $conversationSid
     * @param $messageId
     * @return PromiseInterface|Response
     */
    public function updateConversationMessage($request, $conversationSid, $messageId): PromiseInterface|Response
    {
        $data           = [];
        $data['Author'] = $request->input('author');
        $data['Body']   = $request->input('body');

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->post(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Messages/' . $messageId, $data);
    }

    /**
     * Delete a conversation message
     * @param $conversationSid
     * @param $messageId
     * @return PromiseInterface|Response
     */
    public function deleteConversationMessage($conversationSid, $messageId): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->delete(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Messages/' . $messageId);
    }
}
