<?php


namespace App\Http\Controllers;
use App\SocialType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialAuthController extends BaseController
{
    function __construct()
    {
        Config::set('jwt.user', User::class);
        Config::set('jwt.identifier', 'user_id');
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ]]);
    }
    public function redirect(Request $request)
    {
        $social = $request->social;
        $currentURL = $request->currentURL;
        Session::put('currentURL', $currentURL);
        return Socialite::driver($social)->redirect();
    }

    public function callback($social)
    {
        $providerUser = Socialite::driver($social)->user();
        $user = User::find($providerUser->getId());
        if(!$user) {
            $sc = SocialType::where('social', $social)->first();

            $user = new User();
            $user->user_id = $providerUser->getId();
            $user->name = $providerUser->getName();
            $user->password = bcrypt($providerUser->getId());
            $user->social_id = $sc->social_id;
            $user->avatar = $providerUser->getAvatar().'&width=300&height=300';
            $user->save();
        }
        try {
            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'invalid_credentials', 'user' => $user], 401);
            } else {
                $currentURL = Session::get('currentURL');
                return redirect($currentURL.'?token='.$token);
            }
            return $token;
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }
}