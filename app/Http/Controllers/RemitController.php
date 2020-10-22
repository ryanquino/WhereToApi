<?php

namespace App\Http\Controllers;

use App\Remittance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function riderRemit(Request $request){
        $remitId = $request->json()->get('remitId');
        $imagePath = $request->json()->get('imagePath');

        $remit = Remittance::find($remitId);
        $remit->imagePath = $imagePath;
        $remit->save();
    }
    public function getRiderRemit($id){
        $remit = Remittance::where('riderId', $id)->where('status', 0)->first();
        
        return response()->json($remit);

    }

    public function viewRiderRemittance($id){
        $remittance = DB::table('remittance')
            ->join('users', 'users.id', '=', 'remittance.riderId')
            ->select('remittance.id','remittance.riderId', 'users.name', 'remittance.imagePath')
            ->where('remittance.status', 0)
            ->where('users.cityId', $id)
            ->get();

        return response()->json($remittance);
    }
    public function viewRemittedList($id){
        $list = DB::table('remittance')
            ->join('users', 'users.id', '=', 'remittance.riderId')
            ->select('remittance.id','remittance.riderId', 'users.name', 'remittance.amount','remittance.imagePath', 'remittance.status', 'remittance.created_at')
            ->where('remittance.status', 0)
            ->where('users.cityId', $id)
            ->whereNotNull('remittance.imagePath')->get();


        return response()->json($list);
    }
    public function viewUnremittedList($id){
        $list = DB::table('remittance')
            ->join('users', 'users.id', '=', 'remittance.riderId')
            ->select('remittance.id','remittance.riderId', 'users.name', 'remittance.amount','remittance.imagePath', 'remittance.status', 'remittance.created_at')
            ->where('remittance.imagePath' , NULL)
            ->where('users.cityId', $id)->get();

        return response()->json($list);
    }
    public function approveRemittance($id){
        $remit = Remittance::where('id', $id)
          ->update(['status' => 1]);

        $rider = Remittance::find($id);

        $riderId = $rider->riderId;
        $suspend = DB::table('rider_details')->where('riderId', $riderId)->update(['isSuspended'=> 0]);
    }


}
