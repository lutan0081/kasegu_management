<?php

namespace App\Http\Controllers\Front\Info;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use App\Config;

use Common;

/**
 * 新着情報
 */
class FrontInfoController extends Controller
{   
    /**
     * 表示
     *
     * @param Request $request
     * @return list_info(ページネーションでお知らせをreturnする)
     */
    public function frontInfoInit(Request $request)
    {   
        // お知らせ
        Log::debug('start:' .__FUNCTION__);
        try {

            // ページネーションで値取得
            $list_info = [];

            $str = "select * from informations ";
            $alias = DB::raw("({$str}) as alias");
            $res = DB::table($alias)->selectRaw("*")->orderByRaw("update_id desc")->paginate(8)->onEachSide(1);

            // SQLを確認
            $sql = DB::table($alias)->selectRaw("*")->orderByRaw("update_id desc")->toSql();
            Log::debug('sql:' .$sql);

            // resの中に値が代入されている
            $list_info['res'] = $res;

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }
        Log::debug('end:' .__FUNCTION__);
        return view('front.frontInfo',$list_info);
    }
}