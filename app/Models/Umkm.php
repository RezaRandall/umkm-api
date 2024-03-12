<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    use HasFactory;
    protected $fillable = [
        'umkm_name', 
        'description',
        'address',
        'city',
        'province',
        'owner_name',
        'contact',     
        'image',
        'first_umkm_img',
        'second_umkm_img',
        'third_umkm_img',
        'first_product_img',
        'second_product_img',
        'third_product_img',
        ];
        protected function image(): Attribute
        {
            return Attribute::make(
                get: fn ($image) => Url('/storage/umkms/' . $image),
            );
        }

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
