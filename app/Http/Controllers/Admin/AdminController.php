<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
// mailに必要
use Illuminate\Support\Facades\Mail;
// 暗号化に必要
use Illuminate\Support\Facades\Crypt;

use Common;

/**
 * 管理画面Home、上段ダッシュボードの値取得、updateの情報取得
 */
class AdminController extends Controller
{   
    /**
     *  管理画面(表示)
     *
     * @param Request $request
     * @return view('application.application','list_user_count','list_app_count','list_picture_count','list_contacts_count','list_access_total','list_access_today');
     */
    public function adminInit(Request $request)
    {   
        // 進捗情報のデータ取得
        Log::debug('start:' .__FUNCTION__);

        try {
            $session_id = $request->session()->get('create_user_id');
            Log::debug('session_id:' .$session_id);

            // ページネーションで値取得
            $list_update = [];

            $sql = "select * from updates ";
            $alias = DB::raw("({$sql}) as alias");
            $res = DB::table($alias)->selectRaw("*")->orderByRaw("update_id desc")->paginate(5)->onEachSide(1);

            // sqlを確認
            $sql = DB::table($alias)->selectRaw("*")->orderByRaw("update_id desc")->toSql();
            Log::debug('sql:' .$sql);

            $list_update['res'] = $res;

            // ログイン情報
            $list_user = $this->getDataUser($request);
            // セッションに名前を入れる

            // ユーザ登録(認証前)
            $list_user_count_cmp = $this->getUserCmpCount($request);

            // ユーザ登録(累計)
            $list_user_count = $this->getUserCount($request);

            // アクセス(当日)
            $list_access_today = $this->getTodayCount($request);

            // アクセス(合計)
            $list_access_total = $this->getTotalCount($request);
            
            // 申込
            $list_app_count = $this->getAppCount($request);

            // 写真
            $list_picture_count = $this->getPictureCount($request);

            // メッセージ
            $list_contacts_count = $this->getContactsCount($request);

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminHome',$list_update,compact('list_user_count_cmp','list_user_count','list_app_count','list_picture_count','list_contacts_count','list_access_total','list_access_today'));
    }

    /**
     *  ログイン情報
     *
     * @return $ret(アップデート情報)
     */
    private function getDataUser(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // session_id取得
        $session_id = $request->session()->get('create_user_id');

        // sql
        $str = "select * "
        ."from users "
        ."where "
        ."create_user_id = '$session_id' ";
        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  ユーザ登録数(累計)
     *
     * @return $ret(usersの件数)
     */
    private function getUserCount(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select count(*) as user_count "
        ."from users";
        Log::debug('sql:'.$str);
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  ユーザ登録数(認証前)
     *
     * @return $ret(usersの件数)
     */
    private function getUserCmpCount(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select "
        ."count(*) as user_count_cmp "
        ."from "
        ."( "
        ."select "
        ."complete_flag "
        ."from "
        ."users "
        ."where complete_flag = 0 "
        .")w ";        

        Log::debug('sql:'.$str);
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  申込数
     *
     * @return $ret(real_estate_agentの件数)
     */
    private function getAppCount(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // sql
        $str = "select count(*) as app_count "
        ."from real_estate_agent_forms ";
        Log::debug('sql:'.$str);
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  写真
     *
     * @return $ret(imgsの件数)
     */
    private function getPictureCount(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // sql
        $str = "select count(*) as picture_count "
        ."from imgs ";
        Log::debug('sql:'.$str);
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  メッセージ(合計)
     *
     * @return $ret(contactsの件数)
     */
    private function getContactsCount(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * sql
         */
        $str = "select count(*) as contacts_count "
        ."from contacts "
        ."where contact_read_flag = 0";
        Log::debug('sql:'.$str);
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  access件数(合計)
     *
     * @return $ret(contactsの件数)
     */
    private function getTotalCount(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * sql
         */
        $str = "select count(*) as access_count_total "
        ."from accesses ";
        Log::debug('sql:'.$str);
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  access件数(今日)
     *
     * @return $ret(contactsの件数)
     */
    private function getTodayCount(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // 値取得
        $date = Common::format_date(now());

        /**
         * sql
         */
        $str = "select "
        ."count(*) as access_count_today "
        ."from "
        ."( "
        ."select "
        ."create_date "
        ."from "
        ."accesses "
        ."where create_date = '$date' "
        .")w ";

        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * お知らせ(新規登録)
     *
     * @param Request $request
     * @return return response(status:true=OK/false=NG)
     */
    public function adminInfoEditEntry(Request $request)
    {
        Log::debug('log_start:' .__FUNCTION__);

        $title_name = $request->input('title_name');
        $contents_name = $request->input('contents_name');

        /**
         * validation
         */
        // returnの出力値
        $response = [];
        // status初期値
        $response["status"] = true;

        /**
         * rules
         */
        $rules = [];
        $rules['title_name'] = "required|max:100";
        $rules['contents_name'] = "required|max:300";

        /**
         * messages
         */
        $messages = [];
        $messages['title_name.required'] = "タイトルは必須です。";
        $messages['title_name.max'] = "タイトルの文字数が超過しています。";
        $messages['contents_name.required'] = "内容は必須です。";
        $messages['contents_name.max'] = "内容の文字数が超過しています。";

        $arrString = print_r($messages , true);
        Log::debug('messages:' .$arrString);

        // validation判定
        $validator = Validator::make($request->all(), $rules, $messages);

        // error処理
        if ($validator->fails()) {
            // ajax返却定数
            $keys = [];
            $msgs = [];
            // errorsをjson形式に変換(trueの場合、連想配列)
            $ary = json_decode($validator->errors(), true);
            
            // ループ&値をvalueに設定
            foreach ($ary as $key => $value) {
                // キーを配列に設定
                $keys[] = $key;
                // 値(メッセージ)を設定
                $msgs[] = $value;
            }
            // response値設定
            // status = falseの場合js側でerrorメッセージ表示
            $response["status"] = false;
            $response['msg'] = "入力を確認して下さい。";
            $response["messages"] = $msgs;
            $response["errkeys"] = $keys;
            
            return response()->json($response);
        }

        /**
         * insert
         */
        // 登録完了の場合status = true
        $update_info = $this->insert($request);
        $response["status"] = $update_info['status'];
        
        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * update(新規登録:sql)
     *
     * @param Request $request(formデータ)
     * @return $ret['status']true=OK/false=NG;
     */
    private function insert(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        try {
            $ret = [];
            /**
             * 値取得
             */
            // タイトル
            $title_name = $request->input('title_name');
            // 契約者名
            $contents_name = $request->input('contents_name');
            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('session_id_login:' .$session_id);

            $str = "insert into "
            ."kasegu_management.updates( "
            ."update_title, "
            ."update_contents, "
            ."create_user_id, "
            ."create_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."'$title_name', "
            ."'$contents_name', "
            ."'$session_id', "
            ."now(), "
            ."'$session_id', "
            ."now()"
            .");";

            Log::debug('log_insert_sql:'.$str);
            $ret['status'] = DB::insert($str);
            
            Log::debug('ret:' .$ret['status']);

        // 例外処理
        } catch (\Exception $e) {
            Log::debug('error:'.$e);

            $ret['status'] = false;
        } finally {
            if($ret['status'] == 1){

                $ret['status'] = true;

            }else{
                $ret['status'] = false;
            }
            Log::debug('log_end:' .__FUNCTION__);
            return $ret;
        }
    }

    /**
     * update(削除)
     *
     * @param Request $request
     * @return return response(status:true=OK/false=NG)
     */
    public function adminInfoDeleteEntry(Request $request)
    {
        Log::debug('log_start:' .__FUNCTION__);
        try {

            /**
             * OK=1/NG=0
             */
            $ret = $this->delete($request);

            // js側での判定のステータス(true:OK/false:NG)
            $response["status"] = $ret['status'];

        } catch (\Exception $e) {

            // sqlエラーをログに記録
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $response['status'] = false;

        } finally {

            // OK=1(true)/NG=0(false)
            if($response['status'] == 1){

                $response['status'] = true;

            }else{
                
                $response['status'] = false;
            }

            Log::debug('log_end:' .__FUNCTION__);
            return response()->json($response);
        }
    }

    /**
     * update(削除:sql)
     *
     * @param Request $request(formデータ)
     * @return $ret['status']true=OK/false=NG;
     */
    private function delete(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        /**
         * Requestデータ取得
         */
        // 値設定
        $update_id = $request->input('update_id');

        // query
        $str = "delete from updates where update_id = $update_id; ";
        Log::debug('log_delete_sql:'.$str);

        $ret['status'] = DB::delete($str);
        Log::debug('log_status:' .$ret['status']);

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * ユーザ情報を取得
     *
     * @return void
     */
    public function adminUserInit(){
        Log::debug('log_start:' .__FUNCTION__);

        $str = "select * from users ";

        // query
        $alias = DB::raw("({$str}) as alias");
        // columnの設定、表示件数
        $res = DB::table($alias)->selectRaw("*")->paginate(15)->onEachSide(1);
        // resの中に値が代入されている
        $list_user['res'] = $res;

        Log::debug('log_end:' .__FUNCTION__);
        return view('admin.adminUser',$list_user);
    }
}