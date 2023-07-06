<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Egulias\EmailValidator\Parser\CommentStrategy\CommentStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Following user routes */

Route::get("/follow", [FollowController::class, 'follow']);
Route::delete("/unfollow", [FollowController::class, 'unfollow']);
Route::get('/is-following', [FollowController::class, 'isFollowing']);
Route::get('/is-both-following', [FollowController::class, 'isBothFollowing']);

/* User routes */

Route::get('/search-friend', [UserController::class, 'searchFriend']);
Route::get('/get-user', [UserController::class, 'getUser']);

/* Like routes */

Route::get('/like-post/{signedUserid}/post/{postId}', [LikesController::class, 'likePostController']);
Route::get('/dislike-post/{signedUserid}/post/{postId}', [LikesController::class, 'dislikePostController']);


/* Post routes */

Route::get("/post/{id}/getPost", [PostController::class, 'getPost']);
Route::get("/post/{id}/stats/{signedUserId}", [PostController::class, 'stats']);
Route::get("/post/{id}/save/{signedUserId}", [PostController::class, 'savePostController']);
Route::get("post/{id}/unsave/{signedUserId}", [PostController::class, 'unsavePostController']);
Route::delete("/post/{id}/delete", [PostController::class, 'destroy']);
Route::post("/post/remove-reported", [PostController::class, 'removeFromReported']);
Route::post("/post-report", [PostController::class, 'report']);

/* Comment routes */

Route::get("/comment-store", [CommentController::class, 'store']);
Route::delete("/comment-delete", [CommentController::class, 'delete']);
Route::post("/comment-report", [CommentController::class, 'report']);
Route::post("/comment/remove-reported", [CommentController::class, 'removeFromReported']);

/* Notifications routes */

Route::get("/mark-notifications", [NotificationController::class, 'update']);

/* Messages routes */

Route::get("/get-last-message", [MessageController::class, 'getLastMessage']);
Route::get("/get-unread-count-single", [MessageController::class, 'getUnreadCountSingle']);
Route::post("/send-message", [MessageController::class, 'send']);
Route::get("/get-messages", [MessageController::class, 'getMessages']);
Route::get("/mark-all-read", [MessageController::class, 'markRead']);
Route::get("/mark-read", [MessageController::class, 'markReadOne']);