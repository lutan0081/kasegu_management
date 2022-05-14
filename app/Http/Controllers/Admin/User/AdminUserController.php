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
     * 編集(表示)
     *
     * @param Request $request
     * @return void
     */
    public function adminUserEditInit(Request $request){

        Log::debug('start:' .__FUNCTION__);

        try {

            // ユーザ情報
            $user_info = $this->getUserEditList($request);
            $user_list = $user_info[0];
            // dd($user_list);

            // リスト作成
            $common = new Common();

            // 宅地建物取引士(コンボボックス)
            $user_license_list = $common->getUserLicense($request);
            
            // 保証協会一覧
            $guaranty_association_list = $common->getGuarantyAssociation($request);

            // 法務局リスト
            $legal_place_list = $common->getLegalPlace($request);
            

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminUserEdit' ,compact('user_license_list' ,'guaranty_association_list' ,'legal_place_list' ,'user_list'));    
    }

    /**
     * 編集(申込情報取得:sql)
     *
     * @return void
     */
    private function getUserEditList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            // 値取得
            $create_user_id = $request->input('create_user_id');

            $str = "select "
            ."create_users.create_user_id as create_user_id, "
            ."create_users.create_user_name as create_user_name, "
            ."create_users.create_user_post_number as create_user_post_number, "
            ."create_users.create_user_address as create_user_address, "
            ."create_users.create_user_tel as create_user_tel, "
            ."create_users.create_user_fax as create_user_fax, "
            ."create_users.create_user_mail as create_user_mail, "
            ."create_users.password as password, "
            ."company_licenses.company_license_id as company_license_id, "
            ."company_licenses.company_license_name as company_license_name, "
            ."company_licenses.company_license_representative as company_license_representative, "
            ."company_licenses.company_license_address as company_license_address, "
            ."company_licenses.company_license_tel as company_license_tel, "
            ."company_licenses.company_license_fax as company_license_fax, "
            ."company_licenses.company_license_number as company_license_number, "
            ."company_licenses.company_license_span as company_license_span, "
            ."company_licenses.company_nick_name as company_nick_name, "
            ."company_licenses.company_nick_address as company_nick_address, "
            ."company_licenses.user_license_id as full_time_user_license_id, "
            ."user_licenses.user_license_name as full_time_user_license_name, "
            ."user_licenses.user_license_number as full_time_user_license_number, "
            ."company_licenses.legal_place_id as legal_place_id, "
            ."legal_places.legal_place_name as legal_place_name, "
            ."legal_places.legal_place_post_number as legal_place_post_number, "
            ."legal_places.legal_place_address as legal_place_address, "
            ."legal_places.legal_place_tel as legal_place_tel, "
            ."legal_places.legal_place_fax as legal_place_fax, "
            ."company_licenses.guaranty_association_id as guaranty_association_id, "
            ."guaranty_associations.guaranty_association_name as guaranty_association_name, "
            ."guaranty_associations.guaranty_association_post_number as guaranty_association_post_number, "
            ."guaranty_associations.guaranty_association_address as guaranty_association_address, "
            ."guaranty_associations.guaranty_association_tel as guaranty_association_tel, "
            ."guaranty_associations.guaranty_association_fax as guaranty_association_fax, "
            ."guaranty_association_region.guaranty_association_id as guaranty_association_region_id, "
            ."guaranty_association_region.guaranty_association_name as guaranty_association_region_name, "
            ."guaranty_association_region.guaranty_association_post_number as guaranty_association_region_post_number, "
            ."guaranty_association_region.guaranty_association_address as guaranty_association_region_address, "
            ."guaranty_association_region.guaranty_association_tel as guaranty_association_region_tel, "
            ."guaranty_association_region.guaranty_association_fax as guaranty_association_region_fax, "
            ."create_users.admin_user_flag, "
            ."create_users.entry_date, "
            ."create_users.update_user_id, "
            ."create_users.update_date "
            ."from "
            ."create_users "
            ."left join company_licenses on "
            ."company_licenses.create_user_id = create_users.create_user_id "
            ."left join user_licenses on "
            ."user_licenses.user_license_id = company_licenses.user_license_id "
            ."left join legal_places on "
            ."legal_places.legal_place_id = company_licenses.legal_place_id "
            ."left join guaranty_associations on "
            ."guaranty_associations.guaranty_association_id = company_licenses.guaranty_association_id "
            ."left join guaranty_associations as guaranty_association_region on "
            ."guaranty_association_region.guaranty_association_id = company_licenses.guaranty_association_region_id "
            ."where "
            ."create_users.create_user_id = $create_user_id ";
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
    public function adminUserEditEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);
        
        // return初期値
        $response = [];

        // OK=true NG=false
        $response = $this->editValidation($request);

        if($response["status"] == false){

            Log::debug('validator_status:falseのif文通過');
            return response()->json($response);

        }

        // $responseの値設定
        $ret = $this->updateData($request);
        $arrString = print_r($ret , true);
        Log::debug('ret:'.$arrString);

        // js側での判定のステータス(true:OK/false:NG)
        $response['status'] = $ret['status'];

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
        // アカウント情報
        $rules = [];
        $rules['create_user_name'] = "required|max:50";
        $rules['create_user_mail'] = "required|email";
        $rules['create_user_post_number'] = "required|zip";
        $rules['create_user_address'] = "required|max:200";
        $rules['create_user_tel'] = "required|jptel";
        $rules['create_user_fax'] = "nullable|jptel";
        $rules['password'] = "required|alpha_dash|min:8";

        // 免許情報
        $rules['company_license_name'] = "nullable|max:100";
        $rules['company_license_representative'] = "nullable|max:50";
        $rules['company_license_address'] = "nullable|max:100";
        $rules['company_license_tel'] = "nullable|jptel";
        $rules['company_license_fax'] = "nullable|jptel";
        $rules['company_license_number'] = "nullable|max:20";
        $rules['company_license_span'] = "nullable|max:30";
        $rules['company_nick_name'] = "nullable|max:100";
        $rules['company_nick_address'] = "nullable|max:100";
        
        /**
         * messages
         */
        // アカウント情報
        $messages = [];
        $messages['create_user_name.required'] = "名前は必須です。";
        $messages['create_user_name.max'] = "名前の文字数が超過しています。";
        $messages['create_user_mail.required'] = "メールアドレスは必須です。";
        $messages['create_user_mail.email'] = "メールアドレスの形式が不正です。";
        $messages['create_user_post_number.required'] = "郵便番号は必須です。";
        $messages['create_user_post_number.zip'] = "郵便番号の形式が不正です。";
        $messages['create_user_address.required'] = "住所は必須です。";
        $messages['create_user_address.max'] = "住所の文字数が超過しています。";
        $messages['create_user_tel.required'] = "Telは必須です。";
        $messages['create_user_tel.jptel'] = "Telの形式が不正です。";
        $messages['create_user_fax.jptel'] = "Faxの形式が不正です-。";
        $messages['password.required'] = "パスワードは必須です。";
        $messages['password.alpha_dash'] = "パスワードは半角英数字で入力して下さい。";

        // 免許概要
        $messages['company_license_name.required'] = "商号の文字数が超過しています。";
        $messages['company_license_representative.max'] = "代表者の文字数が超過しています。";
        $messages['company_license_tel.jptel'] = "TELの形式が不正です。";
        $messages['company_license_fax.jptel'] = "FAXの形式が不正です。";
        $messages['company_license_number.max'] = "免許番号の文字数が超過しています。";
        $messages['company_license_span.max'] = "免許年月日の文字数が超過しています。";
        $messages['company_nick_name.max'] = "取扱店の文字数が超過しています。";
        $messages['company_nick_address.max'] = "所在地の文字数が超過しています。";
    
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
    private function updateData(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {

            // トランザクション
            DB::beginTransaction();

            // retrun初期値
            $ret = [];

            /**
             * status:OK=1 NG=0
             */
            // アカウント情報
            $create_user_info = $this->updateCreateUsers($request);

            $ret['status'] = $create_user_info['status'];

            // 免許情報
            $company_license_info = $this->updateCompanyLicenses($request);

            $ret['status'] = $company_license_info['status'];

            // コミット
            DB::commit();

        // 例外処理
        } catch (\Throwable $e) {

            DB::rollback();

            $ret['status'] = 0;

            Log::debug(__FUNCTION__ .':' .$e);

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
     * アカウント情報(情報)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function updateCreateUsers(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $create_user_name = $request->input('create_user_name');
            $create_user_mail = $request->input('create_user_mail');
            $create_user_post_number = $request->input('create_user_post_number');
            $create_user_address = $request->input('create_user_address');
            $create_user_tel = $request->input('create_user_tel');
            $create_user_fax = $request->input('create_user_fax');
            $password = $request->input('password');

            // 現在の日付取得
            $date = now() .'.000';
    
            // 数値に関してはNULLで値を代入出来ないので0、''を入れる
            // アカウント名
            if($create_user_name == null){

                $create_user_name ='';

            }

            // メールアドレス
            if($create_user_mail == null){

                $create_user_mail =0;

            }

            // 郵便番号
            if($create_user_post_number == null){

                $create_user_post_number =0;

            }

            // 所在地
            if($create_user_address == null){

                $create_user_address = '';

            }

            // TEL
            if($create_user_tel == null){

                $create_user_tel = '';

            }

            // FAX
            if($create_user_fax == null){

                $create_user_fax = '';

            }

            // パスワード
            if($password == null){

                $password = '';

            }

            $str = "update "
            ."create_users "
            ."set "
            ."create_user_name = '$create_user_name', "
            ."create_user_post_number = '$create_user_post_number', "
            ."create_user_address = '$create_user_address', "
            ."create_user_tel = '$create_user_tel', "
            ."create_user_fax = '$create_user_fax', "
            ."create_user_mail = '$create_user_mail', "
            ."password = '$password', "
            ."complete_flag = 1, "
            ."admin_user_flag = 1, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."create_user_id = $session_id; ";
            
            Log::debug('sql:'.$str);

            // OK=1/NG=0
            $ret['status'] = DB::update($str);

            $arrString = print_r($ret , true);
            Log::debug('status:'.$arrString);

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
     * 免許情報(編集)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function updateCompanyLicenses(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $company_license_name = $request->input('company_license_name');
            $company_license_representative = $request->input('company_license_representative');
            $company_license_address = $request->input('company_license_address');
            $company_license_tel = $request->input('company_license_tel');
            $company_license_fax = $request->input('company_license_fax');
            $company_license_number = $request->input('company_license_number');
            $company_license_span = $request->input('company_license_span');
            $full_time_user_license_id = $request->input('full_time_user_license_id');
            $full_time_user_license_number = $request->input('full_time_user_license_number');
            $company_nick_name = $request->input('company_nick_name');
            $company_nick_address = $request->input('company_nick_address');
            $legal_place_id = $request->input('legal_place_id');
            $guaranty_association_id = $request->input('guaranty_association_id');
            $guaranty_association_region_id = $request->input('guaranty_association_region_id');

            // 現在の日付取得
            $date = now() .'.000';
    
            // 数値に関してはNULLで値を代入出来ないので0、''を入れる
            // 商号
            if($company_license_name == null){

                $company_license_name = '';

            }

            // 代表者
            if($company_license_representative == null){

                $company_license_representative = '';

            }

            // 所在地
            if($company_license_address == null){

                $company_license_address = '';

            }

            // TEL
            if($company_license_tel == null){

                $company_license_tel = '';

            }

            // FAX
            if($company_license_fax == null){

                $company_license_fax = '';

            }

            // 免許番号
            if($company_license_number == null){

                $company_license_number = '';

            }

            // 免許年月日
            if($company_license_span == null){

                $company_license_span = '';

            }

            // 宅建取引士id
            if($full_time_user_license_id == null){

                $full_time_user_license_id = 0;

            }

            // 登録番号
            if($full_time_user_license_number == null){

                $full_time_user_license_number = '';

            }

            // 取扱店
            if($company_nick_name == null){

                $company_nick_name = '';

            }

            // 所在地
            if($company_nick_address == null){

                $company_nick_address = '';

            }

            // 法務局
            if($legal_place_id == null){

                $legal_place_id = 0;

            }

            // 不動産保証協会
            if($guaranty_association_id == null){

                $guaranty_association_id = 0;

            }

            // 不動産保証協会
            if($guaranty_association_region_id == null){

                $guaranty_association_region_id = 0;

            }

            $str = "update "
            ."company_licenses "
            ."set "
            ."create_user_id = $session_id, "
            ."company_license_name = '$company_license_name', "
            ."company_license_representative = '$company_license_representative', "
            ."company_license_address = '$company_license_address', "
            ."company_license_tel = '$company_license_tel', "
            ."company_license_fax = '$company_license_fax', "
            ."company_license_number = '$company_license_number', "
            ."company_license_span = '$company_license_span', "
            ."company_nick_name = '$company_nick_name', "
            ."company_nick_address = '$company_nick_address', "
            ."user_license_id = $full_time_user_license_id, "
            ."legal_place_id = $legal_place_id, "
            ."guaranty_association_id = $guaranty_association_id, "
            ."guaranty_association_region_id = $guaranty_association_region_id, "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."create_user_id = $session_id; ";
            
            Log::debug('sql:'.$str);

            // OK=1/NG=0
            $ret['status'] = DB::update($str);

            $arrString = print_r($ret , true);
            Log::debug('status:'.$arrString);

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
    public function adminUserDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // return初期値
            $response = [];

            /**
             * 不動産業者
             */
            $user_info = $this->deleteUser($request);

            // js側での判定のステータス(true:OK/false:NG)
            $response['status'] = $user_info['status'];

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
    private function deleteUser(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $create_user_id = $request->input('create_user_id');

            $str = "delete "
            ."from "
            ."create_users "
            ."where "
            ."create_user_id = $create_user_id; ";

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