<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Common;

/**
 * 顧客管理
 */
class AdminAppController extends Controller
{   
    /**
     *  顧客管理(表示・検索)
     *
     * @param Request $request(フォームデータ)
     * @return view('application.application','list_user_count','list_app_count','list_picture_count','list_contacts_count','list_access_total','list_access_today');
     */
    public function adminAppInit(Request $request)
    {   
        // 進捗情報のデータ取得
        Log::debug('start:' .__FUNCTION__);

        try {

            $common = new Common();
            // ユーザ名
            $list_user_name = $common->getUserNameList();
            
            // 顧客一覧
            $list_ref = $this->getRefList($request);

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug("error:" + $e);

        } finally {
        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminApp' ,$list_ref ,compact('list_user_name' ,'list_ref'));
    }

    /**
     * 契約一覧取得(検索時)(sql)
     *
     * @return $ret(顧客情報)
     */
    private function getRefList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // フリーワード
            $free_word = $request->input('free_word');
            Log::debug('$free_word:' .$free_word);

            // 不動産業者
            $create_user_id = $request->input('create_user_id');
            Log::debug('$create_user_id:' .$create_user_id);

            $str = "select "
            ."reaf.application_form_id, "
            ."users.create_user_name, "
            ."users.create_user_tel, "
            ."users.create_user_mail, "
            ."reaf.broker_name, "
            ."reaf.real_estate_name, "
            ."reaf.room_name, "
            ."cf.contract_name, "
            ."cf.mobile_tel, "
            ."reaf.contract_start_date, "
            ."reaf.contract_progress "
            ."from "
            ."real_estate_agent_forms as reaf "
            ."left join users "
            ."on reaf.create_user_id = users.create_user_id "
            ."left join contracts_forms as cf "
            ."on cf.contract_id = reaf.contract_id ";

            // where句
            $where = "";

            // フリーワード
            if($free_word !== null){
                if($where == ""){
                    $where = "where ";
                }else{
                    $where = "and ";
                }

                // ユーザ名、仲介業者名、物件名、契約者名
                $where = $where ."ifnull(create_user_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(broker_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(real_estate_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(contract_name,'') like '%$free_word%'";
            };

            // 取扱店
            if($create_user_id !== null){
                if($where == ""){
                    $where = "where ";
                }else{
                    $where = "and ";
                }
                $where = $where ."reaf.create_user_id = $create_user_id";
            };

            $str = $str .$where;
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");

            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->paginate(10)->onEachSide(1);

            // resの中に値が代入されている
            $ret = [];
            $ret['res'] = $res;

        }catch(\Exception $e) {

            throw $e;

        }finally{

        };

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 新規(表示/ホーム)
     *
     * @return $ret(顧客情報)
     */
    public function adminAppNewInit(Request $request){

        // URLからの場合:True/URLからでない場合:null
        $application_Flag = $request->input('application_Flag');

        // URLからの場合:値取得/URLからでない場合:空白
        $session_id = $request->input('session_id');

        // 顧客情報の空の配列
        $list = $this->getListNewData();

        // 各コンボボックス取得
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

        return view('admin.adminAppEdit', compact('list','list_types','list_uses','list_relation','list_insurance','list_img','application_Flag','session_id'));
    }
    
    /**
     * 新規(表示/空配列)
     *
     * @return $ret(空の配列)
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
     */
    public function adminAppEditInit(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try {

            // requestから値取得
            $application_form_id = $request->input('application_form_id');

            // URLからの場合:true homeからの場合:false
            $application_Flag = $request->input('application_Flag');

            // 直接URL入力された場合ログイン画面にリダイレクト
            if($application_form_id == ""){
                return redirect('/');
            }

            // urlからの処理の場合:true/homeからの場合:false
            if($application_Flag == true){

                Log::debug("URLの処理");

                $req_date = $request->input('deadline');

                // 現在時刻取得- 1時間
                $system_date = date('YmdHis', strtotime('-1 month'));

                // req_date != null:URL処理の場合
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
            };
            
            // URLからの場合:値取得/URLからでない場合:空白
            $session_id = $request->input('create_user_id');

            // URLからの場合、session_idがパラメーターで送られる為、複合化する
            if($session_id !== null){
                // 複合化
                $session_id = Crypt::decrypt($session_id);
            }
            else{
                $session_id = "";
            }

            // 顧客id取得
            $application_form_id = $request->input('application_form_id');

            // 顧客リスト取得
            $list = $this->getListData($application_form_id);

            // 顧客の画像取得(複数)
            $contract_id = $list[0]->contract_id;
            $list_img = $this->getListImg($contract_id);

            // 各コンボボックス取得
            $common = new Common();

            // 申込区分(id.valueの値取得)
            $list_types = $common->getApplicationTypes();

            // 申込種別
            $list_uses = $common->getApplicationUses();

            // 続柄のリスト作成
            $list_relation = $common->getRelationships();

            // 健康保険リスト作成
            $list_insurance = $common->getInsuranceType();

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {
        }

        Log::debug('log_end:'.__FUNCTION__);
        return view('admin.adminAppEdit', compact('list','list_types','list_uses','list_relation','list_insurance','list_img','application_Flag','session_id'));
    }

    /**
     * 編集(表示:sql)
     *
     * @param [type] $session_id
     * @return void
     */
    private function getListData($application_form_id){

        Log::debug('log_start:' .__FUNCTION__);

        try{

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
    
        
        // 例外処理
        } catch (\Throwable $e) {

            throw $e;

        } finally {

        }

        Log::debug('log_end:'.__FUNCTION__);
        return $data;
    }

    /**
     * 画像(取得:slq)
     *
     * @param [type] $contract_id(顧客id)
     * @return data(顧客ごとの画像データ)
     */
    private function getListImg($contract_id){

        Log::debug('log_start:' .__FUNCTION__);

        try{
            $str = "select * from imgs "
            ."where contract_id = '$contract_id' ";
            Log::debug('sql_Img:'.$str);
            $data = DB::select($str);
            
            // 取得データデバック
            $arrString = print_r($data , true);
            Log::debug('log_Img:'.$arrString);
        } catch (\Throwable $e) {

            throw $e;

        } finally {
        }

        Log::debug('log_end:' .__FUNCTION__);

        return $data;
    }

    /**
     * 分岐(新規/編集)
     *
     * @param $request(edit.blade.phpの各項目)
     * @return $response(status:true=OK/false=NG)
     */
    public function appEditEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);
        
        try{
            // return初期値
            $response = [];
    
            // バリデーション:OK=true NG=false
            $response = $this->editValidation($request);
    
            if($response["status"] == false){
                Log::debug('validator_status:falseのif文通過');
                return response()->json($response);
            }
    
            /**
             * application_id=無:insert
             * application_id=有:update
             */
            // 新規登録
            if($request->input('application_id') == ""){
    
                Log::debug('新規の処理');
    
                // $responseの値設定
                $ret = $this->insertData($request);
    
                // js側での判定のステータス(true:OK/false:NG)
                $response["status"] = $ret['status'];
    
            // 編集登録
            }else{
    
                Log::debug('編集の処理');
    
                // $responseの値設定
                $ret = $this->updateData($request);
    
                // js側での判定のステータス(true:OK/false:NG)
                $response["status"] = $ret['status'];
    
            }

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

            $response["status"] = false;

        } finally {

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
        // 不動産業者
        $rules = [];
        $rules['broker_company_name'] = "nullable|max:100";
        $rules['broker_tel'] = "nullable|jptel";
        $rules['broker_mail'] = "nullable|email";
        $rules['contract_start_date'] = "nullable|date";
        $rules['real_estate_name'] = "required|max:50";
        $rules['real_estate_ruby'] = "required|max:100";
        $rules['room_name'] = "required|max:10";
        $rules['post_number'] = "required|zip";
        $rules['address'] = "required|max:200";
        $rules['pet_kind'] = "nullable|max:10";
        $rules['bicycle_number'] = "required|integer";
        $rules['car_number_number'] = "required|integer";

        // 賃借人
        $rules['entry_contract_name'] = "required|max:100";
        $rules['entry_contract_ruby'] = "required|max:200";
        $rules['entry_contract_post_number'] = "nullable|zip";
        $rules['entry_contract_address'] = "nullable|max:100";
        $rules['entry_contract_sex_id'] = "nullable|integer";
        $rules['entry_contract_birthday'] = "nullable|date";
        $rules['entry_contract_age'] = "nullable|integer";
        $rules['entry_contract_home_tel'] = "nullable|jptel";
        $rules['entry_contract_business_name'] = "nullable|max:50";
        $rules['entry_contract_business_ruby'] = "nullable|max:100";
        $rules['entry_contract_business_post_number'] = "nullable|zip";
        $rules['entry_contract_business_address'] = "nullable|max:150";
        $rules['entry_contract_business_tel'] = "nullable|jptel";
        $rules['entry_contract_business_type'] = "nullable|max:20";
        $rules['entry_contract_business_line'] = "nullable|max:20";
        $rules['entry_contract_business_status'] = "nullable|max:10";
        $rules['entry_contract_business_year'] = "nullable|integer";
        $rules['entry_contract_income'] = "nullable|max:10";
        $rules['entry_contract_insurance_type_id'] = "nullable|integer";

        // /**
        //  * 同居人
        //  */
        // $rules['housemate_name'] = "nullable|max:100";
        // $rules['housemate_ruby'] = "nullable|max:200";
        // $rules['housemate_birthday'] = "nullable|date|max:100";
        // $rules['housemate_age'] = "nullable|integer";
        // $rules['housemate_post_number'] = "nullable|zip";
        // $rules['housemate_address'] = "nullable|max:200";
        // $rules['housemate_home_tel'] = "nullable|jptel";
        // $rules['housemate_mobile_tel'] = "nullable|max:100";

        // /**
        //  * 緊急連絡先
        //  */
        // $rules['emergency_contacts_name'] = "nullable|max:100";
        // $rules['emergency_contacts_ruby'] = "nullable|max:200";
        // $rules['emergency_contacts_birthday'] = "nullable|date|max:100";
        // $rules['emergency_contract_age'] = "nullable|max:20";
        // $rules['emergency_contacts_post_number'] = "nullable|zip";
        // $rules['emergency_contacts_post_address'] = "nullable|max:100";
        // $rules['emergency_contacts_home_tel'] = "nullable|jptel|max:20";
        // $rules['emergency_contacts_mobile_tel'] = "nullable|max:20";

        // /**
        //  * 連帯保証人
        //  */
        // $rules['guarantor_name'] = "nullable|max:100";
        // $rules['guarantor_ruby'] = "nullable|max:200";
        // $rules['guarantor_post_number'] = "nullable|zip";
        // $rules['guarantor_address'] = "nullable|max:200";
        // $rules['guarantor_birthday'] = "nullable|date";
        // $rules['guarantor_age'] = "nullable|integer";
        // $rules['guarantor_home_tel'] = "nullable|jptel";
        // $rules['guarantor_mobile_tel'] = "nullable|max:100";
        // $rules['guarantor_work_place_name'] = "nullable|max:100";
        // $rules['guarantor_work_place_ruby'] = "nullable|max:200";
        // $rules['guarantor_work_place_post_number'] = "nullable|zip";
        // $rules['guarantor_work_place_address'] = "nullable|max:200";
        // $rules['guarantor_work_place_tel'] = "nullable|jptel";
        // $rules['guarantor_work_place_Industry'] = "nullable|max:50";
        // $rules['guarantor_work_place_occupation'] = "nullable|max:50";
        // $rules['guarantor_work_place_years'] = "nullable|integer";
        // $rules['guarantor_status'] = "nullable|max:20";
        // $rules['guarantor_annual_income'] = "nullable|integer";
        
        // // 画像(nullableが効かない為、ifで判定)
        // $file_img = $request->file('file_img');
        // Log::debug('バリデーション_file_img:' .$file_img);

        // if($file_img !== null){
        //     Log::debug('画像が添付されています');
        //     $rules['file_img'] = "nullable|mimes:jpeg,png,jpg";
        // }
    
        // $rules['file_img_type_textarea'] = "nullable|max:100";

        /**
         * messages
         */
        // 不動産業者
        $messages['broker_company_name.max'] = "仲介業者名の文字数が超過しています。";
        $messages['broker_tel.jptel'] = "仲介業者Telの形式が不正です。";
        $messages['broker_mail.email'] = "仲介業者E-meilの形式が不正です。";
        $messages['contract_start_date.date'] = "入居開始日の形式が不正です。";
        $messages['real_estate_name.required'] = "物件名は必須です。";
        $messages['real_estate_name.max'] = "物件名の文字数が超過しています。";
        $messages['real_estate_ruby.required'] = "物件名カナは必須です。";
        $messages['real_estate_ruby.max'] = "物件名カナの文字数が超過しています。";
        $messages['room_name.required'] = "号室は必須です。";
        $messages['room_name.max'] = "号室の文字数が超過しています。";
        $messages['post_number.required'] = "郵便番号は必須です。";
        $messages['post_number.zip'] = "郵便番号の形式が不正です。";
        $messages['address.required'] = "住所は必須です。";
        $messages['address.max'] = "住所の文字数が超過しています。";
        $messages['pet_kind.max'] = "ペットの種類の文字数が超過しています。";
        $messages['bicycle_number.required'] = "駐輪台数は必須です。";
        $messages['bicycle_number.integer'] = "駐輪台数の形式が不正です。";
        $messages['car_number_number.required'] = "駐車台数は必須です。";
        $messages['car_number_number.integer'] = "駐車台数の形式が不正です。";
        
        // 契約者
        $messages = [];
        $messages['entry_contract_name.required'] = "契約者は必須です。";
        $messages['entry_contract_name.max'] = "契約者の文字数が超過しています。";
        $messages['entry_contract_ruby.required'] = "契約者は必須です。";
        $messages['entry_contract_ruby.max'] = "契約者カナの文字数が超過しています。";
        $messages['entry_contract_post_number.zip'] = "郵便番号の形式が不正です。";
        $messages['entry_contract_address.max'] = "住所の文字数が超過しています。";
        $messages['entry_contract_sex_id.integer'] = "性別の形式が不正です。";
        $messages['entry_contract_birthday.date'] = "生年月日の形式が不正です。";
        $messages['entry_contract_age.integer'] = "年齢の形式が不正です。";
        $messages['entry_contract_home_tel.jptel'] = "自宅Telの形式が不正です。";
        $messages['entry_contract_business_name.max'] = "勤務先名称の文字数が超過しています。";
        $messages['entry_contract_business_ruby.max'] = "勤務先名称カナの文字数が超過しています。";
        $messages['entry_contract_business_post_number.zip'] = "郵便番号の形式が不正です。";
        $messages['entry_contract_business_address.max'] = "所在地の文字数が超過しています。";
        $messages['entry_contract_business_tel.jptel'] = "勤務先Telの形式が不正です。";
        $messages['entry_contract_business_type.max'] = "業種の文字数が超過しています。";
        $messages['entry_contract_business_line.max'] = "職種の文字数が超過しています。";
        $messages['entry_contract_business_status.max'] = "雇用形態の文字数が超過しています。";
        $messages['entry_contract_business_year.integer'] = "勤続年数の形式が不正です。";
        $messages['entry_contract_income.integer'] = "年収の形式が不正です。";
        $messages['entry_contract_insurance_type_id.integer'] = "健康保険の形式が不正です。";

        // // 同居人
        // $messages['housemate_name.max'] = "同居人名の文字数が超過しています。";
        // // 同居人カナ
        // $messages['housemate_ruby.max'] = "同居人名カナの文字数が超過しています。";
        // // 生年月日
        // $messages['housemate_birthday.date'] = "生年月日の形式が不正です。";
        // $messages['housemate_birthday.max'] = "生年月日の文字数が超過しています。";
        // // 年齢
        // $messages['housemate_age.max'] = "年齢の文字数が超過しています。";
        // // 郵便番号
        // $messages['housemate_post_number.zip'] = "郵便番号の形式が不正です。";
        // // 住所
        // $messages['housemate_address.max'] = "住所の文字数が超過しています。";
        // // 自宅tel
        // $messages['housemate_home_tel.jptel'] = "自宅Telの形式が不正です。";
        // // 携帯番号
        // $messages['housemate_mobile_tel.max'] = "携帯Telの文字数が超過しています。";

        // /**
        //  * 緊急連絡先
        //  */
        // // 緊急連絡先名
        // $messages['emergency_contacts_name.max'] = "緊急連絡先名の文字数が超過しています。";
        // // 緊急連絡先名カナ
        // $messages['emergency_contacts_ruby.max'] = "緊急連絡先名カナの文字数が超過しています。";
        // // 生年月日
        // $messages['emergency_contacts_birthday.date'] = "生年月日の形式が不正です。";
        // $messages['emergency_contacts_birthday.max'] = "生年月日の文字数が超過しています。";
        // // 年齢
        // $messages['emergency_contract_age.max'] = "年齢の文字数が超過しています。";
        // // 郵便番号
        // $messages['emergency_contacts_post_number.zip'] = "郵便番号の形式が不正です。";
        // // 住所
        // $messages['emergency_contacts_post_address.max'] = "住所の文字数が超過しています。";
        // // 自宅tel
        // $messages['emergency_contacts_home_tel.jptel'] = "自宅Telの形式が不正です。";
        // $messages['emergency_contacts_home_tel.max'] = "自宅Telの文字数が超過しています。";
        // // 携帯番号
        // $messages['emergency_contacts_mobile_tel.max'] = "携帯Telの文字数が超過しています。";

        // /**
        //  * 連帯保証人
        //  */
        // // 連帯保証名
        // $messages['guarantor_name.max'] = "連帯保証人の文字数が超過しています。";
        // // 連帯保証人カナ
        // $messages['guarantor_ruby.max'] = "連帯保証人カナの文字数が超過しています。";
        // // 郵便番号
        // $messages['guarantor_post_number.zip'] = "郵便番号の形式が不正です。";
        // // 生年月日
        // $messages['guarantor_birthday.date'] = "生年月日の形式が不正です。";
        // // 住所
        // $messages['guarantor_address.max'] = "住所の文字数が超過しています。";
        // // 自宅tel
        // $messages['guarantor_home_tel.jptel'] = "自宅Telの形式が不正です。";
        // // 携帯番号
        // $messages['guarantor_mobile_tel.max'] = "携帯Telの文字数が超過しています。";
        // // 勤務先名
        // $messages['guarantor_work_place_name.max'] = "勤務先名の文字数が超過しています。";
        // // 勤務先名カナ
        // $messages['guarantor_work_place_ruby.max'] = "勤務先名カナの文字数が超過しています。";
        // // 郵便番号
        // $messages['guarantor_work_place_post_number.zip'] = "郵便番号の形式が不正です。";
        // // 所在地
        // $messages['guarantor_work_place_address.max'] = "所在地の文字数が超過しています。";
        // // 勤務先Tel
        // $messages['guarantor_work_place_tel.jptel'] = "自宅Telの形式が不正です。";
        // // 業種
        // $messages['guarantor_work_place_Industry.max'] = "業種の文字数が超過しています。";
        // // 職種
        // $messages['guarantor_work_place_occupation.max'] = "職種の文字数が超過しています。";
        // // 勤続年数
        // $messages['guarantor_work_place_years.integer'] = "勤続年数の形式が不正です。";
        // // 雇用形態
        // $messages['guarantor_status.max'] = "雇用形態の文字数が超過しています。";
        // // 年収
        // $messages['guarantor_annual_income.integer'] = "年収の形式が不正です。";
        
        // // 画像(nullableが効かない為、ifで判定)
        
        // $messages['file_img_type_textarea.max'] = "補足の文字数が超過しています。";

        // // 画像(nullableが効かない為、ifで判定)
        // $file_img = $request->file('file_img');
        // Log::debug('バリデーション_file_img:' .$file_img);

        // if($file_img !== null){
        //     Log::debug('画像が添付されています');
        //     $messages['file_img.mimes'] = "画像ファイル(jpg,jpeg,png)でアップロードして下さい。";
        // }
    
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
            
            DB::beginTransaction();
            
            // retrun初期値
            $ret = [];
            $ret['status'] = true;

            // 不動産業者insert
            $app_info = $this->InsertApplication($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $app_info['status'];

            // 登録した契約Idを取得
            // $maxId= $contract_info['contract_id'];
            // $ret['maxId'] = $maxId;

            DB::commit();

        // 例外処理
        } catch (\Exception $e) {

            DB::rollback();

            Log::debug('error:'.$e);

            throw new \Exception(__FUNCTION__ .':' .$e);

        // status:OK=1/NG=0
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
     * 不動産業者(登録)
     * 
     * @param Request $request
     * @return void
     */
    private function InsertApplication(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $contract_progress = $request->input('contract_progress');
            $broker_company_name = $request->input('broker_company_name');
            $broker_tel = $request->input('broker_tel');
            $broker_mail = $request->input('broker_mail');
            $broker_name = $request->input('broker_name');
            $application_type_id = $request->input('application_type_id');
            $application_use_id = $request->input('application_use_id');
            $contract_start_date = $request->input('contract_start_date');
            $real_estate_name = $request->input('real_estate_name');
            $real_estate_ruby = $request->input('real_estate_ruby');
            $room_name = $request->input('room_name');
            $post_number = $request->input('post_number');
            $address = $request->input('address');
            $pet_bleed = $request->input('pet_bleed');
            $pet_kind = $request->input('pet_kind');
            $bicycle_number = $request->input('bicycle_number');
            $car_number_number = $request->input('car_number_number');
            $deposit_fee = $request->input('deposit_fee');
            $refund_fee = $request->input('refund_fee');
            $security_fee = $request->input('security_fee');
            $key_fee = $request->input('key_fee');
            $rent_fee = $request->input('rent_fee');
            $service_fee = $request->input('service_fee');
            $water_fee = $request->input('water_fee');
            $ohter_fee = $request->input('ohter_fee');
            $total_fee = $request->input('total_fee');

            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // 進捗状況
            if($contract_progress == null){
                $contract_progress =null;
            }

            // 申込区分
            if($application_type_id == null){
                $application_type_id =null;
            }

            // 申込種別
            if($application_use_id == null){
                $application_use_id =null;
            }

            // 担当者
            if($broker_name == null){
                $broker_name = '';
            }

            // 仲介業者
            if($broker_company_name == null){
                $broker_company_name = '';
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
            if($pet_bleed == null){
                $pet_bleed = '';
            }

            // ペット種類
            if($pet_kind == null){
                $pet_kind = '';
            }

            // 駐輪台数
            if($bicycle_number == null){
                $bicycle_number =null;
            }

            // 駐車台数
            if($car_number_number == null){
                $car_number_number =0;
            }

            // 保証金
            if($deposit_fee == null){
                $deposit_fee =null;
            }

            // 解約引
            if($deposit_fee == null){
                $deposit_fee =null;
            }

            // 敷金
            if($security_fee == null){
                $security_fee =null;
            }

            // 礼金
            if($key_fee == null){
                $key_fee =null;
            }

            // 家賃
            if($rent_fee == null){
                $rent_fee =null;
            }

            // 共益費
            if($service_fee == null){
                $service_fee =null;
            }

            // 水道代
            if($water_fee == null){
                $water_fee =null;
            }

            // その他
            if($ohter_fee == null){
                $ohter_fee =null;
            }

            // 合計
            if($total_fee == null){
                $total_fee =null;
            }

            // sql
            $str = "insert into "
            ."applications( "
            ."create_user_id, "
            ."application_type_id, "
            ."real_estate_use_id, "
            ."contract_start_date, "
            ."real_estate_name, "
            ."real_estate_ruby, "
            ."room_name, "
            ."post_number, "
            ."address, "
            ."pet_bleed, "
            ."pet_kind, "
            ."bicycle_number, "
            ."car_number, "
            ."security_fee, "
            ."deposit_fee, "
            ."key_fee, "
            ."refund_fee, "
            ."rent_fee, "
            ."service_fee, "
            ."water_fee, "
            ."ohter_fee, "
            ."total_fee, "
            ."broker_company_name, "
            ."broker_tel, "
            ."broker_mail, "
            ."broker_name, "
            ."contract_progress_id, "
            ."url_send_flag, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$session_id, "
            ."$application_type_id, "
            ."$application_use_id, "
            ."'$contract_start_date', "
            ."'$real_estate_name', "
            ."'$real_estate_ruby', "
            ."'$room_name', "
            ."'$post_number', "
            ."'$address', "
            ."$pet_bleed, "
            ."'$pet_kind', "
            ."$bicycle_number, "
            ."$car_number_number, "
            ."$security_fee, "
            ."$deposit_fee, "
            ."$refund_fee, "
            ."$key_fee, "
            ."$rent_fee, "
            ."$service_fee, "
            ."$water_fee, "
            ."$ohter_fee, "
            ."$total_fee, "
            ."'$broker_company_name', "
            ."'$broker_tel', "
            ."'$broker_mail', "
            ."'$broker_name', "
            ."$contract_progress_id, "
            ."0, "
            ."$session_id, "
            ."now(), "
            ."$session_id, "
            ."now() "
            ."); ";
            
            Log::debug('sql:'.$str);

            $ret['status'] = DB::insert($str);
            
            
        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

            throw new \Exception(__FUNCTION__ .':' .$e);

        // status:OK=1/NG=0
        } finally {
            
        }

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

} 