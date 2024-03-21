<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 
        'name',
        'price', 
        'umkm_id',
        'first_umkm_img',
        'second_umkm_img',
        'third_umkm_img',
        'first_product_img',
        'second_product_img',
        'third_product_img',
        ];

        // UMKM
        protected function firstUmkmImg(): Attribute
        {
            return Attribute::make(
                get: fn ($firstUmkmImg) => Url('/storage/umkms/' . $firstUmkmImg),
            );
        }
        protected function secondUmkmImg(): Attribute
        {
            return Attribute::make(
                get: fn ($secondUmkmImg) => Url('/storage/umkms/' . $secondUmkmImg),
            );
        }
        protected function thirdUmkmImg(): Attribute
        {
            return Attribute::make(
                get: fn ($thirdUmkmImg) => Url('/storage/umkms/' . $thirdUmkmImg),
            );
        }

        // PRODUCT
        protected function firstProductImg(): Attribute
        {
            return Attribute::make(
                get: fn ($firstProductImg) => url('/storage/products/' . $firstProductImg),
            );
        }
        protected function secondProductImg(): Attribute
        {
            return Attribute::make(
                get: fn ($secondProductImg) => url('/storage/products/' . $secondProductImg),
            );
        }
        protected function thirdProductImg(): Attribute
        {
            return Attribute::make(
                get: fn ($thirdProductImg) => url('/storage/products/' . $thirdProductImg),
            );
        }
}
