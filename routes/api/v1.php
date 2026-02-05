<?php


use App\Http\Controllers\api\v1\auth\ForgotPasswordController;
use App\Http\Controllers\api\v1\auth\UserController;
use App\Http\Controllers\api\v1\CommunityController;
use App\Http\Controllers\api\v1\CommunityMessageController;
use App\Http\Controllers\api\v1\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


Route::post('/check-user-availability', [UserController::class, 'CheckUserAvailability']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);




Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/switch-role', [UserController::class, 'switchRole']);
    Route::post('logout', [UserController::class, 'Logout']);
    Route::get('/users', [UserController::class, 'getUserList']);

    Route::get('/user/status-by-role', [UserController::class, 'getUserStatusByRole']);
    //store matchmaker profile
    Route::post('/matchmaker/profile', [UserController::class, 'storeMatchmakerProfile']);


    // Community routes
    Route::get('/communities', [CommunityController::class, 'index']);
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::get('/communities/{communityId}', [CommunityController::class, 'show']);
//    Route::put('/communities/{id}', [CommunityController::class, 'update']);
    Route::delete('/communities/{communityId}', [CommunityController::class, 'destroy']);

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
