<?php

namespace App\Http\Controllers\Back\UserLicense;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Storage;

// データ縮小
use InterventionImage;

// 暗号化
use Illuminate\Support\Facades\Crypt;

use Common;

/**
 * 不動産保証協会(表示・登録、編集、削除)
 */
class BackUserLicenseController extends Controller
{   
    /**
     *  一覧(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backUserLicenseInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {
            // 不動産保証協会一覧
            $user_license_info = $this->getUserLicenseList($request);
            
        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backUserLicense' ,$user_license_info);
    }

    /**
     * 一覧(sql)
     *
     * @return $ret(宅建取引士一覧)
     */
    private function getUserLicenseList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // フリーワード
            $free_word = $request->input('free_word');
            Log::debug('$free_word:' .$free_word);

            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('$session_id:' .$session_id);

            $str = "select * "
            ."from user_licenses ";
            
            // 初期化
            $where = "";

            // フリーワード
            if($free_word !== null){

                Log::debug('フリーワードの処理');

                if($where == ""){

                    $where = "where ";

                }else{

                    $where = "and ";
                }

                $where = $where ."ifnull(user_license_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(user_license_ruby,'') like '%$free_word%'";

            };

            // id
            if($where == ""){

                $where = $where ."where "
                ."user_licenses.entry_user_id = '$session_id' ";

            }else{

                $where = $where ."and "
                ."user_licenses.entry_user_id = '$session_id' ";
            }

            // order by句
            $order_by = "order by user_license_id ";

            $str = $str .$where .$order_by;
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");

            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->paginate(20)->onEachSide(1);

            // resの中に値が代入されている
            $ret = [];
            $ret['res'] = $res;

        }catch(\Throwable $e) {

            throw $e;

        }finally{

        };

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }
    
    /**
     * 新規(表示)
     *
     * @return void
     */
    public function backUserLicenseNewInit(){
        Log::debug('start:' .__FUNCTION__);

        try {
            // 申込情報取得(新規:空データ)
            $user_license_info = $this->getUserLicenseNewList();
            $user_license_list = $user_license_info;

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backUserLicenseEdit' ,compact('user_license_list'));    
    }
    
    /**
     * 新規(ダミー値取得)
     *
     * @return $ret(空の配列)
     */
    private function getUserLicenseNewList(){
        Log::debug('log_start:'.__FUNCTION__);
        $obj = new \stdClass();
        
        // 募集要項
        $obj->user_license_id  = '';
        $obj->create_user_id = '';
        $obj->create_user_name = '';
        $obj->user_license_name = '';
        $obj->user_license_ruby = '';
        $obj->user_license_number = '';
        $obj->entry_user_id = '';
        $obj->entry_date = '';
        $obj->update_user_id = '';
        $obj->update_date = '';

        $ret = [];
        $ret = $obj;

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  編集(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backUserLicenseEditInit(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {
            // 申込情報取得(新規:空データ)
            $user_license_info = $this->getUserLicenseEditList($request);
            $user_license_list = $user_license_info[0];

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backUserLicenseEdit' ,compact('user_license_list'));    
    }
    
    /**
     * 編集(申込情報取得:sql)
     *
     * @return void
     */
    private function getUserLicenseEditList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            // 値設定
            $user_license_id = $request->input('user_license_id');

            // sql
            $str = "select * "
            ."from user_licenses "
            ."where user_licenses.user_license_id = $user_license_id ";
            Log::debug('sql:' .$str);
            
            $ret = DB::select($str);

        // 例外処理
        } catch (\Exception $e) {

            throw $e;

        } finally {

        }
        
        Log::debug('start:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 登録分岐(新規/編集)
     *
     * @param $request(edit.blade.phpの各項目)
     * @return $response(status:true=OK/false=NG)
     */
    public function backUserLicenseEditEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);
        
        // return初期値
        $response = [];

        // バリデーション:OK=true NG=false
        $response = $this->editValidation($request);

        if($response["status"] == false){

            Log::debug('validator_status:falseのif文通過');
            return response()->json($response);

        }

        /**
         * id=無:insert
         * id=有:update
         */
        $legal_place_id = $request->input('user_license_id');

        // 新規登録
        if($request->input('user_license_id') == ""){

            Log::debug('新規の処理');

            // $responseの値設定
            $ret = $this->insertData($request);

        // 編集登録
        }else{

            Log::debug('編集の処理');

            // $responseの値設定
            $ret = $this->updateData($request);

        }

        // js側での判定のステータス(true:OK/false:NG)
        $response["status"] = $ret['status'];

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * バリデーション
     *
     * @param Request $request(bladeの項目)
     * @return response(status=NG/msg="入力を確認して下さい/messages=$msgs/$errkeys=$keys)
     */
    private function editValidation(Request $request){

        // returnの出力値
        $response = [];

        // 初期値
        $response["status"] = true;

        /**
         * rules
         */
        $rules = [];
        $rules['user_license_name'] = "required|max:50";
        $rules['user_license_ruby'] = "required|max:50";
        $rules['user_license_number'] = "nullable|max:30";

        /**
         * messages
         */
        $messages = [];
        $messages['user_license_name.required'] = "宅地建物取引氏名は必須です。";
        $messages['user_license_name.max'] = "宅地建物取引氏名の文字数が超過しています。";
        $messages['user_license_ruby.required'] = "フリガナは必須です。";
        $messages['user_license_ruby.max'] = "フリガナの文字数が超過しています。";
        $messages['user_license_number.required'] = "登録番号は必須です。";
        $messages['user_license_number.max'] = "登録番号の文字数が超過しています。";
    
        // validation判定
        $validator = Validator::make($request->all(), $rules, $messages);

        // エラーがある場合処理
        if ($validator->fails()) {
            Log::debug('validator:失敗');

            // response初期値
            $keys = [];
            $msgs = [];

            // errorsをjson形式に変換(true=連想配列)
            $ary = json_decode($validator->errors(), true);
            
            // ループ&値をvalueに設定
            foreach ($ary as $key => $value) {
                // キーを配列に設定
                $keys[] = $key;
                // 値(メッセージ)を設定
                $msgs[] = $value;
            }

            // keyデバック
            $arrKeys = print_r($keys , true);
            Log::debug('keys:'.$arrKeys);

            // msgsデバック
            $arrMsgs = print_r($msgs , true);
            Log::debug('msgs:'.$arrMsgs);

            // response値設定
            // status = falseの場合js側でerrorメッセージ表示
            $response["status"] = false;
            $response['msg'] = "入力を確認して下さい。";
            $response["messages"] = $msgs;
            $response["errkeys"] = $keys;
            
            Log::debug('log_end:' .__FUNCTION__);
        }
        return $response;
    }

    /**
     * 新規登録(各テーブルに分岐)
     *
     * @param Request $request(edit.blade.phpの各項目)
     * @return ret(true:登録OK/false:登録NG、maxId(contract_id)、session_id(create_user_id))
     */
    private function insertData(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {

            // retrun初期値
            $ret = [];
            $ret['status'] = true;

            /**
             * 不動産業者(status:OK=1 NG=0/application_id:新規登録のid)
             */
            $user_license_info = $this->insertUserLicense($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $user_license_info['status'];

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            $ret['status'] = 0;

        // status:OK=1/NG=0
        } finally {

            if($ret['status'] == 1){

                Log::debug('status:trueの処理');
                $ret['status'] = true;

            }else{

                // Log::debug('status:falseの処理');
                // $ret['status'] = false;
            }

            Log::debug('log_end:'.__FUNCTION__);
            return $ret;
        }
    }

    /**
     * 宅建取引士(新規登録)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function insertUserLicense(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $user_license_name = $request->input('user_license_name');
            $user_license_ruby = $request->input('user_license_ruby');
            $user_license_number = $request->input('user_license_number');

            // 現在の日付取得
            $date = now() .'.000';
    
            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // 宅地建物取引氏名
            if($user_license_name == null){
                $user_license_name ='';
            }

            // フリガナ
            if($user_license_ruby == null){
                $user_license_ruby ='';
            }

            // 登録番号
            if($user_license_number == null){
                $user_license_number ='';
            }

            // 登録
            $str = "insert "
            ."into "
            ."user_licenses( "
            ."create_user_id, "
            ."user_license_name, "
            ."user_license_ruby, "
            ."user_license_number, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$session_id, "
            ."'$user_license_name', "
            ."'$user_license_ruby', "
            ."'$user_license_number', "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";
            
            Log::debug('sql:'.$str);

            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

        // 例外処理
        } catch (\Throwable  $e) {

            throw $e;

        // status:OK=1/NG=0
        } finally {
            
        }

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 編集登録(各テーブルに分岐)
     *
     * @param Request $request(edit.blade.phpの各項目)
     * @return ret(true:登録OK/false:登録NG)
     */
    private function updateData(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            
            // retrun初期値
            $ret = [];
            $ret['status'] = true;

            /**
             * status:OK=1 NG=0
             */
            $legal_place_info = $this->updateLegalPlace($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $legal_place_info['status'];

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            $ret['status'] = 0;

        // status:OK=1/NG=0
        } finally {

            if($ret['status'] == 1){

                Log::debug('status:trueの処理');
                $ret['status'] = true;

            }else{

                Log::debug('status:falseの処理');
                $ret['status'] = false;
            }

            Log::debug('log_end:'.__FUNCTION__);
            return $ret;
        }
    }

    /**
     * 宅建取引士(編集)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function updateLegalPlace(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $user_license_id = $request->input('user_license_id');
            $user_license_name = $request->input('user_license_name');
            $user_license_ruby = $request->input('user_license_ruby');
            $user_license_number = $request->input('user_license_number');

            // 現在の日付取得
            $date = now() .'.000';
    
            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // 宅地建物取引氏名
            if($user_license_name == null){
                $user_license_name ='';
            }

            // フリガナ
            if($user_license_ruby == null){
                $user_license_ruby ='';
            }

            // 登録番号
            if($user_license_number == null){
                $user_license_number ='';
            }


            $str = "update "
            ."user_licenses "
            ."set "
            ."create_user_id = $session_id, "
            ."user_license_name = '$user_license_name', "
            ."user_license_ruby = '$user_license_ruby', "
            ."user_license_number = '$user_license_number', "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."user_license_id = $user_license_id; ";
            
            Log::debug('sql:'.$str);

            // OK=1/NG=0
            $ret['status'] = DB::update($str);

        // 例外処理
        } catch (\Throwable  $e) {

            throw $e;

        // status:OK=1/NG=0
        } finally {
            
        }

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 削除(各テーブルに分岐)
     *
     * @param Request $request
     * @return void
     */
    public function backUserLicenseDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $response = [];

            $user_license_info = $this->deleteUserLicense($request);

            // js側での判定のステータス(true:OK/false:NG)
            $response['status'] = $user_license_info['status'];

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            $response['status'] = 0;

        // status:OK=1/NG=0
        } finally {

            if($response['status'] == 1){

                Log::debug('status:trueの処理');
                $response['status'] = true;

            }else{

                Log::debug('status:falseの処理');
                $response['status'] = false;
            }

        }

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 削除(宅地建物取引士)
     *
     * @param Request $request
     * @return void
     */
    private function deleteUserLicense(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $user_license_id = $request->input('user_license_id');

            $str = "delete "
            ."from "
            ."user_licenses "
            ."where "
            ."user_license_id = $user_license_id; ";

            // OK=1/NG=0
            $ret['status'] = DB::delete($str);

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            throw $e;

        // status:OK=1/NG=0
        } finally {

        }

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

}

