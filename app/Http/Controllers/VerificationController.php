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
        $user = User::find($id);
        $user->isVerified = 1;
        $user->save();
    }

    public function submitVerification(){
        $userId = $request->json()->get('userId');
        $imagePath = $request->json()->get('imagePath');

        // $verification = new Verification;
        // $verification->imagePath = $imagePath;
        // $verification->isVerified = 0;

        // $user = User::find($userId);
        // $user->verification()->save($verification);

        $user = User::find($userId);

        $user->verification()->save([
            new Verification(['imagePath' => $imagePath, 'isVerified' => 0])
        ]);

    }
}
