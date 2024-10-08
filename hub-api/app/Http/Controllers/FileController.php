<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; 

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
        if($request->validated()){
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
        return 'show';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        return 'destroy';
    }
}
