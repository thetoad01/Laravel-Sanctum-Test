<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// Models
use app\User;

class ApiAuthenticationController extends Controller
{
    /**
     * Authenticate user
     *
     * @param string $email
     * @param string $password
     *
     * @return int
     */
    private function authenticateUser($email, $password)
    {
        try {
            $credentials = ['email' => $email, 'password' => $password];

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'status_code' => 403,
                    'message' => 'Forbidden',
                ]);
            }

            return 200;
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }

    /**
     * Validate request
     */
    private function validateRequest($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return false;
        }

        return true;
    }

    /**
     * Generate a new token for a user
     */
    public function login(Request $request)
    {
        if (!$this->validateRequest($request)) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Bad Request',
            ]);
        }

        $authenticated = $this->authenticateUser($request->email, $request->password);
        if ($authenticated !== 200) {
            return $authenticated;
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

    /**
     * Delete any existing token and regenerate a new token for a user.
     */
    public function forgot(Request $request)
    {
        if (!$this->validateRequest($request)) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Bad Request',
            ]);
        }

        $authenticated = $this->authenticateUser($request->email, $request->password);
        if ($authenticated !== 200) {
            return $authenticated;
        }

        $user = User::all()->where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password, [])) {
            throw new \Exception('Error in Login');
        }

        // check for existing token(s) and delete
        if ($user->tokens->count() > 0)
        {
            $user->tokens()->delete();
        }

        // generate new token
        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Delete any existing token(s) for a user.
     */
    public function destroy(Request $request)
    {
        if (!$this->validateRequest($request)) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Bad Request',
            ]);
        }

        $authenticated = $this->authenticateUser($request->email, $request->password);
        if ($authenticated !== 200) {
            return $authenticated;
        }

        $user = User::all()->where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password, [])) {
            throw new \Exception('Error in Login');
        }

        // check for existing token(s) and delete
        if ($user->tokens->count() > 0)
        {
            $user->tokens()->delete();
        }

        return response()->json([
            'status_code' => 200,
            'message'  => 'All user tokens have been revoked.',
        ]);
    }
}
