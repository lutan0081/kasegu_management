<?php

namespace App\Http\Controllers\Admin\Home;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Crypt;

use Common;

/**
 * 管理者(ホーム画面)
 */
class AdminHomeController extends Controller
{   
    /**
     *  表示
     *
     * @param Request $request
     * @return view('application.application','list_user_count','list_app_count','list_picture_count','list_contacts_count','list_access_total','list_access_today');
     */
    public function adminHomeInit(Request $request)
    {   
        Log::debug('start:' .__FUNCTION__);

        try {

            // 新着情報
            $information_list = $this->getInformationList($request);

            // access数:合計
            $access_info = $this->getAccessCount($request);
            $access_list = $access_info[0];

            // access数:当日
            $access_info_today = $this->getAccessCountToday($request);
            $access_list_today = $access_info_today[0];

            // アカウント数
            $user_info = $this->getUserList($request);
            $user_list = $user_info[0];
        
            // // 申込件数
            // $app_info = $this->getAppList($request);
            // $app_list = $app_info[0];

            // // 申込件数(審査中)
            // $app_judgment_info = $this->getAppJudgmentList($request);
            // $app_judgment_list = $app_judgment_info[0];

            // // 契約件数
            // $contract_info = $this->getContractList($request);
            // $contract_list = $contract_info[0];

            // // 契約手続中
            // $contract_judgment_info = $this->getContractJudgmentList($request);
            // $contract_judgment_list = $contract_judgment_info[0];

            // // ファイル数
            // $img_info = $this->getImgList($request);
            // $img_list = $img_info[0];

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminHome' ,$information_list ,compact('access_list' ,'access_list_today' ,'user_list'));
    }

    /**
     * 新着情報
     */
    private function getInformationList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // session_id
        $session_id = $request->session()->get('create_user_id');
        
        $str = "select "
        ."informations.information_id, "
        ."informations.information_title, "
        ."informations.information_type_id, "
        ."information_types.information_type_name, "
        ."informations.information_contents , "
        ."informations.entry_user_id, "
        ."informations.entry_date, "
        ."informations.update_user_id, "
        ."informations.update_date "
        ."from informations "
        ."left join information_types "
        ."on information_types.information_type_id = informations.information_type_id ";
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
     *  アクセス数:合計
     *
     * @return $ret(real_estate_agentの件数)
     */
    private function getAccessCount(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // sql
        $str = "select count(*) as accesses_count "
        ."from accesses; ";

        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  アクセス数:本日
     *
     * @return $ret(real_estate_agentの件数)
     */
    private function getAccessCountToday(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * 現在日付取得
         */
        $now = now(); 
        $date = date_format($now , 'Y-m-d');
        Log::debug('date:'.$date);

        /**
         * 秒の取得
         */
        $start_min = ' 00:00:00.000';

        $end_min = ' 23:59:59.999';

        /**
         * sql用に日付フォーマット
         */
        $start_date = $date .$start_min;
        Log::debug('start_date:'.$start_date);

        $end_date = $date .$end_min;
        Log::debug('end_date:'.$end_date);

        // sql
        $str = "select "
        ."count(*) as accesses_count_today "
        ."from accesses "
        ."where "
        ."(entry_date > '$start_date') "
        ."and "
        ."(entry_date < '$end_date') ";
        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  アカウント数
     *
     * @return $ret(real_estate_agentの件数)
     */
    private function getUserList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // session_id
        $session_id = $request->session()->get('create_user_id');

        // sql
        $str = "select count(*) as user_count "
        ."from create_users ";
        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
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