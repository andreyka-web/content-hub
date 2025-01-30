<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'user_id',
        'category_id',
        'path'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Delete the file from storage when the model is deleted.  (Important!)
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($file) {
            if ($file->path && Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
        });
    }
}
