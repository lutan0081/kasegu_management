<?php

namespace App\Http\Controllers\Detail;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Storage;

// 暗号化
use Illuminate\Support\Facades\Crypt;

// データ縮小
use InterventionImage;

// app.phpでclassを設定し、コマンドプロントで"composer dump-autoload"実行後use Commonのみで使用できる
use Common;

class DetailController extends Controller
{     
    /**
     * 新規(表示)
     * stdClassでダミー値取得->retrunで返す
     *
     * @param Request $request('list','list_types','list_uses','list_relation','list_insurance','list_img','applicationFlag')
     */
    public function newInit(Request $request)
    {    
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * 締切の設定
         */
        // パラメータの日付取得
        // リクエストパラメータの時刻取得
        $req_date = $request->input('deadline');

        // 現在時刻取得- 1時間
        $system_date = date('YmdHis', strtotime('-3 day'));

        // URLからの場合：true
        $application_Flag = $request->input('application_Flag');

        /**
         * req_date != null:URL処理の場合
         */
        if($req_date !== null){
            /**
             * 2021/11/01 < 2021/10/25 "OK"
             * 2021/11/01 < 2021/10/26 "OK"
             * 2021/11/01 < 2021/11/02 "NG" -> NOT FOUNDに遷移
             */
            if (strtotime($req_date) < strtotime($system_date)) {
                Log::debug("期間外の処理");
                return view('expiry.expiry',[]);
            }
        }

        /**
         * 空配列を渡す
         */
        $list = $this->getListNewData();

        /**
         * URLからの場合の処理
         */
        // URLからの場合:True/URLからでない場合:null
        $application_Flag = $request->input('application_Flag');

        // URLからの場合:値取得/URLからでない場合:空白
        $session_id = $request->input('session_id');
        
        // URLからの場合、session_idがパラメーターで送られる為、複合化する
        if($session_id !== null){
            // 複合化
            $session_id = Crypt::decrypt($session_id);
        }
        else{
            $session_id = "";
        }
        
        /**
         * 各コンボボックス取得
         */
        $common = new Common();

        // 申込区分(id.valueの値取得)
        $list_types = $common->getApplicationTypes();

        // 申込種別
        $list_uses = $common->getApplicationUses();

        // 続柄のリスト作成
        $list_relation = $common->getRelationships();

        // 健康保険リスト作成
        $list_insurance = $common->getInsuranceType();

        // 画像ファイルを空配列作成
        $list_img = [];
        
        Log::debug('log_end:'.__FUNCTION__);
        // フォルダ,ファイル名指定
        return view('edit.edit', compact('list','list_types','list_uses','list_relation','list_insurance','list_img','application_Flag','session_id'));
    }

    /**
     * 新規(ダミー値取得)
     *
     * @return void
     */
    private function getListNewData(){
        Log::debug('log_start:'.__FUNCTION__);
        $obj = new \stdClass();
        
        $obj->application_form_id = '';
        $obj->application_type_id = '';
        $obj->application_type_name = '';
        $obj->application_use_id = '';
        $obj->application_use_name = '';
        $obj->contract_start_date = '';
        $obj->real_estate_name = '';
        $obj->real_estate_ruby = '';
        $obj->room_name = '';
        $obj->real_estate_post_number = '';
        $obj->real_estate_address = '';
        $obj->pet_bleeding_name = '';
        $obj->pet_kind_name = '';
        $obj->bicycle_parking_number = '';
        $obj->car_parking_number = '';
        $obj->security_deposit = '';
        $obj->deposit_money = '';
        $obj->key_money = '';
        $obj->deposit_refund = '';
        $obj->rent_fee = '';
        $obj->service_fee = '';
        $obj->water_fee = '';
        $obj->ohter_fee = '';
        $obj->total_rent_fee = '';
        $obj->broker_name = '';
        $obj->broker_tel = '';
        $obj->broker_mail = '';
        $obj->manager_name = '';
        $obj->contract_progress = '';
        $obj->url_send_flag = '';
        $obj->contract_id = '';
        $obj->contract_name = '';
        $obj->contract_ruby = '';
        $obj->contract_sex_id = '';
        $obj->contracts_sex_name = '';
        $obj->contract_age = '';
        $obj->contract_birthday = '';
        $obj->contract_post_number = '';
        $obj->contract_address = '';
        $obj->contract_home_tel = '';
        $obj->contract_mobile_tel = '';
        $obj->contract_work_place_name = '';
        $obj->contract_work_place_ruby = '';
        $obj->contract_work_place_post_number = '';
        $obj->contract_work_place_address = '';
        $obj->contract_work_place_tel = '';
        $obj->contract_work_place_Industry = '';
        $obj->contract_work_place_occupation = '';
        $obj->contract_work_place_years = '';
        $obj->contract_employment_status = '';
        $obj->contract_annual_income = '';
        $obj->contracts_insurance_type_id = '';
        $obj->contracts_insurance_type_name = '';
        $obj->housemate_id = '';
        $obj->housemate_name = '';
        $obj->housemate_ruby = '';
        $obj->housemates_relationship_id = '';
        $obj->housemates_relationship_name = '';
        $obj->housemates_sex_id = '';
        $obj->housemates_sex_name = '';
        $obj->housemate_age = '';
        $obj->housemate_name_birthday = '';
        $obj->housemate_post_number = '';
        $obj->housemate_address = '';
        $obj->housemate_home_tel = '';
        $obj->housemate_mobile_tel = '';
        $obj->emergency_contact_id = '';
        $obj->emergency_contacts_name = '';
        $obj->emergency_contacts_ruby = '';
        $obj->emergency_contacts_relationships_id = '';
        $obj->emergency_contacts_relationships_name = '';
        $obj->emergency_contacts_sex_id = '';
        $obj->emergency_contacts_sex_name = '';
        $obj->emergency_birthday = '';
        $obj->emergency_age = '';
        $obj->emergency_post_number = '';
        $obj->emergency_address = '';
        $obj->emergency_home_tel = '';
        $obj->emergency_mobile_tel = '';
        $obj->guarantor_contracts_id = '';
        $obj->guarantor_name = '';
        $obj->guarantor_ruby = '';
        $obj->guarantors_sex_id = '';
        $obj->guarantors_sex_name = '';
        $obj->guarantors_relationship_id = '';
        $obj->guarantors_relationships_name = '';
        $obj->guarantor_age = '';
        $obj->guarantor_birthday = '';
        $obj->guarantor_post_number = '';
        $obj->guarantor_address = '';
        $obj->guarantor_home_tel = '';
        $obj->guarantor_mobile_tel = '';
        $obj->guarantor_work_place_name = '';
        $obj->guarantor_work_place_ruby = '';
        $obj->guarantor_work_place_post_number = '';
        $obj->guarantor_work_place_address = '';
        $obj->guarantor_work_place_tel = '';
        $obj->guarantor_work_place_Industry = '';
        $obj->guarantor_work_place_occupation = '';
        $obj->guarantor_work_place_years = '';
        $obj->guarantor_work_place_occupation = '';
        $obj->guarantor_status = '';
        $obj->guarantor_annual_income = '';
        $obj->guarantor_insurance_type_id = '';
        $obj->guarantors_insurancetypes_name = '';
        $obj->contracts_forms_create_user_id = '';
        $obj->contracts_create_user_name = '';
        $obj->img_id = '';
        $obj->img_type = '';
        $obj->img_path = '';
        $obj->img_memo = '';

        $ret = [];
        $ret[0] = $obj;

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 編集(表示)
     * 各コンボボックスの値を取得->retrunで返す
     *
     * @param Request $request
     * @return 'list','list_types','list_uses','list_relation','list_insurance','list_img'
     */
    public function editInit(Request $request)
    {    
        Log::debug('log_start:' .__FUNCTION__);

        // form->controllerにデータ格納
        $application_form_id = $request->input('application_form_id');
        
        $application_Flag = $request->input('application_Flag');

        // 直接URL入力された場合の処理
        if($application_form_id == ""){
            // return redirect('/');
        }
        
        /**
         * 締切の設定
         */
        // パラメータの日付取得
        // リクエストパラメータの時刻取得
        $req_date = $request->input('deadline');
        // 現在時刻取得- 1時間
        $system_date = date('YmdHis', strtotime('-1 month'));
        /**
         * req_date != null:URL処理の場合
         */
        if($req_date !== null){
            /**
             * 2021/11/01 < 2021/10/25 "OK"
             * 2021/11/01 < 2021/10/26 "OK"
             * 2021/11/01 < 2021/11/02 "NG" -> NOT FOUNDに遷移
             */
            if (strtotime($req_date) < strtotime($system_date)) {
                Log::debug("期間外の処理");
                return view('expiry.expiry',[]);
            }
        }

        /**
         * session_idの処理
         */
        // URLからの場合:値取得/URLからでない場合:空白
        $session_id = $request->input('create_user_id');
        
        // URLからの場合、session_idがパラメーターで送られる為、複合化する
        if($session_id !== null){
            // 複合化
            Log::debug('session_id:URLの処理:');
            $session_id = Crypt::decrypt($session_id);
        }
        else{
            Log::debug('session_id:homeの処理:');
            $session_id = "";
        }

        /**
         * URLからの判定処理
         * true(URLから)の場合、複合化
         */
        if($application_Flag == "true"){
            Log::debug('application_Flag:URLの処理');
            $application_form_id =  Crypt::decrypt($application_form_id);
        }

        /**
         * 顧客の項目取得
         */
        $list = $this->getListData($application_form_id);
        
        /**
         * 顧客の画像取得(複数)
         */
        $contract_id = $list[0]->contract_id;
        $list_img = $this->getListImg($contract_id);

        /**
         * 物件用途、物件種別等を取得
         */
        $common = new Common();
        // 申込区分
        $list_types = $common->getApplicationTypes();
        // 申込種別
        $list_uses = $common->getApplicationUses();
        // 続柄
        $list_relation = $common->getRelationships();
        // 健康保険
        $list_insurance = $common->getInsuranceType();
        
        Log::debug('log_end:' .__FUNCTION__);
        return view('edit.edit', compact('list','list_types','list_uses','list_relation','list_insurance','list_img','application_Flag','session_id'));
    }

    /**
     * 編集(表示:sql)
     *
     * @param [type] $session_id
     * @return void
     */
    public function getListData($application_form_id){
        Log::debug('log_start:' .__FUNCTION__);

        $str = "select "
        ."real_estate_agent_forms.application_form_id as application_form_id, "
        ."real_estate_agent_forms.application_type_id as application_type_id, "
        ."application_types.application_type_name as application_type_name, "
        ."real_estate_agent_forms.application_use_id as application_use_id, "
        ."application_uses.application_use_name as application_use_name, "
        ."real_estate_agent_forms.contract_start_date as contract_start_date, "
        ."real_estate_agent_forms.real_estate_name as real_estate_name, "
        ."real_estate_agent_forms.real_estate_ruby as real_estate_ruby, "
        ."real_estate_agent_forms.room_name as room_name, "
        ."real_estate_agent_forms.post_number as real_estate_post_number, "
        ."real_estate_agent_forms.address as real_estate_address, "
        ."real_estate_agent_forms.pet_bleeding_name as pet_bleeding_name, "
        ."real_estate_agent_forms.pet_kind_name as pet_kind_name, "
        ."real_estate_agent_forms.bicycle_parking_number as bicycle_parking_number, "
        ."real_estate_agent_forms.car_parking_number as car_parking_number, "
        ."real_estate_agent_forms.security_deposit as security_deposit, "
        ."real_estate_agent_forms.deposit_money as deposit_money, "
        ."real_estate_agent_forms.key_money as key_money, "
        ."real_estate_agent_forms.deposit_refund as deposit_refund, "
        ."real_estate_agent_forms.rent_fee as rent_fee, "
        ."real_estate_agent_forms.service_fee as service_fee, "
        ."real_estate_agent_forms.water_fee as water_fee, "
        ."real_estate_agent_forms.ohter_fee as ohter_fee, "
        ."real_estate_agent_forms.total_rent_fee as total_rent_fee, "
        ."real_estate_agent_forms.manager_name as manager_name, "
        ."real_estate_agent_forms.broker_name as broker_name, "
        ."real_estate_agent_forms.broker_tel as broker_tel, "
        ."real_estate_agent_forms.broker_mail as broker_mail, "
        ."real_estate_agent_forms.contract_progress as contract_progress, "
        ."real_estate_agent_forms.url_send_flag as url_send_flag, "
        ."contracts_forms.contract_id as contract_id, "
        ."contracts_forms.contract_name as contract_name, "
        ."contracts_forms.contract_ruby as contract_ruby, "
        ."contracts_forms.sex_id as contract_sex_id, "
        ."contracts_sex.sex_name as contracts_sex_name, "
        ."contracts_forms.contract_age as contract_age, "
        ."contracts_forms.contract_birthday as contract_birthday, "
        ."contracts_forms.post_number as contract_post_number, "
        ."contracts_forms.address as contract_address, "
        ."contracts_forms.home_tel as contract_home_tel, "
        ."contracts_forms.mobile_tel as contract_mobile_tel, "
        ."contracts_forms.work_place_name as contract_work_place_name, "
        ."contracts_forms.work_place_ruby as contract_work_place_ruby, "
        ."contracts_forms.work_place_post_number as contract_work_place_post_number, "
        ."contracts_forms.work_place_address as contract_work_place_address, "
        ."contracts_forms.work_place_tel as contract_work_place_tel, "
        ."contracts_forms.work_place_Industry as contract_work_place_Industry, "
        ."contracts_forms.work_place_occupation as contract_work_place_occupation, "
        ."contracts_forms.work_place_years as contract_work_place_years, "
        ."contracts_forms.employment_status as contract_employment_status, "
        ."contracts_forms.annual_income as contract_annual_income, "
        ."contracts_forms.insurance_type_id as contracts_insurance_type_id, "
        ."contracts_insurancetypes.insurance_type_name as contracts_insurance_type_name, "
        ."housemates.housemate_id as housemate_id, "
        ."housemates.housemate_name as housemate_name, "
        ."housemates.housemate_ruby as housemate_ruby, "
        ."housemates.relationship_id as housemates_relationship_id, "
        ."housemates_relationships.relationship_name as housemates_relationship_name, "
        ."housemates_sex.sex_id as housemates_sex_id, "
        ."housemates_sex.sex_name as housemates_sex_name, "
        ."housemates.housemate_age as housemate_age, "
        ."housemates.housemate_name_birthday as housemate_name_birthday, "
        ."housemates.post_number as housemate_post_number, "
        ."housemates.address as housemate_address, "
        ."housemates.housemate_home_tel as housemate_home_tel, "
        ."housemates.housemate_mobile_tel as housemate_mobile_tel, "
        ."emergency_contacts.emergency_contact_id as emergency_contact_id, "
        ."emergency_contacts.emergency_contacts_name as emergency_contacts_name, "
        ."emergency_contacts.emergency_contacts_ruby as emergency_contacts_ruby, "
        ."emergency_contacts.relationship_id as emergency_contacts_relationships_id, "
        ."emergency_contacts_relationships.relationship_name as emergency_contacts_relationships_name, "
        ."emergency_contacts.sex_id as emergency_contacts_sex_id, "
        ."emergency_contacts_sex.sex_name as emergency_contacts_sex_name, "
        ."emergency_contacts.emergency_birthday as emergency_birthday, "
        ."emergency_contacts.emergency_age as emergency_age, "
        ."emergency_contacts.post_number as emergency_post_number, "
        ."emergency_contacts.address as emergency_address, "
        ."emergency_contacts.emergency_home_tel as emergency_home_tel, "
        ."emergency_contacts.emergency_mobile_tel as emergency_mobile_tel, "
        ."guarantors.guarantor_id as guarantor_contracts_id, "
        ."guarantors.guarantor_name as guarantor_name, "
        ."guarantors.guarantor_ruby as guarantor_ruby, "
        ."guarantors.sex_id as guarantors_sex_id, "
        ."guarantors_sex.sex_name as guarantors_sex_name, "
        ."guarantors.relationship_id as guarantors_relationship_id, "
        ."guarantors_relationships.relationship_name as guarantors_relationships_name, "
        ."guarantors.guarantor_age as guarantor_age, "
        ."guarantors.guarantor_birthday as guarantor_birthday, "
        ."guarantors.post_number as guarantor_post_number, "
        ."guarantors.address as guarantor_address, "
        ."guarantors.home_tel as guarantor_home_tel, "
        ."guarantors.mobile_tel as guarantor_mobile_tel, "
        ."guarantors.guarantor_work_place_name as guarantor_work_place_name, "
        ."guarantors.guarantor_work_place_ruby as guarantor_work_place_ruby, "
        ."guarantors.guarantor_work_place_post_number as guarantor_work_place_post_number, "
        ."guarantors.guarantor_work_place_address as guarantor_work_place_address, "
        ."guarantors.guarantor_work_place_tel as guarantor_work_place_tel, "
        ."guarantors.guarantor_work_place_Industry as guarantor_work_place_Industry, "
        ."guarantors.guarantor_work_place_occupation as guarantor_work_place_occupation, "
        ."guarantors.guarantor_work_place_years as guarantor_work_place_years, "
        ."guarantors.guarantor_work_place_occupation as guarantor_work_place_occupation, "
        ."guarantors.guarantor_status as guarantor_status, "
        ."guarantors.guarantor_annual_income as guarantor_annual_income, "
        ."guarantors.guarantor_insurance_type_id as guarantor_insurance_type_id, "
        ."guarantors_insurancetypes.insurance_type_name as guarantors_insurancetypes_name, "
        ."imgs.img_id as img_id, "
        ."imgs.img_type as img_type, "
        ."imgs.img_path as img_path, "
        ."imgs.img_memo as img_memo, "
        ."contracts_forms.create_user_id as contracts_forms_create_user_id, "
        ."users.create_user_name as contracts_create_user_name "
        ."from "
        ."real_estate_agent_forms "
        ."left join application_types on "
        ."real_estate_agent_forms.application_type_id = application_types.application_type_id "
        ."left join application_uses on "
        ."real_estate_agent_forms.application_use_id = application_uses.application_use_id "
        ."left join contracts_forms on "
        ."real_estate_agent_forms.contract_id = contracts_forms.contract_id "
        ."left join sexs as contracts_sex on "
        ."contracts_sex.sex_id = contracts_forms.sex_id "
        ."left join insurancetypes as contracts_insurancetypes on "
        ."contracts_insurancetypes.insurance_type_id = contracts_forms.insurance_type_id "
        ."left join housemates on "
        ."contracts_forms.contract_id = housemates.contract_id "
        ."left join sexs as housemates_sex on "
        ."housemates_sex.sex_id = housemates.sex_id "
        ."left join relationships as housemates_relationships on "
        ."housemates_relationships.relationship_id = housemates.relationship_id "
        ."left join emergency_contacts on "
        ."emergency_contacts.contract_id = contracts_forms.contract_id "
        ."left join sexs as emergency_contacts_sex on "
        ."emergency_contacts_sex.sex_id = emergency_contacts.sex_id "
        ."left join relationships as emergency_contacts_relationships on "
        ."emergency_contacts_relationships.relationship_id = emergency_contacts.relationship_id "
        ."left join guarantors on "
        ."guarantors.contract_id = contracts_forms.contract_id "
        ."left join sexs as guarantors_sex on "
        ."guarantors_sex.sex_id = guarantors.sex_id "
        ."left join insurancetypes as guarantors_insurancetypes on "
        ."guarantors_insurancetypes.insurance_type_id = guarantors.guarantor_insurance_type_id "
        ."left join relationships as guarantors_relationships on "
        ."guarantors_relationships.relationship_id = guarantors.relationship_id "
        ."left join imgs on "
        ."imgs.contract_id = contracts_forms.contract_id "
        ."left join users on "
        ."users.create_user_id = real_estate_agent_forms.create_user_id "
        ."where application_form_id = "
        ."'$application_form_id'";
        Log::debug('log_getListData_sql:'.$str);
        
        // 取得データデバック
        $data = DB::select($str);
        $arrString = print_r($data , true);
        Log::debug('log_getListData_data:'.$arrString);

        Log::debug('log_end:'.__FUNCTION__);
        return $data;
    }

    /**
     * 画像データ(取得:slq)
     *
     * @param [type] $contract_id(顧客id)
     * @return data(顧客ごとの画像データ)
     */
    private function getListImg($contract_id){
        Log::debug('log_start:' .__FUNCTION__);

        $str = "select * from imgs "
        ."where contract_id = '$contract_id' ";
        Log::debug('sql_Img:'.$str);
        $data = DB::select($str);
        
        // 取得データデバック
        $arrString = print_r($data , true);
        Log::debug('log_Img:'.$arrString);

        Log::debug('log_end:' .__FUNCTION__);
        return $data;
    }

    /**
     * 新規:編集(分岐)
     *
     * @param $request(edit.blade.phpの各項目)
     * @return $response(status:true=OK/false=NG)
     */
    public function editEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // true=登録完了 false=errorMessageを返す
        $response = [];

        // バリデーション
        $response = $this->editValidation($request);

        if($response["status"] == false){
            Log::debug('validator_status:falseのif文通過');
            return response()->json($response);
        }

        /**
         * 不動産業者id=無:insert
         * 不動産業者id=有:update
         */
        // 新規登録
        if($request->input('application_form_id') == ""){
            /**
             * $responseの値設定
             */
            $ret = $this->insertData($request);

            // js側での判定のステータス(true:OK/false:NG)
            $response["status"] = $ret['status'];

            // 契約id
            $response["contract_id"] = $ret['maxId'];

            // ユーザid(session_id)
            $response["create_user_id"] = $ret['create_user_id'];

            // 不動産id(session_id)
            $response["application_form_id"] = $ret['application_form_id'];

            // 認証用URL発行フラグ(url_send_flag)
            $response["url_send_flag"] = $ret['url_send_flag'];

            Log::debug('editEntry_insert通過');

        // 編集登録
        }else{
            
            /**
             * $responseの値設定
             */
            $ret = $this->updateData($request);

            // js側での判定のステータス:true=OK / false=NG
            $response["status"] = $ret['status'];

            // updateData関数の最後にreal_estate_agent_formsのカラム取得
            // session_idをresponseに設定
            $response["create_user_id"] = $ret['session_id'];

            // url_send_flagをresponseに設定
            $response['url_send_flag'] = $ret['url_send_flag'];

            // application_form_idは$request内にあるため、$requestから直接取得
            // $response["application_form_id"] = $request->input('application_form_id');
            $response['application_form_id'] = $ret['application_form_id'];
            

            Log::debug('editEntry_update通過');
        }
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
        // 賃借人
        $rules = [];
        $rules['contract_name'] = "required|max:100";
        $rules['contract_ruby'] = "required|max:200";
        $rules['contract_post_number'] = "nullable|zip";
        $rules['contract_address'] = "nullable|max:200";
        $rules['contract_sex_id'] = "nullable|integer";
        $rules['contract_birthday'] = "nullable|date";
        $rules['contract_age'] = "nullable|integer";
        $rules['contract_home_tel'] = "nullable|jptel";
        $rules['contract_work_place_name'] = "nullable|max:100";
        $rules['contract_work_place_ruby'] = "nullable|max:200";
        $rules['contract_work_place_post_number'] = "nullable|zip";
        $rules['contract_work_place_address'] = "nullable|max:100";
        $rules['contract_work_place_tel'] = "nullable|jptel";
        $rules['contract_work_place_Industry'] = "nullable|max:50";
        $rules['contract_work_place_occupation'] = "nullable|max:50";
        $rules['contract_employment_status'] = "nullable|max:50";
        $rules['contract_work_place_years'] = "nullable|integer";
        $rules['contract_annual_income'] = "nullable|integer";
        $rules['contracts_insurance_type_id'] = "nullable|integer";

        /**
         * 不動産業者
         */
        $rules['broker_name'] = "nullable|max:100";
        $rules['broker_tel'] = "nullable|jptel";
        $rules['broker_mail'] = "nullable|email";
        $rules['contract_start_date'] = "nullable|date";
        $rules['real_estate_name'] = "required|max:100";
        $rules['real_estate_ruby'] = "required|max:200";
        $rules['room_name'] = "required|max:100";
        $rules['post_number'] = "required|zip";
        $rules['address'] = "required|max:200";
        $rules['pet_kind_name'] = "nullable|max:10";
        $rules['bicycle_parking_number'] = "required|integer|max:10";
        $rules['car_parking_number'] = "required|integer|max:10";

        /**
         * 同居人
         */
        $rules['housemate_name'] = "nullable|max:100";
        $rules['housemate_ruby'] = "nullable|max:200";
        $rules['housemate_birthday'] = "nullable|date|max:100";
        $rules['housemate_age'] = "nullable|integer";
        $rules['housemate_post_number'] = "nullable|zip";
        $rules['housemate_address'] = "nullable|max:200";
        $rules['housemate_home_tel'] = "nullable|jptel";
        $rules['housemate_mobile_tel'] = "nullable|max:100";

        /**
         * 緊急連絡先
         */
        $rules['emergency_contacts_name'] = "nullable|max:100";
        $rules['emergency_contacts_ruby'] = "nullable|max:200";
        $rules['emergency_contacts_birthday'] = "nullable|date|max:100";
        $rules['emergency_contract_age'] = "nullable|max:20";
        $rules['emergency_contacts_post_number'] = "nullable|zip";
        $rules['emergency_contacts_post_address'] = "nullable|max:100";
        $rules['emergency_contacts_home_tel'] = "nullable|jptel|max:20";
        $rules['emergency_contacts_mobile_tel'] = "nullable|max:20";

        /**
         * 連帯保証人
         */
        $rules['guarantor_name'] = "nullable|max:100";
        $rules['guarantor_ruby'] = "nullable|max:200";
        $rules['guarantor_post_number'] = "nullable|zip";
        $rules['guarantor_address'] = "nullable|max:200";
        $rules['guarantor_birthday'] = "nullable|date";
        $rules['guarantor_age'] = "nullable|integer";
        $rules['guarantor_home_tel'] = "nullable|jptel";
        $rules['guarantor_mobile_tel'] = "nullable|max:100";
        $rules['guarantor_work_place_name'] = "nullable|max:100";
        $rules['guarantor_work_place_ruby'] = "nullable|max:200";
        $rules['guarantor_work_place_post_number'] = "nullable|zip";
        $rules['guarantor_work_place_address'] = "nullable|max:200";
        $rules['guarantor_work_place_tel'] = "nullable|jptel";
        $rules['guarantor_work_place_Industry'] = "nullable|max:50";
        $rules['guarantor_work_place_occupation'] = "nullable|max:50";
        $rules['guarantor_work_place_years'] = "nullable|integer";
        $rules['guarantor_status'] = "nullable|max:20";
        $rules['guarantor_annual_income'] = "nullable|integer";
        
        // 画像(nullableが効かない為、ifで判定)
        $file_img = $request->file('file_img');
        Log::debug('バリデーション_file_img:' .$file_img);

        if($file_img !== null){
            Log::debug('画像が添付されています');
            $rules['file_img'] = "nullable|mimes:jpeg,png,jpg";
        }
    
        $rules['file_img_type_textarea'] = "nullable|max:100";

        /**
         * messages
         */
        // 賃借人
        $messages = [];
        // 申込者名
        $messages['contract_name.required'] = "契約者は必須です。";
        $messages['contract_name.max'] = "契約者の文字数が超過しています。";
        // 申込者カナ
        $messages['contract_ruby.required'] = "契約者は必須です。";
        $messages['contract_ruby.max'] = "契約者カナの文字数が超過しています。";
        // 郵便番号
        $messages['contract_post_number.zip'] = "郵便番号の形式が不正です。";
        // 住所
        $messages['contract_address.max'] = "住所の文字数が超過しています。";
        // 性別
        $messages['contract_sex_id.integer'] = "性別の形式が不正です。";
        // 生年月日
        $messages['contract_birthday.date'] = "生年月日の形式が不正です。";
        // 年齢
        $messages['contract_age.integer'] = "年齢の形式が不正です。";
        // 自宅電話番号
        $messages['contract_home_tel.jptel'] = "自宅Telの形式が不正です。";
        // 勤務先名称
        $messages['contract_work_place_name.max'] = "勤務先名称の文字数が超過しています。";
        // 勤務先名称カナ
        $messages['contract_work_place_ruby.max'] = "勤務先名称カナの文字数が超過しています。";
        // 勤務先郵便番号
        $messages['contract_work_place_post_number.zip'] = "郵便番号の形式が不正です。";
        // 所在地
        $messages['contract_work_place_address.max'] = "所在地の文字数が超過しています。";
        // 勤務先Tel
        $messages['contract_work_place_tel.jptel'] = "勤務先Telの形式が不正です。";
        // 業種
        $messages['contract_work_place_Industry.max'] = "業種の文字数が超過しています。";
        // 職種
        $messages['contract_work_place_occupation.max'] = "職種の文字数が超過しています。";
        // 雇用形態
        $messages['contract_employment_status.max'] = "雇用形態の文字数が超過しています。";
        // 勤続年数
        $messages['contract_work_place_years.integer'] = "勤続年数の形式が不正です。";
        // 年収
        $messages['contract_annual_income.integer'] = "年収の形式が不正です。";
        // 健康保険タイプ
        $messages['contracts_insurance_type_id.integer'] = "健康保険の形式が不正です。";

        /**
         * 不動産業者
         */
        // 仲介業者(仲介業者名、電話番号)
        $messages['broker_name.max'] = "仲介業者名の文字数が超過しています。";
        $messages['broker_tel.jptel'] = "仲介業者Telの形式が不正です。";
        $messages['broker_mail.email'] = "仲介業者E-meilの形式が不正です。";
        // 入居開始日
        $messages['contract_start_date.date'] = "入居開始日の形式が不正です。";
        // 物件名
        $messages['real_estate_name.required'] = "物件名は必須です。";
        $messages['real_estate_name.max'] = "物件名の文字数が超過しています。";
        // 物件名カナ
        $messages['real_estate_ruby.required'] = "物件名カナは必須です。";
        $messages['real_estate_ruby.max'] = "物件名カナの文字数が超過しています。";
        // 号室
        $messages['room_name.required'] = "号室は必須です。";
        $messages['room_name.max'] = "号室の文字数が超過しています。";
        // 郵便番号
        $messages['post_number.required'] = "郵便番号は必須です。";
        $messages['post_number.zip'] = "郵便番号の形式が不正です。";
        // 住所
        $messages['address.required'] = "住所は必須です。";
        $messages['address.max'] = "住所の文字数が超過しています。";
        // ペットの種類
        $messages['pet_kind_name.max'] = "ペットの種類の文字数が超過しています。";
        // 駐車台数
        $messages['bicycle_parking_number.required'] = "駐輪台数は必須です。";
        $messages['bicycle_parking_number.integer'] = "駐輪台数の形式が不正です。";
        $messages['bicycle_parking_number.max'] = "駐輪台数の文字数が超過しています。";
        // 駐輪台数
        $messages['car_parking_number.required'] = "駐車台数は必須です。";
        $messages['car_parking_number.integer'] = "駐車台数の形式が不正です。";
        $messages['car_parking_number.max'] = "駐車台数の文字数が超過しています。";

        /**
         * 同居人
         */
        // 同居人名
        $messages['housemate_name.max'] = "同居人名の文字数が超過しています。";
        // 同居人カナ
        $messages['housemate_ruby.max'] = "同居人名カナの文字数が超過しています。";
        // 生年月日
        $messages['housemate_birthday.date'] = "生年月日の形式が不正です。";
        $messages['housemate_birthday.max'] = "生年月日の文字数が超過しています。";
        // 年齢
        $messages['housemate_age.max'] = "年齢の文字数が超過しています。";
        // 郵便番号
        $messages['housemate_post_number.zip'] = "郵便番号の形式が不正です。";
        // 住所
        $messages['housemate_address.max'] = "住所の文字数が超過しています。";
        // 自宅tel
        $messages['housemate_home_tel.jptel'] = "自宅Telの形式が不正です。";
        // 携帯番号
        $messages['housemate_mobile_tel.max'] = "携帯Telの文字数が超過しています。";

        /**
         * 緊急連絡先
         */
        // 緊急連絡先名
        $messages['emergency_contacts_name.max'] = "緊急連絡先名の文字数が超過しています。";
        // 緊急連絡先名カナ
        $messages['emergency_contacts_ruby.max'] = "緊急連絡先名カナの文字数が超過しています。";
        // 生年月日
        $messages['emergency_contacts_birthday.date'] = "生年月日の形式が不正です。";
        $messages['emergency_contacts_birthday.max'] = "生年月日の文字数が超過しています。";
        // 年齢
        $messages['emergency_contract_age.max'] = "年齢の文字数が超過しています。";
        // 郵便番号
        $messages['emergency_contacts_post_number.zip'] = "郵便番号の形式が不正です。";
        // 住所
        $messages['emergency_contacts_post_address.max'] = "住所の文字数が超過しています。";
        // 自宅tel
        $messages['emergency_contacts_home_tel.jptel'] = "自宅Telの形式が不正です。";
        $messages['emergency_contacts_home_tel.max'] = "自宅Telの文字数が超過しています。";
        // 携帯番号
        $messages['emergency_contacts_mobile_tel.max'] = "携帯Telの文字数が超過しています。";

        /**
         * 連帯保証人
         */
        // 連帯保証名
        $messages['guarantor_name.max'] = "連帯保証人の文字数が超過しています。";
        // 連帯保証人カナ
        $messages['guarantor_ruby.max'] = "連帯保証人カナの文字数が超過しています。";
        // 郵便番号
        $messages['guarantor_post_number.zip'] = "郵便番号の形式が不正です。";
        // 生年月日
        $messages['guarantor_birthday.date'] = "生年月日の形式が不正です。";
        // 住所
        $messages['guarantor_address.max'] = "住所の文字数が超過しています。";
        // 自宅tel
        $messages['guarantor_home_tel.jptel'] = "自宅Telの形式が不正です。";
        // 携帯番号
        $messages['guarantor_mobile_tel.max'] = "携帯Telの文字数が超過しています。";
        // 勤務先名
        $messages['guarantor_work_place_name.max'] = "勤務先名の文字数が超過しています。";
        // 勤務先名カナ
        $messages['guarantor_work_place_ruby.max'] = "勤務先名カナの文字数が超過しています。";
        // 郵便番号
        $messages['guarantor_work_place_post_number.zip'] = "郵便番号の形式が不正です。";
        // 所在地
        $messages['guarantor_work_place_address.max'] = "所在地の文字数が超過しています。";
        // 勤務先Tel
        $messages['guarantor_work_place_tel.jptel'] = "自宅Telの形式が不正です。";
        // 業種
        $messages['guarantor_work_place_Industry.max'] = "業種の文字数が超過しています。";
        // 職種
        $messages['guarantor_work_place_occupation.max'] = "職種の文字数が超過しています。";
        // 勤続年数
        $messages['guarantor_work_place_years.integer'] = "勤続年数の形式が不正です。";
        // 雇用形態
        $messages['guarantor_status.max'] = "雇用形態の文字数が超過しています。";
        // 年収
        $messages['guarantor_annual_income.integer'] = "年収の形式が不正です。";
        
        // 画像(nullableが効かない為、ifで判定)
        
        $messages['file_img_type_textarea.max'] = "補足の文字数が超過しています。";

        // 画像(nullableが効かない為、ifで判定)
        $file_img = $request->file('file_img');
        Log::debug('バリデーション_file_img:' .$file_img);

        if($file_img !== null){
            Log::debug('画像が添付されています');
            $messages['file_img.mimes'] = "画像ファイル(jpg,jpeg,png)でアップロードして下さい。";
        }
    
        // validation判定
        $validator = Validator::make($request->all(), $rules, $messages);

        // エラーがある場合処理
        if ($validator->fails()) {
            Log::debug('validator:失敗');

            // responseの定数
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
     * 新規登録
     *
     * @param Request $request(edit.blade.phpの各項目)
     * @return ret(true:登録OK/false:登録NG、maxId(contract_id)、session_id(create_user_id))
     */
    private function insertData(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        // トランザクション
        try {
            
            DB::beginTransaction();
            
            // jsに返却する初期値
            // $ret = true;
            $ret = [];
            $ret['status'] = true;

             // URLから来た場合のcreate_user_idを取得
            $ret['create_user_id'] = $request->session()->get('create_user_id');

            /**
             * 契約者id取得
             */
            // 契約者のカラムをcontract_infoに代入
            $contract_info = $this->insertContract($request);
            // returnのステータスにtrueを設定
            $ret['status'] = $contract_info['status'];
            // 登録した契約Idを取得
            $maxId= $contract_info['contract_id'];
            $ret['maxId'] = $maxId;

            /**
             * 不動産業者
             */
            $ret['status'] = $this->insertRealEstate($request,$maxId);

            // 同居人
            $ret['status'] = $this->insertHousemate($request,$maxId);

            // 緊急連絡先
            $ret['status'] = $this->insertEmergency($request,$maxId);

            // 保証人
            $ret['status'] = $this->insertGuarantor($request,$maxId);

            // 画像パスがある場合、保存処理実行
            if($request->file('file_img') !== null){
                $ret['status'] = $this->insertImg($request,$maxId);
            }

            /**
             * // 不動産業者idを取得
             */
            $str = "select application_form_id, "
            ."url_send_flag "
            ."from real_estate_agent_forms "
            ."where contract_id = $maxId";
            Log::debug('log_select_sql:'.$str);
            $realEstate_info = DB::select($str);

            // application_form_id
            $ret['application_form_id'] = $realEstate_info[0]->application_form_id;
            
            // url_send_flag
            $ret['url_send_flag'] = $realEstate_info[0]->url_send_flag;
            
            // コミット
            DB::commit();

        // 例外処理
        } catch (\Exception $e) {

            // ロールバック
            DB::rollback();
            // sqlエラーをログに記録
            Log::debug('error:'.$e);
            // 失敗の場合falseを返す
            $ret['status'] = false;

        // updateが完了=1の為trueを代入、その他false
        } finally {

            if($ret['status'] == 1){
                $ret['status'] = true;
            }else{
                $ret['status'] = false;
            }
            Log::debug('log_end:'.__FUNCTION__);
            return $ret;
        }
    }

    /**
     * 編集
     * 新規登録の時に空データをinsertしているので、空データであってもupdateする。
     *
     * @return void
     */
    private function updateData(Request $request){
        Log::debug('log_start:'.__FUNCTION__);
        
        // トランザクション
        try {
            DB::beginTransaction();

            // jsに返却する初期値
            // $ret = true;
            $ret = [];
            $ret['status'] = true;
            /**
             * 登録のsql
             */
            // 賃借人
            $ret['status'] = $this->updateContract($request);

            // 不動産業者
            $ret['status'] = $this->updateRealEstate($request);

            // 同居人
            $ret['status'] = $this->updateHousemate($request);

            // 緊急連絡先
            $ret['status'] = $this->updateEmergency($request);
            
            // 保証人
            $ret['status'] = $this->updateGuarantor($request);

            // 画像(契約者idをmaxidとして引数に渡す)
            if($request->file('file_img') !== null){
                $maxId = $request->input('contract_id');
                $ret['status'] = $this->insertImg($request,$maxId);
            }

            /**
             * updateしたsessionIdを取得した後、bladeに渡す
             * 下記を記載しないと2回目の更新時、session_idが存在しないとエラーになる
             */
            // パラメータで送られて来たapplication_form_idを取得
            $application_form_id = $request->input('application_form_id');

            // 不動産業者カラムを取得
            $str = "select * from real_estate_agent_forms "
            ."where application_form_id = '$application_form_id' ";
            Log::debug('log_realEstate_info_sql:'.$str);
            $realEstate_info = DB::select($str);

            /**
             * 取得したカラムから値設定
             */
            // session_id
            $ret['session_id'] = $realEstate_info[0]->create_user_id;
            // url_send_flag
            $ret['url_send_flag'] = $realEstate_info[0]->url_send_flag;
            // application_form_id
            $ret['application_form_id'] = $realEstate_info[0]->application_form_id;

            // コミット
            DB::commit();

            
        // 例外処理
        } catch (\Exception $e) {
            // ロールバック
            DB::rollback();
            // sqlエラーをログに記録
            Log::debug('error:'.$e);
            // 失敗の場合falseを返す
            $ret['status'] = false;
        }
        // updateが完了=1の為trueを代入、その他false
        finally{
            if($ret['status'] == 1){
                $ret['status'] = true;
            }else{
                $ret['status'] = false;
            }
            Log::debug('log_end:'.__FUNCTION__);
            return $ret;
        }
    }

    /* 画像アップロード(insert)
    *
    * @param Request $request(edit.bladeからの顧客情報)
    * @param [type] $maxId(最新の賃借人id)、updateからの場合(編集中の賃借人id)
    * @return void(true:登録OK/false:登録NG)
    */
    private function insertImg(Request $request,$maxId){

        Log::debug('log_start:'.__FUNCTION__);

        try {

            /**
             * application_Flag = trueの場合:URLからの処理
             * application_Flag = falseの場合:Homeからの処理
             */
            $application_Flag = $request->input('application_Flag');

            if($application_Flag == "true"){

                Log::debug("URLからの処理");
                $session_id = $request->input('session_id');
                
            }else{

                Log::debug("Homeからの処理");
                $session_id = $request->session()->get('create_user_id');
            }

            $file_img = $request->file('file_img');
            Log::debug('file_img:'.$file_img);

            // 種別
            $file_img_type = $request->input('file_img_type');
            Log::debug('file_img_type:'.$file_img_type);

            // 補足
            $file_img_type_textarea = $request->input('file_img_type_textarea');
            Log::debug('file_img_type_textarea:'.$file_img_type_textarea);
        
            // idごとのフォルダ作成のためのパス生成
            $dir ='public/img/' .$maxId;
            
            // 任意のフォルダ作成
            Storage::makeDirectory($dir);

            /**
             * 画像アップロードの処理
             */
            // ファイル名変更
            $file_name = time() .'.' .$file_img->getClientOriginalExtension();
            Log::debug('ファイル名:'.$file_name);

            // ファイルパス+ファイル名
            $tmp_file_path = 'app/' .$dir .'/' .$file_name;
            Log::debug('tmp_file_path :'.$tmp_file_path);

            InterventionImage::make($file_img)->resize(380, null,
            function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path($tmp_file_path));

            /**
             * 画像データ(insert)
             */
            $str = "insert into kasegu_management.imgs "
            ."( "
            ."contract_id, "
            ."img_type, "
            ."img_path, "
            ."img_memo, "
            ."create_user_id, "
            ."create_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."'$maxId', "
            ."'$file_img_type', "
            ."'$tmp_file_path', "
            ."'$file_img_type_textarea', "
            ."'$session_id', "
            ."now(), "
            ."'$session_id', "
            ."now() "
            ."); ";
            Log::debug('log_file_img_sql:'.$str);

            $ret = DB::insert($str);
            Log::debug('ret:'.$str);
            
        } catch (\Exception $e) {

            Log::debug('error:'.$e);
            // storage/app/public/imagesから、画像ファイルを削除する
            Storage::delete($tmp_file_path);
            throw $e;

        }finally{

            Log::debug('log_end:'.__FUNCTION__);
            return $ret;

        }
    }

    /**
     * 不動産業者(insert)
     * 
     * @param Request $request
     * @return void
     */
    private function insertRealEstate(Request $request,$maxId){
        Log::debug('log_start:' .__FUNCTION__);

        /**
         * application_Flag = trueの場合:URLからの処理
         * application_Flag = falseの場合:Homeからの処理
         */
        $application_Flag = $request->input('application_Flag');

        if($application_Flag == true){
            Log::debug("URLからの処理");
            $session_id = $request->input('session_id');
            
        }else{
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
        }

        // 値取得
        $application_form_id = $request->input('application_form_id');
        $contract_progress = $request->input('contract_progress');
        $contract_id = $maxId;
        $broker_name = $request->input('broker_name');
        $broker_tel = $request->input('broker_tel');
        $broker_mail = $request->input('broker_mail');
        $manager_name = $request->input('manager_name');
        $application_type_id = $request->input('application_type_id');
        $application_use_id = $request->input('application_use_id');
        $contract_start_date = $request->input('contract_start_date');
        $real_estate_name = $request->input('real_estate_name');
        $real_estate_ruby = $request->input('real_estate_ruby');
        $room_name = $request->input('room_name');
        $post_number = $request->input('post_number');
        $address = $request->input('address');
        $pet_bleeding_name = $request->input('pet_bleeding_name');
        $pet_kind_name = $request->input('pet_kind_name');
        $bicycle_parking_number = $request->input('bicycle_parking_number');
        $car_parking_number = $request->input('car_parking_number');
        $deposit_money = $request->input('deposit_money');
        $deposit_refund = $request->input('deposit_refund');
        $security_deposit = $request->input('security_deposit');
        $key_money = $request->input('key_money');
        $rent_fee = $request->input('rent_fee');
        $service_fee = $request->input('service_fee');
        $water_fee = $request->input('water_fee');
        $ohter_fee = $request->input('ohter_fee');
        $total_rent_fee = $request->input('total_rent_fee');

        /**
         * 数値に関してはNULLで値を代入出来ないので0、''を入れる
         */
        // 進捗状況
        if($contract_progress == null){
            $contract_progress =0;
        }
        // 申込区分
        if($application_type_id == null){
            $application_type_id =0;
        }
        // 申込種別
        if($application_use_id == null){
            $application_use_id =0;
        }
        // 契約担当者
        if($manager_name == null){
            $manager_name = '';
        }
        // 仲介業者
        if($broker_name == null){
            $broker_name = '';
        }
        // 仲介業者tel
        if($broker_tel == null){
            $broker_tel = '';
        }
        // 仲介業者mail
        if($broker_mail == null){
            $broker_mail = '';
        }
        // 契約開始日
        if($contract_start_date == null){
            $contract_start_date = '';
        }
        // 不動産名
        if($real_estate_name == null){
            $real_estate_name = '';
        }
        // 不動産名カナ
        if($real_estate_ruby == null){
            $real_estate_ruby = '';
        }
        // 号室
        if($room_name == null){
            $room_name = '';
        }
        // 郵便番号
        if($post_number == null){
            $post_number = '';
        }
        // 住所
        if($address == null){
            $address = '';
        }
        // ペット飼育有無
        if($pet_bleeding_name == null){
            $pet_bleeding_name = '';
        }
        // ペット種別
        if($pet_bleeding_name == null){
            $pet_bleeding_name = '';
        }
        // 駐輪台数
        if($bicycle_parking_number == null){
            $bicycle_parking_number =0;
        }
        // 駐車台数
        if($car_parking_number == null){
            $car_parking_number =0;
        }
        // 保証金
        if($deposit_money == null){
            $deposit_money =0;
        }
        // 解約引く
        if($deposit_refund == null){
            $deposit_refund =0;
        }
        // 敷金
        if($security_deposit == null){
            $security_deposit =0;
        }
        // 礼金
        if($key_money == null){
            $key_money =0;
        }
        // 家賃
        if($rent_fee == null){
            $rent_fee =0;
        }
        // 共益費
        if($service_fee == null){
            $service_fee =0;
        }
        // 水道代
        if($water_fee == null){
            $water_fee =0;
        }
        // その他
        if($ohter_fee == null){
            $ohter_fee =0;
        }
        // 合計
        if($total_rent_fee == null){
            $total_rent_fee =0;
        }

        /**
         * sql
         */
        $str = "insert into kasegu_management.real_estate_agent_forms( "
        ."application_type_id, "
        ."application_use_id, "
        ."contract_id, "
        ."contract_start_date, "
        ."real_estate_name, "
        ."real_estate_ruby, "
        ."room_name, "
        ."post_number, "
        ."address, "
        ."pet_bleeding_name, "
        ."pet_kind_name, "
        ."bicycle_parking_number, "
        ."car_parking_number, "
        ."security_deposit, "
        ."deposit_money, "
        ."key_money, "
        ."deposit_refund, "
        ."rent_fee, "
        ."service_fee, "
        ."water_fee, "
        ."ohter_fee, "
        ."total_rent_fee, "
        ."broker_name, "
        ."broker_tel, "
        ."broker_mail, "
        ."manager_name, "
        ."url_send_flag, "
        ."contract_progress, "
        ."create_user_id, "
        ."create_date, "
        ."update_user_id, "
        ."update_date "
        .")VALUES( "
        ."'$application_type_id', "
        ."'$application_use_id', "
        ."'$contract_id', "
        ."'$contract_start_date', "
        ."'$real_estate_name', "
        ."'$real_estate_ruby', "
        ."'$room_name', "
        ."'$post_number', "
        ."'$address', "
        ."'$pet_bleeding_name', "
        ."'$pet_kind_name', "
        ."'$bicycle_parking_number', "
        ."'$car_parking_number', "
        ."'$deposit_money', "
        ."'$deposit_refund', "
        ."'$security_deposit', "
        ."'$key_money', "
        ."'$rent_fee', "
        ."'$service_fee', "
        ."'$water_fee', "
        ."'$ohter_fee', "
        ."'$total_rent_fee', "
        ."'$broker_name', "
        ."'$broker_tel', "
        ."'$broker_mail', "
        ."'$manager_name', "
        ."0, "
        ."'$contract_progress', "
        ."'$session_id', "
        ."now(), "
        ."'$session_id', "
        ."now() "
        ."); ";
        Log::debug('log_insert_sql:'.$str);
        $ret = DB::insert($str);
        
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 不動産業者(update)
     * 
     * @param Request $request
     * @return void
     */
    private function updateRealEstate(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // returnの初期値
        $ret = [];

        /**
         * application_Flag = trueの場合:URLからの処理
         * application_Flag = falseの場合:Homeからの処理
         */
        $session_id = $request->input('session_id');
        
        if($session_id == null){
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
            
        }else{
            Log::debug("URLからの処理");
            $session_id = $session_id;
        }
        
        // 値取得
        $application_form_id = $request->input('application_form_id');
        $contract_progress = $request->input('contract_progress');
        $contract_id = $request->input('contract_id');
        $broker_name = $request->input('broker_name');
        $broker_tel = $request->input('broker_tel');
        $broker_mail = $request->input('broker_mail');
        $manager_name = $request->input('manager_name');
        $application_type_id = $request->input('application_type_id');
        $application_use_id = $request->input('application_use_id');
        $contract_start_date = $request->input('contract_start_date');
        $real_estate_name = $request->input('real_estate_name');
        $real_estate_ruby = $request->input('real_estate_ruby');
        $room_name = $request->input('room_name');
        $post_number = $request->input('post_number');
        $address = $request->input('address');
        $pet_bleeding_name = $request->input('pet_bleeding_name');
        $pet_kind_name = $request->input('pet_kind_name');
        $bicycle_parking_number = $request->input('bicycle_parking_number');
        $car_parking_number = $request->input('car_parking_number');
        $deposit_money = $request->input('deposit_money');
        $deposit_refund = $request->input('deposit_refund');
        $security_deposit = $request->input('security_deposit');
        $key_money = $request->input('key_money');
        $rent_fee = $request->input('rent_fee');
        $service_fee = $request->input('service_fee');
        $water_fee = $request->input('water_fee');
        $ohter_fee = $request->input('ohter_fee');
        $total_rent_fee = $request->input('total_rent_fee');

        /**
         * 数値に関してはNULLで値を代入出来ないので0、''を入れる
         */
        // 申込区分
        if($application_type_id == null){
            $application_type_id =0;
        }
        // 進捗状況
        if($contract_progress == null){
            $contract_progress =0;
        }
        // 申込種別
        if($application_use_id == null){
            $application_use_id =0;
        }
        // 契約担当者
        if($manager_name == null){
            $manager_name = '';
        }
        // 仲介業者
        if($broker_name == null){
            $broker_name = '';
        }
        // 仲介業者tel
        if($broker_tel == null){
            $broker_tel = '';
        }
        // 仲介業者mail
        if($broker_mail == null){
            $broker_mail = '';
        }
        // 契約開始日
        if($contract_start_date == null){
            $contract_start_date = '';
        }
        // 不動産名
        if($real_estate_name == null){
            $real_estate_name = '';
        }
        // 不動産名カナ
        if($real_estate_ruby == null){
            $real_estate_ruby = '';
        }
        // 号室
        if($room_name == null){
            $room_name = '';
        }
        // 郵便番号
        if($post_number == null){
            $post_number = '';
        }
        // 住所
        if($address == null){
            $address = '';
        }
        // ペット飼育有無
        if($pet_bleeding_name == null){
            $pet_bleeding_name = '';
        }
        // ペット種別
        if($pet_bleeding_name == null){
            $pet_bleeding_name = '';
        }
        // 駐輪台数
        if($bicycle_parking_number == null){
            $bicycle_parking_number =0;
        }
        // 駐車台数
        if($car_parking_number == null){
            $car_parking_number =0;
        }
        // 保証金
        if($deposit_money == null){
            $deposit_money =0;
        }
        // 解約引く
        if($deposit_refund == null){
            $deposit_refund =0;
        }
        // 敷金
        if($security_deposit == null){
            $security_deposit =0;
        }
        // 礼金
        if($key_money == null){
            $key_money =0;
        }
        // 家賃
        if($rent_fee == null){
            $rent_fee =0;
        }
        // 共益費
        if($service_fee == null){
            $service_fee =0;
        }
        // 水道代
        if($water_fee == null){
            $water_fee =0;
        }
        // その他
        if($ohter_fee == null){
            $ohter_fee =0;
        }
        // 合計
        if($total_rent_fee == null){
            $total_rent_fee =0;
        }

        /**
         * sql
         */
        $str = "update kasegu_management.real_estate_agent_forms set "
        ."application_type_id='$application_type_id', "
        ."application_use_id='$application_use_id', "
        ."contract_id='$contract_id', "
        ."contract_start_date='$contract_start_date', "
        ."real_estate_name='$real_estate_name', "
        ."real_estate_ruby='$real_estate_ruby', "
        ."room_name='$room_name', "
        ."post_number='$post_number', "
        ."address='$address', "
        ."pet_bleeding_name='$pet_bleeding_name', "
        ."pet_kind_name='$pet_kind_name', "
        ."bicycle_parking_number='$bicycle_parking_number', "
        ."car_parking_number='$car_parking_number', "
        ."security_deposit='$security_deposit', "
        ."deposit_money='$deposit_money', "
        ."key_money='$key_money', "
        ."deposit_refund='$deposit_refund', "
        ."rent_fee='$rent_fee', "
        ."service_fee='$service_fee', "
        ."water_fee='$water_fee', "
        ."ohter_fee='$ohter_fee', "
        ."total_rent_fee='$total_rent_fee', "
        ."broker_name='$broker_name', "
        ."broker_tel='$broker_tel', "
        ."broker_mail='$broker_mail', "
        ."manager_name='$manager_name', "
        ."url_send_flag=1, "
        ."contract_progress='$contract_progress', "
        ."create_user_id='$session_id', "
        ."create_date=now(), "
        ."update_user_id='$session_id', "
        ."update_date=now() "
        ."where application_form_id='$application_form_id'";
        Log::debug('sql_update_real_estate:'.$str);
        $ret['status'] = DB::update($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 賃借人(insert)
     *
     * @param Request $request
     * @return void
     */
    private function insertContract(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        $ret = [];

        /**
         * application_Flag = trueの場合:URLからの処理
         * application_Flag = falseの場合:Homeからの処理
         */
        $application_Flag = $request->input('application_Flag');

        if($application_Flag == true){
            Log::debug("URLからの処理");
            $session_id = $request->input('session_id');
            
        }else{
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
        }
        
        $contract_name = $request->input('contract_name');
        $contract_ruby = $request->input('contract_ruby');
        $contract_post_number = $request->input('contract_post_number');
        $contract_address = $request->input('contract_address');
        $contract_sex_id = $request->input('contract_sex_id');
        $contract_birthday = $request->input('contract_birthday');
        $contract_age = $request->input('contract_age');
        $contract_home_tel = $request->input('contract_home_tel');
        $contract_mobile_tel = $request->input('contract_mobile_tel');
        $contract_work_place_name = $request->input('contract_work_place_name');
        $contract_work_place_ruby = $request->input('contract_work_place_ruby');
        $contract_work_place_post_number = $request->input('contract_work_place_post_number');
        $contract_work_place_address = $request->input('contract_work_place_address');
        $contract_work_place_tel = $request->input('contract_work_place_tel');
        $contract_work_place_Industry = $request->input('contract_work_place_Industry');
        $contract_work_place_occupation = $request->input('contract_work_place_occupation');
        $contract_employment_status = $request->input('contract_employment_status');
        $contract_work_place_years = $request->input('contract_work_place_years');
        $contract_annual_income = $request->input('contract_annual_income');
        $contracts_insurance_type_id = $request->input('contracts_insurance_type_id');

        /**
         * 数値に関してはNULLで値を代入出来ないので0を入れる
         */
        // 契約者名
        if($contract_name == null){
            $contract_name = "";
        }
        // 契約者フリガナ
        if($contract_ruby == null){
            $contract_ruby = "";
        }
        // 郵便番号
        if($contract_post_number == null){
            $contract_post_number = '';
        }
        // 住所
        if($contract_address == null){
            $contract_address = "";
        }
        // 性別
        if($contract_sex_id == null){
            $contract_sex_id = 0;
        }
        // 生年月日
        if($contract_birthday == null){
            $contract_birthday = '';
        }
        // 年齢
        if($contract_age == null){
            $contract_age = 0;
        }
        // 自宅電話番号
        if($contract_home_tel == null){
            $contract_home_tel = "";
        }
        // 携帯電話番号
        if($contract_mobile_tel == null){
            $contract_mobile_tel = "";
        }
        // 勤務先名
        if($contract_work_place_name == null){
            $contract_work_place_name = "";
        }
        // 勤務先名カナ
        if($contract_work_place_ruby == null){
            $contract_work_place_ruby = "";
        }
        // 勤務先郵便番号
        if($contract_work_place_post_number == null){
            $contract_work_place_post_number = '';
        }
        // 勤務先住所
        if($contract_work_place_address == null){
            $contract_work_place_address = "";
        }
        // 勤務先電話番号
        if($contract_work_place_tel == null){
            $contract_work_place_tel = "";
        }
        // 業種
        if($contract_work_place_Industry == null){
            $contract_work_place_Industry = "";
        }
        // 職種
        if($contract_work_place_occupation == null){
            $contract_work_place_occupation = "";
        }
        // 雇用形態
        if($contract_employment_status == null){
            $contract_employment_status = "";
        }
        // 勤続年数
        if($contract_work_place_years == null){
            $contract_work_place_years = 0;
        }
        // 収入
        if($contract_annual_income == null){
            $contract_annual_income = 0;
        }
        // 保険種別
        if($contracts_insurance_type_id == null){
            $contracts_insurance_type_id = 0;
        }

        // 現在日
        $date = now();

        /**
         * sql
         */
        $str = "insert into kasegu_management.contracts_forms( "
        ."contract_name, "
        ."contract_ruby, "
        ."sex_id, "
        ."contract_age, "
        ."contract_birthday, "
        ."post_number, "
        ."address, "
        ."home_tel, "
        ."mobile_tel, "
        ."work_place_name, "
        ."work_place_ruby, "
        ."work_place_post_number, "
        ."work_place_address, "
        ."work_place_tel, "
        ."work_place_Industry, "
        ."work_place_occupation, "
        ."work_place_years, "
        ."employment_status, "
        ."annual_income, "
        ."insurance_type_id, "
        ."create_user_id, "
        ."create_date, "
        ."update_user_id, "
        ."update_date "
        .")values( "
        ."'$contract_name', "
        ."'$contract_ruby', "
        ."'$contract_sex_id', "
        ."'$contract_age', "
        ."'$contract_birthday', "
        ."'$contract_post_number', "
        ."'$contract_address', "
        ."'$contract_home_tel', "
        ."'$contract_mobile_tel', "
        ."'$contract_work_place_name', "
        ."'$contract_work_place_ruby', "
        ."'$contract_work_place_post_number', "
        ."'$contract_work_place_address', "
        ."'$contract_work_place_tel', "
        ."'$contract_work_place_Industry', "
        ."'$contract_work_place_occupation', "
        ."'$contract_work_place_years', "
        ."'$contract_employment_status', "
        ."'$contract_annual_income', "
        ."'$contracts_insurance_type_id', "
        ."'$session_id', "
        ."'$date', "
        ."'$session_id', "
        ."'$date' "
        .")";
        Log::debug('sql_contract:'.$str);
        $ret['status'] = DB::insert($str);

        /**
         * 登録直後の契約idを取得する
         */
        $str = "select contract_id "
        ."from "
        ."contracts_forms "
        ."where "
        ."contracts_forms.create_user_id = '$session_id' "
        ."and contracts_forms.create_date = '$date' "
        ."and contracts_forms.contract_name = '$contract_name' "
        ."and contracts_forms.contract_ruby = '$contract_ruby' ";
        Log::debug('sql_select:'.$str);
        $contract_info = DB::select($str);

        // 取得した契約idの値設定
        $ret['contract_id'] = $contract_info[0]->contract_id;

        Log::debug($ret);
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 賃借人(update)
     *
     * @param Request $request
     * @return void
     */
    private function updateContract(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * application_Flag = false:Home画面空の処理
         * application_Flag != trueの場合:URLの処理
         */
        $application_Flag = $request->input('application_Flag');

        /**
         * session_idの設定
         */
        $session_id = $request->input('session_id');
        Log::debug("updateContract_session_id:" .$session_id);

        // home画面からの処理  
        if($application_Flag == "false"){
            // home画面からの場合、session_idを取得
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
        // URLからの処理  
        }else{
            // URLからの場合、bladeに設定したsession_idを取得
            Log::debug("URLからのsession_id:" .$session_id);
            $session_id = $request->input('session_id');
        }
        
        /**
         * 各値取得
         */
        $contract_id = $request->input('contract_id');
        $contract_name = $request->input('contract_name');
        $contract_ruby = $request->input('contract_ruby');
        $contract_post_number = $request->input('contract_post_number');
        $contract_address = $request->input('contract_address');
        $contract_sex_id = $request->input('contract_sex_id');
        $contract_birthday = $request->input('contract_birthday');
        $contract_age = $request->input('contract_age');
        $contract_home_tel = $request->input('contract_home_tel');
        $contract_mobile_tel = $request->input('contract_mobile_tel');
        $contract_work_place_name = $request->input('contract_work_place_name');
        $contract_work_place_ruby = $request->input('contract_work_place_ruby');
        $contract_work_place_post_number = $request->input('contract_work_place_post_number');
        $contract_work_place_address = $request->input('contract_work_place_address');
        $contract_work_place_tel = $request->input('contract_work_place_tel');
        $contract_work_place_Industry = $request->input('contract_work_place_Industry');
        $contract_work_place_occupation = $request->input('contract_work_place_occupation');
        $contract_employment_status = $request->input('contract_employment_status');
        $contract_work_place_years = $request->input('contract_work_place_years');
        $contract_annual_income = $request->input('contract_annual_income');
        $contracts_insurance_type_id = $request->input('contracts_insurance_type_id');

        /**
         * 数値に関してはNULLで値を代入出来ないので0を入れる
         */
        // 契約者名
        if($contract_name == null){
            $contract_name = "";
        }
        // 契約者フリガナ
        if($contract_ruby == null){
            $contract_ruby = "";
        }
        // 郵便番号
        if($contract_post_number == null){
            $contract_post_number = "";
        }
        // 住所
        if($contract_address == null){
            $contract_address = "";
        }
        // 性別
        if($contract_sex_id == null){
            $contract_sex_id = 0;
        }
        // 生年月日
        if($contract_birthday == null){
            $contract_birthday = "";
        }
        // 年齢
        if($contract_age == null){
            $contract_age = 0;
        }
        // 自宅電話番号
        if($contract_home_tel == null){
            $contract_home_tel = "";
        }
        // 携帯電話番号
        if($contract_mobile_tel == null){
            $contract_mobile_tel = "";
        }
        // 勤務先名
        if($contract_work_place_name == null){
            $contract_work_place_name = "";
        }
        // 勤務先名カナ
        if($contract_work_place_ruby == null){
            $contract_work_place_ruby = "";
        }
        // 勤務先郵便番号
        if($contract_work_place_post_number == null){
            $contract_work_place_post_number = 0;
        }
        // 勤務先住所
        if($contract_work_place_address == null){
            $contract_work_place_address = "";
        }
        // 勤務先電話番号
        if($contract_work_place_tel == null){
            $contract_work_place_tel = "";
        }
        // 業種
        if($contract_work_place_Industry == null){
            $contract_work_place_Industry = "";
        }
        // 職種
        if($contract_work_place_occupation == null){
            $contract_work_place_occupation = "";
        }
        // 雇用形態
        if($contract_employment_status == null){
            $contract_employment_status = "";
        }
        // 勤続年数
        if($contract_work_place_years == null){
            $contract_work_place_years = 0;
        }
        // 収入
        if($contract_annual_income == null){
            $contract_annual_income = 0;
        }
        // 保険種別
        if($contracts_insurance_type_id == null){
            $contracts_insurance_type_id = 0;
        }

        /**
         * sql
         */
        $str = "update kasegu_management.contracts_forms set "
        ."contract_name='$contract_name', "
        ."contract_ruby='$contract_ruby', "
        ."sex_id='$contract_sex_id', "
        ."contract_age='$contract_age', "
        ."contract_birthday='$contract_birthday', "
        ."post_number='$contract_post_number', "
        ."address='$contract_address', "
        ."home_tel='$contract_home_tel', "
        ."mobile_tel='$contract_mobile_tel', "
        ."work_place_name='$contract_work_place_name', "
        ."work_place_ruby='$contract_work_place_ruby', "
        ."work_place_post_number='$contract_work_place_post_number', "
        ."work_place_address='$contract_work_place_address', "
        ."work_place_tel='$contract_work_place_tel', "
        ."work_place_Industry='$contract_work_place_Industry', "
        ."work_place_occupation='$contract_work_place_occupation', "
        ."work_place_years='$contract_work_place_years', "
        ."employment_status='$contract_employment_status', "
        ."annual_income='$contract_annual_income', "
        ."insurance_type_id='$contracts_insurance_type_id', "
        ."create_user_id='$session_id', "
        ."create_date=now(), "
        ."update_user_id='$session_id', "
        ."update_date=now() "
        ."where contract_id='$contract_id'";
        Log::debug('sql_contract:'.$str);
        $ret = DB::update($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 同居人(insert)
     *$maxId=最新の契約者id
     * @param Request $request
     * @return void
     */
    private function insertHousemate(Request $request,$maxId){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * application_Flag = trueの場合:URLからの処理
         * application_Flag = falseの場合:Homeからの処理
         */
        $application_Flag = $request->input('application_Flag');

        if($application_Flag == true){
            Log::debug("URLからの処理");
            $session_id = $request->input('session_id');
            
        }else{
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
        }

        $housemate_id = $request->input('housemate_id');
        $housemate_name = $request->input('housemate_name');
        $housemate_ruby = $request->input('housemate_ruby');
        $housemates_sex_id = $request->input('housemates_sex_id');
        $housemate_relationship_id = $request->input('housemate_relationship_id');
        $housemate_age = $request->input('housemate_age');
        $housemate_birthday = $request->input('housemate_birthday');
        $housemate_post_number = $request->input('housemate_post_number');
        $housemate_address = $request->input('housemate_address');
        $housemate_home_tel = $request->input('housemate_home_tel');
        $housemate_mobile_tel = $request->input('housemate_mobile_tel');

        /**
         * 数値に関してはNULLで値を代入出来ないので0を入れる
         */
        // 同居人名
        if($housemate_name == null){
            $housemate_name = "";
        }
        // 同居人名カナ
        if($housemate_ruby == null){
            $housemate_ruby ="";
        }
        // 性別
        if($housemates_sex_id == null){
            $housemates_sex_id = 0;
        }
        // 続柄
        if($housemate_relationship_id == null){
            $housemate_relationship_id = 0;
        }
        // 生年月日
        if($housemate_birthday == null){
            $housemate_birthday = "";
        }
        // 年齢
        if($housemate_age == null){
            $housemate_age = 0;
        }
        // 郵便番号
        if($housemate_post_number == null){
            $housemate_post_number ="";
        }
        // 住所
        if($housemate_address == null){
            $housemate_address = "";
        }
        // 電話番号
        if($housemate_home_tel == null){
            $housemate_home_tel = "";
        }
        // ファックス番号
        if($housemate_mobile_tel == null){
            $housemate_mobile_tel = "";
        }

        $str = "insert into "
        ."kasegu_management.housemates( "
        ."contract_id, "
        ."housemate_name, "
        ."housemate_ruby, "
        ."relationship_id, "
        ."sex_id, "
        ."housemate_age, "
        ."housemate_name_birthday, "
        ."post_number, "
        ."address, "
        ."housemate_home_tel, "
        ."housemate_mobile_tel, "
        ."create_user_id, "
        ."create_date, "
        ."update_user_id, "
        ."update_date "
        .")values( "
        ."'$maxId', "
        ."'$housemate_name', "
        ."'$housemate_ruby', "
        ."'$housemate_relationship_id', "
        ."'$housemates_sex_id', "
        ."'$housemate_age', "
        ."'$housemate_birthday', "
        ."'$housemate_post_number', "
        ."'$housemate_address', "
        ."'$housemate_home_tel', "
        ."'$housemate_mobile_tel', "
        ."'$session_id', "
        ."now(), "
        ."'$session_id', "
        ."now() "
        ."); ";
        
        Log::debug('sql_housemate:'.$str);
        $ret = DB::insert($str);

        Log::debug($ret);
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 同居人(update)
     *
     * @param Request $request
     * @return void
     */ 
    private function updateHousemate(Request $request){

        /**
         * application_Flag = false:Home画面空の処理
         * application_Flag != trueの場合:URLの処理
         */
        $application_Flag = $request->input('application_Flag');

        /**
         * session_idの設定
         */
        $session_id = $request->input('session_id');
        Log::debug("updateContract_session_id:" .$session_id);

        // home画面からの処理  
        if($application_Flag == "false"){
            // home画面からの場合、session_idを取得
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
        // URLからの処理  
        }else{
            // URLからの場合、bladeに設定したsession_idを取得
            Log::debug("URLからのsession_id:" .$session_id);
            $session_id = $request->input('session_id');
        }

        /**
         * 値取得
         */
        $session_id = $request->session()->get('create_user_id');
        $housemate_id = $request->input('housemate_id');
        $housemate_name = $request->input('housemate_name');
        $housemate_ruby = $request->input('housemate_ruby');
        $housemates_sex_id = $request->input('housemates_sex_id');
        $housemate_relationship_id = $request->input('housemate_relationship_id');
        $housemate_age = $request->input('housemate_age');
        $housemate_birthday = $request->input('housemate_birthday');
        $housemate_post_number = $request->input('housemate_post_number');
        $housemate_address = $request->input('housemate_address');
        $housemate_home_tel = $request->input('housemate_home_tel');
        $housemate_mobile_tel = $request->input('housemate_mobile_tel');

        /**
         * 数値に関してはNULLで値を代入出来ないので0を入れる
         */
        // 同居人名
        if($housemate_name == null){
            $housemate_name = "";
        }
        // 同居人名カナ
        if($housemate_ruby == null){
            $housemate_ruby = "";
        }
        // 性別
        if($housemates_sex_id == null){
            $housemates_sex_id = 0;
        }
        // 続柄
        if($housemate_relationship_id == null){
            $housemate_relationship_id = 0;
        }
        // 生年月日
        if($housemate_birthday == null){
            $housemate_birthday = "";
        }
        // 年齢
        if($housemate_age == null){
            $housemate_age = 0;
        }
        // 郵便番号
        if($housemate_post_number == null){
            $housemate_post_number = "";
        }
        // 住所
        if($housemate_address == null){
            $housemate_address = "";
        }
        // 電話番号
        if($housemate_home_tel == null){
            $housemate_home_tel = "";
        }
        // ファックス番号
        if($housemate_mobile_tel == null){
            $housemate_mobile_tel = "";
        }

        // sql
        $str = "update "
        ."kasegu_management.housemates "
        ."set "
        ."housemate_name = '$housemate_name', "
        ."housemate_ruby = '$housemate_ruby', "
        ."relationship_id = '$housemate_relationship_id', "
        ."sex_id = '$housemates_sex_id', "
        ."housemate_age = '$housemate_age', "
        ."housemate_name_birthday = '$housemate_birthday', "
        ."post_number = '$housemate_post_number', "
        ."address = '$housemate_address', "
        ."housemate_home_tel = '$housemate_home_tel', "
        ."housemate_mobile_tel = '$housemate_mobile_tel', "
        ."create_user_id = '$session_id', "
        ."create_date = now(), "
        ."update_user_id = '$session_id', "
        ."update_date = now() "
        ."where "
        ."housemate_id = '$housemate_id'; ";

        Log::debug('update_housemate:'.$str);
        $ret = DB::update($str);

        Log::debug($ret);
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 緊急連絡先(insert)
     *
     * @param Request $request
     * @param [type] $maxId:最新の契約者id
     */
    private function insertEmergency(Request $request,$maxId){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * application_Flag = trueの場合:URLからの処理
         * application_Flag = falseの場合:Homeからの処理
         */
        $application_Flag = $request->input('application_Flag');

        if($application_Flag == true){
            Log::debug("URLからの処理");
            $session_id = $request->input('session_id');
            
        }else{
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
        }

        $emergency_contacts_name = $request->input('emergency_contacts_name');
        $emergency_contacts_ruby = $request->input('emergency_contacts_ruby');
        $emergency_contacts_sex_id = $request->input('emergency_contacts_sex_id');
        $emergency_contacts_relationships_id = $request->input('emergency_contacts_relationships_id');
        $emergency_contacts_birthday = $request->input('emergency_contacts_birthday');
        $emergency_contract_age = $request->input('emergency_contract_age');
        $emergency_contacts_post_number = $request->input('emergency_contacts_post_number');
        $emergency_contacts_post_address = $request->input('emergency_contacts_post_address');
        $emergency_contacts_home_tel = $request->input('emergency_contacts_home_tel');
        $emergency_contacts_mobile_tel = $request->input('emergency_contacts_mobile_tel');

        /**
         * 数値に関してはNULLで値を代入出来ないので0を入れる
         */
        // 同居人名
        if($emergency_contacts_name == null){
            $emergency_contacts_name = "";
        }
        // 同居人名カナ
        if($emergency_contacts_ruby == null){
            $emergency_contacts_ruby = "";
        }
        // 性別
        if($emergency_contacts_sex_id == null){
            $emergency_contacts_sex_id = 0;
        }
        // 続柄
        if($emergency_contacts_relationships_id == null){
            $emergency_contacts_relationships_id = 0;
        }
        // 生年月日
        if($emergency_contacts_birthday == null){
            $emergency_contacts_birthday = "";
        }
        // 年齢
        if($emergency_contract_age == null){
            $emergency_contract_age = 0;
        }
        // 郵便番号
        if($emergency_contacts_post_number == null){
            $emergency_contacts_post_number = "";
        }
        // 住所
        if($emergency_contacts_post_address == null){
            $emergency_contacts_post_address = "";
        }
        // 電話番号
        if($emergency_contacts_home_tel == null){
            $emergency_contacts_home_tel= "";
        }
        // ファックス番号
        if($emergency_contacts_mobile_tel == null){
            $emergency_contacts_mobile_tel = "";
        }

        $str = "insert into kasegu_management.emergency_contacts( "
        ."contract_id, "
        ."emergency_contacts_name, "
        ."emergency_contacts_ruby, "
        ."relationship_id, "
        ."sex_id, "
        ."emergency_age, "
        ."emergency_birthday, "
        ."post_number, "
        ."address, "
        ."emergency_home_tel, "
        ."emergency_mobile_tel, "
        ."create_user_id, "
        ."create_date, "
        ."update_user_id, "
        ."update_date "
        .")values( "
        ."'$maxId', "
        ."'$emergency_contacts_name', "
        ."'$emergency_contacts_ruby', "
        ."'$emergency_contacts_sex_id', "
        ."'$emergency_contacts_relationships_id', "
        ."'$emergency_contract_age', "
        ."'$emergency_contacts_birthday', "
        ."'$emergency_contacts_post_number', "
        ."'$emergency_contacts_post_address', "
        ."'$emergency_contacts_home_tel', "
        ."'$emergency_contacts_mobile_tel', "
        ."'$session_id', "
        ."now(), "
        ."'$session_id', "
        ."now() "
        ."); ";
        
        Log::debug('sql_emergency_contacts:'.$str);
        $ret = DB::insert($str);

        Log::debug($ret);
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 緊急連絡先(update)
     *
     * @param Request $request
     * @return void
     */
    private function updateEmergency(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * application_Flag = false:Home画面空の処理
         * application_Flag != trueの場合:URLの処理
         */
        $application_Flag = $request->input('application_Flag');

        /**
         * session_idの設定
         */
        $session_id = $request->input('session_id');
        Log::debug("updateContract_session_id:" .$session_id);

        // home画面からの処理  
        if($application_Flag == "false"){
            // home画面からの場合、session_idを取得
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
        // URLからの処理  
        }else{
            // URLからの場合、bladeに設定したsession_idを取得
            Log::debug("URLからのsession_id:" .$session_id);
            $session_id = $request->input('session_id');
        }

        $session_id = $request->session()->get('create_user_id');
        $emergency_contact_id = $request->input('emergency_contact_id');
        $emergency_contacts_name = $request->input('emergency_contacts_name');
        $emergency_contacts_ruby = $request->input('emergency_contacts_ruby');
        $emergency_contacts_sex_id = $request->input('emergency_contacts_sex_id');
        $emergency_contacts_relationships_id = $request->input('emergency_contacts_relationships_id');
        $emergency_contacts_birthday = $request->input('emergency_contacts_birthday');
        $emergency_contract_age = $request->input('emergency_contract_age');
        $emergency_contacts_post_number = $request->input('emergency_contacts_post_number');
        $emergency_contacts_post_address = $request->input('emergency_contacts_post_address');
        $emergency_contacts_home_tel = $request->input('emergency_contacts_home_tel');
        $emergency_contacts_mobile_tel = $request->input('emergency_contacts_mobile_tel');

        /**
         * 数値に関してはNULLで値を代入出来ないので0を入れる
         */
        // 同居人名
        if($emergency_contacts_name == null){
            $emergency_contacts_name = "";
        }
        // 同居人名カナ
        if($emergency_contacts_ruby == null){
            $emergency_contacts_ruby = "";
        }
        // 性別
        if($emergency_contacts_sex_id == null){
            $emergency_contacts_sex_id = 0;
        }
        // 続柄
        if($emergency_contacts_relationships_id == null){
            $emergency_contacts_relationships_id = 0;
        }
        // 生年月日
        if($emergency_contacts_birthday == null){
            $emergency_contacts_birthday = "";
        }
        // 年齢
        if($emergency_contract_age == null){
            $emergency_contract_age = 0;
        }
        // 郵便番号
        if($emergency_contacts_post_number == null){
            $emergency_contacts_post_number = "";
        }
        // 住所
        if($emergency_contacts_post_address == null){
            $emergency_contacts_post_address = "";
        }
        // 電話番号
        if($emergency_contacts_home_tel == null){
            $emergency_contacts_home_tel = "";
        }
        // ファックス番号
        if($emergency_contacts_mobile_tel == null){
            $emergency_contacts_mobile_tel = "";
        }

        $str = "update "
        ."kasegu_management.emergency_contacts "
        ."set "
        ."emergency_contacts_name = '$emergency_contacts_name', "
        ."emergency_contacts_ruby = '$emergency_contacts_ruby', "
        ."relationship_id = '$emergency_contacts_relationships_id', "
        ."sex_id = '$emergency_contacts_sex_id', "
        ."emergency_age = '$emergency_contract_age', "
        ."emergency_birthday = '$emergency_contacts_birthday', "
        ."post_number = '$emergency_contacts_post_number', "
        ."address = '$emergency_contacts_post_address', "
        ."emergency_home_tel = '$emergency_contacts_home_tel', "
        ."emergency_mobile_tel = '$emergency_contacts_mobile_tel', "
        ."create_user_id = '$session_id', "
        ."create_date = now(), "
        ."update_user_id = '$session_id', "
        ."update_date = now() "
        ."where "
        ."emergency_contact_id = '$emergency_contact_id'; ";

        Log::debug('emergency_contact_sql:'.$str);
        $ret = DB::update($str);

        Log::debug($ret);
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 保証人(insert)
     *
     * @param Request $request
     * @param [type] $maxId:最新の契約者id
     */
    private function insertGuarantor(Request $request,$maxId){
        Log::debug('log_start:' .__FUNCTION__);

        /**
         * application_Flag = trueの場合:URLからの処理
         * application_Flag = falseの場合:Homeからの処理
         */
        $application_Flag = $request->input('application_Flag');

        if($application_Flag == true){
            Log::debug("URLからの処理");
            $session_id = $request->input('session_id');
            
        }else{
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
        }

        /**
         * 連帯保証人
         */
        $guarantor_name = $request->input('guarantor_name');
        $guarantor_ruby = $request->input('guarantor_ruby');
        $guarantor_post_number = $request->input('guarantor_post_number');
        $guarantor_address = $request->input('guarantor_address');
        $guarantor_sex_id = $request->input('guarantor_sex_id');
        $guarantor_birthday = $request->input('guarantor_birthday');
        $guarantor_age = $request->input('guarantor_age');
        $guarantors_relationship_id = $request->input('guarantors_relationship_id');
        $guarantor_home_tel = $request->input('guarantor_home_tel');
        $guarantor_mobile_tel = $request->input('guarantor_mobile_tel');
        $guarantor_work_place_name = $request->input('guarantor_work_place_name');
        $guarantor_work_place_ruby = $request->input('guarantor_work_place_ruby');
        $guarantor_work_place_post_number = $request->input('guarantor_work_place_post_number');
        $guarantor_work_place_address = $request->input('guarantor_work_place_address');
        $guarantor_work_place_tel = $request->input('guarantor_work_place_tel');
        $guarantor_work_place_Industry = $request->input('guarantor_work_place_Industry');
        $guarantor_work_place_occupation = $request->input('guarantor_work_place_occupation');
        $guarantor_status = $request->input('guarantor_status');
        $guarantor_work_place_years = $request->input('guarantor_work_place_years');
        $guarantor_annual_income = $request->input('guarantor_annual_income');
        $guarantor_insurance_type_id = $request->input('guarantor_insurance_type_id');
        
        /**
         * 数値に関しては空白で値を代入出来ないので0を入れる
         */
        // 保証人名
        if($guarantor_name == null){
            $guarantor_name = "";
        }
        // 保証人カナ
        if($guarantor_ruby == null){
            $guarantor_ruby = "";
        }
        // 郵便番号
        if($guarantor_post_number == null){
            $guarantor_post_number = "";
        }
        // 住所
        if($guarantor_address == null){
            $guarantor_address = "";
        }
        // 性別
        if($guarantor_sex_id == null){
            $guarantor_sex_id =0;
        }
        // 生年月日
        if($guarantor_birthday == null){
            $guarantor_birthday = "";
        }
        // 年齢
        if($guarantor_age == null){
            $guarantor_age =0;
        }
        // 続柄
        if($guarantors_relationship_id == null){
            $guarantors_relationship_id =0;
        }
        // 自宅電話番号
        if($guarantor_home_tel == null){
            $guarantor_home_tel = "";
        }
        // 携帯番号
        if($guarantor_mobile_tel == null){
            $guarantor_mobile_tel = "";
        }
        // 勤務先名
        if($guarantor_work_place_name == null){
            $guarantor_work_place_name = "";
        }
        // 勤務先名カナ
        if($guarantor_work_place_ruby == null){
            $guarantor_work_place_ruby = "";
        } 
        // 勤務先郵便番号
        if($guarantor_work_place_post_number == null){
            $guarantor_work_place_post_number = "";
        }
        // 勤務先住所
        if($guarantor_work_place_address == null){
            $guarantor_work_place_address = "";
        }
        // 勤務先電話番号
        if($guarantor_work_place_tel == null){
            $guarantor_work_place_tel = "";
        }
        // 勤務先業種
        if($guarantor_work_place_Industry == null){
            $guarantor_work_place_Industry = "";
        }
        // 勤務先職種
        if($guarantor_work_place_occupation == null){
            $guarantor_work_place_occupation = "";
        }
        // 雇用形態
        if($guarantor_status == null){
            $guarantor_status = "";
        }
        // 勤続年数
        if($guarantor_work_place_years == null){
            $guarantor_work_place_years = 0;
        }
        // 年収
        if($guarantor_annual_income == null){
            $guarantor_annual_income = 0;
        }
        // 健康保険種別
        if($guarantor_insurance_type_id == null){
            $guarantor_insurance_type_id = 0;
        }

        $str = "insert into kasegu_management.guarantors( "
        ."contract_id, "
        ."guarantor_name, "
        ."guarantor_ruby, "
        ."sex_id, "
        ."relationship_id, "
        ."guarantor_age, "
        ."guarantor_birthday, "
        ."post_number, "
        ."address, "
        ."home_tel, "
        ."mobile_tel, "
        ."guarantor_work_place_name, "
        ."guarantor_work_place_ruby, "
        ."guarantor_work_place_post_number, "
        ."guarantor_work_place_address, "
        ."guarantor_work_place_tel, "
        ."guarantor_work_place_Industry, "
        ."guarantor_work_place_occupation, "
        ."guarantor_work_place_years, "
        ."guarantor_status, "
        ."guarantor_annual_income, "
        ."guarantor_insurance_type_id, "
        ."create_user_id, "
        ."create_date, "
        ."update_user_id, "
        ."update_date "
        .")values( "
        ."'$maxId', "
        ."'$guarantor_name', "
        ."'$guarantor_ruby', "
        ."'$guarantor_sex_id', "
        ."'$guarantors_relationship_id', "
        ."'$guarantor_age', "
        ."'$guarantor_birthday', "
        ."'$guarantor_post_number', "
        ."'$guarantor_address', "
        ."'$guarantor_home_tel', "
        ."'$guarantor_mobile_tel', "
        ."'$guarantor_work_place_name', "
        ."'$guarantor_work_place_ruby', "
        ."'$guarantor_work_place_post_number', "
        ."'$guarantor_work_place_address', "
        ."'$guarantor_work_place_tel', "
        ."'$guarantor_work_place_Industry', "
        ."'$guarantor_work_place_occupation', "
        ."'$guarantor_work_place_years', "
        ."'$guarantor_status', "
        ."'$guarantor_annual_income', "
        ."'$guarantor_insurance_type_id', "
        ."'$session_id', "
        ."now(), "
        ."'$session_id', "
        ."now() "
        ."); ";
        
        Log::debug('sql_guarantor_contacts:'.$str);
        $ret = DB::insert($str);

        Log::debug($ret);
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 保証人(update)
     *
     * @param Request $request
     * @return void
     */
    private function updateGuarantor(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * application_Flag = false:Home画面空の処理
         * application_Flag != trueの場合:URLの処理
         */
        $application_Flag = $request->input('application_Flag');

        /**
         * session_idの設定
         */
        $session_id = $request->input('session_id');
        Log::debug("updateContract_session_id:" .$session_id);

        // home画面からの処理  
        if($application_Flag == "false"){
            // home画面からの場合、session_idを取得
            Log::debug("Homeからの処理");
            $session_id = $request->session()->get('create_user_id');
        // URLからの処理  
        }else{
            // URLからの場合、bladeに設定したsession_idを取得
            Log::debug("URLからのsession_id:" .$session_id);
            $session_id = $request->input('session_id');
        }

        $session_id = $request->session()->get('create_user_id');
        $guarantor_contracts_id = $request->input('guarantor_contracts_id');
        $guarantor_name = $request->input('guarantor_name');
        $guarantor_ruby = $request->input('guarantor_ruby');
        $guarantor_post_number = $request->input('guarantor_post_number');
        $guarantor_address = $request->input('guarantor_address');
        $guarantor_sex_id = $request->input('guarantor_sex_id');
        $guarantor_birthday = $request->input('guarantor_birthday');
        $guarantor_age = $request->input('guarantor_age');
        $guarantors_relationship_id = $request->input('guarantors_relationship_id');
        $guarantor_home_tel = $request->input('guarantor_home_tel');
        $guarantor_mobile_tel = $request->input('guarantor_mobile_tel');
        $guarantor_work_place_name = $request->input('guarantor_work_place_name');
        $guarantor_work_place_ruby = $request->input('guarantor_work_place_ruby');
        $guarantor_work_place_post_number = $request->input('guarantor_work_place_post_number');
        $guarantor_work_place_address = $request->input('guarantor_work_place_address');
        $guarantor_work_place_tel = $request->input('guarantor_work_place_tel');
        $guarantor_work_place_Industry = $request->input('guarantor_work_place_Industry');
        $guarantor_work_place_occupation = $request->input('guarantor_work_place_occupation');
        $guarantor_status = $request->input('guarantor_status');
        $guarantor_work_place_years = $request->input('guarantor_work_place_years');
        $guarantor_annual_income = $request->input('guarantor_annual_income');
        $guarantor_insurance_type_id = $request->input('guarantor_insurance_type_id');

        /**
         * 数値に関してはNULLで値を代入出来ないので0を入れる
         */
        // 保証人名
        if($guarantor_name == null){
            $guarantor_name = "";
        }
        // 保証人カナ
        if($guarantor_ruby == null){
            $guarantor_ruby = "";
        }
        // 郵便番号
        if($guarantor_post_number == null){
            $guarantor_post_number = "";
        }
        // 住所
        if($guarantor_address == null){
            $guarantor_address = "";
        }
        // 性別
        if($guarantor_sex_id == null){
            $guarantor_sex_id =0;
        }
        // 生年月日
        if($guarantor_birthday == null){
            $guarantor_birthday = "";
        }
        // 年齢
        if($guarantor_age == null){
            $guarantor_age =0;
        }
        // 続柄
        if($guarantors_relationship_id == null){
            $guarantors_relationship_id =0;
        }
        // 自宅電話番号
        if($guarantor_home_tel == null){
            $guarantor_home_tel = "";
        }
        // 携帯番号
        if($guarantor_mobile_tel == null){
            $guarantor_mobile_tel = "";
        }
        // 勤務先名
        if($guarantor_work_place_name == null){
            $guarantor_work_place_name = "";
        }
        // 勤務先名カナ
        if($guarantor_work_place_ruby == null){
            $guarantor_work_place_ruby = "";
        } 
        // 勤務先郵便番号
        if($guarantor_work_place_post_number == null){
            $guarantor_work_place_post_number = "";
        }
        // 勤務先住所
        if($guarantor_work_place_address == null){
            $guarantor_work_place_address = "";
        }
        // 勤務先電話番号
        if($guarantor_work_place_tel == null){
            $guarantor_work_place_tel = "";
        }
        // 勤務先業種
        if($guarantor_work_place_Industry == null){
            $guarantor_work_place_Industry = "";
        }
        // 勤務先職種
        if($guarantor_work_place_occupation == null){
            $guarantor_work_place_occupation = "";
        }
        // 雇用形態
        if($guarantor_status == null){
            $guarantor_status = "";
        }
        // 勤続年数
        if($guarantor_work_place_years == null){
            $guarantor_work_place_years = 0;
        }
        // 年収
        if($guarantor_annual_income == null){
            $guarantor_annual_income = 0;
        }
        // 健康保険種別
        if($guarantor_insurance_type_id == null){
            $guarantor_insurance_type_id = 0;
        }

        $str = "update kasegu_management.guarantors "
        ."set "
        ."guarantor_name = '$guarantor_name', "
        ."guarantor_ruby = '$guarantor_ruby', "
        ."sex_id = '$guarantor_sex_id', "
        ."relationship_id = '$guarantors_relationship_id', "
        ."guarantor_age = '$guarantor_age', "
        ."guarantor_birthday = '$guarantor_birthday', "
        ."post_number = '$guarantor_post_number', "
        ."address = '$guarantor_address', "
        ."home_tel = '$guarantor_home_tel', "
        ."mobile_tel = '$guarantor_mobile_tel', "
        ."guarantor_work_place_name = '$guarantor_work_place_name', "
        ."guarantor_work_place_ruby = '$guarantor_work_place_ruby', "
        ."guarantor_work_place_post_number = '$guarantor_work_place_post_number', "
        ."guarantor_work_place_address ='$guarantor_work_place_address', "
        ."guarantor_work_place_tel = '$guarantor_work_place_tel', "
        ."guarantor_work_place_Industry = '$guarantor_work_place_Industry', "
        ."guarantor_work_place_occupation = '$guarantor_work_place_occupation', "
        ."guarantor_work_place_years = '$guarantor_work_place_years', "
        ."guarantor_status = '$guarantor_status', "
        ."guarantor_annual_income = '$guarantor_annual_income', "
        ."guarantor_insurance_type_id = '$guarantor_insurance_type_id', "
        ."create_user_id = '$session_id', "
        ."create_date = now(), "
        ."update_user_id = '$session_id', "
        ."update_date = now() "
        ."where "
        ."guarantor_id = '$guarantor_contracts_id'; ";

        Log::debug('guarantor_contact_sql:'.$str);
        $ret = DB::update($str);

        Log::debug($ret);
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 画像の編集(ajax)
     *
     * @param Request $request(img_id)
     * @return response(画像データ)
     */
    public function imgEditInit(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        
        // img_id取得
        $img_id = $request->input('img_id');
    
        // db接続
        $str = "select * from imgs "
        ."where img_id = '$img_id' ";
        $list_img = DB::select($str);
        Log::debug('list_img:' .$str);

        $response = [];
        $response['list_img'] = $list_img;
        
        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }


    /**
     * 削除処理
     *
     * @param Request $request($各テーブルid)
     * @return $response(true =OK/false=NG)
     */
    public function deleteEntry(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        // トランザクション
        try {
        DB::beginTransaction();
        
        // jsに返却する初期値
        $response["status"] = true;
        
        /**
         * 削除処理
         * 子テーブルから削除
         * DBの削除完了の返却値が1の為、$retに値格納(登録OK=1)
         * finally内でstatusにtrueを代入
         */
        // 保証人
        $ret = $this->deleteGuarantor($request);

        // 緊急連絡先
        $ret = $this->deleteEmergency($request);

        // 同居人
        $ret = $this->deleteHousemate($request);

        // 賃借人
        $ret = $this->deleteContract($request);

        // 不動産
        $ret = $this->deleteRealEstateAgent($request);

        // 画像
        $ret = $this->deleteImg($request);

        // コミット
        DB::commit();

        // 例外処理
        } catch (\Exception $e) {
            // ロールバック
            DB::rollback();
            // sqlエラーをログに記録
            Log::debug('error:'.$e);
            // 失敗の場合falseを返す
            $ret = false;
        }
        // updateが完了=1の為trueを代入、その他false
        finally{
            if($ret == 1){
                $response["status"] = true;
            }else{
                $response["status"] = false;
            }
            Log::debug('log_end:' .__FUNCTION__);
            return response()->json($response);
        }
    }

    /**
     * 削除(保証人)
     *
     * @param Request $request
     * @return $ret(1=OK/0=NG)
     */
    public function deleteGuarantor(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        
        // 契約者id
        $contract_id = $request->input('contract_id');
        Log::debug('contract_id:'.$contract_id);

        // db接続
        $str = "delete from guarantors "
        ."where contract_id = '$contract_id' ";
        Log::debug('delete_guarantor_sql:'.$str);
        $ret = DB::delete($str);

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }
    
    /**
     * 削除(緊急連絡先)
     *
     * @param Request $request
     * @return $ret(1=OK/0=NG)
     */
    public function deleteEmergency(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        
        // 契約者id
        $contract_id = $request->input('contract_id');
        Log::debug('contract_id:'.$contract_id);

        // db接続
        $str = "delete from emergency_contacts "
        ."where contract_id = '$contract_id' ";
        Log::debug('delete_emergency_contacts_sql:'.$str);
        $ret = DB::delete($str);

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 削除(同居人)
     *
     * @param Request $request
     * @return $ret(1=OK/0=NG)
     */
    public function deleteHousemate(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        
        // 契約者id
        $contract_id = $request->input('contract_id');
        Log::debug('contract_id:'.$contract_id);

        // db接続
        $str = "delete from housemates "
        ."where contract_id = '$contract_id' ";
        Log::debug('delete_housemates_sql:'.$str);
        $ret = DB::delete($str);

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 削除(不動産会社)
     *
     * @param Request $request
     * @return $ret(1=OK/0=NG)
     */
    public function deleteRealEstateAgent(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        
        // 契約者id
        $contract_id = $request->input('contract_id');
        Log::debug('contract_id:'.$contract_id);

        // db接続
        $str = "delete from real_estate_agent_forms "
        ."where contract_id = '$contract_id' ";
        Log::debug('delete_real_estate_agent_forms_sql:'.$str);
        $ret = DB::delete($str);

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 削除(賃借人)
     *
     * @param Request $request
     * @return $ret(1=OK/0=NG)
     */
    public function deleteContract(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        
        // 契約者id
        $contract_id = $request->input('contract_id');
        Log::debug('contract_id:'.$contract_id);

        // db接続
        $str = "delete from contracts_forms "
        ."where contract_id = '$contract_id' ";
        Log::debug('delete_contracts_forms_sql:'.$str);
        $ret = DB::delete($str);

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 削除(画像)
     *
     * @param Request $request
     * @return $ret(1=OK/0=NG)
     */
    public function deleteImg(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        try{

            // トランザクション
            DB::beginTransaction();

            // 初期値:true
            $ret = true;

            // 契約者id取得
            $contract_id = $request->input('contract_id');

            /**
             * 画像データの削除
             * 契約者Idごとの画像データをDBから取得
             * 取得したデータ数だけループ->パス取得->画像データ削除
             */
            $str = "select * from imgs "
            ."where contract_id = '$contract_id' ";
            Log::debug('imgs_select_sql:'.$str);
            $list = DB::select($str);

            // DBからの取得データをデバック
            $arrString = print_r($list , true);
            Log::debug('log_Img:'.$arrString);

            // listにデータが存在しない場合、削除対象が無いのでtrueにする
            if(count($list) == 0){
                Log::debug('画像データは存在しません。');
                /**
                 * この位置にコミットがない場合、トランザクションされてしまい全体のデータ削除が出来ない
                 * そのため、コミット->return:trueを返却する
                 */
                DB::commit();
                return $ret;
            }

            /**
             * フォルダ削除
             */
            // 取得データを"/"で分解、appを除外し文字結合(public/img/214)
            $arr = explode('/', $list[0]->img_path);
            $img_dir_path = $arr[1] ."/" .$arr[2] ."/" .$arr[3];
            Storage::deleteDirectory($img_dir_path);

            /**
             * dbの画像データ削除
             */
            $str = "delete from imgs "
            ."where contract_id = '$contract_id' ";
            Log::debug('imgs_select_sql:'.$str);
            $list = DB::delete($str);

            // コミット
            DB::commit();

        }catch(\Exception $e){
            // ロールバック
            DB::rollback();
            // sqlエラーをログに記録
            Log::debug('error:'.$e);
            // 失敗の場合falseを返す
            $ret = false;
        }finally{
        }

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 画像詳細(削除)
     *
     * @param Request $request(img_id)
     * @return $response["status"] true=OK false=NG
     */
    public function deleteEntryDetail(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        // トランザクション
        try {
        DB::beginTransaction();
        
        // jsに返却する初期値
        $response["status"] = true;
        
        /**
         * 削除処理
         * finally内でstatusにtrueを代入
         */
        // 画像
        $ret = $this->deleteImgDetail($request);

        // コミット
        DB::commit();

        // 例外処理
        } catch (\Exception $e) {
            // ロールバック
            DB::rollback();
            // sqlエラーをログに記録
            Log::debug('error:'.$e);
            // 失敗の場合falseを返す
            $ret = false;
        }
        // updateが完了=1の為trueを代入、その他false
        finally{
            if($ret == 1){
                $response["status"] = true;
            }else{
                $response["status"] = false;
            }
            Log::debug('log_end:' .__FUNCTION__);
            return response()->json($response);
        }
    }

    /**
     * 画像詳細(削除)sql
     *
     * @param Request $request(img_id)
     * @return $ret(true = OK/false = NG)
     */
    private function deleteImgDetail(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        try{

            // トランザクション
            DB::beginTransaction();

            // 初期値:true
            $ret = true;

            // 契約者id取得
            $img_id = $request->input('img_id');

            /**
             * 画像データの削除
             * 画像Idごとの画像データをDBから取得
             */
            $str = "select * from imgs "
            ."where img_id = '$img_id' ";
            Log::debug('imgs_select_sql:'.$str);
            $list = DB::select($str);

            // DBからの取得データをデバック
            $arrString = print_r($list , true);
            Log::debug('log_Img:'.$arrString);

            // listにデータが存在しない場合、削除対象が無いのでtrueで返却する
            if(count($list) == 0){
                /**
                 * この位置にコミットがない場合、トランザクションされてしまい全体のデータ削除が出来ない
                 * そのため、コミット->return:trueを返却する
                 */
                DB::commit();
                return $ret;
            }
            
            /**
             * 画像データ削除
             */
            // 画像を/で分割、appを除外し文字結合(public/img/214/1637578613.jpg)
            $arr = explode('/', $list[0]->img_path);
            $img_name_path = $arr[1] ."/" .$arr[2] ."/" .$arr[3] ."/" .$arr[4];
            Log::debug('img_name_path:'.$img_name_path);
            Storage::delete($img_name_path);
            // Storage::delete('public/img/214/1637578613.jpg');

            /**
             * 画像ファイルの中身取得、空の場合フォルダ削除
             */
            // 取得データを"/"で分解、appを除外し文字結合(public/img/214)
            $arr = explode('/', $list[0]->img_path);
            $img_dir_path = $arr[1] ."/" .$arr[2] ."/" .$arr[3];
            
            // ファイル参照
            $img_arr = Storage::files($img_dir_path);
            // デバック(フォルダ内を参照)
            Log::debug('img_arr:'.$arrString);
            $arrString = print_r($img_arr , true);

            /**
             * 参照の値が空白の場合、フォルダ削除
             */
            if($img_arr == null){
                // フォルダ削除
                Storage::deleteDirectory($img_dir_path);
            }

            /**
             * dbの画像データ削除
             */
            $str = "delete from imgs "
            ."where img_id = '$img_id' ";
            Log::debug('imgs_select_sql:'.$str);
            $list = DB::delete($str);

            // コミット
            DB::commit();

        }catch(\Exception $e){
            // ロールバック
            DB::rollback();
            // sqlエラーをログに記録
            Log::debug('error:'.$e);
            // 失敗の場合falseを返す
            $ret = false;
        }finally{
        }

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }
}
