<?php

namespace App\Actions;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurgeCategory
{
    /**
     * Recursively delete a category, its children, and all associated files.
     */
    public function execute(Category $category): void
    {
        DB::transaction(function () use ($category) {
            // delete child categories first (depth-first)
            $category->children()->cursor()->each(function (Category $child) {
                $this->execute($child);
            });

            // delete files in this category (one row in memory at a time)
            $category->files()->cursor()->each(function ($file) {
                if (Storage::exists($file->path)) {
                    Storage::delete($file->path);
                }
                $file->delete();
            });

            $category->delete();
        });
    }
}