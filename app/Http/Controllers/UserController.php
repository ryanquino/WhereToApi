<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\PushNotificationDevice;
use App\Remittance;
Use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
                'status' => 1,
                'userType' => 0,
                'barangayId' => $request->json()->get('barangayId'),
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
            if($user['userType'] == 1){
                $goOnline = DB::table('users')->where('id', $user['id'])->update(['status'=> 1]);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json([
            'success'=> true,
            'user'=> $user,
            'userType'=>$user['userType'],
            'password'=>JWTAuth::user()->password,
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

    public function goOffline($id){
        $user = User::find($id);
        $user->status = 0;

        if($user->save()){
            return response()->json(true);
        }
        else return response()->json(false);
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

    public function assignPlayerId(Request $request){
        $userId = $request->json()->get('userId');
        $playerId = $request->json()->get('playerId');
        
        $device = PushNotificationDevice::firstOrCreate(['userId' => $userId, 'deviceId' => $playerId]);

        if($device->wasRecentlyCreated)return response()->json(true);
        else return response()->json('Duplicate Entry');     

    }

    public function getAllRiderPlayerId(){
        $details = DB::table('notification_device')
                        ->join('users', 'users.id', '=', 'notification_device.userId')
                        ->select('notification_device.deviceId')
                        ->where('userType', '=', 1)
                        ->get();

        return response()->json($details);

    }

    public function getUserDeviceId($id){
        $details = DB::table('notification_device')
                        ->select('notification_device.deviceId')
                        ->where('userId', '=', $id)
                        ->get();

        return response()->json($details);

    }

    public function getAllAdminDeviceId(){
        $details = DB::table('users')
                        ->join('notification_device', 'users.id', '=', 'notification_device.userId')
                        ->select('notification_device.deviceId')
                        ->where('users.userType', '=', 2)
                        ->get();

        return response()->json($details);

    }

    public function rateRider(Request $request){
        $riderId = $request->json()->get('riderId');
        $currentRating = $request->json()->get('rating');

        $rate = DB::table('rider_details')->select('starRating', 'rateCount')->where('riderId','=',$riderId)->get();


        $rating = $rate[0]->starRating + $currentRating;
        $rateCount = $rate[0]->rateCount + 1;

        $rate = DB::table('rider_details')
                    ->where('riderId', $riderId)
                    ->update(
                        ['starRating' => round($rating), 'rateCount' => $rate[0]->rateCount+1]
                    );

        return response()->json($average);       

    }

    public function getRiderRating($id){
        $rate = DB::table('rider_details')->select('starRating', 'rateCount')->where('riderId','=',$id)->get();

        $average = $rate[0]->starRating/$rate[0]->rateCount;

        return response()->json($average);    
    }

    public function commentRider(Request $request){
        $riderId = $request->json()->get('riderId');
        $comment = $request->json()->get('comment');

        $comment = DB::table('rider_comments')->insert(
                    ['riderId' => $riderId, 'comment' => $comment]
                );
    }

    public function getRiderComments($id){
        $comments = DB::table('rider_comments')->select('comment')->where('riderId','=',$id)->get();

        return response()->json($comments);

    }
    public function addRider(Request $request){        
        $rider = new User;

        $rider->name = $request->json()->get('name');
        $rider->email = $request->json()->get('email');
        $rider->contactNumber = $request->json()->get('contactNumber');
        $rider->address = $request->json()->get('address');
        $rider->barangayId = $request->json()->get('barangayId');
        $rider->password = Hash::make("temppass");
        $rider->status = 0;
        $rider->userType = 1;

        $rider->save();

        $riderId = $rider->id;
        $licenseNumber = $request->json()->get('licenseNumber');
        $plateNumber = $request->json()->get('plateNumber');
        $addRiderDetails = DB::table('rider_details')->insert(['riderId' => $riderId, 'licenseNumber' => $licenseNumber, 'plateNumber' => $plateNumber]);
    }

    public function getRiderDetails($id){
        $rider = DB::table('rider_details')
            ->select('plateNumber', 'licenseNumber')
            ->where('riderId', '=', $id)->get();

        return response()->json($rider);  
    }

    public function changePassword(){
        $validator = Validator::make($request->json()->all() , [
                'password' => 'required|string|min:6', 
            ]);
 v
        if($validator->fails()){
                    return response()->json(
                        $validator->errors()->toJson(), 400,
                    );
            }
        $userId = $request->json()->get('userId');
        $user = User::where('id', $userId)
          ->update(['password' => Hash::make($request->json()->get('password'))]);
    }




}
