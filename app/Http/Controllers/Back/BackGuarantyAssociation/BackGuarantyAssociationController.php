<?php

namespace App\Http\Controllers\Back\BackGuarantyAssociation;

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
 * 保証協会(表示・登録、編集、削除)
 */
class BackGuarantyAssociationController extends Controller
{   
    /**
     *  一覧(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backGuarantyAssociationInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {
            // 情報一覧
            $guaranty_association_info = $this->getList($request);
            
        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backGuarantyAssociation' ,$guaranty_association_info);
    }

    /**
     * 一覧(sql)
     *
     * @return $ret(法務局一覧)
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

            $str = "select "
            ."* "
            ."from "
            ."guaranty_associations ";
                        
            // where句
            $where = "";

            // フリーワード
            if($free_word !== null){

                if($where == ""){

                    $where = "where ";
                }else{

                    $where = "and ";
                }

                $where = $where ."ifnull(guaranty_association_name,'') like '%$free_word%'";
            };

            // id
            if($where == ""){

                $where = $where ."where "
                ."guaranty_associations.entry_user_id = '$session_id' ";

            }else{

                $where = $where ."and "
                ."guaranty_associations.entry_user_id = '$session_id' ";
            }

            // order by句
            $order_by = "order by guaranty_association_id ";

            $str = $str .$where .$order_by;
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");

            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->paginate(50)->onEachSide(1);

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
     *  新規(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backGuarantyAssociationNewInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {    

            // 保証協会一覧
            $guaranty_association_list = $this->getGuarantyAssociationNewList($request);
            
        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backGuarantyAssociationEdit' ,compact('guaranty_association_list'));
    }

    /**
     * 新規(ダミー値取得)
     *
     * @return $ret(空の配列)
     */
    private function getGuarantyAssociationNewList(){
        Log::debug('log_start:'.__FUNCTION__);
        $obj = new \stdClass();
        
        $obj->guaranty_association_id  = '';
        $obj->guaranty_association_name = '';
        $obj->guaranty_association_post_number = '';
        $obj->guaranty_association_address = '';
        $obj->guaranty_association_tel = '';
        $obj->guaranty_association_fax = '';

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
    public function backGuarantyAssociationEditInit(Request $request){ 

        Log::debug('start:' .__FUNCTION__);

        try {
                    
            // 一覧取得
            $guaranty_association_info = $this->getEditList($request);
            $guaranty_association_list = $guaranty_association_info[0];

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backGuarantyAssociationEdit' ,compact('guaranty_association_list'));

    }

    /**
     * 編集(申込情報取得:sql)
     *
     * @return void
     */
    private function getEditList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            // 値設定
            $guaranty_association_id = $request->input('guaranty_association_id');

            // sql
            $str = "select * "
            ."from guaranty_associations "
            ."where guaranty_associations.guaranty_association_id = $guaranty_association_id ";
            Log::debug('sql:' .$str);
            
            $ret = DB::select($str);

        // 例外処理
        } catch (\Exception $e) {

            throw $e;

        } finally {

        }
        
        Log::debug('end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 登録分岐(新規/編集)
     *
     * @param $request(edit.blade.phpの各項目)
     * @return $response(status:true=OK/false=NG)
     */
    public function backGuarantyAssociationEditEntry(Request $request){
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
         * legal_place_id_id=無:insert
         * legal_place_id_id=有:update
         */
        $guaranty_association_id = $request->input('guaranty_association_id');

        // 新規登録
        if($request->input('guaranty_association_id') == ""){

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
        $rules['guaranty_association_name'] = "required|max:50";
        $rules['guaranty_association_post_number'] = "required|zip";
        $rules['guaranty_association_address'] = "required|max:100";
        $rules['guaranty_association_tel'] = "nullable|jptel";
        $rules['guaranty_association_fax'] = "nullable|jptel";

        /**
         * messages
         */
        $messages = [];
        $messages['guaranty_association_name.required'] = "名前は必須です。";
        $messages['guaranty_association_name.max'] = "名前の文字数が超過しています。";
        $messages['guaranty_association_post_number.required'] = "郵便番号は必須です。";
        $messages['guaranty_association_post_number.zip'] = "郵便番号の形式が不正です。";
        $messages['guaranty_association_address.required'] = "住所は必須です。";
        $messages['guaranty_association_address.max'] = "住所の文字数が超過しています。";
        $messages['guaranty_association_tel.jptel'] = "Telの形式が不正です。";
        $messages['guaranty_association_fax.jptel'] = "Faxの形式が不正です。";
    
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
            $guaranty_association_info = $this->insertGuarantyAssociation($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $guaranty_association_info['status'];

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
     * 保証協会(新規登録)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function insertGuarantyAssociation(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $guaranty_association_name = $request->input('guaranty_association_name');
            $guaranty_association_post_number = $request->input('guaranty_association_post_number');
            $guaranty_association_address = $request->input('guaranty_association_address');
            $guaranty_association_tel = $request->input('guaranty_association_tel');
            $guaranty_association_fax = $request->input('guaranty_association_fax');

            // 現在の日付取得
            $date = now() .'.000';
    
            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // 保証協会名
            if($guaranty_association_name == null){
                $guaranty_association_name ='';
            }

            // 郵便番号
            if($guaranty_association_post_number == null){
                $guaranty_association_post_number ='';
            }

            // 住所
            if($guaranty_association_address == null){
                $guaranty_association_address ='';
            }

            // Tel
            if($guaranty_association_tel == null){
                $guaranty_association_tel = '';
            }

            // Fax
            if($guaranty_association_fax == null){
                $guaranty_association_fax = '';
            }

            // 登録
            $str = "insert "
            ."into "
            ."guaranty_associations "
            ."( "
            ."guaranty_association_name, "
            ."guaranty_association_post_number, "
            ."guaranty_association_address, "
            ."guaranty_association_tel, "
            ."guaranty_association_fax, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."'$guaranty_association_name', "
            ."'$guaranty_association_post_number', "
            ."'$guaranty_association_address', "
            ."'$guaranty_association_tel', "
            ."'$guaranty_association_fax', "
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
            $guaranty_association_info = $this->updateGuarantyAssociation($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $guaranty_association_info['status'];

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
     * 保証協会(編集)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function updateGuarantyAssociation(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $guaranty_association_id = $request->input('guaranty_association_id');
            $guaranty_association_name = $request->input('guaranty_association_name');
            $guaranty_association_post_number = $request->input('guaranty_association_post_number');
            $guaranty_association_address = $request->input('guaranty_association_address');
            $guaranty_association_tel = $request->input('guaranty_association_tel');
            $guaranty_association_fax = $request->input('guaranty_association_fax');
            $date = now() .'.000';
    
            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // 保証協会名
            if($guaranty_association_name == null){
                $guaranty_association_name ='';
            }

            // 郵便番号
            if($guaranty_association_post_number == null){
                $guaranty_association_post_number ='';
            }

            // 住所
            if($guaranty_association_address == null){
                $guaranty_association_address ='';
            }

            // Tel
            if($guaranty_association_tel == null){
                $guaranty_association_tel = '';
            }

            // Fax
            if($guaranty_association_fax == null){
                $guaranty_association_fax = '';
            }

            $str = "update "
            ."guaranty_associations "
            ."set "
            ."guaranty_association_name = '$guaranty_association_name', "
            ."guaranty_association_post_number = '$guaranty_association_post_number', "
            ."guaranty_association_address = '$guaranty_association_address', "
            ."guaranty_association_tel = '$guaranty_association_tel', "
            ."guaranty_association_fax = '$guaranty_association_fax', "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."guaranty_association_id = $guaranty_association_id; ";
            
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
    public function backGuarantyAssociationDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // return初期値
            $response = [];

            /**
             * 保証協会
             */
            $guaranty_association_info = $this->deleteGuarantyAssociation($request);

            // js側での判定のステータス(true:OK/false:NG)
            $response['status'] = $guaranty_association_info['status'];

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
     * 削除(保証協会)
     *
     * @param Request $request
     * @return void
     */
    private function deleteGuarantyAssociation(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $guaranty_association_id = $request->input('guaranty_association_id');

            $str = "delete "
            ."from "
            ."guaranty_associations "
            ."where "
            ."guaranty_association_id = $guaranty_association_id; ";

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