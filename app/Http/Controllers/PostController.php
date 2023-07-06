<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Comment;
use App\Models\Likes;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Saved;
use App\Models\User;
use Faker\Provider\Lorem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'image' => 'required|image'
        ],[
            'image.required' => "Odaberite sliku",
            'image.image' => "Neispravan format"
        ]);

        if($validator->fails()){
            return redirect("/addpost")->withErrors($validator);
        }

        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();

        ImageOptimizer::optimize($file->getPath());

        $user = User::firstWhere('id', session('userId'));

        $postPath = public_path() . "/images" . "/$user->email" . "/posts";
        $file->move($postPath, $fileName);

        $post = new Post;
        $post->user_id = session('userId');
        $post->image_name = $fileName;
        $post->description = $request->description;
        $post->save();

        return redirect()->route('profile', [session('userId')])->with(['msg' => 'Uspešno ste dodali objavu']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        if(!session('userId')){
            return redirect("/");
        }

        $post = Post::firstWhere('id', $id);
        $signedUser = User::firstWhere('id', session('userId'));
        $numOfNewNotif = Notification::where('user_id', session('userId'))
                                 ->where('status', 'unread')
                                 ->count();
        return Inertia::render("PostPage", [
            'post' => $post,
            'signedUser' => $signedUser,
            'user' => $post->user,
            'comments' => $post->comments,
            'newNotifs' => $numOfNewNotif
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $postId)
    {
        Post::where('id', $postId)->delete();
        Likes::where('post_id', $postId)->delete();
        Notification::where('post_id', $postId)->delete();
        Saved::where('post_id', $postId)->delete();

        return response()->json(['message' => 'success']);
    }

    public function stats(String $postId, String $signedUserId) {

        $post = Post::firstWhere('id', $postId);
        $user = User::firstWhere('id', $signedUserId);
        $liked = $user->liked($postId);
        $saved = Saved::where('user_id', $signedUserId)
                      ->where('post_id', $postId)
                      ->exists();
        $numberOfLikes = $post->numberOfLikes();
        $numberOfComments = $post->numberOfComments();
        $postData = array("numberOfLikes" => $numberOfLikes, "numberOfComments" => $numberOfComments, "liked" => $liked, "isSaved" => $saved);

        return response()->json($postData);
    }

    public function savePostController(String $postId, String $signedUserId) {

        $newSaved = new Saved;

        $newSaved->user_id = $signedUserId;
        $newSaved->post_id = $postId;
        $newSaved->save();

        return response()->json(['message' => 'success']);
    }

    public function unsavePostController(String $postId, String $signedUserId) {

        $savedPost = Saved::where('user_id', $signedUserId)
                          ->where('post_id', $postId)
                          ->delete();

        return response()-> json(['message' => 'success']);
    }

    public function getPost(String $postId){

        $post = Post::firstWhere('id', $postId);

        return response()->json($post);
    }

    public function report(Request $request){
        $post = Post::firstWhere('id', $request->postID);
        $post->is_reported = true;
        $post->save();

        return response()->json(['message' => 'Uspesno je prijavljena objava.']);
    }

    public function removeFromReported(Request $request){
        $post = Post::firstWhere('id', $request->postId);
        $post->is_reported = false;
        $post->save();

        return response()->json(['message' => 'Uspesno je uklonjena objava sa liste prijavljenih']);
    }

}
