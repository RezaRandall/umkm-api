<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmkmImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'umkm_id', 
        'umkm_image_file1',
        'umkm_image_file2',
        'umkm_image_file3',
        ];

        // Accesore storage image umkm
        protected function image(): Attribute
        {
            return Attribute::make(
                get: fn ($image) => url('/storage/umkm/' . $image),
            );
        }
}
