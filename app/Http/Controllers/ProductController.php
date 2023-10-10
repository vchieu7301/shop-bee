<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = Product::whereNull('deleted_at')->get();
        if($records->isEmpty()){
            return response()->json([
                'error' => 'true',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Can find Record'
            ]);
        }else{
            return response()->json([
                'error' => 'false',
                'code' => Response::HTTP_OK,
                'result' => $records
            ]);
        }   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->input();
        $validator = Validator::make($request->input(), [
            'product_name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'quantily' => 'required',
            'product_description' => 'nullable',
            'images' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'mesage' => $validator->errors()
            ]);
        }
        try{
            $product = new Product();
            $product->product_name = $params['product_name'];
            $product->price = $params['price'];
            $product->product_description = $params['product_description'];
            $product->images = $params['images'];
            $product->category_id = $params['category_id'];
            $product->quantity = $params['quantily'];
            $product->save();
            return response()->json([
                'error' => false,
                'message' => 'Successfull',
            ]);
        }catch(Exception $e){
            Log::info($e);
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'message' => 'Add fail',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $records = Product::where('id', $id)->whereNull('deleted_at')->first();
        if(empty($records)){
            return response()->json([
                'error' => true,
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Can find record'
            ]);
        }else{
            return response()->json([
                'error' => false,
                'code' => Response::HTTP_OK,
                'result' => $records
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $params = $request->input();
        $validator = Validator::make($request->input(), [
            'product_name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'product_description' => 'nullable',
            'images' => 'nullable'
         ]);
         if ($validator->fails()) {
             return response()->json([
                 'error' => true,
                 'code'=> Response::HTTP_BAD_REQUEST,
                 'mesage' => $validator->errors()
             ]);
         }
         try{
             $product = Product::where('id', $id)->whereNull('deleted_at')->first();
             $product->product_name = $params['product_name'];
             $product->price = $params['price'];
             $product->product_description = $params['product_description'];
             $product->images = $params['images'];
             $product->category_id = $params['category_id'];
             $product->save();
             return response()->json([
                 'error' => false,
                 'message' => 'Successfull',
             ]);
         }catch(Exception $e){
             Log::info($e);
             return response()->json([
                 'error' => true,
                 'code'=> Response::HTTP_BAD_REQUEST,
                 'message' => 'Update fail',
             ]);
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $product = Product::where('id', $id)->whereNull('deleted_at')->first();
            $product->deleted_at = Carbon::now();
            $product->save();
            return response()->json([
                'error' => false,
                'message' => 'Successfull',
            ]);
        }catch(Exception $e){
            Log::info($e);
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'message' => 'Delete fail',
            ]);
        }
    }

    public function dashboardProducts()
    {
        $records = Product::where('quantity', '>', 0)->whereNull('deleted_at')->get();
        if(empty($records)){
            return response()->json([
                'error' => 'true',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Can find Record'
            ]);
        }else{
            return response()->json([
                'error' => 'false',
                'code' => Response::HTTP_OK,
                'result' => $records
            ]);
        }   
    }
}
