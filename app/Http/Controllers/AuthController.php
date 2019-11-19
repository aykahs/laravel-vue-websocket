<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
class AuthController extends Controller
{
    //
    public function signup(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
    public function login(Request $request)
    {
        // return $request->all();
               
                $request->validate([
                    'email' => 'required|string|email',
                    'password' => 'required|string',

                ]);
                $credentials = request(['email', 'password']);
                if(!Auth::attempt($credentials))
                    return response()->json([
                        'message' => 'Unauthorized'
                    ], 401);
                $user = $request->user();
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();
                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'user'=>auth()->user()
                ]);

        // $http = new \GuzzleHttp\Client;
        // // dd( config('services.passport.login_endpoint'));
        // try {
        //     $response = $http->post("http://localhost/oauth/token", [
        //         'form_params' => [
        //             'grant_type' => 'password',
        //             'client_id' => config('services.passport.client_id'),
        //             'client_secret' => config('services.passport.client_secret'),
        //             'email' => $request->email,
        //             'password' => $request->password,
        //         ]
        //     ]);
        //     // dd($response);
        //     return $response->getBody();
        // } catch (\GuzzleHttp\Exception\BadResponseException $e) {
        //     if ($e->getCode() === 400) {
        //         return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
        //     } else if ($e->getCode() === 401) {
        //         return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
        //     }
        //     return response()->json('Something went wrong on the server.', $e->getCode());
        // }
    }
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
