<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = Category::whereNull('deleted_at')->get();
        if(empty($records)){
            return response()->json([
                'error' => 'true',
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Records is Empty'
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
            'category_name' => 'required',
            'description' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'mesage' => $validator->errors()
            ]);
        }
        try{
            $category = new Category();
            $category->category_name = $params['category_name'];
            $category->description = $params['description']?? null;
            $category->save();
            return response()->json([
                'error' => false,
                'code' => Response::HTTP_OK,
                'message' => 'Action completed successfully',
            ]);
        }catch(Exception $e){
            Log::info($e);
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'message' => 'Action failed',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $records = Category::where('id', $id)->whereNull('deleted_at')->first();
        if(empty($records)){
            return response()->json([
                'error' => true,
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Record is Empty'
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
            'category_name' => 'required',
            'description' => 'nullable',
         ]);
         if ($validator->fails()) {
             return response()->json([
                 'error' => true,
                 'code'=> Response::HTTP_BAD_REQUEST,
                 'mesage' => $validator->errors()
             ]);
         }
         try{
             $category = Category::where('id', $id)->whereNull('deleted_at')->first();
             $category->category_name = $params['category_name'];
             $category->description = $params['description']?? null;
             $category->save();
             return response()->json([
                 'error' => false,
                 'code' => Response::HTTP_OK,
                 'message' => 'Action completed successfully',
             ]);
         }catch(Exception $e){
             Log::info($e);
             return response()->json([
                 'error' => true,
                 'code'=> Response::HTTP_BAD_REQUEST,
                 'message' => 'Action failed',
             ]);
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $category = Category::where('id', $id)->whereNull('deleted_at')->first();
            $product = Product::where('category_id', $category->id)->whereNull('deleted_at')->first();
            if($product !== NULL){
                return response()->json([
                    'error' => true,
                    'code'=> Response::HTTP_BAD_REQUEST,
                    'message' => 'Some products are using this Category',
                ]);
            }else{
                $category->deleted_at = Carbon::now();
                $category->save();
                return response()->json([
                    'error' => false,
                    'code' => Response::HTTP_OK,
                    'message' => 'Action completed successfully',
                ]);
            }
        }catch(Exception $e){
            Log::info($e);
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'message' => 'Action failed',
            ]);
        }
    }
}
