<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Email Or Password Invalid.', ['error' => 'Unauthorised']);
        }
    }

    public function getProfile(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('User Not Found.', ['error' => 'Not Found'], 404);
        }
        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];
        return $this->sendResponse($data, 'User retrieved successfully.');
    }


    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);
        $user = Auth::user();
        $user->update($data);
        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];
        return $this->sendResponse($data, 'User retrieved successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|confirmed',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError('Current password is incorrect.', ['error' => 'Invalid current password']);
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        return $this->sendResponse([], 'Password changed successfully.');
    }

}
