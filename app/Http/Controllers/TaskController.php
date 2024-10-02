<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        $tasks = Task::leftJoin('users', 'users.id', '=', 'tasks.user_id')
            ->where('archive', $request->archive)
            ->where('title', 'like', "%$request->keyword%");

        if($request->status) {
            $tasks = $tasks->where('status', $request->status);
        }

        if($user->hasRole(['user'])) {
            $tasks->where('user_id', $user->id);
        }

        $tasks->selectRaw('tasks.*, users.name as user_name');

        return response()->json(
            $tasks->orderBy('created_at', 'desc')->paginate(20)
        );
    }

    public function submit(Request $request, string $taskId) {
        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        return DB::transaction(function () use ($request, $taskId) {
            // Get the uploaded file
            $file = $request->file('file');

            // Get the original file name
            $fileName = $file->getClientOriginalName();

            // Store the file in the 'uploads' directory, and specify the filename
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // Find the task by ID or fail
            $task = Task::findOrFail($taskId);

            if($task->user_id != auth('sanctum')->id()) {
                return response()->json(['error' => 'You are not the owner of this task']);
            }

            // Create the task attachment record
            $attachment = TaskAttachment::create([
                'file_name' => $fileName,
                'task_id' => $task->id,
                'remarks' => $request->remarks,
            ]);

            // Update the task status
            $task->update([
                'status' => 'done',
            ]);

            // Return the file path as JSON response
            return response()->json($attachment);
        });
    }

    // public function submit(Request $request, string $taskId)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
    //     ]);

    //     return DB::transaction(function () use ($request, $taskId) {
    //         $file = $request->file('file');

    //         $fileName = $file->getClientOriginalName();

    //         $filePath = $file->store('uploads', $fileName, 'public');

    //         $task = Task::findOrFail($taskId);
    //         TaskAttachment::create([
    //             'file_name' => $fileName,
    //             'task_id' => $task->id,
    //         ]);

    //         $task->update([
    //             'status' => 'done',
    //         ]);

    //         return response()->json(['file_path' => $filePath], 200);
    //     });
    // }

    public function archive($taskId) {
        $task = Task::findOrFail($taskId);
        $task->update([
            'archive' => true,
        ]);

        $task->load('taskAttachments');

        return response()->json($task);
    }

    public function restore($taskId) {
        $task = Task::findOrFail($taskId);
        $task->update([
            'archive' => false,
        ]);

        $task->load('taskAttachments');

        return response()->json($task);
    }

    public function startTask($taskId) {
        $task = Task::findOrFail($taskId);
        $task->update([
            'status' => 'on-going'
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
    public function show(string $taskId)
    {
        $task = Task::with('taskAttachments')->findOrFail($taskId);

        $task->task_attachments = $task->taskAttachments->map(function ($attachment) {
            $attachment['has_file'] = Storage::disk('public')->exists('uploads/' . $attachment->file_name);
            return $attachment;
        });

        return response()->json($task);

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
