<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Gmonitor;

class GmonitorController extends Controller
{

    public function index()
    {
        // 以最新的時間排序  取十筆
        $gmonitor = Gmonitor::orderBy('m_time', 'desc')->take(10)->get()->toArray();
        //將取出來的陣列在依照名字分類
        $gmonitor_groupby = collect($gmonitor)->groupBy('Name')->toArray();

        $response = [
            'success' => true,
            'data' => $gmonitor_groupby,
            'message' => '資料載入成功',
        ];
        return response()->json($response, 200);
    }

    // 預設畫面一小時的數據(dashboard)
    public function onehr_data()
    {
        // $now = date("Y-m-d H:i:s");
        // $one_hour_before = date("Y-m-d H:i:s", strtotime("-1 hour"));
        $now = "2020-11-02 10:43:36";
        $one_hour_before= "2020-11-02 09:43:36";

        //取一小時前的資料(在預設的dashboard上顯示)
        $gmonitor = Gmonitor::whereBetween('m_time', [$one_hour_before,$now])->get()->toArray();
        //將取出來的陣列在依照名字分類
        $gmonitor_groupby = collect($gmonitor)->groupBy('Name')->toArray();

        $response = [
            'success' => true,
            'data' => $gmonitor_groupby,
            'message' => '資料載入成功',
        ];
        return response()->json($response, 200);
    }
 
    public function search_data(Request $request)
    {
        $from= $request->from;
        $to = $request->to;
        // $from = "2020-11-02 22:43:36";
        // $to = "2020-11-02 22:44:36";

        $hour_range= (strtotime($to) - strtotime($from))/ (60*60); //計算相差之小時數

        if($hour_range <=4){
        //取一小時前的資料(在預設的dashboard上顯示)
        $gmonitor = Gmonitor::whereBetween('m_time', [$from,$to])->get()->toArray();
        //將取出來的陣列在依照名字分類
        $gmonitor_groupby = collect($gmonitor)->groupBy('Name')->toArray();

        $response = [
            'success' => true,
            'data' => $gmonitor_groupby,
            'message' => '資料載入成功',
        ];
        return response()->json($response, 200);
    }else{
        $response = [
            'success' => false,
            'message' => '時間範圍錯誤，搜尋區間上限4小時',
        ];
        return response()->json($response, 202);
    }
    }
}
