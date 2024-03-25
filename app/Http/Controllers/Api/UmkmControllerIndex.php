<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Umkm;

class UmkmControllerIndex extends Controller
{
    public function index()
    {
        //get all umkm
        $posts = Umkm::select('umkms.id', 
        'umkms.umkm_name', 
        'umkms.description', 
        'umkms.address', 
        'umkms.city', 
        'umkms.province', 
        'umkms.owner_name', 
        'umkms.contact', 
        'umkm_images.first_umkm_img')
                ->join('umkm_images', 'umkms.id', '=', 'umkm_images.umkm_id')
                ->paginate(5);

        //return collection of umkms as a resource
        return response()->json($posts);
    }

    public function show($id)
    {   
        //find umkm by ID
        $post = Umkm::select('umkms.id','umkms.umkm_name', 'umkms.description', 'umkms.address', 'umkms.city', 'umkms.province', 'umkms.owner_name', 'umkms.contact', 
        'umkm_images.first_umkm_img', 'umkm_images.second_umkm_img', 'umkm_images.third_umkm_img',
        'products.code', 'products.name', 'products.price',
        'product_images.first_product_img', 'product_images.second_product_img', 'product_images.third_product_img')
        ->join('umkm_images', 'umkms.id', '=', 'umkm_images.umkm_id')
        ->join('products', 'umkms.id', '=', 'products.umkm_id')
        ->join('product_images', 'products.id', '=', 'product_images.product_id')
        ->where('umkms.id', $id)
        ->first();

        //return single umkm as a resource
        return response()->json($post);
    }
}
