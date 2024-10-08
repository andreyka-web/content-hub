<?php

namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Auth; 

class ReadFolderController extends Controller
{
    public function index(String $parent = null)
    { 
        $user = Auth::user(); 
         
        if($parent) {
            $folders = $user->categories->where('parent_id', $parent)->all();
            $files = $user->files->where('category_id', $parent)->all();
        }
        else {
            $folders = $user->categories->whereNull('parent_id')->all();
            $files = $user->files->whereNull('category_id')->all();
        }
        
        return [
            'user' => $user->id,
            'parent' => $parent,
            'folders' => $folders,
            'files' => $files
        ];
    } 
}
