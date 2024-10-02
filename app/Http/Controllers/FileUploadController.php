<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        // Store the file
        $filePath = $request->file('file')->store('uploads', 'public');

        // Return the file path or any necessary response
        return response()->json(['file_path' => $filePath], 200);
    }
}
