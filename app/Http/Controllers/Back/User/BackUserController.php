<?php

namespace App\Http\Controllers\Back\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Storage;

use InterventionImage;

use Common;

/**
 * アカウント情報
 */
class BackUserController extends Controller
{   
    /**
     *  アカウント一覧(表示・検索)
     *
     * @param Request $request(フォームデータ)
     * @return 
     */
    public function backUserInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {            
            // アカウント情報
            $user_info = $this->getUserList($request);
            $user_list = $user_info[0];

            $common = new Common();

            // 宅地建物取引士(コンボボックス)
            $user_license_list = $common->getUserLicense($request);

            // 保証協会一覧
            $guaranty_association_list = $common->getGuarantyAssociation($request);
            
            // 法務局リスト
            $legal_place_list = $common->getLegalPlace($request);

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backUser' ,compact('user_license_list' ,'guaranty_association_list' ,'legal_place_list' ,'user_list'));
    }

    /**
     * アカウント情報(取得)
     *
     * @param Request $request
     * @return $ret(アカウント情報)
     */
    public function getUserList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {

            // 値取得
            $session_id = $request->session()->get('create_user_id');


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
            ."where "
            ."create_users.create_user_id = $session_id ";

            Log::debug('$str:'.$str);
            $ret = DB::select($str);

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 宅地建物取引士一覧(取得)
     *
     * @param Request $request
     * @return $ret['res'](宅建取引士一覧)
     */
    private function getUserLicenseRes(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('$session_id:' .$session_id);

            $str = "select "
            ."* "
            ."from "
            ."user_licenses ";

            // where
            $where = "where user_licenses.create_user_id = $session_id ";

            // order by句
            $order_by = "order by user_license_id ";

            $str = $str .$where .$order_by;
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");

            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->paginate(5)->onEachSide(1);

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
     * 専任取引士(コンボボックス変更)
     *
     * @param Request $request
     * @return $response(宅建取引士)
     */
    public function backUserLicenseChange(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // 専任取引士id
        $full_time_user_license_id = $request->input('full_time_user_license_id');

        $str = "select * "
        ."from "
        ."user_licenses "
        ."where user_licenses.user_license_id = $full_time_user_license_id ";
        Log::debug('sql:' .$str);
        $user_license_list = DB::select($str);
        
        // return
        $response = [];
        $response['user_license_list'] = $user_license_list;

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 法務局(コンボボックス変更)
     *
     * @param Request $request
     * @return $response(宅建取引士)
     */
    public function backLegalPlaceChange(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // 専任取引士id
        $legal_place_id = $request->input('legal_place_id');

        $str = "select * "
        ."from "
        ."legal_places "
        ."where legal_places.legal_place_id = $legal_place_id ";
        Log::debug('sql:' .$str);
        $legal_place_list = DB::select($str);
        
        // return
        $response = [];
        $response['legal_place_list'] = $legal_place_list;

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 保証協会(コンボボックス変更)
     *
     * @param Request $request
     * @return $response(不動産保証協会)
     */
    public function backGuarantyAssociationChange(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // 不動産保証協会id
        $guaranty_association_id = $request->input('guaranty_association_id');

        $str = "select * "
        ."from "
        ."guaranty_associations "
        ."where guaranty_associations.guaranty_association_id = $guaranty_association_id ";
        Log::debug('sql:' .$str);
        $guaranty_association_list = DB::select($str);
        
        // return
        $response = [];
        $response['guaranty_association_list'] = $guaranty_association_list;

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 保証協会：所属地方(コンボボックス変更)
     *
     * @param Request $request
     * @return $response(不動産保証協会)
     */
    public function backGuarantyAssociationRegionsregionChange(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // 保証協会id
        $guaranty_association_region_id = $request->input('guaranty_association_region_id');

        $str = "select * "
        ."from "
        ."guaranty_associations "
        ."where guaranty_associations.guaranty_association_id = $guaranty_association_region_id ";
        Log::debug('sql:' .$str);
        $guaranty_association_list = DB::select($str);
        
        // return
        $response = [];
        $response['guaranty_association_list'] = $guaranty_association_list;

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 登録分岐(新規/編集)
     *
     * @param $request(edit.blade.phpの各項目)
     * @return $response(status:true=OK/false=NG)
     */
    public function backUserEditEntry(Request $request){
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
            ."admin_user_flag = 0, "
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
} 