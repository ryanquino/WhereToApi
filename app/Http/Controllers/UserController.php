<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
Use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\PayloadFactory;
use Tymon\JWTAuth\JWTManager as JWT;

class UserController extends Controller
{
    //
        public function register(Request $request)
    {
            $validator = Validator::make($request->json()->all() , [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:55|unique:users',
            'contactNumber' => 'required|string|max:11|unique:users',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:6', 
        ]);

        if($validator->fails()){
                return response()->json(
                    $validator->errors()->toJson(), 400,
                );
        }

        $user = User::create([
            'name' => $request->json()->get('name'),
            'email' => $request->json()->get('email'),
            'contactNumber' => $request->json()->get('contactNumber'),
            'address' => $request->json()->get('address'),
            'password' => Hash::make($request->json()->get('password')),
            'status' => 1
        ]);

        $token = JWTAuth::fromUser($user);
           
            return response()->json([
                'success'=> true,
                'user'=> $user,
                'token' =>$token]);
           
           
        // return response()->json(compact('user','token'),201);
        
    }
    
    public function login(Request $request)
    {
        $credentials = $request->json()->all();
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => false,
                    'error' => 'invalid_credentials'], 400);
            }
            $user = JWTAuth::user();
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json([
            'success'=> true,
            'user'=> $user,
            'token' =>$token
           

        ]);
    }


    public function logout(Request $request)
    {
           
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
           
            return response()->json([
                'success'=>true,
                'message'=>'Logout Success']);

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    public function getCurrentUser(Request $request){
       if(!User::checkToken($request)){
           return response()->json([
            'message' => 'Token is required'
           ],422);
       }
        
        $user = JWTAuth::parseToken()->authenticate();
       $isProfileUpdated=false;
        if($user->isPicUpdated==1 && $user->isEmailUpdated){
            $isProfileUpdated=true;
            
        }
        $user->isProfileUpdated=$isProfileUpdated;

        return $user;
}



    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }
}
