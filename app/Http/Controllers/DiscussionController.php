<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionFile;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function sendMessage(Request $request, $groupId)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $discussion = Discussion::create([
            'group_id' => $groupId,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json(['message' => 'Message envoyé', 'discussion' => $discussion]);
    }

    public function sendFile(Request $request, $groupId, $userId)
    {
        $request->validate([
            'file' => 'required|file|max:10240', 
        ]);


        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        $discussion = DiscussionFile::create([
            'user_id' => $userId,
            'group_id' => $groupId,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
        ]);

        return response()->json(['message' => 'File sent', 'discussion' => $discussion]);

        event(new DiscussionFile($file->getClientOriginalName(), $file->getSize()));

    }

    public function index($groupId)
    {
        return Discussion::where('group_id', $groupId)->get(); 
    }
    public function showFile($groupId)
    {
        $fileGroup=DiscussionFile::where('group_id', $groupId)->get();
        return response()->json(['message' => 'Fichiers envoyés dans le groupe', 'discussion' => $fileGroup]);
    }
}
