<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $params = $request->input();
        $validator = Validator::make($request->input(), [
        'name' => 'required',
        'password' => 'required',
        'email' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'mesage' => $validator->errors()
            ]);
        }
        try{
            $user = new User();
            $user->name = $params['name'];
            $user->password = bcrypt($params['password']);
            $user->email = $params['email'];
            $user->save();
            return response()->json([
                'error' => false,
                'message' => 'Successfull',
            ]);
        }catch(Exception $e){
            Log::info($e);
            return response()->json([
                'error' => true,
                'code'=> Response::HTTP_BAD_REQUEST,
                'message' => 'Add user fail',
            ]);
        }
    }
    
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $tokenName = 'Personal Access Token';
            $token = $request->user()->createToken($tokenName);
            return response()->json([
                'token' => $token->plainTextToken
            ]);
        }
        return response()->json([
            'error' => true,
            'code' => Response::HTTP_UNAUTHORIZED,
            'mesage' => 'Invalid client'
        ]);
    }

    public function unauthenticated ()
    {
        return response()->json(['error' => true,'code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Unauthenticated']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
