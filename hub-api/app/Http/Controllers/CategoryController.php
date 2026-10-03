<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Actions\PurgeCategory;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Category::class);

        $perPage = request()->get('per_page', 25);
        $orderBy = request()->get('order_by', 'name');
        $orderDirection = request()->get('order_direction', 'asc');
        
        $categories = $request->user()->categories()
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage);

        return response()->json($categories, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = $request->user()
            ->categories()
            ->create($request->validated());

        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        Gate::authorize('view', $category);

        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    { 
        $category->update($request->validated());

        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, PurgeCategory $purgeCategory)
    {
        Gate::authorize('delete', $category);

        $purgeCategory->execute($category);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
