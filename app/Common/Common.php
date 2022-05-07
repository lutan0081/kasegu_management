<?php
namespace App\Common;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class Common
{   
    /**
     * 月度
     *
     * @return void
     */
    public function getMonth() {
        Log::debug('log_start:'.__FUNCTION__);
        
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = (int) $i;
        }

        Log::debug('log_end:'.__FUNCTION__);
        return $months; 
    }

    /**
     * 年度(前後5年取得)
     *
     * @return void
     */
    public function getFiveYear() {
        Log::debug('log_start:'.__FUNCTION__);
        
        $days = [];
        $start = date('Y', strtotime('-2 year'));
        $end = date('Y', strtotime('+2 year'));

        for ($i = $start; $i <= $end; $i++) {
            $days[] = (int) $i;
        }

        Log::debug('log_end:'.__FUNCTION__);
        return $days; 
    }

    /**
     * 申込進捗状況
     *
     * @return $ret(契約進捗状況リスト)
     */
    public function getContractProgress(){
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from contract_progress "
        ."order by sort_id asc ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 申込区分リスト作成
     *
     * @return $ret(申込区分リスト)
     */
    public function getApplicationTypes() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from application_types "
        ."order by sort_id asc ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 申込種別リスト作成
     *
     * @return $ret(申込種別リスト)
     */
    public function getApplicationUses() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from application_uses "
        ."order by sort_id asc ";
        Log::debug('sql:'.$str);

        $ret = DB::select($str);
        
        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 続柄リスト作成
     *
     * @return $ret(続柄リスト)
     */
    public function getLinks() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from links "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 健康保険リスト作成
     *
     * @return $ret(健康保険リスト)
     */
    public function getInsurances() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from insurances "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 性別リスト作成
     *
     * @return $ret(性別リスト作成)
     */
    public function getSexes() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from sexes "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 有無リスト作成
     *
     * @return $ret(健康保険リスト)
     */
    public function getNeeds() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from needs "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 画像種別リスト作成
     *
     * @return $ret(画像種別リスト作成)
     */
    public function getImgType() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * "
        ."from img_types "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 宅地建物取引士リスト作成
     *
     * @return $ret(宅地建物取引士リスト作成)
     */
    public function getUserLicense($request) {
        Log::debug('log_start:'.__FUNCTION__);

        // 値取得
        $session_id = $request->session()->get('create_user_id');

        $user_license_id = $request->input('user_license_id');

        $where = '';

        // 免許情報がある場合の処理
        if($user_license_id !== null){

            $where = "and user_license_id = $user_license_id ";

        };

        $str = "select * from user_licenses "
        ."where create_user_id = $session_id "
        .$where;
        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 保証協会リスト作成
     *
     * @return $ret(保証協会リスト作成)
     */
    public function getGuarantyAssociation($request) {
        Log::debug('log_start:'.__FUNCTION__);

        // 値取得
        $session_id = $request->session()->get('create_user_id');

        // sql
        $str = "select * from guaranty_associations "
        ."where guaranty_associations.entry_user_id = $session_id "
        ."order by guaranty_association_id desc ";
        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 法務局リスト作成
     *
     * @return $ret(法務局リスト)
     */
    public function getLegalPlace($request) {
        Log::debug('log_start:'.__FUNCTION__);

        // 値取得
        $session_id = $request->session()->get('create_user_id');

        // sql
        $str = "select * from legal_places "
        ."where legal_places.entry_user_id = $session_id "
        ."order by legal_place_id desc ";
        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 契約進捗状況
     *
     * @return $ret(契約進捗状況リスト)
     */
    public function getContractDetailProgress(){
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from contract_detail_progress "
        ."order by sort_id asc ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 間取タイプ
     *
     * @return $ret(契約進捗状況リスト)
     */
    public function getRoomLayout(){
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from room_layouts "
        ."order by sort_id asc ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 構造
     *
     * @return $ret(契約進捗状況リスト)
     */
    public function getRealEstateStructure(){
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from real_estate_structures "
        ."order by sort_id asc ";

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 商号
     *
     * @return $ret(契約進捗状況リスト)
     */
    public function getCompanyLicense($request){
        Log::debug('log_start:'.__FUNCTION__);

        $session_id = $request->session()->get('create_user_id');
        
        $company_license_id = $request->input('company_license_id');

        $where = '';

        // 会社免許情報がある場合の処理
        if($company_license_id !== null){

            $where = "and company_license_id = $company_license_id ";

        };

        $str = "select "
        ."company_licenses.company_license_id as company_license_id , "
        ."company_licenses.create_user_id as create_user_id, "
        ."company_licenses.company_license_name as company_license_name, "
        ."company_licenses.company_license_representative as company_license_representative, "
        ."company_licenses.company_license_address as company_license_address, "
        ."company_licenses.company_license_tel as company_license_tel, "
        ."company_licenses.company_license_fax as company_license_fax, "
        ."company_licenses.company_license_number as company_license_number, "
        ."company_licenses.company_license_span as company_license_span, "
        ."company_licenses.company_nick_name as company_nick_name, "
        ."company_licenses.company_nick_address as company_nick_address, "
        ."company_licenses.user_license_id as user_license_id, "
        ."company_licenses.legal_place_id	as legal_place_id, "
        ."legal_places.legal_place_name as legal_place_name, "
        ."company_licenses.guaranty_association_id as guaranty_association_id, "
        ."guaranty_associations.guaranty_association_name as guaranty_association_name, "
        ."company_licenses.guaranty_association_region_id as guaranty_association_region_id, "
        ."guaranty_association_region.guaranty_association_name as guaranty_association_region_name, "
        ."company_licenses.entry_user_id as entry_user_id, "
        ."company_licenses.entry_date as entry_user_id, "
        ."company_licenses.update_user_id, "
        ."company_licenses.update_date "
        ."from "
        ."company_licenses "
        ."left join guaranty_associations on "
        ."guaranty_associations.guaranty_association_id = company_licenses.guaranty_association_id "
        ."left join guaranty_associations as guaranty_association_region on "
        ."guaranty_association_region.guaranty_association_id = company_licenses.guaranty_association_region_id "
        ."left join legal_places on "
        ."legal_places.legal_place_id = company_licenses.legal_place_id "
        ."where create_user_id = $session_id "
        .$where;

        Log::debug('sql:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 抵当権リスト作成
     *
     * @return $ret(抵当件リスト)
     */
    public function getRegiMortgages() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from regi_mortgages "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 飲料水
     *
     * @return $ret(飲料水の種別リスト)
     */
    public function getWater() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from waters "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * ガス
     *
     * @return $ret(ガスの種別リスト)
     */
    public function getGas() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from gas "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 排水
     *
     * @return $ret(ガスの種別リスト)
     */
    public function getWasteWater() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from waste_water "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 専用・共用
     *
     * @return $ret(ガスの種別リスト)
     */
    public function getExclusiveType() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from exclusive_types "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * ガス
     *
     * @return $ret(ガスの種別リスト)
     */
    public function getLimitUse() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from limit_uses "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 日割・月割
     *
     * @return $ret(ガスの種別リスト)
     */
    public function getCancelFeeCount() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from cancel_fee_counts "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 取引形態
     *
     * @return $ret(取引形態)
     */
    public function getTradeType() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from trade_types "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 銀行種別
     *
     * @return $ret(取引形態)
     */
    public function getBankType() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select * from bank_types "
        ."order by sort_id asc ";
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 銀行一覧取得
     *
     * @param Request $request
     * @return void
     */
    public function getBankList($request){

        Log::debug('log_start:'.__FUNCTION__);

        try{

            // フリーワード
            $free_word = $request->input('free_word');
            Log::debug('$free_word:' .$free_word);

            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('$session_id:' .$session_id);

            $str = "select "
            ."banks.bank_id as bank_id, "
            ."banks.bank_name as bank_name, "
            ."banks.bank_branch_name as bank_branch_name, "
            ."banks.bank_type_id as bank_type_id, "
            ."bank_types.bank_type_name as bank_type_name, "
            ."bank_number as bank_number, "
            ."bank_account_name as bank_account_name, "
            ."banks.entry_user_id as entry_user_id, "
            ."banks.entry_date as entry_date, "
            ."banks.update_user_id as update_user_id, "
            ."banks.updated as updated "
            ."from "
            ."banks "
            ."left join bank_types on "
            ."banks.bank_type_id = bank_types.bank_type_id ";

            // where句
            $where = "";

            // フリーワード
            if($free_word !== null){

                if($where == ""){

                    $where = "where ";

                }else{

                    $where = "and ";
                }

                $where = $where ."ifnull(bank_name,'') like '%$free_word%'";
            };

            // id
            if($where == ""){

                $where = $where ."where "
                ."banks.entry_user_id = '$session_id' ";

            }else{

                $where = $where ."and "
                ."banks.entry_user_id = '$session_id' ";
            }

            // order by句
            $order_by = "order by bank_id ";

            $str = $str .$where .$order_by;
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");

            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->paginate(10)->onEachSide(1);

        }catch(\Throwable $e) {

            throw $e;

        }finally{

        };

        Log::debug('log_end:'.__FUNCTION__);

        return $res;
    }

    /**
     * 0を空白表示
     * intはinsert時に0を登録されるため、0を空白に置換
     *
     * @param [type] $date
     * @param string $format
     * @return void
     */
    public static function zeroToSpace($val){

        if($val == '0'){

            return '';
        }

        return $val;
    }

    /**
     * 特約事項マスタ一覧取得
     *
     * @param [type] $request
     * @return void
     */
    public function getSpecialContract($request){
        Log::debug('log_start:'.__FUNCTION__);

        $session_id = $request->session()->get('create_user_id');

        $str = "select * from special_contracts "
        ."where entry_user_id = '$session_id' "
        ."order by sort_id asc ";
        Log::debug('str:'.$str);

        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 同居人一覧取得
     *
     * @param [type] $request
     * @return void
     */
    public function getContractHousemate($request){
        Log::debug('log_start:'.__FUNCTION__);

        // 値取得
        $session_id = $request->session()->get('create_user_id');

        $contract_detail_id = $request->input('contract_detail_id');

        /**
         * 新規=0/編集=1以上
         * 新規の場合、nullの為0を代入する
         */
        if($contract_detail_id == null){
            $contract_detail_id = 0;
        }

        $str = "select * from contract_housemates "
        ."where contract_housemates.contract_detail_id = $contract_detail_id "
        ."order by contract_housemate_id ";
        Log::debug('getContractHousemate_sql:'.$str);
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 区域内・区域外
     *
     * @return $ret(取引形態)
     */
    public function getInsideAndOutsideArea($request) {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select "
        ."inside_and_outside_area_id, "
        ."inside_and_outside_area_name, "
        ."sort_id, "
        ."entry_user_id, "
        ."entry_date, "
        ."update_user_id, "
        ."updated "
        ."from "
        ."kasegu_management.inside_and_outside_area "
        ."order by sort_id asc; ";
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 保証会社更新期間
     *
     * @return $ret(取引形態)
     */
    public function getGuaranteeUpdateSpan($request) {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select "
        ."* "
        ."from "
        ."guarantee_update_spans "
        ."order by sort_id asc; ";
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 個人又は法人
     *
     * @param [type] $request
     * @return void
     */
    public function getPrivateOrCompanies() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select "
        ."* "
        ."from "
        ."private_or_companies "
        ."order by sort_id asc; ";
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 保証会社一覧
     *
     * @param [type] $request
     * @return void
     */
    public function getGuaranteeCompanies() {
        Log::debug('log_start:'.__FUNCTION__);

        $str = "select "
        ."* "
        ."from "
        ."guarantee_companies "
        ."order by sort_id asc; ";
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret; 
    }

    /**
     * 銀行一覧取得
     *
     * @param Request $request
     * @return void
     */
    public function getUserList($request){

        Log::debug('log_start:'.__FUNCTION__);

        try{

            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('$session_id:' .$session_id);

            $str = "select "
            ."* "
            ."from "
            ."create_users "
            ."where "
            ."create_users.create_user_id = '$session_id' ";
            Log::debug('$sql:' .$str);

            $ret = DB::select($str);

        }catch(\Throwable $e) {

            throw $e;

        }finally{

        };

        Log::debug('log_end:'.__FUNCTION__);

        return $ret;
    }

    /**
     * 日付fフォーマット(年月日)
     * {{ Common::format_date($update->create_date,'Y年m月d日') }}
     * @return return date('Y/m/d', strtotime($date));
     */
    public static function format_date($date, $format='Y/m/d'){
        return date($format, strtotime($date));
    }

    // 年月日
    public static function format_date_jp($date){
        return self::format_date($date,'Y年m月d日');
    }

    // 年月日時分
    public static function format_date_min($date){
        return self::format_date($date,'Y年m月d日H時i分');
    }

    // 年-月-日
    public static function format_date_hy($date){
        return self::format_date($date,'Y-m-d');
    }
}