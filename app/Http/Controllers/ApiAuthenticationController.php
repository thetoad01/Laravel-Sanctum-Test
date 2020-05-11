<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use app\User;

class ApiAuthenticationController extends Controller
{
    public function login(Request $request)
    {
        if (!$request->email || !$request->password) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Bad Request',
            ]);
        }

        // Authenticate user
        try {
        	$request->validate([
        	    'email' => 'required|email',
        	    'password' => 'required',
        	]);

        	$credentials = request(['email', 'password']);

        	if (!auth()->attempt($credentials)) {
        	    return response()->json([
        	        'status_code' => 403,
        	        'message' => 'Forbidden',
        	    ]);
        	}
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }

        $user = User::all()->where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password, [])) {
            throw new \Exception('Error in Login');
        }

        // check for existing token
        if ($user->tokens->count() > 0)
        {
            return response()->json([
                'status_code' => 200,
                'message' => 'Active token already issued for this user.'
            ]);
        } else {
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]);
        }
    }
}
