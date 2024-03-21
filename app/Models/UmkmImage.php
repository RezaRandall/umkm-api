<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UmkmImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'umkm_id', 
        'first_umkm_img',
        'second_umkm_img',
        'third_umkm_img',
        ];

    protected function firstUmkmImg(): Attribute
    {
        return Attribute::make(
            get: fn ($firstUmkmImg) => url('/storage/umkms/' . $firstUmkmImg),
        );
    }
    protected function secondUmkmImg(): Attribute
    {
        return Attribute::make(
            get: fn ($secondUmkmImg) => url('/storage/umkms/' . $secondUmkmImg),
        );
    }
    protected function thirdUmkmImg(): Attribute
    {
        return Attribute::make(
            get: fn ($thirdUmkmImg) => url('/storage/umkms/' . $thirdUmkmImg),
        );
    }
}
