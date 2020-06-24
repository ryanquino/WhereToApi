<?php

namespace App\Http\Controllers;

use App\Order;
use App\Menu;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function putOrder(Request $request){
        $userId = $request->json()->get('userId');
        $orderList = $request->json()->get('order');
        $optionalAddress = $request->json()->get('optionalAddress');

        $order = new Order;
        $order->clientId = $userId;
        $order->optionalAddress = $optionalAddress;
        $order->status = 0;

        $order->save();


        $transactionId = $order->id;

        for ($i=0; $i < count($orderList); $i++) { 
            $foodOrder = DB::table('food_orders')->insert([
            ['transactionId' => $transactionId, 'menuId' => $orderList[$i]['menuId'], 'quantity' => $orderList[$i]['quantity']]
        ]);
        }

        return response()->json($transactionId);
    }
}
