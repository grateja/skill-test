<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AccountsController extends Controller
{
    public function register(Request $request) {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'nullable'
        ];

        if($this->validate($request, $rules)) {
            return DB::transaction(function() use ($request) {
                $role = Role::find(3);

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);

                $user->roles()->attach($role);

                Auth::login($user);

                return response()->json([
                    'user' => $user,
                ]);
            });
        }
    }
}
