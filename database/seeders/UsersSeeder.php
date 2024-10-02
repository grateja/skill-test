<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(User::count() == 0) {
            $adminId = Str::uuid();
            $managerId = Str::uuid();
            $userId = Str::uuid();
            $users = [
                [
                    'id' => $adminId,
                    'name' => 'Administrator',
                    'email' => 'admin@test.com',
                    'password' => bcrypt('1234'),
                ],
                [
                    'id' => $managerId,
                    'name' => 'Manager',
                    'email' => 'manager@test.com',
                    'password' => bcrypt('1234'),
                ],
                [
                    'id' => $userId,
                    'name' => 'User',
                    'email' => 'user@test.com',
                    'password' => bcrypt('1234'),
                ],
            ];

            $roleUsers = [
                [
                    'user_id' => $adminId,
                    'role_id' => 1,
                ],
                [
                    'user_id' => $managerId,
                    'role_id' => 2,
                ],
                [
                    'user_id' => $userId,
                    'role_id' => 3,
                ],
            ];

            User::insert($users);
            DB::table('role_user')->insert($roleUsers);
        }
    }
}
