<?php

use Illuminate\Support\Facades\Route;
use Moveon\Conversation\Http\Controllers\ConversationController;
use Moveon\Conversation\Http\Controllers\DeliveryController;
use Moveon\Conversation\Http\Controllers\MessageController;
use Moveon\Conversation\Http\Controllers\ParticipantController;
use Moveon\Conversation\Http\Controllers\TwilioRestController;
use Moveon\Conversation\Http\Controllers\UserController;

Route::group(['prefix' => 'v1/'], function () {
    # Conversation webhook route
    Route::group(['prefix' => 'conversations'], function () {
        Route::post('/webhook', [TwilioRestController::class, 'handleRequest']);
    });

    Route::group(['prefix' => 'conversations', 'middleware' => ['auth:api']], function () {
        # Conversation routes
        Route::get('/', [ConversationController::class, 'index']);
        Route::post('/', [ConversationController::class, 'create']);
        Route::get('/{conversationSid}', [ConversationController::class, 'show']);
        Route::put('/{conversationSid}', [ConversationController::class, 'update']);
        Route::delete('/{conversationSid}', [ConversationController::class, 'destroy']);

        # Participant routes
        Route::post('/{conversationSid}/sms-participants', [ParticipantController::class, 'addConversationParticipantSms']);
        Route::post('/{conversationSid}/chat-participants', [ParticipantController::class, 'addConversationParticipantChat']);
        Route::get('/{conversationSid}/participants/{participantSid}', [ParticipantController::class, 'fetchParticipantBySid']);
        Route::get('/{conversationSid}/participants/{identity}', [ParticipantController::class, 'fetchParticipantByIdentity']);
        Route::get('/{conversationSid}/participants', [ParticipantController::class, 'fetchAllParticipant']);
        Route::put('/{conversationSid}/participants/{participantSid}', [ParticipantController::class, 'updateParticipant']);
        Route::put('/{conversationSid}/participants/{participantSid}/attributes', [ParticipantController::class, 'updateParticipantAttribute']);
        Route::delete('/{conversationSid}/participants/{participantSid}', [ParticipantController::class, 'deleteParticipant']);

        # Message routes
        Route::get('/{conversationSid}/messages', [MessageController::class, 'index']);
        Route::post('/{conversationSid}/messages', [MessageController::class, 'create']);
        Route::post('/{conversationSid}/messages/{messageId}', [MessageController::class, 'show']);
        Route::put('/{conversationSid}/messages/{messageId}', [MessageController::class, 'update']);
        Route::delete('/{conversationSid}/messages/{messageId}', [MessageController::class, 'destroy']);

        # Delivery receipt routes
        Route::get('/{conversationSid}/messages/{messageId}/receipts', [DeliveryController::class, 'index']);
        Route::get('/{conversationSid}/messages/{messageId}/receipts/{receiptId}', [DeliveryController::class, 'show']);
    });

    # User conversation routes
    Route::group(['prefix' => 'users', 'middleware' => ['auth:api']], function () {
        Route::get('/{userId}/conversations', [UserController::class, 'index']);
        Route::get('/{userId}/conversations/{conversationSid}', [UserController::class, 'show']);
        Route::post('/{userId}/conversations/{conversationSid}', [UserController::class, 'setNotificationLevel']);
        Route::delete('/{userId}/conversations/{conversationSid}', [UserController::class, 'destroy']);
    });

    # Participant conversation routes
    Route::get('/participant-conversations', [ParticipantController::class, 'participantConversations']);
});

