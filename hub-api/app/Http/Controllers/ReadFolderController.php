<?php

namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Database\Eloquent\Builder;

class ReadFolderController extends Controller
{
    public function index(String $parent)
    { 
        $user = Auth::user(); 
         
        $folders = $user->categories()->when($parent, function(Builder $query) use ($parent) {
            $query->where('parent_id', $parent);
        }, fn(Builder $query) =>
            $query->whereNull('parent_id')
        )->get();

        $files = $user->files()->when($parent, function(Builder $query) use ($parent) {
            $query->where('category_id', $parent);
        }, fn(Builder $query) =>
            $query->whereNull('category_id')
        )->get(); 
        
        return response()->json([
            'user' => $user->id,
            'parent' => $parent,
            'folders' => $folders,
            'files' => $files
        ]);
    } 
}
