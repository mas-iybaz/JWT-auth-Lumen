<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Tymon\JWTAuth\JWTAuth;
// use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    public function register(Request $request)
    {
        // Validate Request
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        try {
            $user = new User;

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));

            $user->save();

            // successfull response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            // error response
            return response()->json(['message' => $e], 409);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        try {
            $credentials = $request->only('email', 'password');

            $token = Auth::attempt($credentials);

            if (!$token) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'FAILED', 'results' => $e], $e->getStatusCode());
        }

        return $this->respondWithToken($token);
    }
}
