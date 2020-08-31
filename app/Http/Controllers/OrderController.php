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
        $restaurantId = $request->json()->get('restaurantId');
        $orderList = $request->json()->get('order');
        $optionalAddress = $request->json()->get('deliveryAddress');
        $deliveryCharge = $request->json()->get('deliveryCharge');
        $barangayId = $request->json()->get('barangayId');

        $order = new Order;
        $order->clientId = $userId;
        $order->restaurantId = $restaurantId;
        $order->deliveryAddress = $optionalAddress;
        $order->deliveryCharge = $deliveryCharge;
        $order->barangayId = $barangayId;
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

    public function updateOrder(Request $request){
        $transactionId = $request->json()->get('transactionId');
        $menuToRemove = $request->json()->get('oldOrder');
        $newOrderList = $request->json()->get('newOrder');

        for ($i=0; $i < count($menuToRemove); $i++) { 
            $foodOrder = DB::table('food_orders')
                        ->where('transactionId','=', $transactionId)
                        ->where('menuId','=', $menuToRemove[$i]['menuId'])
                        ->update(['menuId' => $newOrderList[$i]['menuId'], 'quantity' => $newOrderList[$i]['quantity']]);
        }

        // if(count($newOrderList) >= count($menuToRemove)){
        //     $diff = count($newOrderList ) - count($menuToRemove);
        //     for ($i=0; $i < count($menuToRemove); $i++) { 
        //         $foodOrder = DB::table('food_orders')
        //                     ->where('transactionId','=', $transactionId)
        //                     ->where('menuId','=', $menuToRemove[$i]['menuId'])
        //                     ->update(['menuId' => $newOrderList[$i]['menuId'], 'quantity' => $newOrderList[$i]['quantity']]);
        //     }

        //     if($foodOrder != 0){
        //         for ($i=$diff; $i < count($newOrderList); $i++) { 
        //         $newOrder = DB::table('food_orders')
        //             ->insert(['transactionId' => $transactionId, 
        //                         'menuId' => $newOrderList[$i]['menuId'], 
        //                         'quantity' => $newOrderList[$i]['quantity']]);
        //         }
        //     }         
        // }
        if($foodOrder==1)return response()->json(true);
        else return response()->json(false);
    }

    public function getOrdersPerTransaction($id){
        $menu = DB::table('menu')
            ->join('food_orders', 'food_orders.menuId', '=', 'menu.id')
            ->join('transactions', 'transactions.id', '=', 'food_orders.transactionId')
            ->select('menu.menuName', 'menu.description', 'menu.price', 'food_orders.quantity')
            ->where('food_orders.transactionId', '=', $id)
            ->get();

        return response()->json($menu);
    }
    public function getTransactionDetails(){
        $details = DB::table('transactions')
            ->join('users', 'users.id', '=', 'transactions.clientId')
            ->join('restaurants', 'restaurants.id', '=', 'transactions.restaurantId')
            ->join('notification_device', 'users.id', '=', 'notification_device.userId')
            ->join('barangay', 'barangay.id', '=', 'transactions.barangayId')
            ->select('transactions.id','users.name', 'users.contactNumber','barangay.barangayName','restaurants.restaurantName','restaurants.address', 'transactions.deliveryAddress', 'transactions.created_at', 'notification_device.deviceId', 'transactions.riderId', 'transactions.status', 'transactions.deliveryCharge')
            ->where('transactions.riderId', NULL)
            ->where('transactions.status', '=', 0)
            ->get();

        return response()->json($details);

    }
    public function getTransactionDetailsById($id){
        $details = DB::table('transactions')
            ->join('users', 'users.id', '=', 'transactions.clientId')
            ->join('restaurants', 'restaurants.id', '=', 'transactions.restaurantId')
            ->join('notification_device', 'users.id', '=', 'notification_device.userId')
            ->join('barangay', 'barangay.id', '=', 'transactions.barangayId')
            ->select('transactions.id','users.name', 'users.contactNumber', 'barangay.barangayName','restaurants.restaurantName','restaurants.address', 'transactions.deliveryAddress', 'transactions.created_at', 'notification_device.deviceId', 'transactions.riderId', 'transactions.status', 'transactions.deliveryCharge')
            ->where('transactions.id', '=', $id)
            ->get();

        return response()->json($details);

    }

    public function viewUserOrders($id){
        $currentOrders = DB::table('transactions')
                ->join('restaurants', 'restaurants.id', '=', 'transactions.restaurantId')
                ->select('transactions.id','restaurants.restaurantName','restaurants.address', 'transactions.deliveryAddress', 'transactions.created_at', 'transactions.riderId', 'transactions.status')
                ->where('transactions.clientId', '=', $id)
                ->get();

        return response()->json($currentOrders);
    }
    public function assignRider(Request $request){
        $transactionId = $request->json()->get('transactionId');
        $details = DB::table('transactions')->select('riderId')->where('id', '=', $transactionId)->get();
        $id = json_decode($details);
        foreach ($id as $value) {
            foreach ($value as $key => $val) {
                $riderId = $val;            }
        }
        if($riderId == null){
            $order = Order::find($transactionId);
            $order->riderId = $request->json()->get('riderId');
            $order->status = 1;            
            $order->save();

            return response()->json(true);
        }
        else{
            return response()->json(false);
        }
        
    }

    public function cancelOrder($id){
        $order = DB::table('transactions')->where('id', $id)->delete();
        $order = DB::table('food_orders')->where('transactionId', $id)->delete();

        return response()->json($order);
    }
    public function transactionComplete($id){
        $order = Order::find($id);
        $order->status = 4;


        if($order->save()){
            $menus = DB::table('food_orders')->select('menuId', 'quantity')->where('transactionId','=',$id)->get();
            $menuList = json_decode($menus);

            for ($i=0; $i < count($menuList); $i++) { 
                $increment = DB::table('menu')->where('id','=',$menuList[$i]->menuId)->increment('timesBought', $menuList[$i]->quantity);
            }
            return response()->json(true);
        }
        else{
            return response()->json(false);
        }
    }
    public function transactionDelivery($id){
        $order = Order::find($id);
        $order->status = 3;
        if($order->save()){
            return response()->json(true);
        }
        else{
            return response()->json(false);
        }
    }
    public function transactionBuying($id){
        $order = Order::find($id);
        $order->status = 2;
        if($order->save()){
            return response()->json(true);
        }
        else{
            return response()->json(false);
        }
    }
}
