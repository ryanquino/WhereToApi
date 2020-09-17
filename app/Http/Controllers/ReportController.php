<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function getRestaurantSalesReport(Request $request){
    	$restaurantId = $request->json()->get('restaurantId');
    	$dateFrom = $request->json()->get('dateFrom');
    	$dateTo = $request->json()->get('dateTo');

    	$report = DB::table('transactions')
    		->join('food_orders', 'food_orders.transactionId', '=', 'transactions.id')
    		->join('menu', 'food_orders.menuId', '=', 'menu.id')
    		->select(DB::raw('transactions.id, menu.menuName, menu.price, food_orders.quantity, (menu.price *food_orders.quantity) as total'))
    		->where('transactions.restaurantId', $restaurantId)
    		->whereBetween('transactions.created_at', [$dateFrom, $dateTo])
    		->get();

    	// $report = DB::select('Select transactions.id, transactions.deliveryAddress, menu.menuName, menu.price, food_orders.quantity, (menu.price *food_orders.quantity) as total from transactions join food_orders on food_orders.transactionId = transactions.id join menu on menu.id = food_orders.menuId where transactions.restaurantId = ? and date(transactions.created_at) BETWEEN ? and ?', [$restaurantId, $dateFrom, $dateTo]);

    	return response()->json($report);
    }

    public function getTotalRestaurantSalesReport(Request $request){
    	$restaurantId = $request->json()->get('restaurantId');
    	$dateFrom = $request->json()->get('dateFrom');
    	$dateTo = $request->json()->get('dateTo');
    	$total = DB::select('Select SUM(menu.price *food_orders.quantity) as totalAmount from transactions join food_orders on food_orders.transactionId = transactions.id join menu on menu.id = food_orders.menuId where transactions.restaurantId = ? and date(transactions.created_at) BETWEEN ? and ?', [$restaurantId, $dateFrom, $dateTo]);

    	return response()->json($total);
    }

    public function getRemittanceList(Request $request){
        $dateFrom = $request->json()->get('dateFrom');
        $dateTo = $request->json()->get('dateTo');

        $list = DB::table('remittance')
            ->join('users', 'users.id', '=', 'remittance.riderId')
            ->select('users.name', 'remittance.amount', 'remittance.created_at')
            ->where('remittance.status', 1)
            ->whereBetween('remittance.created_at', [$dateFrom, $dateTo])
            ->get();

        return response()->json($list);
    }

    public function getTotalSalesCommission(Request $request){
        $dateFrom = $request->json()->get('dateFrom');
        $dateTo = $request->json()->get('dateTo');

        $total = DB::select('SELECT SUM(amount) from remittance where date(remittance.created_at) BETWEEN ? and ?', [$dateFrom, $dateTo]);

        return response()->json($total);
    }

    public function getReceipt($id){

        $details = DB::table('transactions')
            ->join('users', 'users.id', '=', 'transactions.clientId')
            ->join('restaurants', 'restaurants.id', '=', 'transactions.restaurantId')
            ->join('barangay', 'barangay.id', '=', 'transactions.barangayId')
            ->select('transactions.id','users.name', 'users.contactNumber','barangay.barangayName','restaurants.restaurantName', 'transactions.created_at', 'transactions.deliveryCharge')
            ->where('transactions.id', $id)
            ->get();

        $orders = DB::table('transactions')
            ->join('food_orders', 'food_orders.transactionId', '=', 'transactions.id')
            ->join('menu', 'food_orders.menuId', '=', 'menu.id')
            ->join('users', 'users.id', '=', 'transactions.clientId')
            ->select(DB::raw('menu.menuName, ((menu.price * menu.markUpPercentage) + menu.price) as totalPrice, food_orders.quantity, (((menu.price * menu.markUpPercentage) + menu.price)*food_orders.quantity) as totalAmount'))
            ->where('food_orders.transactionId', $id)
            ->get();

        $rider = DB::table('users')
            ->join('transactions', 'users.id', '=', 'transactions.riderId')
            ->select('users.name', 'users.contactNumber')
            ->where('transactions.id', $id)
            ->get();

        return response()->json(array('transactionDetails' => $details, 'orders' => $orders, 'riderDetails' => $rider));
    }
}
