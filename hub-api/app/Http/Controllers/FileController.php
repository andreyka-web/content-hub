<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return $user->files->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        if ($request->validated()) {
            $path = $request->file('file')->store('uloads');

            $data = $request->safe()->all();

            $user = Auth::user();
            // create a record in db
            $request->user()->files()->create([
                'filename' => $data['file']->getClientoriginalName(),
                'user_id' => $user->id,
                'category_id' => $data['category'] ?? null,
                'path' => $path
            ]);

            return response()->json(['message' => 'File uploaded successfully']);
        }

        return $request->validator->errors();
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        Gate::authorize('view', $file);

        if (!$file) {
            return response()->json([
                'message' => 'File not found for the specified user'
            ], 404);
        }

        // Check if the file exists in storage
        if (!Storage::exists($file->path)) {
            return response()->json([
                'message' => 'File not found in storage'
            ], 404);
        }

        // Return the file as a response for display or download
        return response()->file(Storage::path($file->path), [
            'Content-Type' => Storage::mimeType($file->path)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {  
        if (!$request->hasAny(['filename','file','category'])) {
            return response()->json(['message' => 'No update required']);
        }

        $validator = Validator::make($request->all(), $request->rules());

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('file')) { 
            Storage::delete($file->path);
            $file->path = $request->file('file')->store('uloads'); 
        }

        $data = $validator->validated(); 

        $file->filename = $data['filename'] ?? $file->filename;
        $file->category_id = $data['category'] ?? $file->category_id;
        $file->save();

        return response()->json([
            'message' => 'File updated successfully',
            'file' => $file
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $user = Auth::user();
        Gate::authorize('delete', [$user, $file]);

        $user->files->where('id', $file->id)->delete();

        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
        }

        return response()->json(['message' => "File {$file->filename} deleted sucessfully"]);
    }
}
