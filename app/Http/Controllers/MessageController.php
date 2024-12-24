<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $models = Message::orderBy('created_at', 'asc')->get();
        return view('index', ['models' => $models]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => ['nullable', 'string', 'max:1000'],
            'file' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,gif,mp4,mov,avi,pdf,docx,xlsx,zip',
                'max:10240',
            ],
        ]);
    
        $filePath = null;
        // dd($validated['file']);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            if (in_array($file->extension(), ['jpeg', 'png', 'jpg', 'gif'])) {
                $folder = 'images';
            } elseif (in_array($file->extension(), ['mp4', 'mov', 'avi'])) {
                $folder = 'videos';
            } else {
                $folder = 'files';
            }
    
            $file_name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
    
            $destinationPath = public_path('uploads/' . $folder);
    
            $file->move($destinationPath, $file_name);
    
            $filePath = 'uploads/' . $folder . '/' . $file_name;
            $validated['file'] = $filePath;
        }
    
        $message = Message::create($validated);
    
        broadcast(new MessageEvent($message))->toOthers();
    
        return redirect()->route('main.page');
    }    
}
