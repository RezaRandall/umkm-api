<?php

namespace App\Http\Controllers\Api;

use App\Models\Umkm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\UmkmResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\UmkmImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UmkmController extends Controller
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
        return new UmkmResource(true, 'List Data UMKM', $posts);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            // validator umkm
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

            // validator image umkm
            'first_umkm_img'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'second_umkm_img'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'third_umkm_img'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // validator image product
            'first_product_img'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'second_product_img'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'third_product_img'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // upload image umkms to local storage
        $firstUmkmImg = $request->file('first_umkm_img');
        $firstUmkmImg->storeAs('public/umkms', $firstUmkmImg->hashName());
        $secondUmkmImg = $request->file('second_umkm_img');
        $secondUmkmImg->storeAs('public/umkms', $secondUmkmImg->hashName());
        $thirdUmkmImg = $request->file('third_umkm_img');
        $thirdUmkmImg->storeAs('public/umkms', $thirdUmkmImg->hashName());

        //upload product umkm to local storage
        $firstProductImg = $request->file('first_product_img');
        $firstProductImg->storeAs('public/products', $firstProductImg->hashName());
        $secondProductImg = $request->file('second_product_img');
        $secondProductImg->storeAs('public/products', $secondProductImg->hashName());
        $thirdProductImg = $request->file('third_product_img');
        $thirdProductImg->storeAs('public/products', $thirdProductImg->hashName());         

        // create umkm data
        $postUmkm = Umkm::create([
            'umkm_name'     => $request->input('umkm_name'),
            'description'   => $request->input('description'),
            'address'   => $request->input('address'),
            'city'   => $request->input('city'),
            'province'   => $request->input('province'),
            'owner_name'   => $request->input('owner_name'),
            'contact'   => $request->input('contact'),
        ]);

        //create umkm image
        $postUmkmImage = UmkmImage::create([
            'umkm_id'             => $postUmkm->id,
            'first_umkm_img'     => $firstUmkmImg->hashName(),
            'second_umkm_img'     => $secondUmkmImg->hashName(),
            'third_umkm_img'     => $thirdUmkmImg->hashName(),
        ]);

        // create product data
        $postProduct = Product::create([
            'code'     => $request->input('code'),
            'name'   => $request->input('name'),
            'price'   => $request->input('price'),
            'umkm_id'   => $postUmkm->id,
        ]);

        // create product image
        $postProductImage = ProductImage::create([
            'product_id'             => $postProduct->id,
            'first_product_img'     => $firstProductImg->hashName(),
            'second_product_img'     => $secondProductImg->hashName(),
            'third_product_img'     => $thirdProductImg->hashName(),
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
        $post = Umkm::select('umkms.id','umkms.umkm_name', 'umkms.description', 'umkms.address', 'umkms.city', 'umkms.province', 'umkms.owner_name', 'umkms.contact', 
        'umkm_images.first_umkm_img', 'umkm_images.second_umkm_img', 'umkm_images.third_umkm_img',
        'products.code', 'products.name', 'products.price',
        'product_images.first_product_img', 'product_images.second_product_img', 'product_images.third_product_img')
        ->join('umkm_images', 'umkms.id', '=', 'umkm_images.umkm_id')
        ->join('products', 'umkms.id', '=', 'products.umkm_id')
        ->join('product_images', 'products.id', '=', 'product_images.product_id')
        ->where('umkms.id', $id)
        ->first();

        //return single post as a resource
        return new UmkmResource(true, 'Detail Data UMKM!', $post);
    }

    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            // validator umkm
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
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find data by ID
        $umkm = Umkm::find($id);
        $umkmImage = UmkmImage::find($id);
        $product = Product::find($id);
        $productImage = ProductImage::find($id);

        //check if image is not empty
        if ($request->hasFile('first_umkm_img') && $request->hasFile('second_umkm_img') && $request->hasFile('third_umkm_img' ) 
            && $request->hasFile('first_product_img') && $request->hasFile('second_product_img') && $request->hasFile('third_product_img')) {

            //upload image umkm
            $first_umkm_img = $request->file('first_umkm_img');
            $first_umkm_img->storeAs('public/umkms', $first_umkm_img->hashName());
            $second_umkm_img = $request->file('second_umkm_img');
            $second_umkm_img->storeAs('public/umkms', $second_umkm_img->hashName());
            $third_umkm_img = $request->file('third_umkm_img');
            $third_umkm_img->storeAs('public/umkms', $third_umkm_img->hashName());

            //upload image product
            $first_product_img = $request->file('first_product_img');
            $first_product_img->storeAs('public/products', $first_product_img->hashName());
            $second_product_img = $request->file('second_product_img');
            $second_product_img->storeAs('public/products', $second_product_img->hashName());
            $third_product_img = $request->file('third_product_img');
            $third_product_img->storeAs('public/products', $third_product_img->hashName());

            //delete old image umkm
            Storage::delete('public/umkms/'.basename($umkmImage->first_umkm_img));
            Storage::delete('public/umkms/'.basename($umkmImage->second_umkm_img));
            Storage::delete('public/umkms/'.basename($umkmImage->third_umkm_img));

            //delete old image product
            Storage::delete('public/products/'.basename($productImage->first_product_img));
            Storage::delete('public/products/'.basename($productImage->second_product_img));
            Storage::delete('public/products/'.basename($productImage->third_product_img));

            //update umkm with new data
            $umkm->update([
                'umkm_name'     => $request->umkm_name,
                'description'   => $request->description,
                'address'   => $request->address,
                'city'   => $request->city,
                'province'   => $request->province,
                'owner_name'   => $request->owner_name,
                'contact'   => $request->contact,
            ]);

            //update product with new data
            $product->update([
                'code'   => $request->code,
                'name'   => $request->name,
                'price'   => $request->price,
            ]);

            //update umkm with new images
            $umkmImage->update([
                'first_umkm_img'     => $first_umkm_img->hashName(),
                'second_umkm_img'     => $second_umkm_img->hashName(),
                'third_umkm_img'     => $third_umkm_img->hashName(),
            ]);

            //update product with new images
            $productImage->update([                
                'first_product_img'   => $first_product_img->hashName(),
                'second_product_img'   => $second_product_img->hashName(),
                'third_product_img'   => $third_product_img->hashName()
            ]);
        } 
        else {
                //update post without image
                $umkm->update([
                    'umkm_name'     => $request->umkm_name,
                    'description'   => $request->description,
                    'address'   => $request->address,
                    'city'   => $request->city,
                    'province'   => $request->province,
                    'owner_name'   => $request->owner_name,
                    'contact'   => $request->contact,
                ]);
    
                $product->update([
                    'code'   => $request->code,
                    'name'   => $request->name,
                    'price'   => $request->price,
                ]);
            }

        //return response
        return [
            'umkm' => new UmkmResource(true, 'Data UMKM Berhasil Dupdate!', $umkm),
            'product' => new UmkmResource(true, 'Data Product Berhasil Dupdate!', $umkmImage),
            'umkmImage' => new UmkmResource(true, 'Data Image UMKM Berhasil Dupdate!', $product),
            'umkmProduct' => new UmkmResource(true, 'Data Image Product Berhasil Dupdate!', $productImage)
        ];
        
    }

    public function destroy($id)
    {

        //find data umkm by ID
        $delUmkm = Umkm::find($id);
        $delUmkmImage = UmkmImage::find($id);
        $delProduct = Product::find($id);
        $delProductImage = ProductImage::find($id);

        //delete image
        Storage::delete('public/umkms/'.basename($delUmkmImage->first_umkm_img));
        Storage::delete('public/umkms/'.basename($delUmkmImage->second_umkm_img));
        Storage::delete('public/umkms/'.basename($delUmkmImage->third_umkm_img));
        Storage::delete('public/products/'.basename($delProductImage->first_product_img));
        Storage::delete('public/products/'.basename($delProductImage->second_product_img));
        Storage::delete('public/products/'.basename($delProductImage->third_product_img));

        //delete umkm data
        $delUmkm->delete();
        $delUmkmImage->delete();
        $delProduct->delete();
        $delProductImage->delete();

        //return response
        return new UmkmResource(true, 'Data Umkm Berhasil Dihapus!', null);
    }

}
