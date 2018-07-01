<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Google_Service_PhotosLibrary;
use App\User;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)
        ->scopes([
            'openid', 
            'profile', 
            'email',
            'https://www.googleapis.com/auth/photoslibrary.readonly', 
            Google_Service_PhotosLibrary::DRIVE_PHOTOS_READONLY,
            Google_Service_PhotosLibrary::PHOTOSLIBRARY,
            Google_Service_PhotosLibrary::PHOTOSLIBRARY_READONLY,
            Google_Service_PhotosLibrary::PHOTOSLIBRARY_SHARING
        ])
        ->redirect();

        // return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
        \Auth::login($authUser, true);

        return redirect('gallery');
        // $user->token;
    }


    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {   
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {


            $authUser->provider = $provider;
            $authUser->provider_id = $user->id;
            $authUser->provider_token = $user->token;
            $authUser->refresh_token = $user->accessTokenResponseBody['access_token'];
            $authUser->expires_in = $user->accessTokenResponseBody['expires_in'];

            $authUser->save();

            return $authUser;
        }

        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id,
            'provider_token' => $user->token,
            'refresh_token' => $user->accessTokenResponseBody['access_token'],
            'expires_in' => $user->accessTokenResponseBody['expires_in']
        ]);
    }
}
