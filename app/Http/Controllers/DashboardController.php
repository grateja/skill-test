<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $usersTask = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->withCount([
            'tasks as tasks_on_going_count' => function ($query) {
                $query->where('status', 'on-going');
            },
            'tasks as tasks_todo_count' => function ($query) {
                $query->where('status', 'todo');
            },
            'tasks as tasks_done_count' => function ($query) {
                $query->where('status', 'done');
            },
            'tasks as total_tasks_count' => function ($query) {
                // Total tasks created by the user
            },
        ])->get();

        return response()->json($usersTask);
    }
}
