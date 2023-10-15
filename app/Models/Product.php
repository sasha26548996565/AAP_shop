<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'price',
        'brand_id',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            $slug = Str::slug($product->title);
            $countSlug = self::where('slug', $slug)->count();

            if ($countSlug > 0) {
                $slug .= '-' . ($countSlug + 1);
            }

            $product->slug = $slug;
        });
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
