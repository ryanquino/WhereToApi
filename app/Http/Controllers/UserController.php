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
                'latitude' => $request->json()->get('latitude'),
                'longitude' => $request->json()->get('longitude'),
                'password' => Hash::make($request->json()->get('password')),
                'status' => 1,
                'userType' => 0,
                'barangayId' => $request->json()->get('barangayId'),
                'imagePath' => $request->json()->get('imagePath')
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

                if($this->checkRiderIfSuspended($user['id'])){              
                    return response()->json(['suspended'=>true]);
                }
                else{

                    if($this->checkRiderRemittance($user['id'])){
                        return response()->json(['remitPending'=>true, 'user'=> $user]);
                    }
                    else{
                        $this->addRemittanceRecord($user['id']);

                        return response()->json([
                            'success'=> true,
                            'user'=> $user,
                            'userType'=>$user['userType'],
                            'token' =>$token
                        ]);
                    }
                    
                }
            }
            else{
                return response()->json([
                    'success'=> true,
                    'user'=> $user,
                    'userType'=>$user['userType'],
                    'password'=>JWTAuth::user()->password,
                    'token' =>$token
                ]);
            }
            
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
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

    public function addRemittanceRecord($id){
        $ifExists = DB::select('SELECT riderId, created_at from remittance where riderId = ? and date(created_at) = ?', [$id, date('Y-m-d')]);

        if(empty($ifExists)){
            $remit = new Remittance;
            $remit->riderId = $id;
            $remit->amount = 0;
            $remit->imagePath = NULL;
            $remit->status = 0;

            $remit->save();
        }       
    }


    public function goOffline($id){
        $user = User::find($id);
        $user->status = 0;

        if($user->save()){
            return response()->json(true);
        }
        else return response()->json(false);
    }

    public function checkRiderRemittance($id){
        if(Remittance::where('riderId', $id)->count() == 0){
            return false;
        }

        else{

            $date = Remittance::where('riderId', $id)
                ->select(DB::raw('date(created_at) as createdDate'))
                ->latest()
                ->first();

            if($date->createdDate == date('Y-m-d')){
                return false;
            }
            else{
               $remitStatus = DB::select('SELECT imagePath from remittance where riderId = ? and date(created_at) < CURDATE() ORDER BY created_at DESC', [$id]);

                if(empty($remitStatus[0]->imagePath)){
                    return true;
                }
                else{
                    return false;
                }  
            }
            
        }
               
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

    public function getAllRiderPlayerId($id){
        $details = DB::table('notification_device')
                        ->join('users', 'users.id', '=', 'notification_device.userId')
                        ->select('notification_device.deviceId')
                        ->where('userType', '=', 1)
                        ->where('notification_device.status', 0)
                        ->where('users.cityId',$id)
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
        $rider->latitude = $request->json()->get('latitude');
        $rider->longitude = $request->json()->get('longitude');
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

    public function changePassword(Request $request){
        $validator = Validator::make($request->json()->all() , [
                'password' => 'required|string|min:6', 
            ]);

        if($validator->fails()){
                    return response()->json(
                        $validator->errors()->toJson(), 400,
                    );
            }
        $userId = $request->json()->get('userId');
        $user = User::where('id', $userId)
          ->update(['password' => Hash::make($request->json()->get('password'))]);
    }

    public function checkRiderIfSuspended($id){
        $isSuspended = DB::table('rider_details')->select('isSuspended')->where('riderId', $id)->get();
        

        if($isSuspended[0]->isSuspended == 0){
            return false;
        }
        else if($isSuspended[0]->isSuspended == 1){
            return true;
        }   
    }

    public function suspendRider($id){
        $suspend = DB::table('rider_details')->where('riderId', $id)->update(['isSuspended'=> 1]);
    }

    public function unSuspendRider($id){
        $suspend = DB::table('rider_details')->where('riderId', $id)->update(['isSuspended'=> 0]);
    }

    public function addCity(Request $request){
        $userId = $request->json()->get('userId');
        $cityId = $request->json()->get('cityId');
        $barangayId =$request->json()->get('barangayId');

        $city = DB::table('users')
                    ->where('id', $userId)
                    ->update(['cityId'=> $cityId, 'barangayId' => $barangayId]);
    }

    public function getCity(){
        $city = DB::table('city')->select('id', 'cityName')->get();

        return response()->json($city); 
    }

    public function addCityFranchise(Request $request){
        $cityName = $request->json()->get('cityName');

        $city = DB::table('city')->insert([
                    ['cityName' => $cityName],
                ]);
    }

    public function addBarangayCharge(Request $request){
        $cityId = $request->json()->get('cityId');
        $barangayName = $request->json()->get('cityName');
        $charge = $request->json()->get('charge');

        $brgy = DB::table('city')->insert([
                    ['cityId' => $cityId, 'barangayName' => $barangayName, 'charge' => $charge],
                ]);

    }

}
