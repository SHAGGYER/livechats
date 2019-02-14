<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function init()
    {
        $user = Auth::user();

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password]))
        {
            $user = Auth::user();

            return response()->json($user, 200);
        }
        else
        {
            return response()->json(['error' => 'Could not log you in.']);
        }
    }

    public function register(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        {
            if (isset($user->id))
            {
                return response()->json(['error' => 'username_taken']);
            }
        }

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        Auth::login($user, true);

        return response()->json($user, 200);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(true, 200);
    }
}
