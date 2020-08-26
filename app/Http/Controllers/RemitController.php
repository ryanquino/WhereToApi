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
        $remit = Remittance::where('riderId', $id)->where('imagePath', NULL)->first();
        
        return response()->json($remit);

    }

    public function viewRiderRemittance(){
        $remittance = DB::table('remittance')
            ->join('users', 'users.id', '=', 'remittance.riderId')
            ->select('remittance.riderId', 'users.name', 'remittance.imagePath')
            ->where('remittance.status', 0)
            ->get();

        return response()->json($remittance);
    }
    public function viewRemittedList(){
        $list = DB::table('remittance')
            ->join('users', 'users.id', '=', 'remittance.riderId')
            ->select('remittance.riderId', 'users.name', 'remittance.amount','remittance.imagePath', 'remittance.status', 'remittance.created_at')
            ->whereNotNull('imagePath')->get();


        return response()->json($list);
    }
    public function viewUnremittedList(){
        $list = DB::table('remittance')
            ->join('users', 'users.id', '=', 'remittance.riderId')
            ->select('remittance.riderId', 'users.name', 'remittance.amount','remittance.imagePath', 'remittance.status', 'remittance.created_at')
            ->where('imagePath' , NULL)->get();

        return response()->json($list);
    }
    public function approveRemittance($id){
        $remit = Remittance::where('id', $id)
          ->update(['status' => 1]);

        $suspend = DB::table('rider_details')->where('riderId', $id)->update(['isSuspended'=> 0]);
    }
    public function checkRiderRemittance($id){
        $remitStatus = DB::select('SELECT imagePath from remittance where riderId = ? and date(created_at) = CURDATE()-1', [$id]);
        if(empty($remitStatus[0]->imagePath)){
            return response()->json(true);
        }
        else{
            return response()->json(false);
        }        
    }
}
