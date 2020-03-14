<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->middleware('auth');
    }

    //
    public function profile()
    {
        return response()->json(['results' => Auth::user()], 200);
    }

    public function users()
    {
        return response()->json(['results' => User::all()], 200);
    }

    public function user($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['results' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not Found', 'error' => $e], 404);
        }
    }
}
