<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendMail(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
        ],[
            'email.required' => "Email adresa je obavezna",
            'email.email' => "Neispravna email adresa",
        ]);

        if($validator->fails()){
            return redirect('/reset-password')->withErrors($validator);
        }

        $user = User::firstWhere('email', $request->email);

        if(!($user && $user->exists())){
            return  redirect('/reset-password')->withErrors(['msg' => 'Nalog sa unetom email adresom ne postoji.']);    
        }

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); 
        $alphaLength = strlen($alphabet) - 1; 
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $resetToken = implode($pass); 

        $user->reset_token = $resetToken;
        $user->save();
        $link = "http://localhost:8000/new-password/" . $user->email . "/" . $resetToken;

        Mail::to($user->email)->send(new ResetPassword($link));

        return redirect("/success");
    }
}
