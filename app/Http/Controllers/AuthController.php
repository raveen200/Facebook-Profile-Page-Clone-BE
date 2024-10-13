<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function registerPost(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('profile_image')) {
            $imageName = time().'.'.$request->profile_image->extension();  
            $request->profile_image->move(public_path('images/profile'), $imageName);
            $user->profile_image = $imageName;
        }

        $user->save();

       $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }


    public function loginPost(Request $request)
    {
        $credetails = $request->only('email', 'password');

        if(!Auth::attempt($credetails)){
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        $user = Auth::user();
       $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

}
