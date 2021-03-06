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
            ->join('categories', 'categories.id', '=', 'menu.categoryId')
            ->join('restaurants', 'restaurants.id', '=', 'menu.restaurant_id')
            ->select(DB::raw('menu.id as menuId, restaurants.id as restaurantId,restaurants.restaurantName, restaurants.latitude, restaurants.longitude, menu.menuName, menu.description , menu.imagePath, ((menu.price * menu.markUpPercentage) + menu.price) as totalPrice'))
            ->where('categoryId' , '=', $id)
            ->where('menu.isActive','=', 1)
            ->get();

        return response()->json($menu);
    }
    public function addMenu(Request $request){
        $restoId = $request->json()->get('restaurantId');
        $menu = $request->json()->get('menu');
        for ($i=0; $i < count($menu); $i++) { 
            $addMenu = new Menu;
            $addMenu->restaurant_id = $restoId;
            $addMenu->menuName = $menu[$i]['menuName'];
            $addMenu->description = $menu[$i]['description'];
            $addMenu->price = $menu[$i]['price'];
            $addMenu->imagePath = $menu[$i]['imagePath'];
            $addMenu->categoryId = $menu[$i]['categoryId'];
            $addMenu->isFeatured = 0;
            $addMenu->save();
            // $addMenu = DB::table('menu')->insert(['restaurant_id' => $restoId, 'menuName' => $menu[$i]['menuName'], 'description' => $menu[$i]['description'],'price' => $menu[$i]['price']]);

        }
    }

    public function makeMenuFeatured($id){
        $menu = Menu::find($id);
        $menu->isFeatured = 1;
        $menu->save();
    }

    public function updateMenu(Request $request){
        $menuId = $request->json()->get('menuId');
        $restaurantId = $request->json()->get('restaurantId');
        $menuName = $request->json()->get('menuName');
        $description = $request->json()->get('description');
        $price = $request->json()->get('price');
        $imagePath = $request->json()->get('imagePath');
        $categoryId = $request->json()->get('categoryId');

        $menu = Menu::find($menuId);
        $menu->restaurant_id = $restaurantId;
        $menu->menuName = $menuName;
        $menu->description = $description;
        $menu->price = $price;
        $menu->imagePath = $imagePath;
        $menu->categoryId = $categoryId;
        $menu->isFeatured = 0;
        $menu->save();

    }
    
    public function getAllMenu($id){
        $menu = DB::table('menu')
            ->join('categories', 'categories.id', '=', 'menu.categoryId')
            ->join('restaurants', 'restaurants.id', '=', 'menu.restaurant_id')
            ->join('barangay', 'barangay.id', '=', 'restaurants.barangayId')
            ->select(DB::raw('menu.id as menuId, restaurants.id as restaurantId,restaurants.restaurantName, restaurants.latitude, restaurants.longitude, barangay.barangayName, menu.menuName, menu.categoryId, menu.isFeatured, categories.categoryName, menu.imagePath, ((menu.price * menu.markUpPercentage) + menu.price) as totalPrice'))
            ->where('menu.isActive', '=',1)
            ->where('restaurants.cityId', '=', $id)
            ->get();
            
        return response()->json($menu);
    }

    public function getMenuPerRestaurant($id){
        $menuList = DB::table('menu')
            ->join('restaurants', 'restaurants.id', '=', 'menu.restaurant_id')          
            ->select(DB::raw('menu.id, menu.menuName, menu.description, menu.imagePath, menu.isFeatured, ((menu.price * menu.markUpPercentage) + menu.price) as totalPrice'))
            ->where('restaurants.id', '=', $id)
            ->where('menu.isActive','=', 1)->get();

         return response()->json($menuList);   
    }

    public function getMenuById($id){
        $menu = Menu::where('id', $id)->where('isActive', 1)->first();

        return response()->json($menu);
    }

    public function deleteMenu($id){
        $deleteMenu = Menu::destroy($id);
    }
}
