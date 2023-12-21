<?php

namespace Moveon\Conversation\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Moveon\Conversation\Http\Requests\ChatBaseParticipantRequest;
use Moveon\Conversation\Http\Requests\SmsBaseParticipantRequest;
use Moveon\Conversation\Http\Requests\UpdateParticipantAttributeRequest;
use Moveon\Conversation\Http\Requests\UpdateParticipantRequest;
use Moveon\Conversation\Services\ParticipantService;

class ParticipantController extends Controller
{
    private ParticipantService $participantService;

    public function __construct(ParticipantService $participantService)
    {
        $this->participantService = $participantService;
    }

    /**
     * Add a participant (SMS)
     * @param SmsBaseParticipantRequest $request
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function addConversationParticipantSms(SmsBaseParticipantRequest $request, $conversationSid): PromiseInterface|Response
    {
        # Add sms based participant
        return $this->participantService->addSmsBaseParticipantToConversation($request, $conversationSid);
    }

    /**
     * Add a participant (Chat)
     * @param ChatBaseParticipantRequest $request
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function addConversationParticipantChat(ChatBaseParticipantRequest $request, $conversationSid): PromiseInterface|Response
    {
        # Add sms based participant
        return $this->participantService->addChatBaseParticipantToConversation($request, $conversationSid);
    }

    /**
     * Fetch participant by sid
     * @param $conversationSid
     * @param $participantSid
     * @return PromiseInterface|Response
     */
    public function fetchParticipantBySid($conversationSid, $participantSid): PromiseInterface|Response
    {
        # Fetch conversation participant by sid
        return $this->participantService->fetchConversationParticipantBySid($conversationSid, $participantSid);
    }

    /**
     * Fetch participant by identity
     * @param $conversationSid
     * @param $identity
     * @return PromiseInterface|Response
     */
    public function fetchParticipantByIdentity($conversationSid, $identity): PromiseInterface|Response
    {
        # Fetch conversation participant by sid
        return $this->participantService->fetchConversationParticipantByIdentity($conversationSid, $identity);
    }

    /**
     * Fetch all participants of a conversation
     * @param $conversationSid
     * @return PromiseInterface|Response
     */
    public function fetchAllParticipant($conversationSid): PromiseInterface|Response
    {
        return $this->participantService->listOfConversationParticipants($conversationSid);
    }

    /**
     * Update a participant of a conversation
     * @param UpdateParticipantRequest $request
     * @param $conversationSid
     * @param $participantSid
     * @return PromiseInterface|Response
     */
    public function updateParticipant(UpdateParticipantRequest $request, $conversationSid, $participantSid): PromiseInterface|Response
    {
        return $this->participantService->updateConversationParticipant($request, $conversationSid, $participantSid);
    }

    /**
     * Update participant attribute
     * @param UpdateParticipantAttributeRequest $request
     * @param $conversationSid
     * @param $participantSid
     * @return PromiseInterface|Response
     */
    public function updateParticipantAttribute(UpdateParticipantAttributeRequest $request, $conversationSid, $participantSid): PromiseInterface|Response
    {
        return $this->participantService->updateConversationParticipantAttribute($request, $conversationSid, $participantSid);
    }

    /**
     * Delete a participant
     * @param $conversationSid
     * @param $participantSid
     * @return PromiseInterface|Response
     */
    public function deleteParticipant($conversationSid, $participantSid): PromiseInterface|Response
    {
        return $this->participantService->deleteConversationParticipant($conversationSid, $participantSid);
    }

    /**
     * List All of a Participant's Conversations
     * @param Request $request
     * @return PromiseInterface|Response
     */
    public function participantConversations(Request $request): PromiseInterface|Response
    {
        return $this->participantService->listOfParticipantConversations($request);
    }
}
