<?php

namespace Moveon\Conversation\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ParticipantService
{
    /**
     * Added participant to a sms base conversation
     * @param $conversationSid
     * @param $request
     * @return PromiseInterface|Response
     */
    public function addSmsBaseParticipantToConversation($request, $conversationSid): PromiseInterface|Response
    {
        $data                                  = [];
        $data['MessagingBinding.Address']      = $request->input('phone'); # Your Personal Mobile Number
        $data['MessagingBinding.ProxyAddress'] = env('TWILIO_PHONE_NUMBER'); # Your Purchased Twilio Phone Number

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->post(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Participants', $data);
    }

    /**
     * Added participant to a chat base conversation
     * @param $request
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function addChatBaseParticipantToConversation($request, $conversationSid): PromiseInterface|Response
    {
        $data             = [];
        $data['Identity'] = $request->input('identity');

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->post(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Participants', $data);
    }

    /**
     * Fetch conversation participant by sid
     * @param $conversationSid
     * @param $participantSid
     * @return PromiseInterface|Response
     */
    public function fetchConversationParticipantBySid($conversationSid, $participantSid): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Participants/' . $participantSid);
    }

    /**
     * Fetch conversation participant by identity
     * @param $conversationSid
     * @param $identity
     * @return PromiseInterface|Response
     */
    public function fetchConversationParticipantByIdentity($conversationSid, $identity): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Participants/' . $identity);
    }

    /**
     * List of participants of a conversation
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function listOfConversationParticipants($conversationSid): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Participants');
    }

    public function updateConversationParticipant($request, $conversationSid, $participantSid): PromiseInterface|Response
    {
        $data                = [];
        $data['DateUpdated'] = $request->input('date_updated');

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->post(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Participants/' . $participantSid, $data);
    }

    /**
     * Update conversation participant attribute
     * @param $request
     * @param $conversationSid
     * @param $participantSid
     * @return PromiseInterface|Response
     */
    public function updateConversationParticipantAttribute($request, $conversationSid, $participantSid): PromiseInterface|Response
    {
        $data               = [];
        $data['Attributes'] = ['role' => $request->input('role')];

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->post(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Participants/' . $participantSid, $data);
    }

    /**
     * Delete a participant from a conversation
     * @param $conversationSid
     * @param $participantSid
     * @return PromiseInterface|Response
     */
    public function deleteConversationParticipant($conversationSid, $participantSid): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->delete(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Participants/' . $participantSid);
    }

    /**
     * List All of a Participant's Conversations
     * @param $request
     * @return PromiseInterface|Response
     */
    public function listOfParticipantConversations($request): PromiseInterface|Response
    {
        $perPage = $request->input('per_page', 20);

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') .'ParticipantConversations?PageSize=' . $perPage);
    }
}
