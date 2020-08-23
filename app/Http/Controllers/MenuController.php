<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Restaurant;
use App\Categories;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MenuController extends Controller
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
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
    }

    public function getMenuCategory($id){
        $menu = DB::table('menu')
            ->join('menu_categories', 'menu.id', '=', 'menu_categories.menuId')
            ->join('categories', 'categories.id', '=', 'menu_categories.categoryId')
            ->join('restaurants', 'restaurants.id', '=', 'menu.restaurant_id')
            ->select('menu.id as menuId', 'restaurants.id as restaurantId','restaurants.restaurantName','menu.menuName','menu.description', 'menu.price', 'menu.imagePath')
            ->where('categoryId' , '=', $id)
            ->get();

        return response()->json($menu);
    }
    public function addMenu(Request $request){
        $restoId = $request->json()->get('restaurantId');
        $menu = $request->json()->get('menu');
        for ($i=0; $i < count($menu); $i++) { 
            $category = $menu[$i]['category'];
            $addMenu = new Menu;
            $addMenu->restaurant_id = $restoId;
            $addMenu->menuName = $menu[$i]['menuName'];
            $addMenu->description = $menu[$i]['description'];
            $addMenu->price = $menu[$i]['price'];
            $addMenu->imagePath = $menu[$i]['imagePath'];
            $addMenu->save();
            // $addMenu = DB::table('menu')->insert(['restaurant_id' => $restoId, 'menuName' => $menu[$i]['menuName'], 'description' => $menu[$i]['description'],'price' => $menu[$i]['price']]);
            for ($j=0; $j <count($category) ; $j++) { 
                # code...
                $addCategory = DB::table('menu_categories')
                    ->insert(['menuId' => $addMenu->id, 'categoryId' => $category[$j]['id']]);   
            }

        }
    }
    
    public function getAllMenu(){
        $menu = DB::table('menu')
            ->join('menu_categories', 'menu_categories.menuId', '=', 'menu.id')
            ->join('categories', 'categories.id', '=', 'menu_categories.categoryId')
            ->join('restaurants', 'restaurants.id', '=', 'menu.restaurant_id')
            ->join('barangay', 'barangay.id', '=', 'restaurants.barangayId')
            ->select('menu.id as menuId', 'restaurants.id as restaurantId','restaurants.restaurantName','restaurants.address','barangay.barangayName','menu.menuName', 'categories.categoryName', 'menu.imagePath')
            ->get();
            
        return response()->json($menu);
    }

    public function getMenuPerRestaurant($id){
        $menuList = DB::table('menu')
            ->join('restaurants', 'restaurants.id', '=', 'menu.restaurant_id')          
            ->select('menu.id', 'menu.menuName','menu.description', 'menu.price', 'menu.imagePath')
            ->where('restaurants.id', '=', $id)->get();

         return response()->json($menuList);   
    }
}
