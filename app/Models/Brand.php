<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Brand $brand) {
            $slug = Str::slug($brand->title);
            $countSlug = self::where('slug', $slug)->count();

            if ($countSlug > 0) {
                $slug .= '-' . ($countSlug + 1);
            }

            $brand->slug = $slug;
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
}
