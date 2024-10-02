<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        \Log::debug($request->archive);
        $tasks = Task::leftJoin('users', 'users.id', '=', 'tasks.user_id')
            ->where('archive', $request->archive)
            ->where('title', 'like', "%$request->keyword%");

        if($user->hasRole(['user'])) {
            $tasks->where('user_id', $user->id);
        }

        $tasks->selectRaw('tasks.*, users.name as user_name');

        return response()->json(
            $tasks->orderBy('created_at', 'desc')->paginate(20)
        );
    }

    public function submit(Request $request, string $taskId)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $filePath = $request->file('file')->store('uploads', 'public');


        $task = Task::findOrFail($taskId);
        $task->update([
            'status' => 'done',
        ]);

        return response()->json(['file_path' => $filePath], 200);
    }

    public function archive($taskId) {
        $task = Task::findOrFail($taskId);
        $task->update([
            'archive' => true,
        ]);

        return response()->json($task);
    }

    public function restore($taskId) {
        $task = Task::findOrFail($taskId);
        $task->update([
            'archive' => false,
        ]);

        return response()->json($task);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'due_date' => 'required',
            'priority_level' => 'required',
            'user_id' => 'required|exists:users,id'
        ];

        $request->validate($rules);

        $request->merge([
            'status' => 'todo'
        ]);

        $task = Task::create($request->only([
            'title',
            'description',
            'due_date',
            'user_id',
            'priority_level',
            'user_id',
            'status'
        ]));

        $task->load('user');
        $task->user_name = $task->user->name;

        return response()->json($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $taskId)
    {
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'due_date' => 'required',
            'priority_level' => 'required',
            'user_id' => 'required|exists:users,id'
        ];

        $request->validate($rules);

        $task = Task::findOrFail($taskId);

        $task->update($request->only([
            'title',
            'description',
            'due_date',
            'user_id',
            'priority_level',
            'user_id',
        ]));

        $task->load('user');
        $task->user_name = $task->user->name;

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
