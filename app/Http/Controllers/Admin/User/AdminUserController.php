<?php

namespace App\Http\Controllers\Admin\User;

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
 * 表示・登録、編集、削除
 */
class AdminUserController extends Controller
{   
    /**
     *  一覧(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function adminUserInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {
            // アカウント一覧
            $user_list = $this->getList($request);
            
        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminUser' ,$user_list);
    }

    /**
     * 一覧(sql)
     *
     * @return $ret
     */
    private function getList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // フリーワード
            $free_word = $request->input('free_word');
            Log::debug('$free_word:' .$free_word);

            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('$session_id:' .$session_id);

            $str = "select * from create_users ";

            Log::debug('$sql:' .$str);
                        
            // where句
            $where = "";

            // フリーワード
            if($free_word !== null){

                if($where == ""){

                    $where = $where ."where ";

                }else{

                    $where = $where ."and ";
                }

                $where = $where ."ifnull(create_user_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(create_user_mail,'') like '%$free_word%'";
                $where = $where ."or ifnull(create_user_tel,'') like '%$free_word%'";
            };

            $str = $str .$where;
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");

            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->orderByRaw("create_user_id desc")->paginate(50)->onEachSide(1);

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
     * 新着情報種別
     */
    private function getInformationTypeList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // session_id
        $session_id = $request->session()->get('create_user_id');
        
        $str = "select * "
        ."from information_types";
        Log::debug('$sql:' .$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  新規(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backLegalPlaceNewInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {            
            // 法務局一覧
            $legal_place_list = $this->getNewList($request);
            
        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backLegalPlaceEdit' ,compact('legal_place_list'));
    }

    /**
     * 新規(ダミー値取得)
     *
     * @return $ret(空の配列)
     */
    private function getNewList(){
        Log::debug('log_start:'.__FUNCTION__);
        $obj = new \stdClass();
        
        // 募集要項
        $obj->legal_place_id  = '';
        $obj->legal_place_name = '';
        $obj->legal_place_post_number = '';
        $obj->legal_place_address = '';
        $obj->legal_place_tel = '';
        $obj->legal_place_fax = '';

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
    public function adminInformationEditInit(Request $request){ 
        
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // return初期値
            $response = [];

            // 一覧取得
            $information_info = $this->getEditList($request);

            // jtrue:OK/false:NG
            $response['status'] = $information_info['status'];

            // information_list
            $response['information_list'] = $information_info['information_list'];

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
     * 編集(sql)
     *
     * @return void
     */
    private function getEditList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            
            /**
             * 値取得
             */
            $information_id = $request->input('information_id');

            /**
             * sql
             */
            $str = "select * "
            ."from informations "
            ."left join information_types "
            ."on informations.information_type_id = information_types.information_type_id "
            ."where informations.information_id = $information_id ";
            Log::debug('sql:' .$str);
            
            $ret['information_list'] = DB::select($str);
            $ret['status'] = 1;

            $arrString = print_r($ret , true);
            Log::debug('messages:'.$arrString);

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
    public function adminInformationEditEntry(Request $request){
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
         * information_id=無:insert
         * information_id=有:update
         */
        $information_id = $request->input('information_id');

        // 新規登録
        if($request->input('information_id') == ""){

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
        $rules['information_title'] = "required|max:100";
        $rules['information_contents'] = "required|max:300";

        /**
         * messages
         */
        $messages = [];
        $messages['information_title.required'] = "タイトルは必須です。";
        $messages['information_title.max'] = "タイトルの文字数が超過しています。";
        $messages['information_contents.required'] = "内容は必須です。";
        $messages['information_contents.max'] = "内容の文字数が超過しています。";

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
             * status:OK=1 NG=0/application_id:新規登録のid
             */
            $information_info = $this->insertInformation($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $information_info['status'];

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
     * 新規登録(sql)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function insertInformation(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {

            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');

            $information_id = $request->input('information_id');

            $information_title = $request->input('information_title');

            $information_type_id = $request->input('information_type_id');

            $information_contents = $request->input('information_contents');

            // 現在の日付取得
            $date = now() .'.000';
    
            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // id
            if($information_id == null){
                $information_id =0;
            }

            // タイトル
            if($information_title == null){
                $information_title ='';
            }

            // 内容
            if($information_contents == null){
                $information_contents ='';
            }

            // 種別
            if($information_type_id == null){
                $information_type_id = 0;
            }

            // sql
            $str = "insert "
            ."into "
            ."informations "
            ."( "
            ."information_title, "
            ."information_type_id, "
            ."information_contents, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."'$information_title', "
            ."$information_type_id, "
            ."'$information_contents', "
            ."1, "
            ."now(), "
            ."1, "
            ."now() "
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
            $information_info = $this->updateInformation($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $information_info['status'];

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
     * 編集登録(sql)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function updateInformation(Request $request){

        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');

            $information_id = $request->input('information_id');

            $information_title = $request->input('information_title');

            $information_type_id = $request->input('information_type_id');

            $information_contents = $request->input('information_contents');

            // 現在の日付取得
            $date = now() .'.000';
    
            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // id
            if($information_id == null){
                $information_id =0;
            }

            // タイトル
            if($information_title == null){
                $information_title ='';
            }

            // 内容
            if($information_contents == null){
                $information_contents ='';
            }

            // 種別
            if($information_type_id == null){
                $information_type_id = 0;
            }

            $str = "update "
            ."informations "
            ."set "
            ."information_title = '$information_title', "
            ."information_type_id = $information_type_id, "
            ."information_contents = '$information_contents', "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."information_id = $information_id; ";
            
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
     * 削除
     *
     * @param Request $request
     * @return void
     */
    public function adminDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // return初期値
            $response = [];

            /**
             * 不動産業者
             */
            $legal_place_info = $this->deleteInformation($request);

            // js側での判定のステータス(true:OK/false:NG)
            $response['status'] = $legal_place_info['status'];

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
     * 削除(sql)
     *
     * @param Request $request
     * @return void
     */
    private function deleteInformation(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $information_id = $request->input('information_id');

            $str = "delete "
            ."from "
            ."informations "
            ."where "
            ."information_id = $information_id; ";

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