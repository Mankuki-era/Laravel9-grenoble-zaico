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
        $log->created_at = strtotime($request->date);
        
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
        // error_log(print_r($request->array,true),"3","/Users/mankuki_era/Documents/debug.log");

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
                        $array[$data['name']] = array('id' => $data['id'], 'name' => $data['name'], 'amount' => intval($data['amount']));
                    }
                };
            }
        };

        // idでソートする
        foreach($array as $key => $value) {
            $sort_keys[$key] = $value['id'];
        };
        
        array_multisort($sort_keys, SORT_ASC, $array);

        return array_values($array);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function arrange()
    {
        $logs = Log::orderBy('created_at', 'asc')->get();

        // error_log(print_r($logs,true),"3","/Users/mankuki_era/Documents/debug.log");

        $datestr1 = ""; // 日付
        $array = [];
        $count = 0;
        foreach($logs as $log){
            $datestr2 = substr($log->created_at, 0, 10);
            // error_log(print_r($datestr2,true),"3","/Users/mankuki_era/Documents/debug.log");
            if($datestr1 != $datestr2){

                // データ更新および削除処理
                if($array != []){
                    foreach($array as $array_data){
                        if(count($array_data['id']) > 1){
                            $update_id = array_pop($array_data['id']);
                            // データ削除
                            foreach($array_data['id'] as $id) {
                                $delete_log = Log::find($id);
                                $delete_log->delete();
                            }
                            // データ更新
                            $update_log = Log::find($update_id);
                            $update_log->data = serialize($array_data['data']);
                            $update_log->save();
                        }
                    }
                }

                $datestr1 = $datestr2;
                $array = [array('data'=>[], 'id'=>[]), array('data'=>[], 'id'=>[])];  // [入庫データ, 出庫データ];

            };
            // error_log(print_r(unserialize($log->data),true),"3","/Users/mankuki_era/Documents/debug.log");
            
            if($log->type === '入庫'){
                $index = 0;
            }elseif($log->type ===  '出庫'){
                $index = 1;
            };
            
            $id_array = array_column($array[$index]['data'], 'id');

            foreach(unserialize($log->data) as $data){
                $key = array_search($data['id'], $id_array);
                if($key === false){
                    array_push($array[$index]['data'], $data);
                }else{
                    $array[$index]['data'][$key]['amount'] += $data['amount'];
                }
            }
            
            array_push($array[$index]['id'], $log->id);

        };

        // データ更新および削除処理
        foreach($array as $array_data){
            if(count($array_data['id']) > 1){
                $update_id = array_pop($array_data['id']);
                // データ削除
                foreach($array_data['id'] as $id){
                    $delete_log = Log::find($id);
                    $delete_log->delete();
                }
                // データ更新
                $update_log = Log::find($update_id);
                $update_log->data = serialize($array_data['data']);
                $update_log->save();
            }
        }
        
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
