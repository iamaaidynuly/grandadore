<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function loginViaMailru()
    {
        return Socialite::with('mailru')->redirect();
    }

    public function loginViaGoogle()
    {
        return Socialite::with('google')->redirect();
    }

    public function loginViaFacebook()
    {
        return Socialite::with('facebook')->redirect();
    }

    public function mailruWebHook(Request $request)
    {
        $mailruUser = Socialite::driver('mailru')->user();

        $email = $mailruUser->getEmail();
        $user = User::query()->where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name'     => $mailruUser->getName(),
                'email'    => $email,
                'password' => Hash::make($mailruUser->getId()),
            ]);

            $user->markEmailAsVerified();
        }

        Auth::loginUsingId($user->id);

        return redirect()->route('cabinet.profile');
    }

    public function googleWebHook(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::query()->where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'     => $googleUser->getName(),
                'email'    => $googleUser->getEmail(),
                'password' => Hash::make($googleUser->getId()),
            ]);

            $user->markEmailAsVerified();
        }

        Auth::loginUsingId($user->id, true);

        return redirect()->route('cabinet.profile');
    }

    public function facebookWebHook(Request $request)
    {
        $facebookUser = Socialite::driver('facebook')->user();

        $user = User::query()->where('email', $facebookUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'     => $facebookUser->getName(),
                'email'    => $facebookUser->getEmail(),
                'password' => Hash::make($facebookUser->getId()),
            ]);

            $user->markEmailAsVerified();
        }

        Auth::loginUsingId($user->id);

        return redirect()->route('cabinet.profile');
    }
}
