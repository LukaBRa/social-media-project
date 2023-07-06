<?php

use App\Events\NewMessage;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Saved;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Login');
});


Route::get('/register', function () {
    return Inertia::render('Register');
});
Route::post('register', [UserController::class, 'store']);

Route::post('/login', [UserController::class, 'login']);

Route::get('/reset-password', function() {
    return Inertia::render('Resetpasswordpage');
});

Route::post('/generate-reset-link', [MailController::class, 'sendMail']);

Route::get('/success', function() {
    return Inertia::render('Success');
});

Route::get('/new-password/{email}/{resetToken}', function(string $email, string $resetToken) {

    $user = User::firstWhere('email', $email);

    if($user->reset_token == null || $user->reset_token != $resetToken){
        return redirect("/");
    }

    return Inertia::render('Passwordform', [
        'email' => $email,
        'resetToken' => $resetToken,
    ]);
});

Route::post('/change-password', [UserController::class, 'resetPassword']);

Route::get('/logout', function() {

    Session::forget('userId');

    return redirect("/");
});

Route::get('/home', function () {
    
    if(!session('userId')){
        return redirect("/");
    }

    $signedUser = User::firstWhere('id', session('userId'));

    $suggestedFriends = User::whereNotIn('id', $signedUser->followed()->pluck('users.id')->toArray())
                            ->where('id', '<>', session('userId'))
                            ->where('user_type', '<>', 'administrator')
                            ->inRandomOrder()
                            ->limit(3)
                            ->get();

    $userFollowed = $signedUser->followed()->pluck('users.id');
    $posts = Post::whereIn('user_id', $userFollowed)->get();

    $numOfNewNotif = Notification::where('user_id', session('userId'))
                                 ->where('status', 'unread')
                                 ->count();
    $numOfNewMsg = Message::where('receiver_id', session('userId'))
                          ->where('status', "unread")
                          ->count();
    $lastSaved = Saved::where("user_id", $signedUser->id)->limit(4)->get();

    return Inertia::render('App', [
        'user' => $signedUser,
        'suggestedFriends' => $suggestedFriends,
        'posts' => $posts,
        'newNotifCount' => $numOfNewNotif,
        'newMsgCount' => $numOfNewMsg,
        'lastSaved' => $lastSaved
    ]);
});

Route::get('/profile/{id}', [UserController::class, 'show'])->name('profile');

Route::get('/addpost', function() {

    if(!session('userId')){
        return redirect("/");
    }

    $numOfNewNotif = Notification::where('user_id', session('userId'))
                                 ->where('status', 'unread')
                                 ->count();
    $numOfNewMsg = Message::where('receiver_id', session('userId'))
                        ->where('status', "unread")
                        ->count();

    return Inertia::render("Addpost", [
        'signedUserId' => session('userId'),
        'newNotifs' => $numOfNewNotif,
        'newMsgCount' => $numOfNewMsg
    ]);
});

Route::post('/addpost', [PostController::class, 'store']);

Route::get('/search', function() {

    if(!session('userId')){
        return redirect("/");
    }

    $numOfNewNotif = Notification::where('user_id', session('userId'))
                                 ->where('status', 'unread')
                                 ->count();
    $numOfNewMsg = Message::where('receiver_id', session('userId'))
                        ->where('status', "unread")
                        ->count();

    return Inertia::render("Search", [
        'user' => User::firstWhere('id', session('userId')),
        'newNotifs' => $numOfNewNotif,
        'newMsgCount' => $numOfNewMsg
    ]);
});

Route::get('/post/{id}', [PostController::class, 'show']);

Route::get("/settings", function() {
    if(!session('userId')){
        return redirect("/");
    }

    $numOfNewNotif = Notification::where('user_id', session('userId'))
                                 ->where('status', 'unread')
                                 ->count();
    $numOfNewMsg = Message::where('receiver_id', session('userId'))
                        ->where('status', "unread")
                        ->count();

    return Inertia::render("Settings", [
        'user' => User::firstWhere('id', session('userId')),
        'newNotifs' => $numOfNewNotif,
        'newMsgCount' => $numOfNewMsg,
        'msg' => session("msg"),
    ]);
})->name("settings");

Route::post("/user-update", [UserController::class, 'update'])->name('updateUser');

Route::get('/notifications', function() {
    if(!session('userId')){
        return redirect("/");
    }
    $user = User::firstWhere('id', session('userId'));
    $notifications = Notification::where('user_id', session('userId'))
                                ->where('status', 'unread')
                                ->get();
    $numOfNewMsg = Message::where('receiver_id', session('userId'))
                        ->where('status', "unread")
                        ->count();
    return Inertia::render('Notifications', [
        'user' => $user,
        'notifications' => $notifications,
        'newMsgCount' => $numOfNewMsg
    ]);
});

Route::get('/chat', function() {
    if(!session('userId')){
        return redirect("/");
    }
    $numOfNewNotif = Notification::where('user_id', session('userId'))
                                 ->where('status', 'unread')
                                 ->count();
    $user = User::firstWhere('id', session('userId'));
    $followed = $user->followed;
    return Inertia::render("Chat", [
        'user' => $user,
        'friends' => $followed,
        'newNotifs' => $numOfNewNotif,
    ]);
});

Route::get("/dashboard", function() {

    if(!session('userId')){
        return redirect("/");
    }

    return Inertia::render("Dashboard", [
        'admin' => User::firstWhere('id', session('userId')),
    ]);
});

Route::get("/add-admin", function() {
    if(!session('userId')){
        return redirect("/");
    }
    return Inertia::render("Addadmin", [
        'admin' => User::firstWhere('id', session('userId')),
    ]);
});

Route::post("/addadmin", [UserController::class, 'addAdmin']);

Route::get("/reported-posts", function() {

    if(!session('userId')){
        return redirect("/");
    }

    return Inertia::render("Reportedposts", [
        'admin' => User::firstWhere('id', session('userId')),
        'reportedposts' => Post::where('is_reported', true)->get()
    ]);
});

Route::get("/reported-comments", function() {

    if(!session('userId')){
        return redirect("/");
    }

    return Inertia::render('Reportedcomments', [
        'admin' => User::firstWhere('id', session('userId')),
        'reportedcomments' => Comment::where('is_reported', true)->get()
    ]);
});