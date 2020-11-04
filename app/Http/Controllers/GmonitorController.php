<?php

namespace App\Http\Controllers;

use App\Models\Gmonitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GmonitorController extends Controller
{

    public function index_old()
    {
        //
        $gmonitor_ID_max = Gmonitor::max('ID');

        $gmonitor = Gmonitor::orderBy('m_time', 'desc')->take($gmonitor_ID_max)->get()->toArray();
        // dd($gmonitor);
        //將取出來的陣列在依照名字分類
        $gmonitor_groupby = collect($gmonitor)->groupBy('Name')->toArray();
        // dd($gmonitor_groupby);
        $response = [
            'success' => true,
            'data' => $gmonitor_groupby,
            'message' => '資料載入成功',
        ];
        return response()->json($response, 200);
    }

    // 預設畫面半小時的數據(dashboard)
    public function index()
    {
        $now = date("Y-m-d H:i:s");
        $half_hour_before = date("Y-m-d H:i:s", strtotime("- 30 minute"));
        // dd($half_hour_before);

        //取半小時前的資料(在預設的dashboard上顯示)
        $gmonitor = Gmonitor::where('Name', "=", 'Default_DNS')->where('Seq', "=", "1")->whereBetween('m_time', [$half_hour_before, $now])->get()->toArray();
// dd($gmonitor);
        $response = [
            'success' => true,
            'data' => $gmonitor,
            'message' => '資料載入成功',
        ];
        return response()->json($response, 200);
    }

    public function search_data(Request $request)
    {
        $wan = $request->wan;
        $name = $request->name;
        $from = $request->from;
        $to = $request->to;

        //做表單驗證判斷
        $rules = [
            //填入須符合的格式
            'wan' => 'required',
            'name' => 'required',
            'from' => 'required',
            'to' => 'required',
        ];
        $messages = [
            //驗證未通過的訊息提示
            'from.required' => '請選擇開始時間',
            'to.required' => '請選擇結束時間',
            'wan.required' => '請選擇 WAN 類型',
            'name.required' => '請選擇監控項目類型',

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();
            $response = [
                'success' => false,
                'data' => "Error",
                'message' => $errors[0],
            ];
            return response()->json($response, 202);
        }

        //開始執行撈取資料庫動作

        //計算時間差區間
        $hour_range = (strtotime($to) - strtotime($from)) / (60 * 60);

        if ($hour_range <= 4) {
            //取一小時前的資料(在預設的dashboard上顯示)
            $gmonitor = Gmonitor::where('Name', $name)->where('Seq', $wan)->whereBetween('m_time', [$from, $to])->get()->toArray();

            

            $response = [
                'success' => true,
                'data' => $gmonitor,
                'message' => '資料載入成功',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => '時間範圍錯誤，搜尋區間上限4小時',
            ];
            return response()->json($response, 202);
        }
    }
}
