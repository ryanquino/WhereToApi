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
                'address' => 'required|string|max:255',
                'contactNumber' => 'required|string|max:11'
            ]);
        if($validator->fails()){
                return response()->json(
                    $validator->errors()->toJson(), 400,
                );
        }
        $resto = new Restaurant;

        $resto->restaurantName = $request->json()->get('restaurantName');
        $resto->address = $request->json()->get('address');
        $resto->barangayId = $request->json()->get('barangayId');
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

    public function getFeaturedRestaurant(){

        $featuredResto = DB::table('restaurants')
            ->select('restaurants.id', 'restaurants.restaurantName', 'restaurants.address', 'barangay.barangayName', 'restaurants.contactNumber', 'restaurants.imagePath')
            ->join('barangay', 'barangay.id', '=', 'restaurants.barangayId')
            ->where('isFeatured', '=', 1)->get();

        return response()->json($featuredResto);
    }

    public function getRestaurantMenu($id){

        $menu = Restaurant::find($id)->menu;

        return response()->json($menu);

    }

}
