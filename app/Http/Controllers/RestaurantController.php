<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class RestaurantController extends Controller
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
        $validator = Validator::make($request->json()->all() , [
                'restaurantName' => 'required|string|max:255',
                'contactNumber' => 'required|string|max:11'
            ]);
        if($validator->fails()){
                return response()->json(
                    $validator->errors()->toJson(), 400,
                );
        }
        $resto = new Restaurant;

        $resto->restaurantName = $request->json()->get('restaurantName');
        $resto->owner = $request->json()->get('owner');
        $resto->representative = $request->json()->get('representative');
        $resto->latitude = $request->json()->get('latitude');
        $resto->longitude = $request->json()->get('longitude');
        $resto->barangayId = $request->json()->get('barangayId');
        $resto->cityId = $request->json()->get('cityId');
        $resto->contactNumber = $request->json()->get('contactNumber');
        $resto->openTime = $request->json()->get('openTime');
        $resto->closingTime = $request->json()->get('closingTime');
        $resto->closeOn = $request->json()->get('closeOn');
        $resto->isFeatured = $request->json()->get('isFeatured');
        $resto->imagePath = $request->json()->get('imagePath');
        $resto->status = 1;
        $resto->save();

        $restaurantId = $resto->id;

        return response()->json($restaurantId);
    }
    public function makeRestaurantFeatured($id){
        $resto = Restaurant::find($id);
        $resto->isFeatured = 1;
        $resto->save();
    }
    public function updateRestaurant(Request $request){
        $resto = Restaurant::find($request->json()->get('restaurantId'));
        $resto->restaurantName = $request->json()->get('restaurantName');
        $resto->owner = $request->json()->get('owner');
        $resto->representative = $request->json()->get('representative');
        $resto->latitude = $request->json()->get('latitude');
        $resto->longitude = $request->json()->get('longitude');
        $resto->barangayId = $request->json()->get('barangayId');
        $resto->cityId = $request->json()->get('cityId');
        $resto->contactNumber = $request->json()->get('contactNumber');
        $resto->openTime = $request->json()->get('openTime');
        $resto->closingTime = $request->json()->get('closingTime');
        $resto->closeOn = $request->json()->get('closeOn');
        $resto->isFeatured = $request->json()->get('isFeatured');
        $resto->imagePath = $request->json()->get('imagePath');
        $resto->status = 1;
        $resto->save();

    }
    public function activateRestaurant($id){
        $activate = DB::table('restaurants')->where('id', $id)->update(['isActive' => 1]);
        $activate = DB::table('menu')->where('restaurant_id', $id)->update(['isActive' => 1]);
    }
    public function deleteRestaurant($id){
        $deleteResto = DB::table('restaurants')->where('id', $id)->update(['isActive' => 0]);
        $deleteMenu = DB::table('menu')->where('restaurant_id', $id)->update(['isActive' => 0]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurant $restaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        //
    }

    public function getFeaturedRestaurant($id){
        $resto = Restaurant::where('isFeatured', 1)->where('cityId', $id)
            ->where('isActive', 1)->get();

        return response()->json($resto);
    }

    public function getRestaurantById($id){
        $resto = Restaurant::where('id', $id)->where('isActive', 1)->first();

        return response()->json($resto);
    }

}
