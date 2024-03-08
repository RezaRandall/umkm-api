<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'umkmId', 
        'product_id',
        'product_image_file1',
        'product_image_file2',
        'product_image_file3'
        ];

        // Accesore storage image product
        protected function image(): Attribute
        {
            return Attribute::make(
                get: fn ($image) => url('/storage/product/' . $image),
            );
        }
}
