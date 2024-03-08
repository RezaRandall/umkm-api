<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $fillable = [
        'umkm_name', 
        'description',
        'address',
        'city',
        'province',
        'owner_name',
        'contact',
        ];
}
