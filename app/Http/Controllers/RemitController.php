<?php

namespace App\Http\Controllers;

use App\Remittance;
use Illuminate\Http\Request;

class RemitController extends Controller
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
     * @param  \App\Remittance  $remittance
     * @return \Illuminate\Http\Response
     */
    public function show(Remittance $remittance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Remittance  $remittance
     * @return \Illuminate\Http\Response
     */
    public function edit(Remittance $remittance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Remittance  $remittance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Remittance $remittance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Remittance  $remittance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Remittance $remittance)
    {
        //
    }

    puglic function riderRemit(){
        $riderId = $request->json()->get('riderId');
        $imagePath = $request->json()->get('imagePath');
        $remit = new Remittance;
        $remit->riderId = $riderId;
        $remit->imagePath = $imagePath;
        $remit->status = 0;

        $remit->save();
    }

    public function viewRiderRemittance($id){
        $remittance = Remittance::where('riderId', $id)->first();

        return response()->json($remittance);
    }

    public function approveRemittance($id){
        $remit = Remittance::where('riderId', $id)
          ->update(['status' => 1]);
    }
}