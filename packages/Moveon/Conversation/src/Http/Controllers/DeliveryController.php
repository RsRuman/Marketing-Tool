<?php

namespace Moveon\Conversation\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Moveon\Conversation\Services\DeliveryService;

class DeliveryController extends Controller
{
    private DeliveryService $deliveryService;

    public function __construct(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    /**
     * Read all receipts
     * @param Request $request
     * @param $conversationSid
     * @param $messageId
     * @return PromiseInterface|Response
     */
    public function index(Request $request, $conversationSid, $messageId): PromiseInterface|Response
    {
        return $this->deliveryService->readMultipleConversationMessageReceipt($request, $conversationSid, $messageId);
    }

    /**
     * Fetch a conversation message receipt
     * @param $conversationSid
     * @param $messageId
     * @param $receiptId
     * @return PromiseInterface|Response
     */
    public function show($conversationSid, $messageId, $receiptId): PromiseInterface|Response
    {
        return $this->deliveryService->fetchConversationMessageReceipt($conversationSid, $messageId, $receiptId);
    }

}
