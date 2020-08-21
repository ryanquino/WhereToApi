<?php

namespace App\Http\Controllers;
use App\User;
use App\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Verification  $verification
     * @return \Illuminate\Http\Response
     */
    public function show(Verification $verification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Verification  $verification
     * @return \Illuminate\Http\Response
     */
    public function edit(Verification $verification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Verification  $verification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Verification $verification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Verification  $verification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Verification $verification)
    {
        //
    }

    public function verifyUser($id){
        $verification = Verification::where('userId', $id)
          ->update(['isVerified' => 1]);

        if($verification)response()->json(true);
        else response()->json(false);
    }

    public function submitVerification(Request $request){
        $userId = $request->json()->get('userId');
        $imagePath = $request->json()->get('imagePath');

        $verification = new Verification;
        $verification->imagePath = $imagePath;
        $verification->isVerified = 0;

        $user = User::find($userId);
        $user->verification()->save($verification);

    }

    public function viewUserVerification($id){
        $user = DB::table('verification')
            ->join('users', 'users.id', '=', 'verification.userId')
            ->where('verification.userId', $id)
            ->select('verification.userId', 'users.name', 'verification.imagePath','verification.isVerified')
            ->get();

        return response()->json($user);
    }

    public function getUnverifiedList(){
        $verificationList = DB::table('verification')
            ->join('users', 'users.id', '=', 'verification.userId')
            ->where('isVerified', 0)
            ->select('verification.userId', 'users.name', 'verification.imagePath')
            ->get();

        return response()->json($verificationList);
    }

    public function suspendAccount($id){
        $verification = Verification::where('userId', $id)
          ->update(['isSuspended' => 1]);

        if($verification)return response()->json(true);
        else response()->json(false);
    }

    public function isAccountSuspended($id){
        $isSuspended = Verification::where('userId', $id)->first()->isSuspended;

        if($isSuspended == 1)return response()->json(true);
        else if($isSuspended == 0)return response()->json(false);
    }
}
