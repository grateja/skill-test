<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index() {
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->get();

        return response()->json($users);
    }

    public function updateProfile(Request $request) {
        $user = auth('sanctum')->user();
        $rules = [
            'name' => 'required',
            'email' => 'required|email'
        ];

        if($user->email != $request->email) {
            $rules['email'] = 'required|email|unique:users';
        }

        $request->validate($rules);

        $user->update($request->only([
            'name',
            'email'
        ]));

        return response()->json([
            'user' => $user
        ]);
    }

    public function changePassword(Request $request) {
        $rules = [];
        $rules = [
            'currentPassword' => 'required',
            'newPassword' => 'required|confirmed',
            'newPassword_confirmation' => 'nullable'
        ];

        $request->validate($rules);

        $user = auth('sanctum')->user();

        if (!Hash::check($request->newPassword, $user->password)) {
            return response()->json([
                'errors' => [
                    'currentPassword' => ['Wrong password']
                ]
            ], 422);
        } else {
            $user->update([
                'password' => Hash::make($request->newPassword)
            ]);

            return response()->json([
                'user' => $user
            ]);
        }
    }

    public function show($userId) {
        return User::findOrFail($userId);
    }
}
