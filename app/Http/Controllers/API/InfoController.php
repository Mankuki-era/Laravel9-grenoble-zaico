<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Info;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     return Info::all();
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $item = new Item;

    //     $data = $request->data;

    //     $item->name = $data["name"];
    //     $item->price = isset($data["price"]) ? $data["price"] : null;
    //     $item->stocks = isset($data["stocks"]) ? $data["stocks"] : null;

    //     $item->save();
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Info::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $item = Item::find($id);
        
    //     $data = $request->data;

    //     $item->name = $data["name"];
    //     $item->price = isset($data["price"]) ? $data["price"] : null;
    //     $item->stocks = isset($data["stocks"]) ? $data["stocks"] : null;

    //     $item->save();

    // }
}
