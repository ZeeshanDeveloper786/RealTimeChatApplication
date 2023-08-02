<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

class authController extends Controller
{
    public function register(Request $req){
      
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }


       $userRegistered =  User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);

        // $token = $userRegistered->createToken('token')->accessToken;
        if($userRegistered){

        $message = 'Data saved successfully!';
        session()->flash('success', $message);
        return response()->json(['success' =>true,'message' => $message]);

        }else{
       
            $message = 'something went wrong';
            session()->flash('success', $message);
            return response()->json(['success' =>false,'message' => $message]);
        }
    }

    public function login(Request $req){
        $validator = Validator::make($req->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }   

        $cred = [
            'email' => $req->email,
            'password' => $req->password,
        ];

        if(Auth::attempt($cred)){
          $token = auth()->user()->createToken('Token')->accessToken;
         
          return response()->json(['token' => $token,'success' => true]);
        }else{
        return response()->json(['message' => 'Something went wrong']);
        }
    }

    public function logout(Request $request){
        $done = $request->user()->tokens()->delete();
        if($done){
            return response()->json(['message' => 'Logged out successfully'], 200);   
        }else{
            return response()->json(['message' => 'Something went wrong'], 400);   
        }
    }

    
}
 