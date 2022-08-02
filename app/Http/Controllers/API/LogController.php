<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Log::orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $log = new Log;

        $log->type = $request->type;
        $log->data = serialize($request->data);
        
        $log->save();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $log = Log::find($id);
        return response()->json([
            'type' => $log->type,
            'data' => unserialize($log->data),
            'created_at' => $log->created_at
        ]);
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
        error_log(print_r($request->array,true),"3","/Users/mankuki_era/Documents/debug.log");

        foreach($request->array as $val){
            $item = Item::find($val['id']);
            $item->stocks = $item->stocks + $val['changeStocks'];
            $item->save();
        };

        $log = Log::find($id);
        $log->data = serialize($request->data);
        $log->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function analysis(Request $request)
    {
        // error_log(print_r($request->from,true),"3","/Users/mankuki_era/Documents/debug.log");
        $logs = Log::whereBetween("created_at", [$request->from, date('Y-m-d', strtotime('+1 day', strtotime($request->to)))])->get();

        $array = array();
        foreach($logs as $log){
            if($log->type === '出庫'){
                $datas = unserialize($log->data);
                foreach($datas as $data){
                    if(array_key_exists($data['name'], $array)){
                        $array[$data['name']]['amount'] += intval($data['amount']);
                    }else{
                        $array[$data['name']] = array('name' => $data['name'], 'amount' => intval($data['amount']));
                    }
                };
            }
        };

        return array_values($array);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        DB::table('logs')->truncate();
    }
}
