<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;

class FacebookController extends Controller
{
    /**
     * Redirect to Facebook for authentication
     */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle callback from Facebook
     */
    public function callback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $facebookUser->getEmail()],
                [
                    'name' => $facebookUser->getName(),
                    'password' => bcrypt(uniqid()) // random password
                ]
            );

            // Login the user
            Auth::login($user);

            // Redirect home
            return redirect('/');

        } catch (Exception $e) {
            return redirect('/login')->withErrors([
                'facebook_error' => 'Facebook login failed. ' . $e->getMessage()
            ]);
        }
    }
}
