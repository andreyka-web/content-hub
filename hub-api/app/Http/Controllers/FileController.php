<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\FileUpdateRequest;
use App\Http\Resources\FileResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $files = $user->files->all();
        return FileResource::collection($files);
    }

    public function show(File $file)
    {
        if (!$file || !Storage::fileExists($file->path)) {
            abort(404);
        }

        return response()->file(Storage::path($file->path), ['Content-Type' => Storage::mimeType($file->path)]);
    }

    public function store(StoreFileRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();
        $path = $request->file('file')->store('uloads');

        $file = File::create([
            'user_id' => $user->id,
            'filename' => $validatedData['file']->getClientoriginalName(),
            'category_id' => $validatedData['category'] ?? null,
            'path' => $path
        ]);
        return new FileResource($file);
    }

    // php limitations do not let to upload file with put|patch request so we will update file name only
    public function update(FileUpdateRequest $request, File $file)
    {  
        $file->update([
            'filename' => $request->input('name'),
            'category' => $request->input('category') ?? null
        ]);
         
        return new FileResource($file);
    }

    public function destroy(File $file)
    {
        $fileResource = new FileResource($file);
        $fileResource->destroy($file->id);
        return response()->noContent(200);
    }
}
