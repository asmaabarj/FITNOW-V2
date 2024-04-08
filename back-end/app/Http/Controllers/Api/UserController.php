<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // ----------Register The User----------
/**
* @OA\Post(
     *     path="/api/auth/register",
     *      *  *      tags={"userAuth"},

     *     summary="Add New user",
     *    
     *  
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="user name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="user email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     
     *     @OA\Response(response="200", description="User created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }


    // ----------Login The User----------
/**
* @OA\Post(
     *     path="/api/auth/login",
     *      *  *      tags={"userAuth"},

     *     summary="log user",
     *    
     *  
     *  
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="user email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     
     *     @OA\Response(response="200", description="User created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password do not match with our records.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    // ----------Logout The User----------
/**
* @OA\Post(
     *     path="/api/auth/logout",
     *      *  *      tags={"userAuth"},

     *     summary="logout user",
     *    
     *  
   
     *     @OA\Response(response="200", description="User created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function logoutUser()
    {

        Auth::logout();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ], 200);
    }

}
