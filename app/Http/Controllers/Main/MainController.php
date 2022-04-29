<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

/**
 * main画面表示の処理
 */
class MainController extends Controller
{     
    /**
     * 業者ごとの顧客データ取得->main.blade.phpに顧客リストを返却
     * 
     * @param Request $request
     * @return list(顧客リスト)
     */
    public function mainInit(Request $request)
    {   
        Log::debug('start:' .__FUNCTION__);
        // session_id取得
        $session_id = $request->session()->get('create_user_id');
        
        /**
         * session_idが空白の場合、login画面に強制遷移
         * (URLの直接入力での移行防止)
         */
        if($session_id == ""){
            return redirect('/');
        }

        /**
         * 顧客リスト取得
         */
        $list = $this->getListData($session_id);

        // デバック
        $arrString = print_r($list,true);
        Log::debug('output_data:' .$arrString);

        Log::debug('end:' .__FUNCTION__);
        return view('main.main',compact('list'));
    }

    /**
     * sql(顧客一覧取得)
     * session_id(業者ごと)に取得する
     *
     * @param [type] $session_id(業者id)
     * @return $data(業者ごとの顧客リスト)
     */
    private function getListData($session_id){
        Log::debug('log_start:' .__FUNCTION__);

        $str = "select "
        ." real_estate_agent_forms.application_form_id as application_form_id, "
        ." real_estate_agent_forms.application_type_id as application_type_id, "
        ." application_types.application_type_name as application_type_name, "
        ." real_estate_agent_forms.application_use_id as application_use_id, "
        ." application_uses.application_use_name as application_use_name, "
        ." real_estate_agent_forms.contract_start_date as contract_start_date, "
        ." real_estate_agent_forms.real_estate_name as real_estate_name, "
        ." real_estate_agent_forms.real_estate_ruby as real_estate_ruby, "
        ." real_estate_agent_forms.room_name as room_name, "
        ." real_estate_agent_forms.post_number as real_estate_post_number, "
        ." real_estate_agent_forms.address as real_estate_address, "
        ." real_estate_agent_forms.pet_bleeding_name as pet_bleeding_name, "
        ." real_estate_agent_forms.pet_kind_name as pet_kind_name, "
        ." real_estate_agent_forms.bicycle_parking_number as bicycle_parking_number, "
        ." real_estate_agent_forms.car_parking_number as car_parking_number, "
        ." real_estate_agent_forms.security_deposit as security_deposit, "
        ." real_estate_agent_forms.deposit_money as deposit_money, "
        ." real_estate_agent_forms.key_money as key_money, "
        ." real_estate_agent_forms.deposit_refund as deposit_refund, "
        ." real_estate_agent_forms.rent_fee as rent_fee, "
        ." real_estate_agent_forms.service_fee as service_fee, "
        ." real_estate_agent_forms.water_fee as water_fee, "
        ." real_estate_agent_forms.ohter_fee as ohter_fee, "
        ." real_estate_agent_forms.total_rent_fee as total_rent_fee, "
        ." real_estate_agent_forms.manager_name as manager_name, "
        ." contracts_forms.contract_id as contract_id, "
        ." contracts_forms.contract_name as contract_name, "
        ." contracts_forms.sex_id as contract_sex_id, "
        ." contracts_sex.sex_name as contracts_sex_name, "
        ." contracts_forms.contract_age as contract_age, "
        ." contracts_forms.contract_birthday as contract_birthday, "
        ." contracts_forms.post_number as contract_post_number, "
        ." contracts_forms.address as contract_address, "
        ." contracts_forms.home_tel as contract_home_tel, "
        ." contracts_forms.mobile_tel as contract_mobile_tel, "
        ." contracts_forms.work_place_name as contract_work_place_name, "
        ." contracts_forms.work_place_ruby as contract_work_place_ruby, "
        ." contracts_forms.work_place_address as contract_work_place_address, "
        ." contracts_forms.work_place_tel as contract_work_place_tel, "
        ." contracts_forms.work_place_Industry as contract_work_place_Industry, "
        ." contracts_forms.work_place_occupation as contract_work_place_occupation, "
        ." contracts_forms.work_place_years as contract_work_place_years, "
        ." contracts_forms.employment_status as contract_employment_status, "
        ." contracts_forms.annual_income as contract_annual_income, "
        ." contracts_forms.insurance_type_id as contracts_insurance_type_id, "
        ." contracts_insurancetypes.insurance_type_name as contracts_insurancetypes_name, "
        ." housemates.housemate_id as housemate_id, "
        ." housemates.housemate_name as housemate_name, "
        ." housemates.housemate_ruby as housemate_ruby, "
        ." housemates.relationship_id as housemates_relationship_id, "
        ." housemates_relationships.relationship_name as housemates_relationship_id, "
        ." housemates_sex.sex_id as housemates_sex_id, "
        ." housemates_sex.sex_name as housemates_sex_name, "
        ." housemates.housemate_age as housemate_age, "
        ." housemates.housemate_name_birthday as housemate_name_birthday, "
        ." housemates.post_number as housemate_post_number, "
        ." housemates.address as housemate_address, "
        ." housemates.housemate_home_tel as housemate_home_tel, "
        ." housemates.housemate_mobile_tel as housemate_mobile_tel, "
        ." emergency_contacts.emergency_contact_id as emergency_contact_id, "
        ." emergency_contacts.emergency_contacts_name as emergency_contacts_name, "
        ." emergency_contacts.emergency_contacts_ruby as emergency_contacts_ruby, "
        ." emergency_contacts.relationship_id as emergency_contacts_relationships_id, "
        ." emergency_contacts_relationships.relationship_name as emergency_contacts_relationships_name, "
        ." emergency_contacts.sex_id as emergency_contacts_sex_id, "
        ." emergency_contacts_sex.sex_name as emergency_contacts_sex_name, "
        ." emergency_contacts.emergency_birthday as emergency_birthday, "
        ." emergency_contacts.emergency_age as emergency_age, "
        ." emergency_contacts.post_number as emergency_post_number, "
        ." emergency_contacts.address as emergency_address, "
        ." emergency_contacts.emergency_home_tel as emergency_home_tel, "
        ." emergency_contacts.emergency_mobile_tel as emergency_mobile_tel, "
        ." guarantors.guarantor_id as guarantor_contracts_id, "
        ." guarantors.guarantor_name as guarantor_name, "
        ." guarantors.guarantor_ruby as guarantor_ruby, "
        ." guarantors.sex_id as guarantors_sex_id, "
        ." guarantors_sex.sex_name as guarantors_sex_name, "
        ." guarantors.relationship_id as guarantors_relationship_id, "
        ." guarantors_relationships.relationship_name as guarantors_relationships_name, "
        ." guarantors.guarantor_age as guarantor_age, "
        ." guarantors.guarantor_birthday as guarantor_birthday, "
        ." guarantors.post_number as guarantor_post_number, "
        ." guarantors.address as guarantor_address, "
        ." guarantors.home_tel as guarantor_home_tel, "
        ." guarantors.mobile_tel as guarantor_mobile_tel, "
        ." guarantors.guarantor_work_place_name as guarantor_work_place_name, "
        ." guarantors.guarantor_work_place_ruby as guarantor_work_place_ruby, "
        ." guarantors.guarantor_work_place_address as guarantor_work_place_address, "
        ." guarantors.guarantor_work_place_tel as guarantor_work_place_tel, "
        ." guarantors.guarantor_work_place_Industry as guarantor_work_place_Industry, "
        ." guarantors.guarantor_work_place_occupation as guarantor_work_place_occupation, "
        ." guarantors.guarantor_work_place_years as guarantor_work_place_years, "
        ." guarantors.guarantor_work_place_occupation as guarantor_work_place_occupation, "
        ." guarantors.guarantor_status as guarantor_status, "
        ." guarantors.guarantor_annual_income as guarantor_annual_income, "
        ." guarantors.guarantor_insurance_type_id as guarantor_insurance_type_id, "
        ." guarantors_insurancetypes.insurance_type_name as guarantors_insurancetypes_name, "
        ." imgs.img_id as img_id, "
        ." imgs.img_type as img_type, "
        ." imgs.img_path as img_path, "
        ." imgs.img_memo as img_memo, "
        ." contracts_forms.create_user_id as contracts_forms_create_user_id, "
        ." users.create_user_name as contracts_create_user_name "
        ."from "
        ." real_estate_agent_forms "
        ."left join application_types on "
        ." real_estate_agent_forms.application_type_id = application_types.application_type_id "
        ."left join application_uses on "
        ." real_estate_agent_forms.application_use_id = application_uses.application_use_id "
        ."left join contracts_forms on "
        ." real_estate_agent_forms.contract_id = contracts_forms.contract_id "
        ."left join sexs as contracts_sex on "
        ." contracts_sex.sex_id = contracts_forms.sex_id "
        ."left join insurancetypes as contracts_insurancetypes on "
        ." contracts_insurancetypes.insurance_type_id = contracts_forms.insurance_type_id "
        ."left join housemates on "
        ." contracts_forms.contract_id = housemates.contract_id "
        ."left join sexs as housemates_sex on "
        ." housemates_sex.sex_id = housemates.sex_id "
        ."left join relationships as housemates_relationships on "
        ." housemates_relationships.relationship_id = housemates.relationship_id "
        ."left join emergency_contacts on "
        ." emergency_contacts.contract_id = contracts_forms.contract_id "
        ."left join sexs as emergency_contacts_sex on "
        ." emergency_contacts_sex.sex_id = emergency_contacts.sex_id "
        ."left join relationships as emergency_contacts_relationships on "
        ." emergency_contacts_relationships.relationship_id = emergency_contacts.relationship_id "
        ."left join guarantors on "
        ." guarantors.contract_id = contracts_forms.contract_id "
        ."left join sexs as guarantors_sex on "
        ." guarantors_sex.sex_id = guarantors.sex_id "
        ."left join insurancetypes as guarantors_insurancetypes on "
        ." guarantors_insurancetypes.insurance_type_id = guarantors.guarantor_insurance_type_id "
        ."left join relationships as guarantors_relationships on "
        ." guarantors_relationships.relationship_id = guarantors.relationship_id "
        ."left join users on "
        ." users.create_user_id = contracts_forms.create_user_id "
        ."left join( "
        ."select "
        ."img_id, "
        ."contract_id, "
        ."img_type, "
        ."img_path, "
        ."img_memo "
        ."from imgs "
        ."where (contract_id,img_id)in( "
        ."select "
        ."contract_id, "
        ."min(img_id) as img_id "
        ."from imgs "
        ."where img_type = 1 "
        ."group by contract_id) "
        .") as imgs on "
        ." contracts_forms.contract_id = imgs.contract_id "
        ."where contracts_forms.create_user_id='$session_id' ";

        Log::debug('getListData_sql:'.$str);
        
        // デバック(顧客リスト)
        $data = DB::select($str);
        $arrString = print_r($data , true);
        Log::debug('log_data:' .$arrString);

        Log::debug('log_end:' .__FUNCTION__);
        return $data;
    }
}