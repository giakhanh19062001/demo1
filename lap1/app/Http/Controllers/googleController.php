<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class googleController extends Controller
{
    function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    function handleGoogleCallBack(){
        $user = Socialite::driver('google')->user();
        $findUser = User::where('google_id', $user->id)->first();
        if ($findUser) {
            Auth::login($findUser);
        }
        else{
            $user = User::updateOrCreate([
                'email'=>$user->email,
            ],[
                'name'=>$user->name,
                'google_id'=>$user->id,
                'password'=>bcrypt('123456789'),
            ]);
        }Auth::login($user);
        return redirect()->intended('home');
    }
}
    