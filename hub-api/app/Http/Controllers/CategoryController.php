<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); 
        
        // todo: pagination
        return $user->categories->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        if($request->validated()){

            $data = $request->safe()->all();
            $category = $request->user()->categories()->create($data);

            return $category;
        }

        return $request->validator->errors();
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    { 
        Gate::authorize('view', $category); 
 
        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    { 
        $category->update($request->safe()->all());
        return $category;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);

        $this->purgeCategory($category); 
        
        return ['message' => 'The category was deleted'];
    }
    
    // recursively delete category and all files it contains
    private function purgeCategory($category) {
        $user = Auth::user(); 
        // delete all files in the category
        $files = $user->files->where('category_id', $category->id);
        foreach($files as $file){
            $file->delete();
            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
        }

        $category->delete();

        // get child categories 
        $categories = $user->categories->where('parent_id', $category->id);
        foreach($categories as $category){
            $this->purgeCategory($category);
        }
    }
}
