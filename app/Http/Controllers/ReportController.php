<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function getRestaurantSalesReport(Request $request){
    	$report = DB::table('transactions')
    		->join('food_orders', 'food_orders.transactionId', '=', 'transactions.id')
    		->select()->get();
    }
}
