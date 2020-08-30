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
    		->select(DB::raw('transactions.id, transactions.deliveryAddress, menu.menuName, menu.price, food_orders.quantity, (menu.price *food_orders.quantity) as total'))
    		->where('transactions.restaurantId', $restaurantId)
    		->whereBetween('transactions.created_at', [$dateFrom, $dateTo])
    		->get();

    	return response()->json($report);
    }
}
