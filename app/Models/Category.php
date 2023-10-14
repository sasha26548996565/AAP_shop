<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Category $category) {
            $slug = Str::slug($category->title);
            $countSlug = self::where('slug', $slug)->count();

            if ($countSlug > 0)
            {
                $slug .= '-' . ($countSlug + 1);
            }

            $category->slug = $slug;
        });
    }
}
