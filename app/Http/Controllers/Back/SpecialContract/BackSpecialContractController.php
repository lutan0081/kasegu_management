<?php

namespace App\Http\Controllers\Back\SpecialContract;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Storage;

use Common;

/**
 * 特約事項(表示・登録、編集、削除)
 */
class BackSpecialContractController extends Controller
{   
    /**
     *  一覧(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backSpecialContractInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {

            // 特約事項一覧
            $special_contract_info = $this->getSpecialContractList($request);
            
        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backSpecialContract' ,$special_contract_info);
    }

    /**
     * 一覧(sql)
     *
     * @return $ret(特約事項)
     */
    private function getSpecialContractList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // フリーワード
            $free_word = $request->input('free_word');
            Log::debug('$free_word:' .$free_word);

            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('$session_id:' .$session_id);

            $str = "select * "
            ."from special_contracts ";
            
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

                $where = $where ."ifnull(special_contract_name,'') like '%$free_word%'";
            };

            // id
            if($where == ""){

                $where = $where ."where "
                ."special_contracts.entry_user_id = '$session_id' ";

            }else{

                $where = $where ."and "
                ."special_contracts.entry_user_id = '$session_id' ";
            }

            $str = $str .$where;
            Log::debug('sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");

            // columnの設定、表示件数
            // $res = DB::table($alias)->selectRaw("*")->orderByRaw("sort_id")->paginate(20)->onEachSide(1);
            $res = DB::table($alias)->selectRaw("*")->orderByRaw("sort_id ASC")->paginate(50)->onEachSide(1);

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
     *  編集(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backSpecialContractEdit(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try { 
            // 申込情報取得(新規:空データ)
            $special_contract_info = $this->getSpecialContractEditList($request);

            $response = [];

            $response['special_contract_info'] = $special_contract_info[0];
            $arrString = print_r($response , true);
            Log::debug('status:'.$arrString);

        // 例外処理
        } catch (\Exception $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            $ret['status'] = 0;

        } finally {

        }

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);;    
    }
    
    /**
     * 編集(申込情報取得:sql)
     *
     * @return void
     */
    private function getSpecialContractEditList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            // 値設定
            $special_contracts_id = $request->input('special_contract_id');

            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('$session_id:' .$session_id);

            $str = "select "
            ."* "
            ."from "
            ."special_contracts "
            ."where "
            ."special_contracts.special_contract_id = $special_contracts_id "
            ."order by "
            ."special_contract_id ";

            Log::debug('$sql:' .$str);
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
    public function backSpecialContractEntry(Request $request){
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
        $special_contract_id = $request->input('special_contract_id');

        // 新規登録
        if($request->input('special_contract_id') == ""){

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
        $rules['special_contract_name'] = "required|max:300";

        /**
         * messages
         */
        $messages = [];
        $messages['special_contract_name.required'] = "内容は必須です。";
        $messages['special_contract_name.max'] = "内容の文字数が超過しています。";
    
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
            $special_contact = $this->insertSpecialContact($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $special_contact['status'];

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
     * 特約事項(新規登録)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function insertSpecialContact(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $special_contract_name = $request->input('special_contract_name');
            $special_contract_default_id = $request->input('special_contract_default_id');

            // 現在の日付取得
            $date = now() .'.000';
    
            // 空の値を挿入
            // 内容
            if($special_contract_name == null){
                $special_contract_name ='';
            }

            // デフォルト値
            if($special_contract_default_id == null){
                $special_contract_default_id = 0;
            }

            // 登録
            $str = "insert "
            ."into "
            ."special_contracts "
            ."( "
            ."create_user_id, "
            ."special_contract_name, "
            ."special_contract_default_id, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$session_id, "
            ."'$special_contract_name', "
            ."$special_contract_default_id, "
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
            $special_contact_info = $this->updateSpecialContact($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $special_contact_info['status'];

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
     * 特約事項(編集登録)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function updateSpecialContact(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $special_contract_id = $request->input('special_contract_id');
            $special_contract_name = $request->input('special_contract_name');
            $special_contract_default_id = $request->input('special_contract_default_id');

            // 現在の日付取得
            $date = now() .'.000';
    
            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // 空の値を挿入
            // 内容
            if($special_contract_name == null){
                $special_contract_name ='';
            }

            // デフォルト値
            if($special_contract_default_id == null){
                $special_contract_default_id = 0;
            }

            $str = "update "
            ."special_contracts "
            ."set "
            ."create_user_id = $session_id, "
            ."special_contract_name = '$special_contract_name', "
            ."special_contract_default_id = $special_contract_default_id, "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."special_contract_id = $special_contract_id; ";
            
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
    public function backSpecialDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $response = [];

            $special_contract_info = $this->deleteSpecialContractDelete($request);

            // js側での判定のステータス(true:OK/false:NG)
            $response['status'] = $special_contract_info['status'];

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
    private function deleteSpecialContractDelete(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $special_contract_id = $request->input('special_contract_id');

            $str = "delete "
            ."from "
            ."special_contracts "
            ."where "
            ."special_contract_id = $special_contract_id; ";
            Log::debug('str:' .$str);

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

    /**
     * 一覧並び替え
     *
     * @param Request $request
     * @return void
     */
    public function backSpecialSortEntry(Request $request){

        Log::debug('start:' .__FUNCTION__);

        $response = [];

        try { 
            
            $sort_info = $this->updateSortData($request);

            $response['status'] = $sort_info['status'];

        // 例外処理
        } catch (\Exception $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            $response['status'] = false;

        } finally {

        }

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);;  
    }
    
    private function updateSortData(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            
            // retrun初期値
            $ret = [];
            $ret['status'] = true;

            $ids = $request->input('ids');

            $session_id = $request->session()->get('create_user_id');

            // sortid初期値
            $sort_number = 1;
            
            foreach ($ids as $id){

                $str = "update "
                ."special_contracts "
                ."set "
                ."sort_id = $sort_number, "
                ."update_user_id = $session_id, "
                ."update_date = now() "
                ."where "
                ."special_contract_id = $id; ";

                Log::debug('sql:'.$str);

                // OK=1/NG=0
                $ret['status'] = DB::update($str);

                $sort_number++;
            }

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

    

}

