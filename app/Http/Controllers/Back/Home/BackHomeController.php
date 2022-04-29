<?php

namespace App\Http\Controllers\Back\Home;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Crypt;

use Common;

/**
 * ホーム(バックエンド)
 */
class BackHomeController extends Controller
{   
    /**
     *  ホーム(表示)
     *
     * @param Request $request
     * @return view('application.application','list_user_count','list_app_count','list_picture_count','list_contacts_count','list_access_total','list_access_today');
     */
    public function backHomeInit(Request $request)
    {   
        Log::debug('start:' .__FUNCTION__);

        try {

            // 申込件数
            $app_info = $this->getAppList($request);
            $app_list = $app_info[0];

            // 申込件数(審査中)
            $app_judgment_info = $this->getAppJudgmentList($request);
            $app_judgment_list = $app_judgment_info[0];

            // 契約件数
            $contract_info = $this->getContractList($request);
            $contract_list = $contract_info[0];

            // 契約手続中
            $contract_judgment_info = $this->getContractJudgmentList($request);
            $contract_judgment_list = $contract_judgment_info[0];

            // ファイル数
            $img_info = $this->getImgList($request);
            $img_list = $img_info[0];

            // 新着情報
            $information_list = $this->getInformationList($request);

            // 新着情報未読件数
            $information_count_info = $this->getInformationNonRead($request);
            $information_count_list = $information_count_info[0];

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backHome',$information_list ,compact('app_list' ,'app_judgment_list' ,'contract_list' ,'contract_judgment_list' ,'img_list' ,'information_count_list'));
    }

    /**
     *  申込件数
     *
     * @return $ret(real_estate_agentの件数)
     */
    private function getAppList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // session_id
        $session_id = $request->session()->get('create_user_id');

        // sql
        $str = "select count(*) as app_count "
        ."from applications "
        ."where entry_user_id = $session_id ";
        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 申込件数（審査中）
     */
    private function getAppJudgmentList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // session_id
        $session_id = $request->session()->get('create_user_id');

        $str = "select count(*) as app_count "
        ."from applications "
        ."where entry_user_id = $session_id "
        ."and "
        ."applications.contract_progress_id = 2 ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 契約件数
     */
    private function getContractList(Request $request){

        Log::debug('log_start:'.__FUNCTION__);

        // session_id
        $session_id = $request->session()->get('create_user_id');

        $str = "select count(*) as contract_count "
        ."from contract_details "
        ."where entry_user_id = $session_id ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 契約件数（審査中）
     */
    private function getContractJudgmentList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // session_id
        $session_id = $request->session()->get('create_user_id');

        $str = "select count(*) as contract_count "
        ."from contract_details "
        ."where entry_user_id = $session_id "
        ."and "
        ."contract_details.contract_detail_progress_id = 1 ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * ファイル数
     */
    private function getImgList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // session_id
        $session_id = $request->session()->get('create_user_id');

        $str = "select count(*) as img_count "
        ."from imgs "
        ."where entry_user_id = $session_id ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 新着情報
     */
    private function getInformationList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // session_id
        $session_id = $request->session()->get('create_user_id');
        
        $str = "select * from informations ";
        Log::debug('$sql:' .$str);

        // query
        $alias = DB::raw("({$str}) as alias");

        // columnの設定、表示件数
        $res = DB::table($alias)->selectRaw("*")->orderByRaw("update_date desc")->paginate(3)->onEachSide(1);

        // resの中に値が代入されている
        $ret = [];
        $ret['res'] = $res;

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 未読件数
     *
     * @param Request $request
     * @return void
     */
    private function getInformationNonRead(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select count(*) as informations_count "
        ."from informations ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }
}