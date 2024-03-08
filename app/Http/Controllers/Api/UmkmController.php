<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UmkmResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Umkm;
use App\Models\UmkmImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UmkmController extends Controller
{
    public function index()
    {
        //get all posts
        $posts = Umkm::latest()->paginate(5);

        // $posts = Umkm::select('umkms.umkm_name', 'umkms.description', 'umkms.address', 'umkms.city', 'umkms.province', 'umkms.owner_name', 'umkms.contact', 'umkm_images.umkm_image_file1')
        //         ->join('umkm_images', 'umkms.id', '=', 'umkm_images.umkm_id')
        //         ->paginate(5);

        // $posts = DB::table('umkms')
        //         ->select('umkms.umkm_name', 'umkms.description', 'umkms.address', 'umkms.city', 'umkms.province', 'umkms.owner_name', 'umkms.contact',
        //                   'umkm_images.umkm_image_file1')
        //         ->join('umkm_images', 'umkms.id', '=', 'umkm_images.umkm_id')
        //         ->paginate(5);

        //return collection of posts as a resource
        return new UmkmResource(true, 'List Data UMKM', $posts);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            // validator umkm
            'imageUmkm1'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imageUmkm2'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imageUmkm3'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'umkm_name' => 'required', 
            'description' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'owner_name' => 'required',
            'contact' => 'required',

            // validator product
            'code' => 'required',
            'name' => 'required',
            'price' => 'required',
            'imageProduct1'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imageProduct2'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imageProduct3'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image umkm
        $imageUmkm1 = $request->file('imageUmkm1');
        $imageUmkm1->storeAs('public/umkms', $imageUmkm1->hashName());

        $imageUmkm2 = $request->file('imageUmkm2');
        $imageUmkm2->storeAs('public/umkms', $imageUmkm2->hashName());

        $imageUmkm3 = $request->file('imageUmkm3');
        $imageUmkm3->storeAs('public/umkms', $imageUmkm3->hashName());

        //upload image umkm
        $imageProduct1 = $request->file('imageProduct1');
        $imageProduct1->storeAs('public/products', $imageProduct1->hashName());

        $imageProduct2 = $request->file('imageProduct2');
        $imageProduct2->storeAs('public/products', $imageProduct2->hashName());

        $imageProduct3 = $request->file('imageProduct3');
        $imageProduct3->storeAs('public/products', $imageProduct3->hashName());

        // create post umkm data
        $postUmkm = Umkm::create([
            'umkm_name'     => $request->input('umkm_name'),
            'description'   => $request->input('description'),
            'address'   => $request->input('address'),
            'city'   => $request->input('city'),
            'province'   => $request->input('province'),
            'owner_name'   => $request->input('owner_name'),
            'contact'   => $request->input('contact')
        ]);

        // create post product data
        $postProduct = Product::create([
            'code'     => $request->input('code'),
            'name'   => $request->input('name'),
            'price'   => $request->input('price'),
            'umkm_id'   => $postUmkm->id,
        ]);
        
        //create post umkm image
        $postUmkmImage = UmkmImage::create([
            'umkm_id'             => $postUmkm->id,
            'umkm_image_file1'     => $imageUmkm1->hashName(),
            'umkm_image_file2'     => $imageUmkm2->hashName(),
            'umkm_image_file3'     => $imageUmkm3->hashName(),
        ]);

        //create post product image
        $postProductImage = ProductImage::create([
            'product_id'             => $postProduct->id,
            'product_image_file1'     => $imageProduct1->hashName(),
            'product_image_file2'     => $imageProduct2->hashName(),
            'product_image_file3'     => $imageProduct3->hashName(),
        ]);

        //return response
        return [
            'umkm' => new UmkmResource(true, 'Data UMKM Berhasil Ditambahkan!', $postUmkm),
            'product' => new UmkmResource(true, 'Data Product Berhasil Ditambahkan!', $postProduct),
            'umkmImage' => new UmkmResource(true, 'Data Image UMKM Berhasil Ditambahkan!', $postUmkmImage),
            'umkmProduct' => new UmkmResource(true, 'Data Image Product Berhasil Ditambahkan!', $postProductImage)
        ];
    }

    public function show($id)
    {   
        //find umkm by ID
        $post = Umkm::select('umkms.umkm_name', 'umkms.description', 'umkms.address', 'umkms.city', 'umkms.province', 'umkms.owner_name', 'umkms.contact',
                         'umkm_images.umkm_image_file1', 'umkm_images.umkm_image_file2', 'umkm_images.umkm_image_file3',
                         'products.code', 'products.name', 'products.price', 'products.umkm_id',
                         'product_images.product_image_file1', 'product_images.product_image_file2', 'product_images.product_image_file3')
                 ->join('umkm_images', 'umkms.id', '=', 'umkm_images.umkm_id')
                 ->join('products', 'umkms.id', '=', 'products.umkm_id')
                 ->join('product_images', 'products.id', '=', 'product_images.product_id')
                 ->where('umkms.id', $id)
                 ->first();

        //return single post as a resource
        return new UmkmResource(true, 'Detail Data Post!', $post);
    }

    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            // validator umkm
            'imageUmkm1'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imageUmkm2'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imageUmkm3'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'umkm_name' => 'required', 
            'description' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'owner_name' => 'required',
            'contact' => 'required',

            // validator product
            'code' => 'required',
            'name' => 'required',
            'price' => 'required',
            'imageProduct1'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imageProduct2'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imageProduct3'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $post = Umkm::select('umkms.umkm_name', 'umkms.description', 'umkms.address', 'umkms.city', 'umkms.province', 'umkms.owner_name', 'umkms.contact',
                         'umkm_images.umkm_image_file1', 'umkm_images.umkm_image_file2', 'umkm_images.umkm_image_file3',
                         'products.code', 'products.name', 'products.price', 'products.umkm_id',
                         'product_images.product_image_file1', 'product_images.product_image_file2', 'product_images.product_image_file3')
                 ->join('umkm_images', 'umkms.id', '=', 'umkm_images.umkm_id')
                 ->join('products', 'umkms.id', '=', 'products.umkm_id')
                 ->join('product_images', 'products.id', '=', 'product_images.product_id')
                 ->where('umkms.id', $id)
                 ->first();

        //check if image is not empty
        if ($request->hasFile('imageUmkm1') && $request->hasFile('imageUmkm2') && $request->hasFile('imageUmkm3' ) 
            && $request->hasFile('imageProduct1') && $request->hasFile('imageProduct2') && $request->hasFile('imageProduct3' )) {

            //upload image umkm
            $imageUmkm1 = $request->file('imageUmkm1');
            $imageUmkm1->storeAs('public/umkms', $imageUmkm1->hashName());
            $imageUmkm2 = $request->file('imageUmkm2');
            $imageUmkm2->storeAs('public/umkms', $imageUmkm2->hashName());
            $imageUmkm3 = $request->file('imageUmkm3');
            $imageUmkm3->storeAs('public/umkms', $imageUmkm3->hashName());

            //upload image product
            $imageProduct1 = $request->file('imageProduct1');
            $imageProduct1->storeAs('public/products', $imageProduct1->hashName());
            $imageProduct2 = $request->file('imageProduct2');
            $imageProduct2->storeAs('public/products', $imageProduct2->hashName());
            $imageProduct3 = $request->file('imageProduct3');
            $imageProduct3->storeAs('public/products', $imageProduct3->hashName());

            //delete old image umkm
            Storage::delete('public/umkms/'.basename($post->umkm_image_file1));
            Storage::delete('public/umkms/'.basename($post->umkm_image_file2));
            Storage::delete('public/umkms/'.basename($post->umkm_image_file3));

            //delete old image product
            Storage::delete('public/products/'.basename($post->product_image_file1));
            Storage::delete('public/products/'.basename($post->product_image_file2));
            Storage::delete('public/products/'.basename($post->product_image_file3));

            //update post with new image
            $post->update([
                // umkm
                'imageUmkm1'     => $imageUmkm1->hashName(),
                'imageUmkm2'     => $imageUmkm2->hashName(),
                'imageUmkm3'     => $imageUmkm3->hashName(),
                'umkm_name'     => $request->umkm_name,
                'description'   => $request->description,
                'address'   => $request->address,
                'city'   => $request->city,
                'province'   => $request->province,
                'owner_name'   => $request->owner_name,
                'contact'   => $request->contact,

                // product
                'code'   => $request->code,
                'name'   => $request->name,
                'price'   => $request->price,
                'imageProduct1'   => $request->imageProduct1,
                'imageProduct2'   => $request->imageProduct2,
                'imageProduct3'   => $request->imageProduct3
            ]);

        } 
        // else {

        //     //update post without image
        //     $post->update([
        //         'title'     => $request->title,
        //         'content'   => $request->content,
        //     ]);
        // }

        //return response
        return new UmkmResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    public function destroy($id)
    {

        //find post by ID
        $delUmkm = Umkm::find($id);
        $delUmkmImage = UmkmImage::find($id);
        $delProduct = Product::find($id);
        $delProductImage = ProductImage::find($id);

        //delete image
        Storage::delete('public/umkms/'.basename($delUmkmImage->umkm_image_file1));
        Storage::delete('public/umkms/'.basename($delUmkmImage->umkm_image_file2));
        Storage::delete('public/umkms/'.basename($delUmkmImage->umkm_image_file3));
        Storage::delete('public/products/'.basename($delProductImage->product_image_file1));
        Storage::delete('public/products/'.basename($delProductImage->product_image_file2));
        Storage::delete('public/products/'.basename($delProductImage->product_image_file3));

        //delete post
        $delUmkm->delete();
        $delUmkmImage->delete();
        $delProduct->delete();
        $delProductImage->delete();

        //return response
        return new UmkmResource(true, 'Data Post Berhasil Dihapus!', null);
    }

}
