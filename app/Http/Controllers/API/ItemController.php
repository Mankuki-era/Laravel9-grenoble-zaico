<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Item::orderBy('favorite', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new Item;

        $data = $request->data;

        $item->name = $data["name"];
        $item->price = isset($data["price"]) ? $data["price"] : null;
        $item->stocks = isset($data["stocks"]) ? $data["stocks"] : null;

        $item->save();
    }

    /**
     * Import a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // error_log(print_r($request->data[0]['教材名'],true),"3","/Users/mankuki_era/Documents/debug.log");

        foreach($request->data as $data){
            $item = new Item;
    
            $item->name = $data['教材名'];
            $item->stocks = isset($data['在庫数']) ? $data['在庫数'] : null;
            $item->price = isset($data['価格']) ? $data['価格'] : null;
    
            $item->save();
        }
    }

    /**
     * Import a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allupdate(Request $request)
    {
        // error_log(print_r($request->data[0]['教材名'],true),"3","/Users/mankuki_era/Documents/debug.log");

        foreach($request->data as $data){
            $item = Item::find($data['id']);
    
            $item->stocks = isset($data['stocks']) ? $data['stocks'] : null;
    
            $item->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Item::find($id);
    }

    /**
     * Update favorite.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function favorite(Request $request, $id)
    {
        $item = Item::find($id);

        $item->favorite = $request->favorite;

        $item->save();
    }

    /**
     * Update stocks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stock(Request $request)
    {

        foreach($request->data as $data){
            $item = Item::find($data["id"]);
    
            $item->stocks = isset($data["stocks"]) ? $data["stocks"] : null;
    
            $item->save();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Item::find($id);
        
        $data = $request->data;

        $item->name = $data["name"];
        $item->price = isset($data["price"]) ? $data["price"] : null;
        $item->stocks = isset($data["stocks"]) ? $data["stocks"] : null;

        $item->save();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        $item->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        DB::table('items')->truncate();
    }
}