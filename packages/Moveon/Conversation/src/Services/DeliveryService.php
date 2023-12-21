<?php

namespace Moveon\Conversation\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class DeliveryService
{
    /**
     * Read multiple conversation message receipt
     * @param $request
     * @param $conversationSid
     * @param $messageId
     * @return PromiseInterface|Response
     */
    public function readMultipleConversationMessageReceipt($request, $conversationSid, $messageId): PromiseInterface|Response
    {
        $perPage = $request->input('per_page', 20);

        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Messages/' . $messageId . '/Receipts?PageSize=' . $perPage);
    }

    /**
     * Fetch a conversation message receipt
     * @param $conversationSid
     * @param $messageId
     * @param $receiptId
     * @return PromiseInterface|Response
     */
    public function fetchConversationMessageReceipt($conversationSid, $messageId, $receiptId): PromiseInterface|Response
    {
        return Http::withBasicAuth(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'))
            ->get(env('TWILIO_CONVERSATION_BASE_URL') . '/Conversations/' . $conversationSid . '/' . 'Messages/' . $messageId . '/Receipts/' . $receiptId);
    }
}
