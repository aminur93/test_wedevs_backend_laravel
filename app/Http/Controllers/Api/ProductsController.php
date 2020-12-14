<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsRequest;
use App\Products;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use Image;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Products::latest()->get();

        return response()->json([
            'products' => $products,
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : create Blog Posts

                $product = new Products();

                $product->title = $request->title;
                $product->description = $request->description;
                $product->price = $request->price;

                if($request->hasFile('image')){

                    $image_tmp = $request->file('image');
                    if($image_tmp->isValid()){
                        $extenson = $image_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extenson;

                        $original_image_path = public_path().'/assets/uploads/original_image/'.$filename;
                        $small_image_path = public_path().'/assets/uploads/small/'.$filename;

                        //Resize Image
                        Image::make($image_tmp)->save($original_image_path);
                        Image::make($image_tmp)->resize(100,100)->save($small_image_path);

                        $product->image = $filename;
                    }
                }


                $product->save();

                DB::commit();

                return response()->json([
                    'message' => 'Products Added Successfully'
                ],200);

            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],500);
            }
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : create Blog Posts

                if($request->hasFile('image')){

                    $image_tmp = $request->file('image');
                    if($image_tmp->isValid()){
                        $extenson = $image_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extenson;

                        $original_image_path = public_path().'/assets/uploads/original_image/'.$filename;
                        $small_image_path = public_path().'/assets/uploads/small/'.$filename;

                        //Resize Image
                        Image::make($image_tmp)->save($original_image_path);
                        Image::make($image_tmp)->resize(100,100)->save($small_image_path);

                    }
                }else{
                    $filename = $request->current_image;
                }

                $product = Products::findOrFail($id);

                $product->title = $request->title;
                $product->description = $request->description;
                $product->price = $request->price;
                $product->image = $filename;

                $product->save();

                DB::commit();

                return response()->json([
                    'message' => 'Products Updated Successfully'
                ],200);

            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],500);
            }
        }
    }


    public function deleteImage($id)
    {
        $product = Products::findOrFail($id);

        if ($product->image)
        {
            $original_path = public_path().'/assets/uploads/original_image/'.$product->image;
            $small_path = public_path().'/assets/uploads/small/'.$product->image;

            unlink($original_path);
            unlink($small_path);
        }

        $product->update(['image' => null]);

        return response()->json([
            'updateProduct' => $product,
            'status_code' => 200
        ],200);
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);

        if (!$product->image)
        {
            $original_path = public_path().'/assets/uploads/original_image/'.$product->image;
            $small_path = public_path().'/assets/uploads/small/'.$product->image;

            unlink($original_path);
            unlink($small_path);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product Deleted Successfully',
            'status_code' => 200
        ],200);
    }


}
