<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController as BaseController;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;

class AuthController extends BaseController
{
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {

            return $this->sendError('Validation Error.', $validator->errors());
        }

            $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
            ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response_data = [
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                    ];
        return $this->sendResponse($response_data);
    }

    public function me(request $request) {
        return $request->user();
    }
}
