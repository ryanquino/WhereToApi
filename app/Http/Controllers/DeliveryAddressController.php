<?php

namespace App\Http\Controllers;

use App\User;
use App\DeliveryAddress;
use Illuminate\Http\Request;

class DeliveryAddressController extends Controller
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
     * @param  \App\DeliveryAddress  $deliveryAddress
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryAddress $deliveryAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DeliveryAddress  $deliveryAddress
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryAddress $deliveryAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DeliveryAddress  $deliveryAddress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryAddress $deliveryAddress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DeliveryAddress  $deliveryAddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryAddress $deliveryAddress)
    {
        //
    }

    public function assignNewAddress(Request $request){
        $userId = $request->json()->get('userId');
        $addressName = $request->json()->get('addressName');
        $latitude = $request->json()->get('latitude');
        $longitude = $request->json()->get('longitude');

        $address = new DeliveryAddress;
        $address->userId  = $userId;
        $address->addressName = $addressName;
        $addressName->latitude = $latitude;
        $addressName->longitude = $longitude;
        $address->save();

    }

    public function getUserDeliveryAddress($id){
        $address = DeliveryAddress::where('userId', $id)->get();

        return response()->json($address);
    }
}
