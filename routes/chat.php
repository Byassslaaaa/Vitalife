<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Chat Routes
|--------------------------------------------------------------------------
|
| Routes untuk Chat System dengan Shopee-like features
| Includes: User chat, Admin panel, Quick replies, Ratings
|
*/

// User Chat Routes (requires authentication)
Route::middleware(['auth'])->group(function () {

    // Get or create conversation
    Route::get('/chat/conversation', [ChatController::class, 'getOrCreateConversation'])
        ->name('chat.conversation');

    // Send message (with smart AI routing)
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])
        ->name('chat.send');

    // Rate conversation after resolution
    Route::post('/chat/rate/{conversationId}', [ChatController::class, 'rateConversation'])
        ->name('chat.rate');

    // Check admin availability
    Route::get('/chat/admin-status', [ChatController::class, 'checkAdminActivityStatus'])
        ->name('chat.admin.status');
});

// Admin Chat Routes (requires admin role)
Route::middleware(['auth'])->prefix('admin/chat')->name('admin.chat.')->group(function () {

    // Admin panel view
    Route::get('/', [ChatController::class, 'showAdminChat'])
        ->name('index');

    // Get all conversations
    Route::get('/conversations', [ChatController::class, 'adminGetConversations'])
        ->name('conversations');

    // Get messages for specific conversation
    Route::get('/{conversationId}/messages', [ChatController::class, 'adminGetMessages'])
        ->name('messages');

    // Send message as admin
    Route::post('/{conversationId}/send', [ChatController::class, 'adminSendMessage'])
        ->name('send');

    // Resolve/close conversation
    Route::post('/{conversationId}/resolve', [ChatController::class, 'adminResolveConversation'])
        ->name('resolve');

    // Close conversation (deprecated)
    Route::post('/{conversationId}/close', [ChatController::class, 'adminCloseConversation'])
        ->name('close');

    // Reopen conversation
    Route::post('/{conversationId}/reopen', [ChatController::class, 'adminReopenConversation'])
        ->name('reopen');

    // Assign conversation to self
    Route::post('/{conversationId}/assign', [ChatController::class, 'adminAssignToSelf'])
        ->name('assign');

    // Transfer conversation to another admin
    Route::post('/{conversationId}/transfer', [ChatController::class, 'adminTransferConversation'])
        ->name('transfer');

    // Get canned responses (quick templates)
    Route::get('/canned-responses', [ChatController::class, 'getCannedResponses'])
        ->name('canned');

    // Get admin performance statistics
    Route::get('/stats', [ChatController::class, 'adminGetStats'])
        ->name('stats');

    // Update admin activity (heartbeat)
    Route::post('/activity', [ChatController::class, 'updateAdminActivityStatus'])
        ->name('activity');
});
