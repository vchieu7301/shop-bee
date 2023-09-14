<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = Order::whereNull('deleted_at')->get();
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = $request->user()->id;
        $params = $request->input();
        $validator = Validator::make($request->input(), [
            'status'=> 'required',
            'payment_method'=> 'required',
            'shipping_address'=> 'required',
            'coupon_code' => 'nullable',
            'shipping_fee' => 'nullable',
            'order_items' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'mesage' => $validator->errors()
            ]);
        }
        try{
            $order = new Order();
            $order->user_id = $user_id;
            $order->order_date = Carbon::now();
            $order->status = $params['status'];
            $order->payment_method = $params['payment_method'];
            $order->shipping_address = $params['shipping_address'];
            $order->coupon_code = $params['coupon_code'];
            $order->shipping_fee = $params['shipping_fee'];
            $order->save();
            foreach ($request->order_items as $itemData) {
                $product = Product::where('id', $itemData['product_id'])->first();
                $totalPrice = $product->price * $itemData['quantity'];
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $itemData['quantity'];
                $orderItem->subtotal = $totalPrice;
                $orderItem->save();
            }
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
        $records = Order::where('id', $id)->whereNull('deleted_at')->first();
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
            'status'=> 'required',
            'payment_method'=> 'required',
            'shipping_address'=> 'required',
            'coupon_code' => 'nullable',
            'shipping_fee' => 'nullable',
            'tax_amount'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'mesage' => $validator->errors()
            ]);
        }
        try{
            $order = new Order();
            $order->order_date = Carbon::now();
            $order->status = $params['status'];
            $order->payment_method = $params['payment_method'];
            $order->shipping_address = $params['shipping_address'];
            $order->coupon_code = $params['coupon_code'];
            $order->shipping_fee = $params['shipping_fee'];
            $order->save();
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
            $order = Order::where('id', $id)->whereNull('deleted_at')->first();
            $order->deleted_at = Carbon::now();
            $order->save();
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

    public function palceOrder(Request $request)
    {
        $user_id = $request->user()->id;
        $params = $request->input();
        $validator = Validator::make($request->input(), [
            'payment_method'=> 'required',
            'shipping_address'=> 'required',
            'coupon_code' => 'nullable',
            'shipping_fee' => 'nullable',
            'order_items' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'mesage' => $validator->errors()
            ]);
        }
        try{
            $order = new Order();
            $order->user_id = $user_id;
            $order->order_date = Carbon::now();
            $order->status = 'Pending Confirmation';
            $order->payment_method = $params['payment_method'];
            $order->shipping_address = $params['shipping_address'];
            $order->coupon_code = $params['coupon_code'];
            $order->shipping_fee = $params['shipping_fee'];
            $order->save();
            foreach ($request->order_items as $itemData) {
                $product = Product::where('id', $itemData['product_id'])->first();
                $totalPrice = $product->price * $itemData['quantity'];
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $itemData['quantity'];
                $orderItem->subtotal = $totalPrice;
                $orderItem->save();
            }
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

    public function cancelOrder(string $id)
    {
        try{
            $order = Order::where('id', $id)->whereNull('deleted_at')->first();
            $order->status = 'Cancelled';
            $order->deleted_at = Carbon::now();
            $order->save();
            return response()->json([
                'error' => false,
                'message' => 'Successfull',
            ]);
        }catch(Exception $e){
            Log::info($e);
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'message' => 'An error occurred while canceling the order',
            ]);
        }
    }
}
