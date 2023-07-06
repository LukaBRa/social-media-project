<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Saved;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class UserController extends Controller
{
    public function store(Request $request) /*: RedirectResponse */ {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'profileImage' => 'required|image'
        ],[
            'name.required' => "Molimo Vas unesite vase ime",
            'email.required' => "Email adresa je obavezna",
            'email.email' => "Neispravna email adresa",
            'email.unique' => "Nalog sa unetom email adresom vec postoji",
            'password.required' => "Lozinka je obavezna",
            'password.min' => "Lozinka mora imati vise od 7 karaktera",
            'profileImage.image' => "Molimo Vas izaberite sliku",
            'profileImage.required' => "Molimo Vas izaberite sliku"
        ]);

        if($validator->fails()) {
            return redirect("/register")->withErrors($validator);
        }

        $userEmail = $request->input('email');

        $userDirPath = public_path() . "/images" . "/$userEmail";
        $userPostsPath  = $userDirPath . "/posts";
        
        File::makeDirectory($userDirPath);
        File::makeDirectory($userPostsPath);

        $userImage = $request->file('profileImage');
        $userImageName = $userImage->getClientOriginalName();
        $userImage->move($userDirPath, $userImageName);

        $newUser = new User;
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->image_name = $userImageName;
        $newUser->user_type = "guest";
        $newUser->reset_token = null;
        $newUser->save();

        Session::put('userId', $newUser->id);

        return redirect("/home");        
    }

    public function addAdmin(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ],[
            'name.required' => "Molimo Vas unesite vase ime",
            'email.required' => "Email adresa je obavezna",
            'email.email' => "Neispravna email adresa",
            'email.unique' => "Nalog sa unetom email adresom vec postoji",
            'password.required' => "Lozinka je obavezna",
            'password.min' => "Lozinka mora imati vise od 7 karaktera",
        ]);

        if($validator->fails()) {
            return redirect("/add-admin")->withErrors($validator);
        }

        $newUser = new User;
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->user_type = "administrator";
        $newUser->save();

        Session::put('userId', $newUser->id);

        return redirect("/add-admin");
    }

    public function login(Request $request): RedirectResponse {

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ],[
            'email.required' => "Email adresa je obavezna",
            'email.email' => "Neispravna email adresa",
            'password.required' => "Lozinka je obavezna"
        ]);

        if($validator->fails()){
            return redirect('/')->withErrors($validator);
        }

        $user = User::firstWhere('email', $request->email);

        if(!($user && $user->exists())){
            return  redirect('/')->withErrors(['msg' => 'Niste uneli ispravne podatke.']);    
        }

        if(!Hash::check($request->password, $user->password)){
            return  redirect('/')->withErrors(['msg' => 'Niste uneli ispravne podatke.']);
        }
        
        Session::put('userId', $user->id);

        if($user->user_type == "administrator"){
            return redirect("/dashboard");
        }

        return redirect("/home");
    }

    public function show(string $id) {
        if(!session('userId')){
            return redirect("/");
        }
        $user = User::firstWhere('id', $id);
        $numberOfPosts = $user->posts()->count();
        $numberOfFollowers = $user->followers()->count();
        $numberOfFollowed = $user->followed()->count();
        $posts = Post::where('user_id', $user->id)->get();
        $savedPosts = Saved::where('user_id', $id)->get();
        $numOfNewNotif = Notification::where('user_id', session('userId'))
                                 ->where('status', 'unread')
                                 ->count();
        $numOfNewMsg = Message::where('receiver_id', session('userId'))
                        ->where('status', "unread")
                        ->count();
        $followers = $user->followers;
        $followed = $user->followed;

        return Inertia::render('Profil', [
            'user' => $user,
            'numberOfPosts' => $numberOfPosts,
            'signedUserId' => session('userId'),
            'numberOfFollowers' => $numberOfFollowers,
            'numberOfFollowed' => $numberOfFollowed,
            'posts' => $posts,
            'savedPosts' => $savedPosts,
            'newNotifs' => $numOfNewNotif,
            'followers' =>$followers,
            'followed' => $followed,
            'newMsgCount' => $numOfNewMsg,
            'msg' => session('msg'),
        ]);
    }

    public function searchFriend(Request $request){

        $userId = $request->signedUserId;
        $users = User::where('id', '<>', $userId)
                    ->where('user_type', '<>', 'administrator')
                    ->where('name', 'like', '%'. $request->srcTxt . '%')
                    ->get();
        return json_encode($users);
    }

    public function getUser(Request $request){

        $user = User::firstWhere('id', $request->userId);

        return response()->json($user);
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'profileImage' => 'nullable|image',
            'password' => 'nullable|min:8'
        ], [
            'profileImage.image' => "Molimo Vas izaberite sliku",
            'password.min' => "Lozinka mora imati vise od 7 karaktera"
        ]);

        if($validator->fails()){
            return redirect("/settings")->withErrors($validator);
        }

        $user = User::firstWhere('id', session('userId'));

        if($request->file('profileImage') != null){

            $userEmail = $user->email;
            $userCurrentImageName = $user->image_name;
            $userDirPath = public_path() . "/images" . "/$userEmail";

            unlink($userDirPath . "/$userCurrentImageName");

            $image = $request->file('profileImage');
            $imageName = $image->getClientOriginalName();
            $image->move($userDirPath, $imageName);

            $user->image_name = $imageName;

        }

        if($request->name != null){
            $user->name = $request->name;
        }

        if($request->password != null){
            $user->password = $request->password;
        }

        $user->save();

        return redirect()->route('settings')->with(['msg' => 'Uspešna izmena']);
    }

    function resetPassword(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
            'confirmPassword' => 'required',
        ],[
            'email.required' => "Email adresa je obavezna",
            'email.email' => "Neispravna email adresa",
            'password.required' => "Lozinka je obavezna",
            'confirmPassword.required' => "Potvrdite lozinku"
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        $user = User::firstWhere('email', $request->email);

        if(!($user && $user->exists())){
            return  redirect()->back()->withErrors(['msg' => 'Niste uneli ispravnu email adresu.']);    
        }

        if($request->confirmPassword != $request->password){
            return  redirect()->back()->withErrors(['confirmPassword' => 'Lozinke se ne poklapaju']); 
        }

        $user->reset_token = null;
        $user->password = $request->password;
        $user->save();

        return redirect('/')->withErrors(['success' => 'Uspesno ste promenili lozinku.']);

    }

}
