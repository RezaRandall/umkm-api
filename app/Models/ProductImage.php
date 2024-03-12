<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'umkmId', 
        'product_id',
        'first_product_img',
        'second_product_img',
        'third_product_img'
        ];

     /**
     * first_product_img
     * second_product_img
     * third_product_img
     * 
     * @return Attribute
     */

        // Accesore storage image product
        protected function firstProductImg(): Attribute
        {
            return Attribute::make(
                get: fn ($firstProductImg) => Url('/storage/products/' . $firstProductImg),
            );
        }
        protected function secondProductImg(): Attribute
        {
            return Attribute::make(
                get: fn ($secondProductImg) => Url('/storage/products/' . $secondProductImg),
            );
        }
        protected function thirdProductImg(): Attribute
        {
            return Attribute::make(
                get: fn ($thirdProductImg) => Url('/storage/products/' . $thirdProductImg),
            );
        }
}
