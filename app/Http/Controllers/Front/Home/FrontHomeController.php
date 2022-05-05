<?php

namespace App\Http\Controllers\Front\Home;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Common;

/**
 * フロント画面
 */
class FrontHomeController extends Controller
{   
    /**
     *  表示
     *
     * @param Request $request(フォームデータ)
     * @return view('admin.adminContact',$list_contact(ページネーション));
     */
    public function frontHomeInit(Request $request)
    {   
        Log::debug('start:' .__FUNCTION__);

        try {
            // アクセスカウンター(DBに登録)
            $count = $this->ipInsert($request);

            // 新着情報
            $list_info = $this->getInfoList();
            $arrString = print_r($list_info,true);
            Log::debug('list_info:' .$arrString);

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('front.frontHome',compact('list_info'));
    }

    public function frontHome2Init(Request $request)
    {   
        Log::debug('start:' .__FUNCTION__);

        try {
            // アクセスカウンター(DBに登録)
            $count = $this->ipInsert($request);

            // 新着情報
            $list_info = $this->getInfoList();
            $arrString = print_r($list_info,true);
            Log::debug('list_info:' .$arrString);

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('front.frontHome2',compact('list_info'));
    }

    /**
     * アクセスカウンター(DBに登録)
     *
     * @param Request $request
     * @return void
     */
    private function ipInsert(Request $request){

        Log::debug('log_start:' .__FUNCTION__);

        // ipアドレス取得
        $ip = $request->ip();
        Log::debug('ip:' .$ip);

        // sql
        $str = "insert "
        ."into "
        ."accesses( "
        ."ip_address, "
        ."entry_date)values( "
        ."'$ip', "
        ."now() "
        ."); ";
        Log::debug('str:' .$str);
        
        DB::insert($str);

        Log::debug('log_end:' .__FUNCTION__);
    }

    /**
     * 新着情報取得(sql)
     *
     * @param [type] $session_id(業者id)
     * @return $data(業者ごとの顧客リスト)
     */
    private function getInfoList(){
        Log::debug('log_start:' .__FUNCTION__);
        
        // sql
        $str = "select * from informations "
        ."order by information_id desc "
        ."LIMIT 4; ";
        Log::debug('str:' .$str);

        // query
        $list = DB::select($str);

        Log::debug('log_end:' .__FUNCTION__);
        return $list;
    }
}