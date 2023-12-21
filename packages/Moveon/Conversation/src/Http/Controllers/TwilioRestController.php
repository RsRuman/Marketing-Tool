<?php

namespace Moveon\Conversation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Moveon\Conversation\Http\Requests\ChatBaseParticipantRequest;
use Moveon\Conversation\Http\Requests\ConversationCreateRequest;
use Moveon\Conversation\Http\Requests\SmsBaseParticipantRequest;
use Moveon\Conversation\Services\ConversationService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TwilioRestController extends Controller
{
    private ConversationService $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    public function handleRequest(Request $request): void
    {
        $from = $request->input('From');
        $body = $request->input('Body');

        # Initialize Twilio REST API parameters
        # This information we will save in database based platform
        $accountSid    = env('TWILIO_ACCOUNT_SID');
        $authToken     = env('twilio_auth_token');
        $twilioNumber  = env('TWILIO_PHONE_NUMBER');
        $twilioBaseURL = env('TWILIO_CONVERSATION_BASE_URL') . '/' . $accountSid;

        # Create or retrieve a conversation SID based on sender's phone number
        $conversationSid = $this->conversationService->getOrCreateConversation($from, $accountSid, $authToken, $twilioBaseURL);

        # Send the received message to the conversation using Twilio REST API
        $this->conversationService->sendMessageToConversation($conversationSid, $from, $body, $accountSid, $authToken, $twilioBaseURL);
    }
}
