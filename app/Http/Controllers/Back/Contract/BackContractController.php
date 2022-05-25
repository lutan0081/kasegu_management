<?php

namespace App\Http\Controllers\Back\Contract;

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
 * 契約管理(表示・登録、編集、削除)
 */
class BackContractController extends Controller
{   
    /**
     *  一覧(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backContractInit(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {
            
            // 契約一覧
            $contract_list = $this->getList($request);

            // 契約詳細一覧
            $common = new Common();

            // 契約進捗状況
            $contract_detail_progress = $common->getContractDetailProgress();
            

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backContract' ,$contract_list ,compact('contract_detail_progress'));
    }

    /**
     * 一覧(sql)
     *
     * @return $ret(銀行一覧)
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

            // contract_detail_progress_id
            $contract_detail_progress_id = $request->input('contract_progress_id');
            Log::debug('$contract_detail_progress_id:' .$contract_detail_progress_id);

            // 契約始期
            $start_date = $request->input('start_date');
            Log::debug('$start_date:' .$start_date);

            // 契約終期
            $end_date = $request->input('end_date');
            Log::debug('$end_date:' .$end_date);

            $str = "select "
            ."contract_details.contract_detail_id as contract_detail_id, "
            ."contract_details.create_user_id as create_user_id, "
            ."contract_details.contract_detail_progress_id as contract_detail_progress_id, "
            ."contract_detail_progress.contract_detail_progress_name as contract_detail_progress_name, "
            ."application_id as application_id, "
            ."company_license_id as company_license_id, "
            ."manager_name as manager_name, "
            ."user_license_id as user_license_id, "
            ."user_license_name as user_license_name, "
            ."user_license_number as user_license_number, "
            ."trade_type_id as trade_type_id, "
            ."contract_name as contract_name, "
            ."contract_ruby as contract_ruby, "
            ."contract_date as contract_date, "
            ."contract_tel as contract_tel, "
            ."real_estate_name as real_estate_name, "
            ."real_estate_post_number as real_estate_post_number, "
            ."real_estate_address as real_estate_address, "
            ."room_name as room_name, "
            ."room_size as room_size, "
            ."real_estate_structure_id as real_estate_structure_id, "
            ."real_estate_floor as real_estate_floor, "
            ."room_layout_name as room_layout_name, "
            ."room_layout_id as room_layout_id, "
            ."real_estate_age as real_estate_age, "
            ."owner_name as owner_name, "
            ."owner_post_number as owner_post_number, "
            ."owner_address as owner_address, "
            ."bank_id as bank_id, "
            ."bank_name as bank_name, "
            ."bank_branch_name as bank_branch_name, "
            ."bank_type_id as bank_type_id, "
            ."bank_number as bank_number, "
            ."bank_account_name as bank_account_name, "
            ."m_share_name as m_share_name, "
            ."m_share_address as m_share_address, "
            ."m_share_tel as m_share_tel, "
            ."m_own_name as m_own_name, "
            ."m_own_address as m_own_address, "
            ."m_own_tel as m_own_tel, "
            ."report_asbestos as report_asbestos, "
            ."report_earthquake as report_earthquake, "
            ."regi_name as regi_name, "
            ."regi_right as regi_right, "
            ."regi_mortgage as regi_mortgage, "
            ."regi_difference_owner as regi_difference_owner, "
            ."completion_date as completion_date, "
            ."hazard_map as hazard_map, "
            ."warning_flood as warning_flood, "
            ."warning_storm_surge as warning_storm_surge, "
            ."warning_rain_water as warning_rain_water, "
            ."security_fee as security_fee, "
            ."rent_fee as rent_fee, "
            ."service_fee as service_fee, "
            ."water_fee as water_fee, "
            ."ohter_fee as ohter_fee, "
            ."bicycle_fee as bicycle_fee, "
            ."car_fee as car_fee, "
            ."car_deposit_fee as car_deposit_fee, "
            ."fire_insurance_fee as fire_insurance_fee, "
            ."fire_insurance_span as fire_insurance_span, "
            ."guarantee_fee as guarantee_fee, "
            ."guarantee_update_span as guarantee_update_span, "
            ."guarantee_update_fee as guarantee_update_fee, "
            ."support_fee as support_fee, "
            ."disinfect_fee as disinfect_fee, "
            ."other_name1 as other_name1, "
            ."other_fee1 as other_fee1, "
            ."other_name2 as other_name2, "
            ."other_fee2 as other_fee2, "
            ."broker_fee as broker_fee, "
            ."car_broker_fee as car_broker_fee, "
            ."today_account_fee_date as today_account_fee_date, "
            ."today_account_fee as today_account_fee, "
            ."payment_date as payment_date, "
            ."keep_account_fee as keep_account_fee, "
            ."introduction_fee as introduction_fee, "
            ."water as water, "
            ."water_type_name as water_type_name, "
            ."electricity as electricity, "
            ."electricity_type_name as electricity_type_name, "
            ."gas as gas, "
            ."gas_type_name as gas_type_name, "
            ."waste_water as waste_water, "
            ."waste_water_name as waste_water_name, "
            ."kitchen as kitchen, "
            ."kitchen_exclusive_type_id as kitchen_exclusive_type_id, "
            ."cooking_stove as cooking_stove, "
            ."cooking_exclusive_type_id as cooking_exclusive_type_id, "
            ."bath as bath, "
            ."bath_exclusive_type_id as bath_exclusive_type_id, "
            ."toilet as toilet, "
            ."toilet_exclusive_type_id as toilet_exclusive_type_id, "
            ."water_heater as water_heater, "
            ."water_heater_exclusive_type_id as water_heater_exclusive_type_id, "
            ."air_conditioner as air_conditioner, "
            ."air_conditioner_exclusive_type_name as air_conditioner_exclusive_type_name, "
            ."elevator as elevator, "
            ."elevator_type_name as elevator_type_name, "
            ."contract_start_date as contract_start_date, "
            ."contract_end_date as contract_end_date, "
            ."contract_update_span as contract_update_span, "
            ."contract_update_item as contract_update_item, "
            ."daily_calculation as daily_calculation, "
            ."security_settle_detail as security_settle_detail, "
            ."key_money_settle_detail as key_money_settle_detail, "
            ."limit_use as limit_use, "
            ."limit_type as limit_type, "
            ."announce_cancel_date as announce_cancel_date, "
            ."soon_cancel_date as soon_cancel_date, "
            ."cancel_fee_count as cancel_fee_count, "
            ."cancel_contract_document as cancel_contract_document, "
            ."remove_contract_document as remove_contract_document, "
            ."penalty_fee as penalty_fee, "
            ."penalty_fee_late_document as penalty_fee_late_document, "
            ."claim_fee_document as claim_fee_document, "
            ."fix_document as fix_document, "
            ."recovery_document as recovery_document, "
            ."rent_fee_payment_date as rent_fee_payment_date, "
            ."mail_box_number as mail_box_number, "
            ."admin_number as admin_number, "
            ."contract_details.entry_user_id as entry_user_id, "
            ."contract_details.entry_date as entry_date, "
            ."contract_details.update_user_id as update_user_id, "
            ."contract_details.update_date as update_date "
            ."from "
            ."contract_details "
            ."left join contract_detail_progress "
            ."on contract_detail_progress.contract_detail_progress_id = contract_details.contract_detail_progress_id "
            ."where contract_details.create_user_id = $session_id ";
            
            // where句
            $where = "";

            // フリーワード
            if($free_word !== null){

                $where = "and ";
                $where = $where ."ifnull(contract_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(real_estate_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(contract_tel,'') like '%$free_word%'";
                $where = $where ."or ifnull(admin_number,'') like '%$free_word%'";
                $where = $where ."or ifnull(m_share_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(m_own_name,'') like '%$free_word%'";

            };

            // 進捗状況
            if($contract_detail_progress_id !== null){
                    
                    $where = "and ";
                    $where = $where ."contract_details.contract_detail_progress_id = '$contract_detail_progress_id' ";

            }

            // 始期終期
            if($start_date !== null && $end_date !== null){

                Log::debug('始期・終期選択の処理');

                $where = "and ";
                $where = $where ."(contract_details.contract_start_date >= '$start_date') "
                ."and "
                ."(contract_details.contract_start_date <= '$end_date')";
            
            }else{
                
                // 始期
                if($start_date !== null){

                    $where = "and ";
                    $where = $where ."contract_details.contract_start_date >= '$start_date' ";

                }

                // 終期
                if($end_date !== null){

                    $where = "and ";
                    $where = $where ."contract_details.contract_start_date <= '$end_date' ";

                }

            }
        
            // order by句
            $order_by = "order by contract_detail_id ";

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
     *  新規(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backContractNewInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {

            $session_id = $request->session()->get('create_user_id');

            // 契約情報一覧(新規:空データ)
            $contract_info = $this->getNewList();
            $contract_list = $contract_info;

            $clone_flag = 'false';

            // 契約詳細一覧
            $common = new Common();

            // 契約進捗状況
            $contract_detail_progress_list = $common->getContractDetailProgress();

            // 間取タイプ
            $room_layout_list = $common->getRoomLayout();

            // 構造
            $real_estate_structure_list = $common->getRealEstateStructure();

            // 免許一覧
            $real_estate_structure_list = $common->getRealEstateStructure();

            // 保証協会
            $guaranty_association_list = $common->getGuarantyAssociation($request);

            // 法務局
            $legal_place_list = $common->getLegalPlace($request);

            // 商号
            $company_license_list = $common->getCompanyLicense($request);

            // 宅地建物取引士
            $user_license_list = $common->getUserLicense($request);

            // 有無
            $need_list = $common->getNeeds($request);

            // 抵当権・根抵当権設定有
            $regi_mortgages_list = $common->getRegiMortgages($request);

            // 水道
            $water_list = $common->getWater($request);

            // ガス
            $gas_list = $common->getGas($request);

            // 排水
            $waste_water_list = $common->getWasteWater($request);

            // 専用・共同
            $exclusive_type_list = $common->getExclusiveType($request);
            
            // 用途制限
            $limit_uses_list = $common->getLimitUse($request);

            // 日割・月割
            $cancel_fee_count_list = $common->getCancelFeeCount($request);

            // 取引形態
            $trade_type_list = $common->getTradeType($request);

            // 銀行種別
            $bank_type_list = $common->getBankType($request);

            // 特約事項
            $special_contract_list = $common->getSpecialContract($request);

            // 同居人
            $contract_housemate_list = $common->getContractHousemate($request);

            // 区域外、区域内
            $inside_and_outside_area_list = $common->getInsideAndOutsideArea($request);

            // 保証会社更新期間
            $guarantee_update_spans_list = $common->getGuaranteeUpdateSpan($request);

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backContractEdit' ,compact('contract_list' ,'contract_detail_progress_list' ,'room_layout_list' ,'real_estate_structure_list' ,'guaranty_association_list' ,'legal_place_list' ,'company_license_list' ,'user_license_list' ,'need_list' ,'regi_mortgages_list' ,'water_list' ,'gas_list' ,'waste_water_list' ,'exclusive_type_list' ,'limit_uses_list' ,'cancel_fee_count_list' ,'trade_type_list' ,'bank_type_list' ,'special_contract_list' ,'contract_housemate_list' ,'clone_flag' ,'inside_and_outside_area_list' ,'guarantee_update_spans_list'));
    }

    /**
     * 新規(ダミー値取得)
     *
     * @return $ret(空の配列)
     */
    private function getNewList(){
        Log::debug('log_start:'.__FUNCTION__);
        $obj = new \stdClass();

        $obj->contract_detail_id= '';
        $obj->create_user_id= '';
        $obj->contract_detail_progress_id= '';
        $obj->contract_detail_progress_name= '';
        $obj->application_id= '';
        $obj->manager_name= '';
        $obj->company_license_id= '';
        $obj->company_license_representative= '';
        $obj->company_license_address= '';
        $obj->company_license_tel= '';
        $obj->company_license_fax= '';
        $obj->company_license_number= '';
        $obj->company_license_span= '';
        $obj->company_nick_name= '';
        $obj->company_nick_address= '';
        $obj->guaranty_association_name = '';
        $obj->guaranty_association_region_name = '';
        $obj->legal_place_name= '';
        $obj->user_license_id= '';
        $obj->user_license_name= '';
        $obj->user_license_number= '';
        $obj->trade_type_id= '';
        $obj->contract_name= '';
        $obj->contract_ruby= '';
        $obj->contract_date= '';
        $obj->contract_tel= '';
        $obj->real_estate_name= '';
        $obj->real_estate_post_number= '';
        $obj->real_estate_address= '';
        $obj->room_name= '';
        $obj->room_size= '';
        $obj->real_estate_structure_id= '';
        $obj->real_estate_floor= '';
        $obj->room_layout_name= '';
        $obj->room_layout_id= '';
        $obj->real_estate_age= '';
        $obj->owner_name= '';
        $obj->owner_post_number= '';
        $obj->owner_address= '';
        $obj->bank_id= '';
        $obj->bank_name= '';
        $obj->bank_branch_name= '';
        $obj->bank_type_id= '';
        $obj->bank_number= '';
        $obj->bank_account_name= '';
        $obj->m_share_name= '';
        $obj->m_share_post_number= '';
        $obj->m_share_address= '';
        $obj->m_share_tel= '';
        $obj->m_own_name= '';
        $obj->m_own_post_number= '';
        $obj->m_own_address= '';
        $obj->m_own_tel= '';
        $obj->report_asbestos= '';
        $obj->report_earthquake= '';
        $obj->land_disaster_prevention_area= '';
        $obj->tsunami_disaster_alert_area= '';
        $obj->sediment_disaster_area= '';
        $obj->regi_name= '';
        $obj->regi_right= '';
        $obj->regi_mortgage= '';
        $obj->regi_difference_owner= '';
        $obj->completion_date= '';
        $obj->hazard_map= '';
        $obj->warning_flood= '';
        $obj->warning_storm_surge= '';
        $obj->warning_rain_water= '';
        $obj->security_fee= '';
        $obj->security_fee= '';
        $obj->rent_fee= '';
        $obj->service_fee= '';
        $obj->water_fee= '';
        $obj->ohter_fee= '';
        $obj->total_fee= '';
        $obj->penalty_fee= '';
        $obj->bicycle_fee= '';
        $obj->car_fee= '';
        $obj->car_deposit_fee= '';
        $obj->fire_insurance_fee= '';
        $obj->fire_insurance_span= '';
        $obj->guarantee_fee= '';
        $obj->guarantee_update_span= '';
        $obj->guarantee_update_fee= '';
        $obj->support_fee= '';
        $obj->disinfect_fee= '';
        $obj->other_name1= '';
        $obj->other_fee1= '';
        $obj->other_name2= '';
        $obj->other_fee2= '';
        $obj->broker_fee= '';
        $obj->car_broker_fee= '';
        $obj->today_account_fee_date= '';
        $obj->today_account_fee= '';
        $obj->payment_date= '';
        $obj->keep_account_fee= '';
        $obj->introduction_fee= '';
        $obj->water= '';
        $obj->water_type_name= '';
        $obj->electricity= '';
        $obj->electricity_type_name= '';
        $obj->gas= '';
        $obj->gas_type_name= '';
        $obj->waste_water= '';
        $obj->waste_water_name= '';
        $obj->kitchen= '';
        $obj->kitchen_exclusive_type_id= '';
        $obj->cooking_stove= '';
        $obj->cooking_exclusive_type_id= '';
        $obj->bath= '';
        $obj->bath_exclusive_type_id= '';
        $obj->toilet= '';
        $obj->toilet_exclusive_type_id= '';
        $obj->water_heater= '';
        $obj->water_heater_exclusive_type_id= '';
        $obj->air_conditioner= '';
        $obj->air_conditioner_exclusive_type_name= '';
        $obj->elevator= '';
        $obj->elevator_exclusive_type_name = '';
        $obj->contract_start_date= '';
        $obj->contract_end_date= '';
        $obj->contract_update_span= '';
        $obj->contract_update_item= '';
        $obj->security_settle_detail= '';
        $obj->key_money_settle_detail= '';
        $obj->limit_use= '';
        $obj->limit_type= '';
        $obj->announce_cancel_date= '';
        $obj->soon_cancel_date= '';
        $obj->cancel_fee_count= '';
        $obj->cancel_contract_document= '';
        $obj->remove_contract_document= '';
        $obj->remove_contract_document= '';
        $obj->penalty_fee_late_document= '';
        $obj->claim_fee_document= '';
        $obj->fix_document = '';
        $obj->recovery_document= '';
        $obj->rent_fee_payment_date= '';
        $obj->mail_box_number= '';
        $obj->special_contract_detail_name= '';
        $obj->guarantor_need_id= '';
        $obj->guarantor_max_payment= '';
        $obj->daily_calculation= '';
        $obj->admin_number= '';
        $obj->admin_user_flag= '';
        $obj->admin_number= '';
        $obj->entry_user_id= '';
        $obj->entry_date= '';
        $obj->update_user_id= '';
        $obj->update_date= '';

        // id
        $obj->special_contract_detail_id = '';

        Log::debug('log_end:'.__FUNCTION__);
        return $obj;
    }

    /**
     * 商号コンボボックス変更時の値取得
     *
     * @return void
     */
    public function backChangeCompanyLicense(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {
            $response = [];
            
            // 契約詳細一覧
            $common = new Common();

            // 商号
            $company_license_list = $common->getCompanyLicense($request);
            $response['company_license_list'] = $company_license_list[0];

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 宅建取引士変更時の値取得
     *
     * @return void
     */
    public function backChangeUserLicense(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {
            $response = [];
            
            // 契約詳細一覧
            $common = new Common();

            // 商号
            $user_license_list = $common->getUserLicense($request);
            $response['user_license_list'] = $user_license_list[0];

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 銀行一覧取得(モーダル初期表示・検索)
     *
     * @param Request $request
     * @return void
     */
    public function backSearchBank(Request $request){
        
        Log::debug('log_start:'.__FUNCTION__);
        
        // return初期値
        $response = [];

        $common = new Common();

        $bank_list = $common->getBankList($request);

        // js側での判定のステータス(true:OK/false:NG)
        $response["bank_list"] = $bank_list;

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     *  編集(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backContractEditInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {
            $session_id = $request->session()->get('create_user_id');

            /**
             * 複製フラグ
             * true = 複製(blade側で契約詳細idを空白)
             * false = 編集(blade側で契約詳細idを設定)
             */
            $clone_flag = $request->input('clone_flag');
            Log::debug('clone_flag:' .$clone_flag);

            // 契約詳細一覧
            $contract_info = $this->getEditList($request);
            $contract_list = $contract_info[0];

            // 配列デバック
            $arrString = print_r($contract_list , true);
            Log::debug('contract_list:'.$arrString);

            // 複製登録の場合、value値を初期化
            // 複製登録の場合、空白にする
            if($clone_flag == 'true'){

                // 契約詳細id
                $contract_list->contract_detail_id= '';

                // 申込id
                $contract_list->application_id= '';

                // 特約id
                $contract_list->special_contract_detail_id= '';

                // 契約者名
                $contract_list->contract_name= '';

                // 契約者フリガナ
                $contract_list->contract_ruby= '';

                // 契約者生年月日
                $contract_list->contract_date= '';

                // 契約者tel
                $contract_list->contract_tel= '';

            }
            
            // dd($contract_list);

            // 契約詳細一覧
            $common = new Common();

            // 契約進捗状況
            $contract_detail_progress_list = $common->getContractDetailProgress();

            // 間取タイプ
            $room_layout_list = $common->getRoomLayout();

            // 構造
            $real_estate_structure_list = $common->getRealEstateStructure();

            // 免許一覧
            $real_estate_structure_list = $common->getRealEstateStructure();

            // 保証協会
            $guaranty_association_list = $common->getGuarantyAssociation($request);

            // 法務局
            $legal_place_list = $common->getLegalPlace($request);

            // 商号
            $company_license_list = $common->getCompanyLicense($request);

            // 宅地建物取引士
            $user_license_list = $common->getUserLicense($request);

            // 有無
            $need_list = $common->getNeeds($request);

            // 抵当権・根抵当権設定有
            $regi_mortgages_list = $common->getRegiMortgages($request);

            // 水道
            $water_list = $common->getWater($request);

            // ガス
            $gas_list = $common->getGas($request);

            // 排水
            $waste_water_list = $common->getWasteWater($request);

            // 専用・共同
            $exclusive_type_list = $common->getExclusiveType($request);
            
            // 用途制限
            $limit_uses_list = $common->getLimitUse($request);

            // 日割・月割
            $cancel_fee_count_list = $common->getCancelFeeCount($request);

            // 取引形態
            $trade_type_list = $common->getTradeType($request);

            // 銀行種別
            $bank_type_list = $common->getBankType($request);

            // 特約事項
            $special_contract_list = $common->getSpecialContract($request);

            // 同居人
            $contract_housemate_list = $common->getContractHousemate($request);

            // 区域外、区域内
            $inside_and_outside_area_list = $common->getInsideAndOutsideArea($request);

            // 保証会社更新期間
            $guarantee_update_spans_list = $common->getGuaranteeUpdateSpan($request);

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backContractEdit' ,compact('contract_list' ,'contract_detail_progress_list' ,'room_layout_list' ,'real_estate_structure_list' ,'guaranty_association_list' ,'legal_place_list' ,'company_license_list' ,'user_license_list' ,'need_list' ,'regi_mortgages_list' ,'water_list' ,'gas_list' ,'waste_water_list' ,'exclusive_type_list' ,'limit_uses_list' ,'cancel_fee_count_list' ,'trade_type_list' ,'bank_type_list' ,'special_contract_list' ,'contract_housemate_list' ,'clone_flag' ,'inside_and_outside_area_list' ,'guarantee_update_spans_list'));
    }

    /**
     * 編集(表示:sql)
     *
     * @return void
     */
    private function getEditList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            // 値設定
            $contract_detail_id = $request->input('contract_detail_id');

            // sql
            $str = "select "
            ."contract_details.contract_detail_id as contract_detail_id, "
            ."contract_details.create_user_id as create_user_id, "
            ."contract_details.contract_detail_progress_id as contract_detail_progress_id, "
            ."contract_detail_progress.contract_detail_progress_name as contract_detail_progress_name, "
            ."application_id as application_id, "
            ."contract_details.company_license_id as company_license_id, "
            ."company_licenses.company_license_name as company_license_name, "
            ."company_licenses.company_license_representative  as company_license_representative, "
            ."company_licenses.company_license_address as company_license_address, "
            ."company_licenses.company_license_tel as company_license_tel, "
            ."company_licenses.company_license_fax as company_license_fax, "
            ."company_licenses.company_license_number as company_license_number, "
            ."company_licenses.company_license_span as company_license_span, "
            ."company_licenses.company_nick_name as company_nick_name, "
            ."company_licenses.company_nick_address as company_nick_address, "
            ."company_licenses.legal_place_id as legal_place_id, "
            ."legal_places.legal_place_name as legal_place_name, "
            ."company_licenses.guaranty_association_id as guaranty_association_id, "
            ."guaranty_associations.guaranty_association_name as guaranty_association_name, "
            ."company_licenses.guaranty_association_id as guaranty_association_region_id, "
            ."guaranty_association_region.guaranty_association_name as guaranty_association_region_name, "
            ."manager_name as manager_name, "
            ."contract_details.user_license_id as user_license_id, "
            ."user_license_name as user_license_name, "
            ."user_license_number as user_license_number, "
            ."trade_type_id as trade_type_id, "
            ."contract_name as contract_name, "
            ."contract_ruby as contract_ruby, "
            ."contract_date as contract_date, "
            ."contract_tel as contract_tel, "
            ."real_estate_name as real_estate_name, "
            ."real_estate_post_number as real_estate_post_number, "
            ."real_estate_address as real_estate_address, "
            ."room_name as room_name, "
            ."room_size as room_size, "
            ."real_estate_structure_id as real_estate_structure_id, "
            ."real_estate_floor as real_estate_floor, "
            ."room_layout_name as room_layout_name, "
            ."room_layout_id as room_layout_id, "
            ."real_estate_age as real_estate_age, "
            ."owner_name as owner_name, "
            ."owner_post_number as owner_post_number, "
            ."owner_address as owner_address, "
            ."bank_id as bank_id, "
            ."bank_name as bank_name, "
            ."bank_branch_name as bank_branch_name, "
            ."bank_type_id as bank_type_id, "
            ."bank_number as bank_number, "
            ."bank_account_name as bank_account_name, "
            ."m_share_name as m_share_name, "
            ."m_share_post_number as m_share_post_number, "
            ."m_share_address as m_share_address, "
            ."m_share_tel as m_share_tel, "
            ."m_own_name as m_own_name, "
            ."m_own_post_number as m_own_post_number, "
            ."m_own_address as m_own_address, "
            ."m_own_tel as m_own_tel, "
            ."report_asbestos as report_asbestos, "
            ."report_earthquake as report_earthquake, "
            ."land_disaster_prevention_area as land_disaster_prevention_area, "
            ."tsunami_disaster_alert_area as tsunami_disaster_alert_area, "
            ."sediment_disaster_area as sediment_disaster_area, "
            ."regi_name as regi_name, "
            ."regi_right as regi_right, "
            ."regi_mortgage as regi_mortgage, "
            ."regi_difference_owner as regi_difference_owner, "
            ."completion_date as completion_date, "
            ."hazard_map as hazard_map, "
            ."warning_flood as warning_flood, "
            ."warning_storm_surge as warning_storm_surge, "
            ."warning_rain_water as warning_rain_water, "
            ."security_fee as security_fee, "
            ."rent_fee as rent_fee, "
            ."service_fee as service_fee, "
            ."water_fee as water_fee, "
            ."ohter_fee as ohter_fee, "
            ."bicycle_fee as bicycle_fee, "
            ."total_fee as total_fee, "
            ."car_fee as car_fee, "
            ."car_deposit_fee as car_deposit_fee, "
            ."fire_insurance_fee as fire_insurance_fee, "
            ."fire_insurance_span as fire_insurance_span, "
            ."guarantee_fee as guarantee_fee, "
            ."guarantee_update_span as guarantee_update_span, "
            ."guarantee_update_fee as guarantee_update_fee, "
            ."support_fee as support_fee, "
            ."disinfect_fee as disinfect_fee, "
            ."other_name1 as other_name1, "
            ."other_fee1 as other_fee1, "
            ."other_name2 as other_name2, "
            ."other_fee2 as other_fee2, "
            ."broker_fee as broker_fee, "
            ."car_broker_fee as car_broker_fee, "
            ."today_account_fee_date as today_account_fee_date, "
            ."today_account_fee as today_account_fee, "
            ."payment_date as payment_date, "
            ."keep_account_fee as keep_account_fee, "
            ."introduction_fee as introduction_fee, "
            ."water as water, "
            ."water_type_name as water_type_name, "
            ."electricity as electricity, "
            ."electricity_type_name as electricity_type_name, "
            ."gas as gas, "
            ."gas_type_name as gas_type_name, "
            ."waste_water as waste_water, "
            ."waste_water_name as waste_water_name, "
            ."kitchen as kitchen, "
            ."kitchen_exclusive_type_id as kitchen_exclusive_type_id, "
            ."cooking_stove as cooking_stove, "
            ."cooking_exclusive_type_id as cooking_exclusive_type_id, "
            ."bath as bath, "
            ."bath_exclusive_type_id as bath_exclusive_type_id, "
            ."toilet as toilet, "
            ."toilet_exclusive_type_id as toilet_exclusive_type_id, "
            ."water_heater as water_heater, "
            ."water_heater_exclusive_type_id as water_heater_exclusive_type_id, "
            ."air_conditioner as air_conditioner, "
            ."air_conditioner_exclusive_type_name as air_conditioner_exclusive_type_name, "
            ."elevator as elevator, "
            ."elevator_type_name as elevator_exclusive_type_name, "
            ."contract_start_date as contract_start_date, "
            ."contract_end_date as contract_end_date, "
            ."contract_update_span as contract_update_span, "
            ."contract_update_item as contract_update_item, "
            ."daily_calculation as daily_calculation, "
            ."security_settle_detail as security_settle_detail, "
            ."key_money_settle_detail as key_money_settle_detail, "
            ."limit_use as limit_use, "
            ."limit_type as limit_type, "
            ."announce_cancel_date as announce_cancel_date, "
            ."soon_cancel_date as soon_cancel_date, "
            ."cancel_fee_count as cancel_fee_count, "
            ."cancel_contract_document as cancel_contract_document, "
            ."remove_contract_document as remove_contract_document, "
            ."penalty_fee as penalty_fee, "
            ."penalty_fee_late_document as penalty_fee_late_document, "
            ."claim_fee_document as claim_fee_document, "
            ."fix_document as fix_document, "
            ."recovery_document as recovery_document, "
            ."rent_fee_payment_date as rent_fee_payment_date, "
            ."mail_box_number as mail_box_number, "
            ."special_contract_details.special_contract_detail_id as special_contract_detail_id, "
            ."special_contract_details.special_contract_detail_name as special_contract_detail_name, "
            ."guarantor_need_id as guarantor_need_id, "
            ."guarantor_max_payment as guarantor_max_payment, "
            ."admin_number as admin_number, "
            ."contract_details.entry_user_id as entry_user_id, "
            ."contract_details.entry_date as entry_date, "
            ."contract_details.update_user_id as update_user_id, "
            ."contract_details.update_date as update_date, "
            ."create_users.admin_user_flag as admin_user_flag "
            ."from "
            ."contract_details "
            ."left join contract_detail_progress "
            ."on contract_detail_progress.contract_detail_progress_id = contract_details.contract_detail_id "
            ."left join company_licenses "
            ."on company_licenses.company_license_id = contract_details.company_license_id "
            ."left join legal_places "
            ."on legal_places.legal_place_id = company_licenses.legal_place_id "
            ."left join guaranty_associations "
            ."on guaranty_associations.guaranty_association_id = company_licenses.guaranty_association_id "
            ."left join guaranty_associations as guaranty_association_region on "
            ."guaranty_association_region.guaranty_association_id = company_licenses.guaranty_association_region_id "
            ."left join special_contract_details "
            ."on special_contract_details.contract_detail_id = contract_details.contract_detail_id "
            ."left join create_users "
            ."on create_users.create_user_id = contract_details.create_user_id "
            ."where "
            ."contract_details.contract_detail_id = $contract_detail_id ";

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
    public function backContractEditEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // 一時登録フラグ
        $temporarily_flag = $request->input('temporarily_flag');
        
        // return初期値
        $response = [];

        // temporarily_flag:true=バリデーション無し
        if($temporarily_flag !== 'true'){

            Log::debug('temporarily_flag=falseの処理');

            // バリデーション:OK=true NG=false
            $response = $this->editValidation($request);
        
        // temporarily_flag:false=バリデーション有り
        }else{

            Log::debug('temporarily_flag=trueの処理');

            $response["status"] = true;

        }

        // バリデーションで該当した場合、status=false
        if($response["status"] == false){

            Log::debug('validator_status:falseのif文通過');
            return response()->json($response);

        }

        /**
         * id=無:insert
         * id=有:update
         */
        $contract_detail_id = $request->input('contract_detail_id');

        // 新規登録
        if($request->input('contract_detail_id') == ""){

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

        // 契約id
        $contract_detail_id = $request->input('contract_detail_id');

        // returnの出力値
        $response = [];

        // 初期値
        $response["status"] = true;

        /**
         * rules
         */
        // 物件概要
        $rules = [];
        $rules['real_estate_name'] = "required|max:100";
        $rules['room_name'] = "required|max:10";
        $rules['room_size'] = "required|max:10";
        $rules['real_estate_post_number'] = "required|zip";
        $rules['real_estate_address'] = "required|max:100";
        $rules['real_estate_floor'] = "required|integer";
        $rules['real_estate_age'] = "required|date";
        $rules['real_estate_structure_id'] = "required|integer";
        $rules['room_layout_name'] = "required|integer";
        $rules['room_layout_id'] = "required|integer";
        $rules['owner_name'] = "required|max:100";
        $rules['owner_post_number'] = "required|zip";
        $rules['owner_address'] = "required|max:100";
        $rules['m_share_name'] = "required|max:100";
        $rules['m_share_post_number'] = "required|zip";
        $rules['m_share_address'] = "required|max:100";
        $rules['m_share_tel'] = "required|jptel";
        $rules['m_own_name'] = "required|max:100";
        $rules['m_own_post_number'] = "required|zip";
        $rules['m_own_address'] = "required|max:100";
        $rules['m_own_tel'] = "required|jptel";

        /**
         * 契約者・同居人
         */
        $rules['contract_name'] = "required|max:100";
        $rules['contract_ruby'] = "required|max:200";
        $rules['contract_date'] = "required|date";
        $rules['contract_tel'] = "required|jptel";

        /**
         * 商号
         */
        $rules['user_license_number'] = "required|max:30";
        $rules['manager_name'] = "required|max:50";

        /**
         * 登記事項
         */
        $rules['regi_name'] = "required|max:100";
        $rules['regi_difference_owner'] = "nullable|max:100";
        $rules['completion_date'] = "nullable|max:100";

        /**
         * 授受される金額
         */
        $rules['security_fee'] = "required|integer";
        $rules['key_fee'] = "required|integer";
        $rules['rent_fee'] = "required|integer";
        $rules['service_fee'] = "required|integer";
        $rules['water_fee'] = "required|integer";
        $rules['ohter_fee'] = "required|integer";
        $rules['bicycle_fee'] = "required|integer";
        $rules['total_fee'] = "required|integer";
        $rules['car_deposit_fee'] = "nullable|integer";
        $rules['fire_insurance_fee'] = "nullable|integer";
        $rules['fire_insurance_span'] = "nullable|integer";
        $rules['guarantee_fee'] = "nullable|integer";
        $rules['guarantee_update_fee'] = "nullable|integer";
        $rules['support_fee'] = "nullable|integer";
        $rules['disinfect_fee'] = "nullable|integer";
        $rules['other_name1'] = "nullable|max:50";
        $rules['other_fee1'] = "nullable|integer";
        $rules['other_name2'] = "nullable|max:50";
        $rules['other_fee2'] = "nullable|integer";
        $rules['car_broker_fee'] = "nullable|integer";
        $rules['broker_fee'] = "nullable|integer";
        $rules['today_account_fee_date'] = "nullable|date";
        $rules['today_account_fee'] = "nullable|integer";
        $rules['payment_date'] = "nullable|max:50";

        /**
         * 設備
         */
        $rules['water_type_name'] = "nullable|max:100";
        $rules['gas_type_name'] = "nullable|max:100";
        $rules['electricity'] = "required|max:20";
        $rules['electricity_type_name'] = "nullable|max:100";
        $rules['waste_water_name'] = "nullable|max:100";
        $rules['air_conditioner_exclusive_type_name'] = "nullable|max:10";
        $rules['elevator_exclusive_type_name'] = "nullable|max:10";

        /**
         * 契約期間
         */
        $rules['contract_start_date'] = "required|date";
        $rules['contract_end_date'] = "required|date";
        $rules['contract_update_span'] = "required|integer";
        $rules['contract_update_item'] = "nullable|max:100";

        /**
         * 用途の制限
         */
        $rules['limit_type_name'] = "required|max:50";

        /**
         * 契約の解約及び解除
         */
        $rules['announce_cancel_date'] = "required|integer";
        $rules['soon_cancel_date'] = "required|integer";
        $rules['cancel_contract_document'] = "required|max:50";
        $rules['remove_contract_document'] = "required|max:50";

        /**
         * 損害賠償・違約金・免責
         */
        $rules['penalty_fee'] = "nullable|max:200";
        $rules['penalty_fee_late_document'] = "required|max:50";
        $rules['claim_fee_document'] = "required|max:50";
        $rules['fix_document'] = "required|max:50";
        $rules['recovery_document'] = "required|max:50";

        /**
         * 家賃振込先
         */
        $rules['bank_name'] = "required|max:50";
        $rules['bank_branch_name'] = "required|max:50";
        $rules['bank_number'] = "required|max:50";
        $rules['bank_account_name'] = "required|max:50";
        $rules['rent_fee_payment_date'] = "required|max:5";

        /**
         * その他
         */
        $rules['mail_box_number'] = "nullable|max:50";

        /**
         * 管理番号
         * contract_detail_idがある場合insert、バリデーションを実行
         * contract_detail_idがない場合update、バリデーション
         */
        // $rules['admin_number'] = "nullable|adminnumberdb";

        /**
         * messages
         */
        /**
         * 物件概要
         */
        $messages = [];

        // 管理番号
        $messages['admin_number.adminnumberdb'] = "管理番号が重複しています。";
        
        // 物件名
        $messages['real_estate_name.required'] = "物件名は必須です。";
        $messages['real_estate_name.max'] = "物件名の文字数が超過しています。";
        // 号室
        $messages['room_name.required'] = "号室は必須です。";
        $messages['room_name.max'] = "号室の文字数が超過しています。";
        // 契約面積
        $messages['room_size.required'] = "契約面積は必須です。";
        $messages['room_size.max'] = "契約面積が超過しています。";
        // 郵便番号
        $messages['real_estate_post_number.required'] = "郵便番号は必須です。";
        $messages['real_estate_post_number.zip'] = "郵便番号の形式が不正です。";
        // 住所
        $messages['real_estate_address.required'] = "住所は必須です。";
        $messages['real_estate_address.max'] = "住所の文字数が超過しています。";
        // 階数
        $messages['real_estate_floor.required'] = "地上階数は必須です。";
        $messages['real_estate_floor.integer'] = "地上階数の形式が不正です。";
        // 築年月日
        $messages['real_estate_age.required'] = "築年月日は必須です。";
        $messages['real_estate_age.date'] = "築年月日の形式が不正です。";
        // 構造
        $messages['real_estate_structure_id.required'] = "構造は必須いです。";
        $messages['real_estate_structure_id.integer'] = "構造の形式が不正です。";
        // 間取数
        $messages['room_layout_name.required'] = "間取数は必須です。";
        $messages['room_layout_name.integer'] = "間取数の形式が不正です。";
        // 間取
        $messages['room_layout_id.required'] = "間取種別は必須です。";
        $messages['room_layout_id.integer'] = "間取種別の形式が不正です。";
        // 家主名
        $messages['owner_name.required'] = "家主名は必須です。";
        $messages['owner_name.max'] = "家主名の文字数が超過しています。";
        // 郵便番号
        $messages['owner_post_number.required'] = "郵便番号は必須です。";
        $messages['owner_post_number.zip'] = "郵便番号の形式が不正です。";
        // 住所
        $messages['owner_address.required'] = "住所は必須です。";
        $messages['owner_address.max'] = "住所の文字数が超過しています。";
        // 管理の委託先(共有)
        $messages['m_share_name.required'] = "管理の委託先(共有)は必須です。";
        $messages['m_share_name.max'] = "管理の委託先(共有)の文字数が超過しています。";
        // 郵便番号
        $messages['m_share_post_number.required'] = "郵便番号は必須です。";
        $messages['m_share_post_number.zip'] = "郵便番号の形式が不正です。";
        // 住所
        $messages['m_share_address.required'] = "住所は必須です。";
        $messages['m_share_address.max'] = "住所の文字数が超過しています。";
        // Tel
        $messages['m_share_tel.required'] = "Telは必須です。";
        $messages['m_share_tel.jptel'] = "Telの形式が不正です。";
        // 管理の委託先(専有)
        $messages['m_own_name.required'] = "管理の委託先(専有)は必須です。";
        $messages['m_own_name.max'] = "管理の委託先(専有)の文字数が超過しています。";
        // 郵便番号
        $messages['m_own_post_number.required'] = "郵便番号は必須です。";
        $messages['m_own_post_number.zip'] = "郵便番号の形式が不正です。";
        // 住所
        $messages['m_own_address.required'] = "住所は必須です。";
        $messages['m_own_address.max'] = "住所の文字数が超過しています。";
        // Tel
        $messages['m_own_tel.required'] = "Telは必須です。";
        $messages['m_own_tel.jptel'] = "Telの形式が不正です。";

        /**
         * 契約者・同居人名
         */
        // 契約者名
        $messages['contract_name.required'] = "契約者は必須です。";
        $messages['contract_name.max'] = "契約者の文字数が超過しています。";
        // 契約者フリガナ
        $messages['contract_ruby.required'] = "フリガナは必須です。";
        $messages['contract_ruby.max'] = "フリガナの文字数が超過しています。";
        // 生年月日
        $messages['contract_date.required'] = "生年月日は必須です。";
        $messages['contract_date.date'] = "生年月日の文字数が超過しています。";
        // tel
        $messages['contract_tel.required'] = "Telは必須です。";
        $messages['contract_tel.jptel'] = "Telの形式が不正です。";

        /**
         * 商号
         */

        // 登録番号
        $messages['user_license_number.max'] = "登録番号は必須です。";
        $messages['user_license_number.max'] = "登録番号の文字数が超過しています。";

        // 担当者
        $messages['manager_name.required'] = "担当者は必須です。";
        $messages['manager_name.max'] = "担当者の文字数が超過しています。";

        /**
         * 登記事項
         */
        // 所有権
        $messages['regi_name.max'] = "所有権の文字数が超過しています。";
        // 所有者と貸主が違う場合
        $messages['regi_difference_owner.max'] = "文字数が超過しています。";

        /**
         * 授受される金額
         */
        // 敷金
        $messages['security_fee.required'] = "敷金は必須です。";
        $messages['security_fee.integer'] = "敷金の値が不正です。";
       // 礼金
        $messages['key_fee.required'] = "礼金は必須です。";
        $messages['key_fee.integer'] = "礼金の値が不正です。";
       // 家賃
        $messages['rent_fee.required'] = "家賃は必須です。";
        $messages['rent_fee.integer'] = "家賃の値が不正です。";
       // 共益費
        $messages['service_fee.required'] = "共益費は必須です。";
        $messages['service_fee.integer'] = "共益費の値が不正です。";
        // 水道代
        $messages['water_fee.required'] = "水道代は必須です。";
        $messages['water_fee.integer'] = "水道代の値が不正です。";
        // その他
        $messages['ohter_fee.required'] = "その他は必須です。";
        $messages['ohter_fee.integer'] = "その他の値が不正です。";
        // 駐車場保証金
        $messages['bicycle_fee.required'] = "駐輪代は必須です。";
        $messages['bicycle_fee.integer'] = "駐輪代の値が不正です。";
        // 合計
        $messages['total_fee.required'] = "賃料合計は必須です。";
        $messages['total_fee.integer'] = "賃料合計の値が不正です。";
        // 駐車場敷金
        $messages['car_deposit_fee.integer'] = "駐車場保証金の値が不正です。";
        // 火災保険料
        $messages['fire_insurance_fee.integer'] = "火災保険料の値が不正です。";
        // 火災保険料更新期間
        $messages['fire_insurance_span.integer'] = "火災保険料の値が不正です。";
        // 保証会社費用
        $messages['guarantee_fee.integer'] = "保証会社費用の値が不正です。";
        // 更新期間
        $messages['guarantee_update_span.integer'] = "保証会社更新期間の値が不正です。";
        // 保証会社更新料
        $messages['guarantee_update_fee.integer'] = "保証会社更新料の値が不正です。";
        // 安心サポート
        $messages['support_fee.integer'] = "安心サポートの値が不正です。";
        // 防虫代
        $messages['disinfect_fee.integer'] = "防虫・抗菌代の値が不正です。";
        // その他項目①
        $messages['other_name1.max'] = "その他項目①の文字数が超過しています。";
        // その他費用①
        $messages['other_fee1.integer'] = "その他費用①の形式が不正です。";
        // その他項目②
        $messages['other_name2.max'] = "その他項目②の文字数が超過しています。";
        // その他費用②
        $messages['other_fee2.integer'] = "その他費用②の形式が不正です。";
        // 仲介手数料(駐車場)
        $messages['car_broker_fee.integer'] = "仲介手数料（駐車場）の形式が不正です。";
        // 仲介手数料
        $messages['broker_fee.integer'] = "仲介手数料の形式が不正です。";
        // 預り金日付
        $messages['today_account_fee_date.date'] = "預り金の日付の値が不正です。";
        // 預り金
        $messages['today_account_fee.integer'] = "預り金の値が不正です。";
        // 決済予定日
        $messages['payment_date.max'] = "決済予定日の文字数が超過しています。";

        /**
         * 設備
         */
        // 飲用水備考
        $messages['water_type_name.max'] = "備考の文字数が超過しています。";
        // 電気
        $messages['electricity.max'] = "電力会社の文字数が超過しています。";
        // 電力会社備考
        $messages['electricity_type_name.max'] = "備考の文字数が超過しています。";
        // 排水
        $messages['waste_water_name.max'] = "排水の文字数が超過しています。";
        // 冷暖房設備
        $messages['air_conditioner_exclusive_type_name.max'] = "台数の文字数が超過しています。";
        // エレベーター
        $messages['elevator_exclusive_type_name.max'] = "台数の文字数が超過しています。";

        /**
         * 契約期間
         */
        // 契約期間
        $messages['contract_start_date.required'] = "契約期間は必須です。";
        $messages['contract_start_date.date'] = "契約期間の形式が不正です。";
        $messages['contract_end_date.required'] = "契約期間は必須です。";
        $messages['contract_end_date.date'] = "契約期間の形式が不正です。";

        // 更新に必要な事項
        $messages['contract_update_item.max'] = "更新に必要な事項の文字数が超過しています。";

        /**
         * 用途の制限
         */
        // 利用の制限
        $messages['limit_type_name.required'] = "利用の制限は必須です。";
        $messages['limit_type_name.max'] = "利用の制限の文字数が超過しています。";
        
        /**
         * 契約の解約及び解除
         */
        // 解約予告
        $messages['announce_cancel_date.required'] = "解約予告は必須です。";
        $messages['announce_cancel_date.integer'] = "解約予告の形式が不正です。";
        // 即時解約
        $messages['soon_cancel_date.required'] = "即時解約は必須です。";
        $messages['soon_cancel_date.integer'] = "即時解約の形式が不正です。";
        // 契約の解除
        $messages['cancel_contract_document.required'] = "契約の解除は必須です。";
        $messages['cancel_contract_document.max'] = "契約の解除の文字数が超過しています。";
        // 契約の消滅
        $messages['remove_contract_document.required'] = "契約の消滅は必須です。";
        $messages['remove_contract_document.max'] = "契約の消滅の文字数が超過しています。";

        /**
         * 損害賠償・違約金・免責
         */
        // 違約金
        $messages['penalty_fee.max'] = "違約金の文字数が超過しています。";
        // 支払遅延損害金
        $messages['penalty_fee_late_document.required'] = "支払遅延損害金は必須です。";
        $messages['penalty_fee_late_document.max'] = "支払遅延損害金の文字数が超過しています。";
        // 損害賠償
        $messages['claim_fee_document.required'] = "損害賠償は必須です。";
        $messages['claim_fee_document.max'] = "損害賠償の文字数が超過しています。";
        // 入居中の修繕に関する事項
        $messages['fix_document.required'] = "入居中の修繕に関する事項は必須です。";
        $messages['fix_document.max'] = "入居中の修繕に関する事項の文字数が超過しています。";
        // 明渡し及び原状回復
        $messages['recovery_document.required'] = "明渡し及び原状回復は必須です。";
        $messages['recovery_document.max'] = "明渡し及び原状回復の文字数が超過しています。";

        /**
         * 家賃振込先
         */
        // 銀行名
        $messages['bank_name.required'] = "銀行名は必須です。";
        $messages['bank_name.max'] = "銀行名の文字数が超過しています。";
        // 支店名
        $messages['bank_branch_name.required'] = "支店名は必須です。";
        $messages['bank_branch_name.max'] = "支店名の文字数が超過しています。";
        // 口座番号
        $messages['bank_number.required'] = "口座番号は必須です。";
        $messages['bank_number.max'] = "口座番号の文字数が超過しています。";
        // 名義人
        $messages['bank_account_name.required'] = "名義人は必須です。";
        $messages['bank_account_name.max'] = "名義人の文字数が超過しています。";
        // 家賃支払日
        $messages['rent_fee_payment_date.required'] = "家賃支払日は必須です。";
        $messages['rent_fee_payment_date.max'] = "家賃支払日の文字数が超過しています。";

        /**
         * その他
         */
        $messages['mail_box_number.max'] = "ポスト番号の文字数宇が超過しています。";

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
            // トランザクション
            DB::beginTransaction();

            // retrun初期値
            $ret = [];
            $ret['status'] = true;

            /**
             * 契約詳細(status:OK=1 NG=0/contract_detail_id:新規登録のid)
             */
            $contract_info = $this->insertContractDetail($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $contract_info['status'];

            // 新規登録のid取得
            $contract_detail_id = $contract_info['contract_detail_id'];

            /**
             * 特約事項(status:OK=1 NG=0)
             */
            $special_contract_info = $this->insertSpecialContract($request ,$contract_detail_id);

            // returnのステータスにtrueを設定
            $ret['status'] = $special_contract_info['status'];

            // コミット
            DB::commit();

        // 例外処理
        } catch (\Throwable $e) {

            // rollback
            DB::rollback();

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
     * 契約詳細(新規登録)
     * 
     * @param Request $request
     * @return $ret['status:1=OK/0=NG']''
     */
    private function insertContractDetail(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');

            // 契約詳細id
            $contract_detail_id = $request->input('contract_detail_id');

            // 申込id
            $application_id = $request->input('application_id');

            /**
             * 進捗状況
             */
            $contract_detail_progress_id = $request->input('contract_detail_progress_id');

            $admin_number = $request->input('admin_number');

            /**
             * 物件概要
             */
            $real_estate_name = $request->input('real_estate_name');

            $room_name = $request->input('room_name');

            $room_size = $request->input('room_size');

            $real_estate_post_number = $request->input('real_estate_post_number');

            $real_estate_address = $request->input('real_estate_address');

            $real_estate_structure_id = $request->input('real_estate_structure_id');

            $real_estate_floor = $request->input('real_estate_floor');

            $real_estate_age = $request->input('real_estate_age');

            $room_layout_name = $request->input('room_layout_name');

            $room_layout_id = $request->input('room_layout_id');

            $owner_name = $request->input('owner_name');

            $owner_post_number = $request->input('owner_post_number');

            $owner_address = $request->input('owner_address');

            $m_share_name = $request->input('m_share_name');

            $m_share_post_number = $request->input('m_share_post_number');

            $m_share_address = $request->input('m_share_address');

            $m_share_tel = $request->input('m_share_tel');

            $m_own_name = $request->input('m_own_name');

            $m_own_post_number = $request->input('m_own_post_number');

            $m_own_address = $request->input('m_own_address');

            $m_own_tel = $request->input('m_own_tel');

            /**
             * 契約者・同居人
             */
            $contract_name = $request->input('contract_name');

            $contract_ruby = $request->input('contract_ruby');

            $contract_date = $request->input('contract_date');

            $contract_tel = $request->input('contract_tel');

            $contract_housemate_name = $request->input('contract_housemate_name');

            $contract_housemate_birthday = $request->input('contract_housemate_birthday');

            /**
             * 商号
             */
            $company_license_id = $request->input('company_license_id');

            $user_license_id = $request->input('user_license_id');

            $user_license_name = $request->input('user_license_name');

            $user_license_number = $request->input('user_license_number');

            $manager_name = $request->input('manager_name');

            /**
             * 法令
             */
            $report_asbestos = $request->input('report_asbestos');

            $report_earthquake = $request->input('report_earthquake');

            $land_disaster_prevention_area = $request->input('land_disaster_prevention_area');
            
            $tsunami_disaster_alert_area = $request->input('tsunami_disaster_alert_area');

            $sediment_disaster_area = $request->input('sediment_disaster_area');

            $hazard_map = $request->input('hazard_map');

            $warning_flood = $request->input('warning_flood');

            $warning_storm_surge = $request->input('warning_storm_surge');

            $warning_rain_water = $request->input('warning_rain_water');

            /**
             * 登記事項
             */
            $regi_name = $request->input('regi_name');

            $regi_right = $request->input('regi_right');

            $regi_mortgage_id = $request->input('regi_mortgage_id');

            $regi_difference_owner = $request->input('regi_difference_owner');

            $completion_date = $request->input('completion_date');

            /**
             * 授受される金額
             */
            $security_fee = $request->input('security_fee');

            $key_fee = $request->input('key_fee');

            $rent_fee = $request->input('rent_fee');

            $service_fee = $request->input('service_fee');

            $water_fee = $request->input('water_fee');

            $ohter_fee = $request->input('ohter_fee');

            $bicycle_fee = $request->input('bicycle_fee');

            $total_fee = $request->input('total_fee');

            $car_deposit_fee = $request->input('car_deposit_fee');

            $car_fee = $request->input('car_fee');

            $fire_insurance_fee = $request->input('fire_insurance_fee');

            $fire_insurance_span = $request->input('fire_insurance_span');

            $guarantee_fee = $request->input('guarantee_fee');

            $guarantee_update_span = $request->input('guarantee_update_span');

            $guarantee_update_fee = $request->input('guarantee_update_fee');

            $support_fee = $request->input('support_fee');

            $disinfect_fee = $request->input('disinfect_fee');

            $other_name1 = $request->input('other_name1');

            $other_fee1 = $request->input('other_fee1');

            $other_name2 = $request->input('other_name2');

            $other_fee2 = $request->input('other_fee2');

            $car_broker_fee = $request->input('car_broker_fee');

            $broker_fee = $request->input('broker_fee');

            $today_account_fee_date = $request->input('today_account_fee_date');

            $today_account_fee = $request->input('today_account_fee');

            $payment_date = $request->input('payment_date');

            $introduction_security_fee = $request->input('introduction_security_fee');

            $introduction_key_fee = $request->input('introduction_key_fee');
            
            $keep_account_fee = $request->input('keep_account_fee');

            $introduction_fee = $request->input('introduction_fee');

            /**
             * 設備状況
             */
            $water = $request->input('water');

            $water_type_name = $request->input('water_type_name');

            $gas = $request->input('gas');

            $gas_type_name = $request->input('gas_type_name');

            $electricity = $request->input('electricity');

            $electricity_type_name = $request->input('electricity_type_name');

            $waste_water = $request->input('waste_water');

            $waste_water_name = $request->input('waste_water_name');

            $kitchen = $request->input('kitchen');

            $kitchen_exclusive_type_id = $request->input('kitchen_exclusive_type_id');

            $cooking_stove = $request->input('cooking_stove');

            $cooking_stove_exclusive_type_id = $request->input('cooking_stove_exclusive_type_id');

            $bath = $request->input('bath');
            
            $bath_exclusive_type_id = $request->input('bath_exclusive_type_id');

            $toilet = $request->input('toilet');

            $toilet_exclusive_type_id = $request->input('toilet_exclusive_type_id');

            $water_heater = $request->input('water_heater');

            $water_heater_exclusive_type_id = $request->input('water_heater_exclusive_type_id');

            $air_conditioner = $request->input('air_conditioner');

            $air_conditioner_exclusive_type_name = $request->input('air_conditioner_exclusive_type_name');

            $elevator = $request->input('elevator');

            $elevator_exclusive_type_name = $request->input('elevator_exclusive_type_name');

            /**
             * 契約期間
             */
            $contract_start_date = $request->input('contract_start_date');

            $contract_end_date = $request->input('contract_end_date');

            $contract_update_span = $request->input('contract_update_span');

            $contract_update_item = $request->input('contract_update_item');

            $daily_calculation = $request->input('daily_calculation');
            
            /**
             * 用途の制限
             */
            $limit_use_id = $request->input('limit_use_id');

            $limit_type_name = $request->input('limit_type_name');

            /**
             * 契約の解除及び解約
             */

            $announce_cancel_date = $request->input('announce_cancel_date');

            $soon_cancel_date = $request->input('soon_cancel_date');

            $cancel_fee_count = $request->input('cancel_fee_count');

            $cancel_contract_document = $request->input('cancel_contract_document');

            $remove_contract_document = $request->input('remove_contract_document');

            /**
             * 損害賠償・違約金・免責
             */
            $penalty_fee = $request->input('penalty_fee');

            $penalty_fee_late_document = $request->input('penalty_fee_late_document');

            $claim_fee_document = $request->input('claim_fee_document');

            $fix_document = $request->input('fix_document');

            $recovery_document = $request->input('recovery_document');

            /**
             * 家賃振込先
             */
            $bank_id = $request->input('bank_id');

            $bank_name = $request->input('bank_name');

            $bank_branch_name = $request->input('bank_branch_name');

            $bank_type_id = $request->input('bank_type_id');

            $bank_number = $request->input('bank_number');

            $bank_account_name = $request->input('bank_account_name');

            $rent_fee_payment_date = $request->input('rent_fee_payment_date');

            /**
             * その他
             */
            $trade_type_id = $request->input('trade_type_id');

            $mail_box_number = $request->input('mail_box_number');

            $guarantor_need_id = $request->input('guarantor_need_id');

            $guarantor_max_payment = $request->input('guarantor_max_payment');
            
            // 現在の日付取得
            $date = now() .'.000';
            
            /**
             * 契約進捗
             */
            if($admin_number == null){
                $admin_number = '';
            }
            
            /**
             * 契約進捗
             */
            if($contract_detail_progress_id == null){
                $contract_detail_progress_id = 0;
            }

            /**
             * 申込id
             */
            if($application_id == null){
                $application_id = 0;
            }

            /**
             * 物件概要
             * int = 0/null=''
             */
            // 不動産名
            if($real_estate_name == null){
                $real_estate_name = '';
            }

            // 号室
            if($room_name == null){
                $room_name ='';
            }

            // 契約面積
            if($room_size == null){
                $room_size ='';
            }

            // 郵便番号
            if($real_estate_post_number == null){
                $real_estate_post_number ='';
            }

            // 住所
            if($real_estate_address == null){
                $real_estate_address = '';
            }

            // 構造id
            if($real_estate_structure_id == null){
                $real_estate_structure_id = 0;
            }

            // 階数
            if($real_estate_floor == null){
                $real_estate_floor = 0;
            }

            // 築年月日
            if($real_estate_age == null){
                $real_estate_age = '';
            }

            // 部屋数
            if($room_layout_name == null){
                $room_layout_name = 0;
            }

            // 間取種別
            if($room_layout_id == null){
                $room_layout_id = 0;
            }

            // 家主名
            if($owner_name == null){
                $owner_name = '';
            }

            // 郵便番号
            if($owner_post_number == null){
                $owner_post_number = '';
            }

            // 家主住所
            if($owner_address == null){
                $owner_address = '';
            }

            // 管理の委託先(共有)
            if($m_share_name == null){
                $m_share_name = '';
            }

            // 郵便番号
            if($m_share_post_number == null){
                $m_share_post_number = '';
            }

            // 住所
            if($m_share_address == null){
                $m_share_address = '';
            }

            // Tel
            if($m_share_tel == null){
                $m_share_tel = '';
            }

            // 管理の委託先(専有)
            if($m_own_name == null){
                $m_own_name = '';
            }

            // 郵便番号
            if($m_own_post_number == null){
                $m_own_post_number = '';
            }

            // 住所
            if($m_own_address == null){
                $m_own_address = '';
            }

            // Tel
            if($m_own_tel == null){
                $m_own_tel = '';
            }

            /**
             * 契約者・同居人
             */
            if($contract_name == null){
                $contract_name = '';
            }

            if($contract_ruby == null){
                $contract_ruby = '';
            }

            if($contract_date == null){
                $contract_date = '';
            }

            if($contract_tel == null){
                $contract_tel = '';
            }

            if($contract_housemate_name == null){
                $contract_housemate_name = '';
            }

            if($contract_housemate_birthday == null){
                $contract_housemate_birthday = '';
            }

            /**
             * 商号
             */
            if($company_license_id == null){
                $company_license_id = 0;
            }

            if($user_license_id == null){
                $user_license_id = 0;
            }

            if($user_license_name == null){
                $user_license_name = '';
            }

            if($user_license_number == null){
                $user_license_number = '';
            }

            if($manager_name == null){
                $manager_name = '';
            }

            
            /**
             * 法令
             */
            if($report_asbestos == null){
                $report_asbestos = 0;
            }

            if($report_earthquake == null){
                $report_earthquake = 0;
            }

            if($land_disaster_prevention_area == null){
                $land_disaster_prevention_area = 0;
            }

            if($tsunami_disaster_alert_area == null){
                $tsunami_disaster_alert_area = 0;
            }

            if($sediment_disaster_area == null){
                $sediment_disaster_area = 0;
            }

            if($hazard_map == null){
                $hazard_map = 0;
            }

            if($warning_flood == null){
                $warning_flood = 0;
            }

            if($warning_storm_surge == null){
                $warning_storm_surge = 0;
            }

            if($warning_rain_water == null){
                $warning_rain_water = 0;
            }

            /**
             * 登記事項
             */
            if($regi_name == null){
                $regi_name = '';
            }

            if($regi_right == null){
                $regi_right = 0;
            }

            if($regi_mortgage_id == null){
                $regi_mortgage_id = 0;
            }

            if($regi_difference_owner == null){
                $regi_difference_owner = '';
            }

            if($completion_date == null){
                $completion_date = '';
            }

            /**
             * 授受される金額
             */
            if($security_fee == null){
                $security_fee = 0;
            }

            if($key_fee == null){
                $key_fee = 0;
            }

            if($rent_fee == null){
                $rent_fee = 0;
            }

            if($service_fee == null){
                $service_fee = 0;
            }

            if($water_fee == null){
                $water_fee = 0;
            }

            if($ohter_fee == null){
                $ohter_fee = 0;
            }

            if($bicycle_fee == null){
                $bicycle_fee = 0;
            }

            if($total_fee == null){
                $total_fee = 0;
            }

            if($car_deposit_fee == null){
                $car_deposit_fee = 0;
            }

            if($car_fee == null){
                $car_fee = 0;
            }

            if($fire_insurance_fee == null){
                $fire_insurance_fee = 0;
            }

            if($fire_insurance_span == null){
                $fire_insurance_span = 0;
            }

            if($guarantee_fee == null){
                $guarantee_fee = 0;
            }

            if($guarantee_update_span == null){
                $guarantee_update_span = 0;
            }

            if($guarantee_update_fee == null){
                $guarantee_update_fee = 0;
            }

            if($support_fee == null){
                $support_fee = 0;
            }

            if($disinfect_fee == null){
                $disinfect_fee = 0;
            }

            if($other_name1 == null){
                $other_name1 = '';
            }

            if($other_fee1 == null){
                $other_fee1 = 0;
            }

            if($other_name2 == null){
                $other_name2 = '';
            }

            if($other_fee2 == null){
                $other_fee2 = 0;
            }

            if($car_broker_fee == null){
                $car_broker_fee = 0;
            }

            if($broker_fee == null){
                $broker_fee = 0;
            }

            if($today_account_fee_date == null){
                $today_account_fee_date = '';
            }

            if($today_account_fee == null){
                $today_account_fee = 0;
            }

            if($payment_date == null){
                $payment_date = '';
            }

            if($introduction_security_fee == null){
                $introduction_security_fee = 0;
            }

            if($introduction_key_fee == null){
                $introduction_key_fee = 0;
            }

            if($keep_account_fee == null){
                $keep_account_fee = 0;
            }

            if($introduction_fee == null){
                $introduction_fee = 0;
            }

            /**
             * 設備状況
             */
            if($water == null){
                $water = 0;
            }

            if($water_type_name == null){
                $water_type_name = '';
            }

            if($gas == null){
                $gas = 0;
            }

            if($gas_type_name == null){
                $gas_type_name = '';
            }

            if($electricity == null){
                $electricity = '';
            }

            if($electricity_type_name == null){
                $electricity_type_name = '';
            }

            if($waste_water == null){
                $waste_water = 0;
            }

            if($waste_water_name == null){
                $waste_water_name = '';
            }

            if($kitchen == null){
                $kitchen = 0;
            }

            if($kitchen_exclusive_type_id == null){
                $kitchen_exclusive_type_id = 0;
            }

            if($cooking_stove == null){
                $cooking_stove = 0;
            }

            if($cooking_stove_exclusive_type_id == null){
                $cooking_stove_exclusive_type_id = 0;
            }

            if($bath == null){
                $bath = 0;
            }

            if($bath_exclusive_type_id == null){
                $bath_exclusive_type_id = 0;
            }

            if($toilet == null){
                $toilet = 0;
            }

            if($toilet_exclusive_type_id == null){
                $toilet_exclusive_type_id = 0;
            }

            if($water_heater == null){
                $water_heater = 0;
            }

            if($water_heater_exclusive_type_id == null){
                $water_heater_exclusive_type_id = 0;
            }

            if($air_conditioner == null){
                $air_conditioner = 0;
            }

            if($air_conditioner_exclusive_type_name == null){
                $air_conditioner_exclusive_type_name = '';
            }

            if($elevator == null){
                $elevator = 0;
            }

            if($elevator_exclusive_type_name == null){
                $elevator_exclusive_type_name = '';
            }

            /**
             * 契約期間
             */
            if($contract_start_date == null){
                $contract_start_date = '';
            }

            if($contract_end_date == null){
                $contract_end_date = '';
            }

            if($contract_update_span == null){
                $contract_update_span = 0;
            }

            if($contract_update_item == null){
                $contract_update_item = '';
            }

            if($daily_calculation == null){
                $daily_calculation = 0;
            }

            /**
             * 用途の制限
             */
            if($limit_use_id == null){
                $limit_use_id = 0;
            }

            if($limit_type_name == null){
                $limit_type_name = '';
            }

            /**
             * 契約の解約及び解除
             */
            if($announce_cancel_date == null){
                $announce_cancel_date = 0;
            }

            if($soon_cancel_date == null){
                $soon_cancel_date = 0;
            }

            if($cancel_fee_count == null){
                $cancel_fee_count = 0;
            }

            if($cancel_contract_document == null){
                $cancel_contract_document = '';
            }
            if($remove_contract_document == null){
                $remove_contract_document = '';
            }

            /**
             * 損害賠償・違約金・免責
             */
            if($penalty_fee == null){
                $penalty_fee = '';
            }

            if($penalty_fee_late_document == null){
                $penalty_fee_late_document = '';
            }

            if($claim_fee_document == null){
                $claim_fee_document = '';
            }

            if($fix_document == null){
                $fix_document = '';
            }

            if($recovery_document == null){
                $recovery_document = '';
            }

            /**
             * 家賃振込先
             */
            if($bank_id == null){
                $bank_id = 0;
            }

            if($bank_name == null){
                $bank_name = '';
            }

            if($bank_branch_name == null){
                $bank_branch_name = '';
            }

            if($bank_type_id == null){
                $bank_type_id = 0;
            }

            if($bank_number == null){
                $bank_number = '';
            }

            if($bank_account_name == null){
                $bank_account_name = '';
            }

            if($rent_fee_payment_date == null){
                $rent_fee_payment_date = '';
            }
            
            /**
             * その他
             */
            if($trade_type_id == null){
                $trade_type_id = 0;
            }

            if($mail_box_number == null){
                $mail_box_number = '';
            }

            if($guarantor_need_id == null){
                $guarantor_need_id = 0;
            }

            if($guarantor_max_payment == null){
                $guarantor_max_payment = 0;
            }


            $str = "insert "
            ."into "
            ."contract_details "
            ."( "
            ."create_user_id, "
            ."contract_detail_progress_id, "
            ."application_id, "
            ."company_license_id, "
            ."user_license_id, "
            ."user_license_name, "
            ."user_license_number, "
            ."manager_name, "
            ."trade_type_id, "
            ."contract_name, "
            ."contract_ruby, "
            ."contract_date, "
            ."contract_tel, "
            ."real_estate_name, "
            ."real_estate_post_number, "
            ."real_estate_address, "
            ."room_name, "
            ."room_size, "
            ."real_estate_structure_id, "
            ."real_estate_floor, "
            ."room_layout_name, "
            ."room_layout_id, "
            ."real_estate_age, "
            ."owner_name, "
            ."owner_post_number, "
            ."owner_address, "
            ."bank_id, "
            ."bank_name, "
            ."bank_branch_name, "
            ."bank_type_id, "
            ."bank_number, "
            ."bank_account_name, "
            ."m_share_name, "
            ."m_share_address, "
            ."m_share_tel, "
            ."m_own_name, "
            ."m_own_address, "
            ."m_own_tel, "
            ."report_asbestos, "
            ."report_earthquake, "
            ."land_disaster_prevention_area, "
            ."tsunami_disaster_alert_area, "
            ."sediment_disaster_area, "
            ."regi_name, "
            ."regi_right, "
            ."regi_mortgage, "
            ."regi_difference_owner, "
            ."completion_date, "
            ."hazard_map, "
            ."warning_flood, "
            ."warning_storm_surge, "
            ."warning_rain_water, "
            ."security_fee, "
            ."key_fee, "
            ."rent_fee, "
            ."service_fee, "
            ."water_fee, "
            ."ohter_fee, "
            ."bicycle_fee, "
            ."total_Fee, "
            ."car_fee, "
            ."car_deposit_fee, "
            ."fire_insurance_fee, "
            ."fire_insurance_span, "
            ."guarantee_fee, "
            ."guarantee_update_span, "
            ."guarantee_update_fee, "
            ."support_fee, "
            ."disinfect_fee, "
            ."other_name1, "
            ."other_fee1, "
            ."other_name2, "
            ."other_fee2, "
            ."broker_fee, "
            ."car_broker_fee, "
            ."today_account_fee_date, "
            ."today_account_fee, "
            ."payment_date, "
            ."keep_account_fee, "
            ."introduction_fee, "
            ."water, "
            ."water_type_name, "
            ."electricity, "
            ."electricity_type_name, "
            ."gas, "
            ."gas_type_name, "
            ."waste_water, "
            ."waste_water_name, "
            ."kitchen, "
            ."kitchen_exclusive_type_id, "
            ."cooking_stove, "
            ."cooking_exclusive_type_id, "
            ."bath, "
            ."bath_exclusive_type_id, "
            ."toilet, "
            ."toilet_exclusive_type_id, "
            ."water_heater, "
            ."water_heater_exclusive_type_id, "
            ."air_conditioner, "
            ."air_conditioner_exclusive_type_name, "
            ."elevator, "
            ."elevator_type_name, "
            ."contract_start_date, "
            ."contract_end_date, "
            ."contract_update_span, "
            ."contract_update_item, "
            ."daily_calculation, "
            ."security_settle_detail, "
            ."key_money_settle_detail, "
            ."limit_use, "
            ."limit_type, "
            ."announce_cancel_date, "
            ."soon_cancel_date, "
            ."cancel_fee_count, "
            ."cancel_contract_document, "
            ."remove_contract_document, "
            ."penalty_fee, "
            ."penalty_fee_late_document, "
            ."claim_fee_document, "
            ."fix_document, "
            ."recovery_document, "
            ."rent_fee_payment_date, "
            ."mail_box_number, "
            ."guarantor_need_id, "
            ."guarantor_max_payment, "
            ."admin_number, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$session_id, "
            ."$contract_detail_progress_id, "
            ."$application_id, "
            ."$company_license_id, "
            ."$user_license_id, "
            ."'$user_license_name', "
            ."'$user_license_number', "
            ."'$manager_name', "
            ."$trade_type_id, "
            ."'$contract_name', "
            ."'$contract_ruby', "
            ."'$contract_date', "
            ."'$contract_tel', "
            ."'$real_estate_name', "
            ."'$real_estate_post_number', "
            ."'$real_estate_address', "
            ."'$room_name', "
            ."'$room_size', "
            ."$real_estate_structure_id, "
            ."$real_estate_floor, "
            ."$room_layout_name, "
            ."$room_layout_id, "
            ."'$real_estate_age', "
            ."'$owner_name', "
            ."'$owner_post_number', "
            ."'$owner_address', "
            ."$bank_id, "
            ."'$bank_name', "
            ."'$bank_branch_name', "
            ."$bank_type_id, "
            ."'$bank_number', "
            ."'$bank_account_name', "
            ."'$m_share_name', "
            ."'$m_share_address', "
            ."'$m_share_tel', "
            ."'$m_own_name', "
            ."'$m_own_address', "
            ."'$m_own_tel', "
            ."$report_asbestos, "
            ."$report_earthquake, "
            ."$land_disaster_prevention_area, "
            ."$tsunami_disaster_alert_area, "
            ."$sediment_disaster_area, "
            ."'$regi_name', "
            ."$regi_right, "
            ."$regi_mortgage_id, "
            ."'$regi_difference_owner', "
            ."'$completion_date', "
            ."$hazard_map, "
            ."$warning_flood, "
            ."$warning_storm_surge, "
            ."$warning_rain_water, "
            ."$security_fee, "
            ."$key_fee, "
            ."$rent_fee, "
            ."$service_fee, "
            ."$water_fee, "
            ."$ohter_fee, "
            ."$bicycle_fee, "
            ."$total_fee, "
            ."$car_fee, "
            ."$car_deposit_fee, "
            ."$fire_insurance_fee, "
            ."$fire_insurance_span, "
            ."$guarantee_fee, "
            ."$guarantee_update_span, "
            ."$guarantee_update_fee, "
            ."$support_fee, "
            ."$disinfect_fee, "
            ."'$other_name1', "
            ."$other_fee1, "
            ."'$other_name2', "
            ."$other_fee2, "
            ."$broker_fee, "
            ."$car_broker_fee, "
            ."'$today_account_fee_date', "
            ."$today_account_fee, "
            ."'$payment_date', "
            ."$keep_account_fee, "
            ."$introduction_fee, "
            ."$water, "
            ."'$water_type_name', "
            ."'$electricity', "
            ."'$electricity_type_name', "
            ."$gas, "
            ."'$gas_type_name', "
            ."$waste_water, "
            ."'$waste_water_name', "
            ."$kitchen, "
            ."$kitchen_exclusive_type_id, "
            ."$cooking_stove, "
            ."$cooking_stove_exclusive_type_id, "
            ."$bath, "
            ."$bath_exclusive_type_id, "
            ."$toilet, "
            ."$toilet_exclusive_type_id, "
            ."$water_heater, "
            ."$water_heater_exclusive_type_id, "
            ."$air_conditioner, "
            ."'$air_conditioner_exclusive_type_name', "
            ."$elevator, "
            ."'$elevator_exclusive_type_name', "
            ."'$contract_start_date', "
            ."'$contract_end_date', "
            ."$contract_update_span, "
            ."'$contract_update_item', "
            ."'$daily_calculation', "
            ."$introduction_security_fee, "
            ."$introduction_key_fee, "
            ."$limit_use_id, "
            ."'$limit_type_name', "
            ."'$announce_cancel_date', "
            ."'$soon_cancel_date', "
            ."$cancel_fee_count, "
            ."'$cancel_contract_document', "
            ."'$remove_contract_document', "
            ."'$penalty_fee', "
            ."'$penalty_fee_late_document', "
            ."'$claim_fee_document', "
            ."'$fix_document', "
            ."'$recovery_document', "
            ."'$rent_fee_payment_date', "
            ."'$mail_box_number', "
            ."$guarantor_need_id, "
            ."$guarantor_max_payment, "
            ."'$admin_number', "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";
            
            Log::debug('sql:'.$str);

            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

            // 登録したidを取得
            $str = "select * "
            ."from contract_details "
            ."where "
            ."real_estate_name = '$real_estate_name' "
            ."and "
            ."entry_date = '$date' ";
            Log::debug('select_sql:'.$str);

            // ログ
            $contract_detail_info = DB::select($str);
            Log::debug($contract_detail_info);

            $ret['contract_detail_id'] = $contract_detail_info[0]->contract_detail_id;

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
     * 特約事項(新規登録)
     *
     * @param Request $request
     * @return void
     */
    private function insertSpecialContract(Request $request ,$contract_detail_id){

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');

            // 特約事項
            $textarea_checked = $request->input('textarea_checked');

            // 現在の日付取得
            $date = now() .'.000';

            /**
             * 値置換
             */
            if($contract_detail_id == null){
                $contract_detail_id = 0;
            }

            if($textarea_checked == null){
                $textarea_checked = '';
            }

            // sql
            $str = "insert "
            ."into "
            ."special_contract_details( "
            ."contract_detail_id, "
            ."special_contract_detail_name, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$contract_detail_id, "
            ."'$textarea_checked', "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";

            Log::debug('insertSpecialContract_sql:'.$str);

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
            $contract_detail_info = $this->updateContractDetail($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $contract_detail_info['status'];


            /**
             * 特約事項(status:OK=1 NG=0)
             */
            $special_contract_info = $this->updateSpecialContractDetail($request);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $special_contract_info['status'];


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
     * 契約詳細(編集)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function updateContractDetail(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');

            // 契約詳細id
            $contract_detail_id = $request->input('contract_detail_id');

            // 申込id
            $application_id = $request->input('application_id');

            /**
             * 進捗状況
             */
            $contract_detail_progress_id = $request->input('contract_detail_progress_id');

            $admin_number = $request->input('admin_number');

            /**
             * 物件概要
             */
            $real_estate_name = $request->input('real_estate_name');

            $room_name = $request->input('room_name');

            $room_size = $request->input('room_size');

            $real_estate_post_number = $request->input('real_estate_post_number');

            $real_estate_address = $request->input('real_estate_address');

            $real_estate_structure_id = $request->input('real_estate_structure_id');

            $real_estate_floor = $request->input('real_estate_floor');

            $real_estate_age = $request->input('real_estate_age');

            $room_layout_name = $request->input('room_layout_name');

            $room_layout_id = $request->input('room_layout_id');

            $owner_name = $request->input('owner_name');

            $owner_post_number = $request->input('owner_post_number');

            $owner_address = $request->input('owner_address');

            $m_share_name = $request->input('m_share_name');

            $m_share_post_number = $request->input('m_share_post_number');

            $m_share_address = $request->input('m_share_address');

            $m_share_tel = $request->input('m_share_tel');

            $m_own_name = $request->input('m_own_name');

            $m_own_post_number = $request->input('m_own_post_number');

            $m_own_address = $request->input('m_own_address');

            $m_own_tel = $request->input('m_own_tel');

            /**
             * 契約者・同居人
             */
            $contract_name = $request->input('contract_name');

            $contract_ruby = $request->input('contract_ruby');

            $contract_date = $request->input('contract_date');

            $contract_tel = $request->input('contract_tel');

            $contract_housemate_name = $request->input('contract_housemate_name');

            $contract_housemate_birthday = $request->input('contract_housemate_birthday');

            /**
             * 商号
             */
            $company_license_id = $request->input('company_license_id');

            $user_license_id = $request->input('user_license_id');

            $user_license_name = $request->input('user_license_name');

            $user_license_number = $request->input('user_license_number');

            $manager_name = $request->input('manager_name');

            /**
             * 法令
             */
            $report_asbestos = $request->input('report_asbestos');

            $report_earthquake = $request->input('report_earthquake');

            $land_disaster_prevention_area = $request->input('land_disaster_prevention_area');

            $tsunami_disaster_alert_area = $request->input('tsunami_disaster_alert_area');

            $sediment_disaster_area = $request->input('sediment_disaster_area');

            $hazard_map = $request->input('hazard_map');

            $warning_flood = $request->input('warning_flood');

            $warning_storm_surge = $request->input('warning_storm_surge');

            $warning_rain_water = $request->input('warning_rain_water');

            /**
             * 登記事項
             */
            $regi_name = $request->input('regi_name');

            $regi_right = $request->input('regi_right');

            $regi_mortgage_id = $request->input('regi_mortgage_id');

            $regi_difference_owner = $request->input('regi_difference_owner');

            $completion_date = $request->input('completion_date');

            /**
             * 授受される金額
             */
            $security_fee = $request->input('security_fee');

            $key_fee = $request->input('key_fee');

            $rent_fee = $request->input('rent_fee');

            $service_fee = $request->input('service_fee');

            $water_fee = $request->input('water_fee');

            $ohter_fee = $request->input('ohter_fee');

            $bicycle_fee = $request->input('bicycle_fee');

            $total_fee = $request->input('total_fee');

            $car_deposit_fee = $request->input('car_deposit_fee');

            $car_fee = $request->input('car_fee');

            $fire_insurance_fee = $request->input('fire_insurance_fee');

            $fire_insurance_span = $request->input('fire_insurance_span');

            $guarantee_fee = $request->input('guarantee_fee');

            $guarantee_update_span = $request->input('guarantee_update_span');

            $guarantee_update_fee = $request->input('guarantee_update_fee');

            $support_fee = $request->input('support_fee');

            $disinfect_fee = $request->input('disinfect_fee');

            $other_name1 = $request->input('other_name1');

            $other_fee1 = $request->input('other_fee1');

            $other_name2 = $request->input('other_name2');

            $other_fee2 = $request->input('other_fee2');

            $car_broker_fee = $request->input('car_broker_fee');

            $broker_fee = $request->input('broker_fee');

            $today_account_fee_date = $request->input('today_account_fee_date');

            $today_account_fee = $request->input('today_account_fee');

            $payment_date = $request->input('payment_date');

            $introduction_security_fee = $request->input('introduction_security_fee');

            $introduction_key_fee = $request->input('introduction_key_fee');
            
            $keep_account_fee = $request->input('keep_account_fee');

            $introduction_fee = $request->input('introduction_fee');

            /**
             * 設備状況
             */
            $water = $request->input('water');

            $water_type_name = $request->input('water_type_name');

            $gas = $request->input('gas');

            $gas_type_name = $request->input('gas_type_name');

            $electricity = $request->input('electricity');

            $electricity_type_name = $request->input('electricity_type_name');

            $waste_water = $request->input('waste_water');

            $waste_water_name = $request->input('waste_water_name');

            $kitchen = $request->input('kitchen');

            $kitchen_exclusive_type_id = $request->input('kitchen_exclusive_type_id');

            $cooking_stove = $request->input('cooking_stove');

            $cooking_stove_exclusive_type_id = $request->input('cooking_stove_exclusive_type_id');

            $bath = $request->input('bath');
            
            $bath_exclusive_type_id = $request->input('bath_exclusive_type_id');

            $toilet = $request->input('toilet');

            $toilet_exclusive_type_id = $request->input('toilet_exclusive_type_id');

            $water_heater = $request->input('water_heater');

            $water_heater_exclusive_type_id = $request->input('water_heater_exclusive_type_id');

            $air_conditioner = $request->input('air_conditioner');

            $air_conditioner_exclusive_type_name = $request->input('air_conditioner_exclusive_type_name');

            $elevator = $request->input('elevator');

            $elevator_exclusive_type_name = $request->input('elevator_exclusive_type_name');

            /**
             * 契約期間
             */
            $contract_start_date = $request->input('contract_start_date');

            $contract_end_date = $request->input('contract_end_date');

            $contract_update_span = $request->input('contract_update_span');

            $contract_update_item = $request->input('contract_update_item');

            $daily_calculation = $request->input('daily_calculation');
            
            /**
             * 用途の制限
             */
            $limit_use_id = $request->input('limit_use_id');

            $limit_type_name = $request->input('limit_type_name');

            /**
             * 契約の解除及び解約
             */

            $announce_cancel_date = $request->input('announce_cancel_date');

            $soon_cancel_date = $request->input('soon_cancel_date');

            $cancel_fee_count = $request->input('cancel_fee_count');

            $cancel_contract_document = $request->input('cancel_contract_document');

            $remove_contract_document = $request->input('remove_contract_document');

            /**
             * 損害賠償・違約金・免責
             */
            $penalty_fee = $request->input('penalty_fee');

            $penalty_fee_late_document = $request->input('penalty_fee_late_document');

            $claim_fee_document = $request->input('claim_fee_document');

            $fix_document = $request->input('fix_document');

            $recovery_document = $request->input('recovery_document');

            /**
             * 家賃振込先
             */
            $bank_id = $request->input('bank_id');

            $bank_name = $request->input('bank_name');

            $bank_branch_name = $request->input('bank_branch_name');

            $bank_type_id = $request->input('bank_type_id');

            $bank_number = $request->input('bank_number');

            $bank_account_name = $request->input('bank_account_name');

            $rent_fee_payment_date = $request->input('rent_fee_payment_date');

            /**
             * その他
             */
            $trade_type_id = $request->input('trade_type_id');

            $mail_box_number = $request->input('mail_box_number');

            $guarantor_need_id = $request->input('guarantor_need_id');

            $guarantor_max_payment = $request->input('guarantor_max_payment');
            
            // 現在の日付取得
            $date = now() .'.000';
    
            /**
             * 契約進捗
             */
            if($contract_detail_progress_id == null){
                $contract_detail_progress_id = 0;
            }

            /**
             * 管理者番号
             */
            if($admin_number == null){
                $admin_number = '';
            }

            /**
             * 申込id
             */
            if($application_id == null){
                $application_id = 0;
            }

            /**
             * 物件概要
             * int = 0/null=''
             */
            // 不動産名
            if($real_estate_name == null){
                $real_estate_name = '';
            }

            // 号室
            if($room_name == null){
                $room_name ='';
            }

            // 契約面積
            if($room_size == null){
                $room_size ='';
            }

            // 郵便番号
            if($real_estate_post_number == null){
                $real_estate_post_number ='';
            }

            // 住所
            if($real_estate_address == null){
                $real_estate_address = '';
            }

            // 構造id
            if($real_estate_structure_id == null){
                $real_estate_structure_id = 0;
            }

            // 階数
            if($real_estate_floor == null){
                $real_estate_floor = 0;
            }

            // 築年月日
            if($real_estate_age == null){
                $real_estate_age = '';
            }

            // 部屋数
            if($room_layout_name == null){
                $room_layout_name = 0;
            }

            // 間取種別
            if($room_layout_id == null){
                $room_layout_id = 0;
            }

            // 家主名
            if($owner_name == null){
                $owner_name = '';
            }

            // 郵便番号
            if($owner_post_number == null){
                $owner_post_number = '';
            }

            // 家主住所
            if($owner_address == null){
                $owner_address = '';
            }

            // 管理の委託先(共有)
            if($m_share_name == null){
                $m_share_name = '';
            }

            // 郵便番号
            if($m_share_post_number == null){
                $m_share_post_number = '';
            }

            // 住所
            if($m_share_address == null){
                $m_share_address = '';
            }

            // Tel
            if($m_share_tel == null){
                $m_share_tel = '';
            }

            // 管理の委託先(専有)
            if($m_own_name == null){
                $m_own_name = '';
            }

            // 郵便番号
            if($m_own_post_number == null){
                $m_own_post_number = '';
            }

            // 住所
            if($m_own_address == null){
                $m_own_address = '';
            }

            // Tel
            if($m_own_tel == null){
                $m_own_tel = '';
            }

            /**
             * 契約者・同居人
             */
            if($contract_name == null){
                $contract_name = '';
            }

            if($contract_ruby == null){
                $contract_ruby = '';
            }

            if($contract_date == null){
                $contract_date = '';
            }

            if($contract_tel == null){
                $contract_tel = '';
            }

            if($contract_housemate_name == null){
                $contract_housemate_name = '';
            }

            if($contract_housemate_birthday == null){
                $contract_housemate_birthday = '';
            }

            /**
             * 商号
             */
            if($company_license_id == null){
                $company_license_id = 0;
            }

            if($user_license_id == null){
                $user_license_id = 0;
            }

            if($user_license_name == null){
                $user_license_name = '';
            }

            if($user_license_number == null){
                $user_license_number = '';
            }

            if($manager_name == null){
                $manager_name = '';
            }

            
            /**
             * 法令
             */
            if($report_asbestos == null){
                $report_asbestos = 0;
            }

            if($report_earthquake == null){
                $report_earthquake = 0;
            }

            if($land_disaster_prevention_area == null){
                $land_disaster_prevention_area = 0;
            }

            if($tsunami_disaster_alert_area == null){
                $tsunami_disaster_alert_area = 0;
            }

            if($sediment_disaster_area == null){
                $sediment_disaster_area = 0;
            }

            if($hazard_map == null){
                $hazard_map = 0;
            }

            if($warning_flood == null){
                $warning_flood = 0;
            }

            if($warning_storm_surge == null){
                $warning_storm_surge = 0;
            }

            if($warning_rain_water == null){
                $warning_rain_water = 0;
            }

            /**
             * 登記事項
             */
            if($regi_name == null){
                $regi_name = '';
            }

            if($regi_right == null){
                $regi_right = 0;
            }

            if($regi_mortgage_id == null){
                $regi_mortgage_id = 0;
            }

            if($regi_difference_owner == null){
                $regi_difference_owner = '';
            }

            if($completion_date == null){
                $completion_date = '';
            }

            /**
             * 授受される金額
             */
            if($security_fee == null){
                $security_fee = 0;
            }

            if($key_fee == null){
                $key_fee = 0;
            }

            if($rent_fee == null){
                $rent_fee = 0;
            }

            if($service_fee == null){
                $service_fee = 0;
            }

            if($water_fee == null){
                $water_fee = 0;
            }

            if($ohter_fee == null){
                $ohter_fee = 0;
            }

            if($bicycle_fee == null){
                $bicycle_fee = 0;
            }

            if($total_fee == null){
                $total_fee = 0;
            }

            if($car_deposit_fee == null){
                $car_deposit_fee = 0;
            }

            if($car_fee == null){
                $car_fee = 0;
            }

            if($fire_insurance_fee == null){
                $fire_insurance_fee = 0;
            }

            if($fire_insurance_span == null){
                $fire_insurance_span = 0;
            }

            if($guarantee_fee == null){
                $guarantee_fee = 0;
            }

            if($guarantee_update_span == null){
                $guarantee_update_span = 0;
            }

            if($guarantee_update_fee == null){
                $guarantee_update_fee = 0;
            }

            if($support_fee == null){
                $support_fee = 0;
            }

            if($disinfect_fee == null){
                $disinfect_fee = 0;
            }

            if($other_name1 == null){
                $other_name1 = '';
            }

            if($other_fee1 == null){
                $other_fee1 = 0;
            }

            if($other_name2 == null){
                $other_name2 = '';
            }

            if($other_fee2 == null){
                $other_fee2 = 0;
            }

            if($car_broker_fee == null){
                $car_broker_fee = 0;
            }

            if($broker_fee == null){
                $broker_fee = 0;
            }

            if($today_account_fee_date == null){
                $today_account_fee_date = '';
            }

            if($today_account_fee == null){
                $today_account_fee = 0;
            }

            if($payment_date == null){
                $payment_date = '';
            }

            if($introduction_security_fee == null){
                $introduction_security_fee = 0;
            }

            if($introduction_key_fee == null){
                $introduction_key_fee = 0;
            }

            if($keep_account_fee == null){
                $keep_account_fee = 0;
            }

            if($introduction_fee == null){
                $introduction_fee = 0;
            }

            /**
             * 設備状況
             */
            if($water == null){
                $water = 0;
            }

            if($water_type_name == null){
                $water_type_name = '';
            }

            if($gas == null){
                $gas = 0;
            }

            if($gas_type_name == null){
                $gas_type_name = '';
            }

            if($electricity == null){
                $electricity = '';
            }

            if($electricity_type_name == null){
                $electricity_type_name = '';
            }

            if($waste_water == null){
                $waste_water = 0;
            }

            if($waste_water_name == null){
                $waste_water_name = '';
            }

            if($kitchen == null){
                $kitchen = 0;
            }

            if($kitchen_exclusive_type_id == null){
                $kitchen_exclusive_type_id = 0;
            }

            if($cooking_stove == null){
                $cooking_stove = 0;
            }

            if($cooking_stove_exclusive_type_id == null){
                $cooking_stove_exclusive_type_id = 0;
            }

            if($bath == null){
                $bath = 0;
            }

            if($bath_exclusive_type_id == null){
                $bath_exclusive_type_id = 0;
            }

            if($toilet == null){
                $toilet = 0;
            }

            if($toilet_exclusive_type_id == null){
                $toilet_exclusive_type_id = 0;
            }

            if($water_heater == null){
                $water_heater = 0;
            }

            if($water_heater_exclusive_type_id == null){
                $water_heater_exclusive_type_id = 0;
            }

            if($air_conditioner == null){
                $air_conditioner = 0;
            }

            if($air_conditioner_exclusive_type_name == null){
                $air_conditioner_exclusive_type_name = '';
            }

            if($elevator == null){
                $elevator = 0;
            }

            if($elevator_exclusive_type_name == null){
                $elevator_exclusive_type_name = '';
            }

            /**
             * 契約期間
             */
            if($contract_start_date == null){
                $contract_start_date = '';
            }

            if($contract_end_date == null){
                $contract_end_date = '';
            }

            if($contract_update_span == null){
                $contract_update_span = 0;
            }

            if($contract_update_item == null){
                $contract_update_item = '';
            }

            if($daily_calculation == null){
                $daily_calculation = 0;
            }

            /**
             * 用途の制限
             */
            if($limit_use_id == null){
                $limit_use_id = 0;
            }

            if($limit_type_name == null){
                $limit_type_name = '';
            }

            /**
             * 契約の解約及び解除
             */
            if($announce_cancel_date == null){
                $announce_cancel_date = 0;
            }

            if($soon_cancel_date == null){
                $soon_cancel_date = 0;
            }

            if($cancel_fee_count == null){
                $cancel_fee_count = 0;
            }

            if($cancel_contract_document == null){
                $cancel_contract_document = '';
            }
            if($remove_contract_document == null){
                $remove_contract_document = '';
            }

            /**
             * 損害賠償・違約金・免責
             */
            if($penalty_fee == null){
                $penalty_fee = '';
            }

            if($penalty_fee_late_document == null){
                $penalty_fee_late_document = '';
            }

            if($claim_fee_document == null){
                $claim_fee_document = '';
            }

            if($fix_document == null){
                $fix_document = '';
            }

            if($recovery_document == null){
                $recovery_document = '';
            }

            /**
             * 家賃振込先
             */
            if($bank_id == null){
                $bank_id = 0;
            }

            if($bank_name == null){
                $bank_name = '';
            }

            if($bank_branch_name == null){
                $bank_branch_name = '';
            }

            if($bank_type_id == null){
                $bank_type_id = 0;
            }

            if($bank_number == null){
                $bank_number = '';
            }

            if($bank_account_name == null){
                $bank_account_name = '';
            }

            if($rent_fee_payment_date == null){
                $rent_fee_payment_date = '';
            }
            
            /**
             * その他
             */
            if($trade_type_id == null){
                $trade_type_id = 0;
            }

            if($mail_box_number == null){
                $mail_box_number = '';
            }

            if($guarantor_max_payment == null){
                $guarantor_max_payment = 0;
            }

            if($guarantor_need_id == null){
                $guarantor_need_id = 0;
            }
            
            $str = "update "
            ."contract_details "
            ."set "
            ."create_user_id = $session_id, "
            ."contract_detail_progress_id = $contract_detail_progress_id, "
            ."application_id = $application_id, "
            ."company_license_id = $company_license_id, "
            ."user_license_id = $user_license_id, "
            ."user_license_name = '$user_license_name', "
            ."user_license_number = '$user_license_number', "
            ."manager_name = '$manager_name', "
            ."trade_type_id = $trade_type_id, "
            ."contract_name = '$contract_name', "
            ."contract_ruby = '$contract_ruby', "
            ."contract_date = '$contract_date', "
            ."contract_tel = '$contract_tel', "
            ."real_estate_name = '$real_estate_name', "
            ."real_estate_post_number = '$real_estate_post_number', "
            ."real_estate_address = '$real_estate_address', "
            ."room_name = '$room_name', "
            ."room_size = '$room_size', "
            ."real_estate_structure_id = $real_estate_structure_id, "
            ."real_estate_floor = $real_estate_floor, "
            ."room_layout_name = $room_layout_name, "
            ."room_layout_id = $room_layout_id, "
            ."real_estate_age = '$real_estate_age', "
            ."owner_name = '$owner_name', "
            ."owner_post_number = '$owner_post_number', "
            ."owner_address = '$owner_address', "
            ."bank_id = $bank_id, "
            ."bank_name = '$bank_name', "
            ."bank_branch_name = '$bank_branch_name', "
            ."bank_type_id = $bank_type_id, "
            ."bank_number = '$bank_number', "
            ."bank_account_name = '$bank_account_name', "
            ."m_share_name = '$m_share_name', "
            ."m_share_post_number = '$m_share_post_number', "
            ."m_share_address = '$m_share_address', "
            ."m_share_tel = '$m_share_tel', "
            ."m_own_name = '$m_own_name', "
            ."m_own_post_number = '$m_own_post_number', "
            ."m_own_address = '$m_own_address', "
            ."m_own_tel = '$m_own_tel', "
            ."report_asbestos = $report_asbestos, "
            ."report_earthquake = $report_earthquake, "
            ."land_disaster_prevention_area = $land_disaster_prevention_area, "
            ."tsunami_disaster_alert_area = $tsunami_disaster_alert_area, "
            ."sediment_disaster_area = $sediment_disaster_area, "
            ."regi_name = '$regi_name', "
            ."regi_right = '$regi_right', "
            ."regi_mortgage = $regi_mortgage_id, "
            ."regi_difference_owner = '$regi_difference_owner', "
            ."completion_date = '$completion_date', "
            ."hazard_map = $hazard_map, "
            ."warning_flood = $warning_flood, "
            ."warning_storm_surge = $warning_storm_surge, "
            ."warning_rain_water = $warning_rain_water, "
            ."security_fee = $security_fee, "
            ."key_fee = $key_fee, "
            ."rent_fee = $rent_fee, "
            ."service_fee = $service_fee, "
            ."water_fee = $water_fee, "
            ."ohter_fee = $ohter_fee, "
            ."bicycle_fee = $bicycle_fee, "
            ."total_fee = $total_fee, "
            ."car_fee = $car_fee, "
            ."car_deposit_fee = $car_deposit_fee, "
            ."fire_insurance_fee = $fire_insurance_fee, "
            ."fire_insurance_span = $fire_insurance_span, "
            ."guarantee_fee = $guarantee_fee, "
            ."guarantee_update_span = $guarantee_update_span, "
            ."guarantee_update_fee = $guarantee_update_fee, "
            ."support_fee = $support_fee, "
            ."disinfect_fee = $disinfect_fee, "
            ."other_name1 = '$other_name1', "
            ."other_fee1 = $other_fee1, "
            ."other_name2 = '$other_name2', "
            ."other_fee2 = $other_fee2, "
            ."broker_fee = $broker_fee, "
            ."car_broker_fee = $car_broker_fee, "
            ."today_account_fee_date = '$today_account_fee_date', "
            ."today_account_fee = $today_account_fee, "
            ."payment_date = '$payment_date', "
            ."keep_account_fee = $keep_account_fee, "
            ."introduction_fee = $introduction_fee, "
            ."water = $water, "
            ."water_type_name = '$water_type_name', "
            ."electricity = '$electricity', "
            ."electricity_type_name = '$electricity_type_name', "
            ."gas = $gas, "
            ."gas_type_name = '$gas_type_name', "
            ."waste_water = $waste_water, "
            ."waste_water_name = '$waste_water_name', "
            ."kitchen = $kitchen, "
            ."kitchen_exclusive_type_id = $kitchen_exclusive_type_id, "
            ."cooking_stove = $cooking_stove, "
            ."cooking_exclusive_type_id = $cooking_stove_exclusive_type_id, "
            ."bath = $bath, "
            ."bath_exclusive_type_id = $bath_exclusive_type_id, "
            ."toilet = $toilet, "
            ."toilet_exclusive_type_id = $toilet_exclusive_type_id, "
            ."water_heater = $water_heater, "
            ."water_heater_exclusive_type_id = $water_heater_exclusive_type_id, "
            ."air_conditioner = $air_conditioner, "
            ."air_conditioner_exclusive_type_name = '$air_conditioner_exclusive_type_name', "
            ."elevator = $elevator, "
            ."elevator_type_name = '$elevator_exclusive_type_name', "
            ."contract_start_date = '$contract_start_date', "
            ."contract_end_date = '$contract_end_date', "
            ."contract_update_span = $contract_update_span, "
            ."contract_update_item = '$contract_update_item', "
            ."daily_calculation = '$daily_calculation', "
            ."security_settle_detail = $introduction_security_fee, "
            ."key_money_settle_detail = $introduction_key_fee, "
            ."limit_use = $limit_use_id, "
            ."limit_type = '$limit_type_name', "
            ."announce_cancel_date = $announce_cancel_date, "
            ."soon_cancel_date = $soon_cancel_date, "
            ."cancel_fee_count = $cancel_fee_count, "
            ."cancel_contract_document = '$cancel_contract_document', "
            ."remove_contract_document = '$remove_contract_document', "
            ."penalty_fee = '$penalty_fee', "
            ."penalty_fee_late_document = '$penalty_fee_late_document', "
            ."claim_fee_document = '$claim_fee_document', "
            ."fix_document = '$fix_document', "
            ."recovery_document = '$recovery_document', "
            ."rent_fee_payment_date = '$rent_fee_payment_date', "
            ."mail_box_number = '$mail_box_number', "
            ."guarantor_need_id = '$guarantor_need_id', "
            ."guarantor_max_payment = '$guarantor_max_payment', "
            ."admin_number = '$admin_number', "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."contract_detail_id = $contract_detail_id; ";
            
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
     * 特約事項(編集)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function updateSpecialContractDetail(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            /**
             * 値格納
             */
            // session
            $session_id = $request->session()->get('create_user_id');

            // 契約詳細id
            $contract_detail_id = $request->input('contract_detail_id');

            // 特約事項id
            $special_contract_detail_id = $request->input('special_contract_detail_id');

            // 特約事項
            $special_contract_detail_name = $request->input('textarea_checked');
            
            // 現在の日付取得
            $date = now() .'.000';

            // sql
            $str = "update "
            ."special_contract_details "
            ."set "
            ."contract_detail_id = $contract_detail_id, "
            ."special_contract_detail_name = '$special_contract_detail_name', "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."special_contract_detail_id = $special_contract_detail_id; ";
        
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
    public function backBankDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // return初期値
            $response = [];

            /**
             * 不動産業者
             */
            $bank_info = $this->deleteBank($request);

            // js側での判定のステータス(true:OK/false:NG)
            $response['status'] = $bank_info['status'];

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
     * 削除(集金口座)
     *
     * @param Request $request
     * @return void
     */
    private function deleteBank(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $bank_id = $request->input('bank_id');

            $str = "delete "
            ."from "
            ."banks "
            ."where "
            ."bank_id = $bank_id; ";

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
     * 同居人編集(表示)
     *
     * @param Request $request
     * @return void
     */
    public function backContractHouseMateEditInit(Request $request){

        Log::debug('log_start:'.__FUNCTION__);
        
        // return初期値
        $response = [];

        // 値取得
        $contract_housemate_id = $request->input('contract_housemate_id');

        // sql
        $str = "select * from contract_housemates "
        ."where contract_housemate_id = $contract_housemate_id";
        Log::debug('backContractHouseMate_sql:' .$str);
        
        // 配列で取得、0行目をreturnで返却
        $contract_housemates_info = DB::select($str);
        $response["contract_housemates_info"] = $contract_housemates_info[0];

        Log::debug('log_end:' .__FUNCTION__);

        return response()->json($response);
    }

    /**
     * 同居人登録分岐(新規/編集)
     *
     * @param Request $request
     * @return void
     */
    public function backContractHouseMateEditEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);
        
        // return初期値
        $response = [];

        // バリデーション:OK=true NG=false
        $response = $this->housemateValidation($request);

        if($response["status"] == false){

            Log::debug('validator_status:falseのif文通過');
            return response()->json($response);

        }

        /**
         * id=無:insert
         * id=有:update
         */
        $contract_housemate_id = $request->input('contract_housemate_id');
        Log::debug('contract_housemate_id:' .$contract_housemate_id);

        try {
            
            /**
             * 新規登録
             */
            if($request->input('contract_housemate_id') == ""){

                Log::debug('新規の処理');

                // $responseの値設定
                $ret = $this->insertContractHouseMate($request);

            /**
             * 編集登録
             */
            }else{

                Log::debug('編集の処理');

                // $responseの値設定
                $ret = $this->updateContractHouseMate($request);

            }

        } catch (\Throwable $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            $response['status'] = 0;

        // status:OK=1/NG=0
        } finally {

            if($ret['status'] == 1){

                Log::debug('status:trueの処理');
                $response['status'] = true;

            }else{

                Log::debug('status:falseの処理');
                $response['status'] = false;
            }

            Log::debug('log_end:'.__FUNCTION__);
            return response()->json($response);
        }
    }

    /**
     * バリデーション
     *
     * @param Request $request(bladeの項目)
     * @return response(status=NG/msg="入力を確認して下さい/messages=$msgs/$errkeys=$keys)
     */
    private function housemateValidation(Request $request){

        // returnの出力値
        $response = [];

        // 初期値
        $response["status"] = true;

        /**
         * rules
         */
        $rules = [];
        $rules['modal_housemate_name'] = "required|max:100";
        $rules['modal_housemate_date'] = "required|max:15";

        /**
         * messages
         */
        $messages = [];
        $messages['modal_housemate_name.required'] = "同居人は必須です。";
        $messages['modal_housemate_name.max'] = "同居人の文字数が超過しています。";

        $messages['modal_housemate_date.required'] = "生年月日は必須です。";
        $messages['modal_housemate_date.max'] = "生年月日の文字数が超過しています。";

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
     * 同居人(新規登録)
     *
     * @param Request $request
     * @return void
     */
    private function insertContractHouseMate(Request $request){

        try {
            // returnの初期値
            $ret=[];

            /**
             * 値取得
             */
            // session
            $session_id = $request->session()->get('create_user_id');

            // 契約詳細id
            $contract_detail_id = $request->input('contract_detail_id');
            
            // 同居人名
            $contract_housemate_name = $request->input('modal_housemate_name');
            Log::debug('contract_housemate_name:'.$contract_housemate_name);
            
            // 生年月日
            $contract_housemate_date = $request->input('modal_housemate_date');

            // 現在の日付取得
            $date = now() .'.000';

            /**
             * 値置換
             */
            if($contract_housemate_name == null){
                $contract_housemate_name = '';
            }

            if($contract_housemate_date == null){
                $contract_housemate_date = '';
            }

            // sql
            $str = "insert "
            ."into "
            ."contract_housemates( "
            ."contract_detail_id, "
            ."contract_housemate_name, "
            ."contract_housemate_birthday, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$contract_detail_id, "
            ."'$contract_housemate_name', "
            ."'$contract_housemate_date', "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";

            Log::debug('insertContractHouseMate_sql:'.$str);

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
     * 同居人(編集)
     *
     * @param Request $request
     * @return void
     */
    private function updateContractHouseMate(Request $request){

        try {
            // returnの初期値
            $ret=[];

            /**
             * 値取得
             */
            // session
            $session_id = $request->session()->get('create_user_id');

            // 同居人id
            $contract_housemate_id = $request->input('contract_housemate_id');

            // 契約詳細id
            $contract_detail_id = $request->input('contract_detail_id');
            
            // 同居人名
            $contract_housemate_name = $request->input('modal_housemate_name');
            Log::debug('contract_housemate_name:'.$contract_housemate_name);
            
            // 生年月日
            $contract_housemate_date = $request->input('modal_housemate_date');

            // 現在の日付取得
            $date = now() .'.000';

            /**
             * 値置換
             */
            if($contract_housemate_name == null){
                $contract_housemate_name = '';
            }

            if($contract_housemate_date == null){
                $contract_housemate_date = '';
            }

            // sql
            $str = "update "
            ."contract_housemates "
            ."set "
            ."contract_detail_id = $contract_detail_id, "
            ."contract_housemate_name = '$contract_housemate_name', "
            ."contract_housemate_birthday = '$contract_housemate_date', "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."contract_housemate_id = $contract_housemate_id; ";

            Log::debug('updateContractHouseMate_sql:'.$str);

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
     * 同居人(削除)
     *
     * @param Request $request
     * @return void
     */
    public function backContractHouseMateDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $response = [];

            // $responseの値設定
            $ret = $this->deleteContractHouseMate($request);

            // js側での判定のステータス(true:OK/false:NG)
            $response["status"] = $ret['status'];

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
     * 同居人(削除:sql)
     *
     * @param Request $request
     * @return void
     */
    private function deleteContractHouseMate(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $contract_housemate_id = $request->input('contract_housemate_id');

            $str = "delete "
            ."from "
            ."contract_housemates "
            ."where "
            ."contract_housemate_id = $contract_housemate_id; ";
            Log::debug('deleteContractHouseMate_sql:' .$str);

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
     * 削除
     *
     * @param Request $request
     * @return void
     */
    public function backContractDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // トランザクション
            DB::beginTransaction();

            // return初期値
            $response = [];

            /**
             * 各テーブルに削除
             */
            // 契約詳細
            $ret = $this->deleteContractDetail($request);
            
            // js側での判定のステータス(true:OK/false:NG)
            $response["status"] = $ret['status'];

            // 同居人
            $ret = $this->deleteContractDetailHousemate($request);
            
            // js側での判定のステータス(true:OK/false:NG)
            $response["status"] = $ret['status'];

            // 特約事項
            $ret = $this->deleteSpecialContract($request);
    
            // js側での判定のステータス(true:OK/false:NG)
            $response["status"] = $ret['status'];    

            // コミット
            DB::commit();


        // 例外処理
        } catch (\Throwable $e) {

            // rollback
            DB::rollback();

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
     * 削除:sql(契約詳細)
     *
     * @param Request $request
     * @return void
     */
    private function deleteContractDetail(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $contract_detail_id = $request->input('contract_detail_id');

            $str = "delete from contract_details "
            ."where contract_detail_id = $contract_detail_id; ";
            Log::debug('deleteContractDetail_sql:' .$str);
    
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
     * 削除:sql(同居人)
     *
     * @param Request $request
     * @return void
     */
    private function deleteContractDetailHousemate(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $contract_detail_id = $request->input('contract_detail_id');
            
            $str = "delete from contract_housemates "
            ."where contract_detail_id = $contract_detail_id; ";

            Log::debug('deleteContractDetailHousemate_sql:' .$str);
    
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
     * 削除:sql(特約事項)
     *
     * @param Request $request
     * @return void
     */
    private function deleteSpecialContract(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $contract_detail_id = $request->input('contract_detail_id');
            
            $str = "delete from special_contract_details "
            ."where contract_detail_id = $contract_detail_id; ";

            Log::debug('deleteSpecialContract_sql:' .$str);
    
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