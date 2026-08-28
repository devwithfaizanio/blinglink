<?php

use App\Http\Controllers\api\v1\admin\PromoCodeController;
use App\Http\Controllers\api\v1\auth\ForgotPasswordController;
use App\Http\Controllers\api\v1\auth\UserController;
use App\Http\Controllers\api\v1\CommunityController;
use App\Http\Controllers\api\v1\CommunityMessageController;
use App\Http\Controllers\api\v1\ConciergeController;
use App\Http\Controllers\api\v1\ConnectionChatController;
use App\Http\Controllers\api\v1\ConnectionController;
use App\Http\Controllers\api\v1\EventController;
use App\Http\Controllers\api\v1\MatchMakerChatController;
use App\Http\Controllers\api\v1\MentorChatController;
use App\Http\Controllers\api\v1\paymentController;
use App\Http\Controllers\api\v1\ReferralController;
use App\Http\Controllers\api\v1\ReportController;
use App\Http\Controllers\api\v1\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


Route::post('/check-user-availability', [UserController::class, 'CheckUserAvailability']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::prefix('forgot_password')->group(function() {
    Route::post('/send_otp', [ForgotPasswordController::class,'sendOtp']);
    Route::post('/verify_otp', [ForgotPasswordController::class,'VerifyOtp']);
    Route::post('/reset_password', [ForgotPasswordController::class,'ResetPassword']);
});




Route::group(['middleware' => ['auth:sanctum']], function(){
    //profile
    Route::get('/profile', [UserController::class, 'getProfile']);
    //update profile
    Route::post('/update-profile', [UserController::class, 'updateProfile']);


    Route::post('/switch-role', [UserController::class, 'switchRole']);
    Route::post('logout', [UserController::class, 'Logout']);
    Route::post('delete', [UserController::class, 'deleteUser']);
    Route::get('/users', [UserController::class, 'getUserList']);

    Route::get('/user/status-by-role', [UserController::class, 'getUserStatusByRole']);
    //store matchmaker profile
    Route::post('/matchmaker/profile', [UserController::class, 'storeMatchmakerProfile']);
    Route::post('/mentor/profile', [UserController::class, 'storeMentorProfile']);


    // Community routes
    Route::get('/communities', [CommunityController::class, 'index']);
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::get('/communities/{communityId}', [CommunityController::class, 'show']);
//    Route::put('/communities/{id}', [CommunityController::class, 'update']);
    Route::delete('/communities/{communityId}', [CommunityController::class, 'destroy']);
    Route::post('/communities/accept-decline', [CommunityController::class, 'acceptDecline']);

    // Community members routes
    Route::get('/communities/{communityId}/members', [CommunityController::class, 'getMembers']);
    Route::post('/communities/add_members', [CommunityController::class, 'addMember']);
    Route::post('/communities/remove_members', [CommunityController::class, 'removeMember']);

    // Community messages routes
    Route::get('/communities/{communityId}/messages', [CommunityMessageController::class, 'index']);
    Route::post('/communities/messages', [CommunityMessageController::class, 'store']);
    Route::delete('/communities/{communityId}/messages/{messageId}', [CommunityMessageController::class, 'destroy']);






    Route::prefix('events')->group(function() {
        Route::get('/all', [EventController::class, 'allEvents']);
        Route::post('/create', [EventController::class, 'store']);
        Route::get('/{eventId}', [EventController::class, 'show']);
        Route::post('/update/{eventId}', [EventController::class, 'update']);
        Route::delete('/delete/{eventId}', [EventController::class, 'destroy']);
        Route::post('/{eventId}/join', [EventController::class, 'join']);
        Route::post('/{eventId}/cancel-join', [EventController::class, 'cancelJoin']);
        Route::post('/{eventId}/update-status', [EventController::class, 'updateStatus']);
    });




    Route::prefix('connections')->group(function() {
        Route::get('/suggested', [ConnectionController::class, 'suggestedConnections']);
        Route::get('/', [ConnectionController::class, 'myConnections']);
        Route::post('/send', [ConnectionController::class, 'send']);
        Route::post('/accept', [ConnectionController::class, 'accept']);
        Route::post('/reject', [ConnectionController::class, 'reject']);
        Route::post('/cancel', [ConnectionController::class, 'cancel']);
    });


    Route::prefix('referrals')->group(function() {
        Route::get('/my-code', [ReferralController::class, 'getMyReferralInfo']);
        Route::post('/send-invite', [ReferralController::class, 'sendFriendInvite']);
    });


    Route::group(['prefix' => 'connection-chat'],function (){
        Route::get('/user-list',[ConnectionChatController::class,'ListUserChat']);
        Route::get('/chat/{id}',[ConnectionChatController::class,'ChatList']);
        Route::post('/send_message',[ConnectionChatController::class,'SendMessage']);
    });

    //connect to matchmaker
    Route::post('/connect-matchmaker', [paymentController::class, 'connectToMatchmaker']);
    Route::post('/connect-mentor', [paymentController::class, 'connectToMentor']);
    Route::get('/my-mentors', [paymentController::class, 'myMentors']);
    Route::get('/my-matchmakers', [paymentController::class, 'myMatchmakers']);

    Route::group(['prefix' => 'mentor-chat'],function (){
        Route::get('/user-list',[MentorChatController::class,'ListUserChat']);
        Route::get('/chat/{id}',[MentorChatController::class,'ChatList']);
        Route::post('/send_message',[MentorChatController::class,'SendMessage']);
    });

    Route::group(['prefix' => 'matchmaker-chat'],function (){
        Route::get('/user-list',[MatchMakerChatController::class,'ListUserChat']);
        Route::get('/chat/{id}',[MatchMakerChatController::class,'ChatList']);
        Route::post('/send_message',[MatchMakerChatController::class,'SendMessage']);
    });

    Route::post('/recommended-clubs',[ConciergeController::class,'recommendPlaces']);
    Route::get('/inspiration',[ConciergeController::class,'relationshipInspiration']);


    Route::get('generate_Ephemeral_Key', [StripeController::class,'generateEphemeralKey']);




    Route::post('/reports', [ReportController::class, 'store']);


    Route::prefix('admin')->middleware('role:admin')->group(function () {

        Route::get('/reports', [ReportController::class, 'index']);
        Route::post('/reports-update', [ReportController::class, 'update']);



        Route::group(['prefix' => 'users'],function (){
            Route::get('/list',[App\Http\Controllers\api\v1\admin\UserController::class,'allUsers']);
            Route::get('/detail/{userId}',[App\Http\Controllers\api\v1\admin\UserController::class,'singleUser']);
            Route::post('/verify',[App\Http\Controllers\api\v1\admin\UserController::class,'verifyUser']);
            Route::post('/account-status-update',[App\Http\Controllers\api\v1\admin\UserController::class,'accountStatusUpdate']);
            Route::get('/network', [App\Http\Controllers\api\v1\admin\UserController::class, 'netWork']);
        });
        Route::get('/matchmaker_list',[App\Http\Controllers\api\v1\admin\UserController::class,'getMatchmakers']);



        Route::group(['prefix' => 'blogs'], function () {
            Route::get('/list', [App\Http\Controllers\api\v1\admin\BlogController::class, 'index']);
            Route::get('/detail/{blogId}', [App\Http\Controllers\api\v1\admin\BlogController::class, 'show']);
            Route::post('/store', [App\Http\Controllers\api\v1\admin\BlogController::class, 'store']);
            Route::post('/update/{blogId}', [App\Http\Controllers\api\v1\admin\BlogController::class, 'update']);
            Route::delete('/delete/{blogId}', [App\Http\Controllers\api\v1\admin\BlogController::class, 'destroy']);
            Route::post('/change-status', [App\Http\Controllers\api\v1\admin\BlogController::class, 'changeStatus']);

        });


        Route::group(['prefix' => 'chats'], function () {
            Route::get('/connection_chats_user_list', [App\Http\Controllers\api\v1\admin\ChatsController::class, 'connectionChatsUserList']);
            Route::get('/connection_chat_list', [App\Http\Controllers\api\v1\admin\ChatsController::class, 'connectionChatList']);



            Route::get('/matchmaker_chats_user_list', [App\Http\Controllers\api\v1\admin\ChatsController::class, 'matchmakerChatsUserList']);
            Route::get('/matchmaker_chat_list', [App\Http\Controllers\api\v1\admin\ChatsController::class, 'matchmakerChatList']);


            Route::get('/mentor_chats_user_list', [App\Http\Controllers\api\v1\admin\ChatsController::class, 'mentorChatsUserList']);
            Route::get('/mentor_chat_list', [App\Http\Controllers\api\v1\admin\ChatsController::class, 'mentorChatList']);
        });




        Route::group(['prefix' => 'trophy'], function () {
            Route::get('/list', [App\Http\Controllers\api\v1\admin\TrophyController::class, 'index']);
            Route::post('/store', [App\Http\Controllers\api\v1\admin\TrophyController::class, 'store']);
            Route::post('/update', [App\Http\Controllers\api\v1\admin\TrophyController::class, 'update']);
            Route::post('/assign', [App\Http\Controllers\api\v1\admin\TrophyController::class, 'assignTrophy']);
            Route::post('/remove', [App\Http\Controllers\api\v1\admin\TrophyController::class, 'removeTrophy']);
        });

        Route::group(['prefix' => 'promo-codes'], function () {
            Route::get('/list', [PromoCodeController::class, 'index']);
            Route::get('/detail/{id}', [PromoCodeController::class, 'show']);
            Route::post('/store', [PromoCodeController::class, 'store']);
            Route::post('/update/{id}', [PromoCodeController::class, 'update']);
            Route::delete('/delete/{id}', [PromoCodeController::class, 'destroy']);
        });

    });

});




//Route::get('/pusher-test', function () {
//    broadcast(new \App\Events\CommunityMessageSent(
//        \App\Models\CommunityMessage::latest()->first()
//    ));
//
//    return response()->json([
//        'status' => 'Pusher event sent'
//    ]);
//});
