<?php

namespace App\Http\Controllers\Admin\App;

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

use App\Config;

use Illuminate\Support\Facades\Mail;

use Common;

/**
 * 申込管理
 */
class AdminAppController extends Controller
{   
    /**
     *  申込一覧(表示・検索)
     *
     * @param Request $request(フォームデータ)
     * @return view;
     */
    public function adminAppInit(Request $request)
    {   
        Log::debug('start:' .__FUNCTION__);

        try {            
            // 申込一覧取得
            $app_list = $this->getAppList($request);
            
            $common = new Common();

            // 契約進捗状況
            $contract_progress = $common->getContractProgress();

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminApp' ,$app_list,compact('contract_progress'));
    }

    /**
     * 申込一覧(sql)
     *
     * @return $ret(顧客情報)
     */
    private function getAppList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('$session_id:' .$session_id);

            // 値取得
            $free_word = $request->input('free_word');
            Log::debug('$free_word:' .$free_word);

            // 進捗状況
            $contract_progress_id = $request->input('contract_progress_id');
            Log::debug('$contract_progress_id:' .$contract_progress_id);

            // 日付始期
            $start_date = $request->input('start_date');
            Log::debug('$start_date:' .$start_date);

            // 日付終期
            $end_date = $request->input('end_date');
            Log::debug('$end_date:' .$end_date);

            // 全て表示、キャンセル
            $display_filter = $request->input('radio');
            Log::debug('$display_filter:' .$display_filter);

            $str = "select "
            ."applications.application_id as application_id , "
            ."broker_company_name as broker_company_name , "
            ."broker_tel as broker_tel , "
            ."broker_mail as broker_mail , "
            ."real_estate_name as real_estate_name , "
            ."room_name as room_name , "
            ."entry_contracts.entry_contract_name as entry_contract_name, "
            ."entry_contracts.entry_contract_mobile_tel as entry_contract_mobile_tel, "
            ."contract_start_date as contract_start_date, "
            ."contract_progress_name as contract_progress_name, "
            ."applications.contract_progress_id as contract_progress_id "
            ."from applications "
            ."left join entry_contracts "
            ."on applications.application_id = entry_contracts.application_id "
            ."left join contract_progress "
            ."on contract_progress.contract_progress_id = applications.contract_progress_id ";

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
                $where = $where ."ifnull(broker_company_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(real_estate_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(entry_contract_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(entry_contract_mobile_tel,'') like '%$free_word%'";
            };

            // 進捗状況
            if($contract_progress_id !== null){

                if($where == ""){

                    $where = "where ";

                }else{
                    
                    $where = "and ";
                }

                // ユーザ名、仲介業者名、物件名、契約者名
                $where = $where ."applications.contract_progress_id = '$contract_progress_id' ";
            };

            // 始期終期
            if($start_date !== null && $end_date !== null){

                Log::debug('始期・終期選択の処理');

                if($where == ""){

                    $where = "where ";

                }else{
                    
                    $where = "and ";
                }

                $where = $where ."(applications.contract_start_date >= '$start_date') "
                ."and "
                ."(applications.contract_start_date <= '$end_date')";
            
            }else{

                // 始期
                if($start_date !== null){

                    Log::debug('始期の処理');

                    if($where == ""){

                        $where = "where ";
    
                    }else{
                        
                        $where = "and ";
                    }

                    $where = $where ."applications.contract_start_date >= '$start_date' ";

                }

                // 終期
                if($end_date !== null){

                    Log::debug('終期の処理');

                    if($where == ""){

                        $where = "where ";
    
                    }else{
                        
                        $where = "and ";
                    }

                    $where = $where ."applications.contract_start_date <= '$end_date' ";

                }

            }

            // 全て表示、キャンセル
            if($display_filter == 1){

                if($where == ""){

                    $where = "where ";

                }else{
                    
                    $where = "and ";
                }

                $where = $where ."applications.contract_progress_id = 7 ";
            };

            // id
            if($where == ""){

                $where = $where ."where "
                ."applications.entry_user_id = '$session_id' ";

            }else{

                $where = $where ."and "
                ."applications.entry_user_id = '$session_id' ";
            }  

            $str = $str .$where;
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
     * 新規(表示)
     *
     * @return void
     */
    public function adminAppNewInit(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {
            // 申込情報取得(新規:空データ)
            $app_info = $this->getAppNewList();
            $app_list = $app_info;

            // 入居者一覧取得(空配列)
            $houseMate_list = [];

            // 写真一覧取得(空配列)
            $img_list = [];

            $common = new Common();

            // 契約進捗状況
            $contract_progress = $common->getContractProgress();
            
            // 申込区分リスト
            $app_types = $common->getApplicationTypes();

            // 申込種別リスト
            $app_uses = $common->getApplicationUses();

            // 続柄リスト
            $app_links = $common->getLinks();

            // 健康保険リスト
            $app_insurances = $common->getInsurances();

            // 性別
            $app_sexes = $common->getSexes();

            // 有無
            $needs = $common->getNeeds();

            // 画像種別
            $img_type = $common->getImgType();

            // 個人又は法人
            $private_or_companies = $common->getPrivateOrCompanies();

            // 保証会社一覧
            $guarantee_companies = $common->getGuaranteeCompanies();

            // ユーザ情報
            // $users = $common->getUserList($request)[0];

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backAppEdit' ,compact('contract_progress' ,'app_types' ,'app_uses' ,'app_links' ,'app_insurances' ,'needs' ,'app_sexes' ,'app_list' ,'houseMate_list' ,'img_type' ,'img_list' ,'private_or_companies' ,'guarantee_companies'));    
    }
    
    /**
     * 新規(ダミー値取得)
     *
     * @return $ret(空の配列)
     */
    private function getAppNewList(){
        Log::debug('log_start:'.__FUNCTION__);
        $obj = new \stdClass();
        
        // 募集要項
        $obj->application_id  = '';
        $obj->contract_progress_id = '';
        $obj->broker_company_name = '';
        $obj->broker_name = '';
        $obj->broker_tel = '';
        $obj->broker_mail = '';
        $obj->application_type_name = '';
        $obj->application_type_id = '';
        $obj->application_use_name = '';
        $obj->application_use_id = '';
        $obj->contract_start_date = '';
        $obj->real_estate_name = '';
        $obj->real_estate_ruby = '';
        $obj->room_name = '';
        $obj->post_number = '';
        $obj->address = '';
        $obj->pet_kind = '';
        $obj->pet_bleed = '';
        $obj->bicycle_number = '';
        $obj->car_number = '';
        $obj->security_fee = '';
        $obj->deposit_fee = '';
        $obj->key_fee = '';
        $obj->refund_fee = '';
        $obj->rent_fee = '';
        $obj->service_fee = '';
        $obj->water_fee = '';
        $obj->ohter_fee = '';
        $obj->total_fee = '';
        $obj->guarantor_flag = '';

        // 契約者
        $obj->entry_contract_name = '';
        $obj->entry_contract_ruby = '';
        $obj->entry_contract_post_number = '';
        $obj->entry_contract_address = '';
        $obj->entry_contract_sex_id = '';
        $obj->entry_contract_birthday = '';
        $obj->entry_contract_age = '';
        $obj->entry_contract_home_tel = '';
        $obj->entry_contract_mobile_tel = '';
        $obj->entry_contract_business_name = '';
        $obj->entry_contract_business_ruby = '';
        $obj->entry_contract_business_post_number = '';
        $obj->entry_contract_business_address = '';
        $obj->entry_contract_business_tel = '';
        $obj->entry_contract_business_type = '';
        $obj->entry_contract_business_line = '';
        $obj->entry_contract_business_status = '';
        $obj->entry_contract_business_year = '';
        $obj->entry_contract_income = '';
        $obj->entry_contract_insurance_type_id = '';

        // 同居人
        $obj->housemate_id = '';
        $obj->housemate_name = '';
        $obj->housemate_ruby = '';
        $obj->housemate_link_id = '';
        $obj->housemate_sex_id = '';
        $obj->housemate_age = '';
        $obj->housemate_birthday = '';
        $obj->housemate_post_number = '';
        $obj->housemate_address = '';
        $obj->housemate_home_tel = '';
        $obj->housemate_mobile_tel = '';

        // 緊急連絡先
        $obj->emergency_name = '';
        $obj->emergency_ruby = '';
        $obj->emergency_sex_id = '';
        $obj->emergency_link_id = '';
        $obj->emergency_birthday = '';
        $obj->emergency_age = '';
        $obj->emergency_post_number = '';
        $obj->emergency_address = '';
        $obj->emergency_home_tel = '';
        $obj->emergency_mobile_tel = '';

        // 連帯保証人
        $obj->guarantor_name = '';
        $obj->guarantor_ruby = '';
        $obj->guarantor_sex_id = '';
        $obj->guarantor_link_id = '';
        $obj->guarantor_birthday = '';
        $obj->guarantor_age = '';
        $obj->guarantor_post_number = '';
        $obj->guarantor_address = '';
        $obj->guarantor_home_tel = '';
        $obj->guarantor_mobile_tel = '';
        $obj->guarantor_business_name = '';
        $obj->guarantor_business_ruby = '';
        $obj->guarantor_business_post_number = '';
        $obj->guarantor_business_address = '';
        $obj->guarantor_business_tel = '';
        $obj->guarantor_business_type = '';
        $obj->guarantor_business_line = '';
        $obj->guarantor_business_status = '';
        $obj->guarantor_business_years = '';
        $obj->guarantor_income = '';
        $obj->guarantor_insurance_type_id = '';
        $obj->private_or_company_id = '';
        $ret = [];
        $ret = $obj;

        

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 登録分岐(新規/編集)
     *
     * @param $request(edit.blade.phpの各項目)
     * @return $response(status:true=OK/false=NG)
     */
    public function adminAppEditEntry(Request $request){
        
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

        if($response["status"] == false){

            Log::debug('validator_status:falseのif文通過');

            return response()->json($response);

        }

        /**
         * id=無:insert
         * id=有:update
         */
        $application_id = $request->input('application_id');

        // 新規登録
        if($request->input('application_id') == ""){

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

        /**
         * 値取得
         */
        $guarantor_flag = $request->input('guarantor_flag');

        // returnの出力値
        $response = [];

        // 初期値
        $response["status"] = true;

        /**
         * rules
         */
        // 募集要項
        $rules = [];
        $rules['broker_company_name'] = "required|max:100";
        $rules['broker_tel'] = "required|jptel";
        $rules['broker_mail'] = "required|email";
        $rules['broker_name'] = "required|max:50";


        // 不動産業者
        $rules['real_estate_name'] = "required|max:50";
        $rules['real_estate_ruby'] = "required|max:100";
        $rules['room_name'] = "required|max:10";
        $rules['post_number'] = "required|zip";
        $rules['address'] = "required|max:200";
        $rules['pet_kind'] = "nullable|max:10";
        $rules['bicycle_number'] = "required|integer";
        $rules['car_number_number'] = "required|integer";
        $rules['contract_start_date'] = "required|date";

        // 賃借人
        $rules['entry_contract_name'] = "required|max:100";
        $rules['entry_contract_ruby'] = "required|max:200";
        $rules['entry_contract_post_number'] = "required|zip";
        $rules['entry_contract_address'] = "required|max:100";
        $rules['entry_contract_sex_id'] = "required|integer";
        $rules['entry_contract_birthday'] = "required|date";
        $rules['entry_contract_age'] = "required|integer";
        $rules['entry_contract_home_tel'] = "required|jptel";
        $rules['entry_contract_mobile_tel'] = "nullable|jptel";
        $rules['entry_contract_business_name'] = "required|max:50";
        $rules['entry_contract_business_ruby'] = "required|max:100";
        $rules['entry_contract_business_post_number'] = "required|zip";
        $rules['entry_contract_business_address'] = "required|max:150";
        $rules['entry_contract_business_tel'] = "required|jptel";
        $rules['entry_contract_business_type'] = "required|max:20";
        $rules['entry_contract_business_line'] = "required|max:20";
        $rules['entry_contract_business_status'] = "required|max:10";
        $rules['entry_contract_business_year'] = "required|integer";
        $rules['entry_contract_income'] = "required|max:10";
        $rules['entry_contract_insurance_type_id'] = "required|integer";

        /**
         * 同居人
         */
        $rules['housemate_name'] = "nullable|max:100";
        $rules['housemate_ruby'] = "nullable|max:200";
        $rules['housemate_birthday'] = "nullable|date|max:100";
        $rules['housemate_age'] = "nullable|integer";
        $rules['housemate_home_tel'] = "nullable|jptel";
        $rules['housemate_mobile_tel'] = "nullable|jptel";

        /**
         * 緊急連絡先
         */
        $rules['emergency_name'] = "required|max:100";
        $rules['emergency_ruby'] = "required|max:200";
        $rules['emergency_birthday'] = "required|date|max:100";
        $rules['emergency_age'] = "required|max:20";
        $rules['emergency_post_number'] = "required|zip";
        $rules['emergency_address'] = "required|max:100";
        $rules['emergency_home_tel'] = "required|jptel";
        $rules['emergency_mobile_tel'] = "nullable|jptel";

        /**
         * 連帯保証人
         */
        // 1=連帯保証人有りの場合のみ必須にする
        if($guarantor_flag == 1){

            Log::debug('連帯保証人有りの場合の処理');

            $rules['guarantor_name'] = "required|max:100";
            $rules['guarantor_ruby'] = "required|max:200";
            $rules['guarantor_post_number'] = "required|zip";
            $rules['guarantor_address'] = "required|max:150";
            $rules['guarantor_birthday'] = "required|date";
            $rules['guarantor_age'] = "required|integer";
            $rules['guarantor_home_tel'] = "required|jptel";
            $rules['guarantor_mobile_tel'] = "nullable|jptel";
            $rules['guarantor_business_name'] = "required|max:50";
            $rules['guarantor_business_ruby'] = "required|max:100";
            $rules['guarantor_business_post_number'] = "required|zip";
            $rules['guarantor_business_address'] = "required|max:150";
            $rules['guarantor_business_tel'] = "required|jptel";
            $rules['guarantor_business_type'] = "required|max:10";
            $rules['guarantor_business_line'] = "required|max:10";
            $rules['guarantor_business_years'] = "required|integer";
            $rules['guarantor_business_status'] = "required|max:10";
            $rules['guarantor_income'] = "required|max:10";
        };
        
        // 2=連帯保証人無しの場合の処理
        if($guarantor_flag == 2){
            
            Log::debug('連帯保証人無しの場合の処理');

            $rules['guarantor_name'] = "nullable|max:100";
            $rules['guarantor_ruby'] = "nullable|max:200";
            $rules['guarantor_post_number'] = "nullable|zip";
            $rules['guarantor_address'] = "nullable|max:150";
            $rules['guarantor_birthday'] = "nullable|date";
            $rules['guarantor_age'] = "nullable|integer";
            $rules['guarantor_home_tel'] = "nullable|jptel";
            $rules['guarantor_mobile_tel'] = "nullable|jptel";
            $rules['guarantor_business_name'] = "nullable|max:50";
            $rules['guarantor_business_ruby'] = "nullable|max:100";
            $rules['guarantor_business_post_number'] = "nullable|zip";
            $rules['guarantor_business_address'] = "nullable|max:150";
            $rules['guarantor_business_tel'] = "nullable|jptel";
            $rules['guarantor_business_type'] = "nullable|max:10";
            $rules['guarantor_business_line'] = "nullable|max:10";
            $rules['guarantor_business_years'] = "nullable|integer";
            $rules['guarantor_business_status'] = "nullable|max:10";
            $rules['guarantor_income'] = "nullable|max:10";
        };
    
        /**
         * 画像
         * nullableが効かない為、if文で判定
         */
        $img_file = $request->file('img_file');
        Log::debug('バリデーション_img_file:' .$img_file);

        if($img_file !== null){

            Log::debug('画像が添付されています');
            $rules['img_file'] = "nullable|mimes:jpeg,png,jpg";

        }
    
        $rules['img_text'] = "nullable|max:20";

        /**
         * messages
         */
        $messages = [];

        // 不動産業者
        $messages['broker_company_name.required'] = "仲介業者名は必須です。";
        $messages['broker_company_name.max'] = "仲介業者名の文字数が超過しています。";

        $messages['broker_tel.required'] = "仲介業者Telは必須です。";
        $messages['broker_tel.jptel'] = "仲介業者Telの形式が不正です。";

        $messages['broker_mail.required'] = "仲介業者E-mailは必須です。";
        $messages['broker_mail.email'] = "仲介業者E-mailの形式が不正です。";

        $messages['broker_name.required'] = "担当者は必須です。";
        $messages['broker_name.max'] = "担当者の文字数が超過しています。";

        // 募集要項
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

        $messages['contract_start_date.required'] = "入居開始日は必須です。";
        $messages['contract_start_date.date'] = "入居開始日の形式が不正です。";

        // 契約者
        $messages['entry_contract_name.required'] = "契約者は必須です。";
        $messages['entry_contract_name.max'] = "契約者の文字数が超過しています。";

        $messages['entry_contract_ruby.required'] = "契約者は必須です。";
        $messages['entry_contract_ruby.max'] = "契約者カナの文字数が超過しています。";

        $messages['entry_contract_post_number.required'] = "郵便番号は必須です。";
        $messages['entry_contract_post_number.zip'] = "郵便番号の形式が不正です。";

        $messages['entry_contract_address.required'] = "住所は必須です。";
        $messages['entry_contract_address.max'] = "住所の文字数が超過しています。";

        $messages['entry_contract_sex_id.required'] = "性別は必須です。";
        $messages['entry_contract_sex_id.integer'] = "性別の形式が不正です。";

        $messages['entry_contract_birthday.required'] = "生年月日は必須です。";
        $messages['entry_contract_birthday.date'] = "生年月日の形式が不正です。";

        $messages['entry_contract_age.required'] = "年齢の形式が不正です。";
        $messages['entry_contract_age.integer'] = "年齢の形式が不正です。";

        $messages['entry_contract_home_tel.required'] = "電話番号は必須です。";
        $messages['entry_contract_home_tel.jptel'] = "電話番号の形式が不正です。";

        $messages['entry_contract_mobile_tel.jptel'] = "電話番号2の形式が不正です。";
        
        $messages['entry_contract_business_name.required'] = "勤務先名称は必須です。";
        $messages['entry_contract_business_name.max'] = "勤務先名称の文字数が超過しています。";

        $messages['entry_contract_business_ruby.required'] = "勤務先カナは必須です。";
        $messages['entry_contract_business_ruby.max'] = "勤務先カナの文字数が超過しています。";

        $messages['entry_contract_business_post_number.required'] = "郵便番号は必須です。";
        $messages['entry_contract_business_post_number.zip'] = "郵便番号の形式が不正です。";

        $messages['entry_contract_business_address.required'] = "所在地は必須です。";
        $messages['entry_contract_business_address.max'] = "所在地の文字数が超過しています。";

        $messages['entry_contract_business_tel.required'] = "勤務先Telは必須です。";
        $messages['entry_contract_business_tel.jptel'] = "勤務先Telの形式が不正です。";

        $messages['entry_contract_business_type.required'] = "業種のは必須です。";
        $messages['entry_contract_business_type.max'] = "業種の文字数が超過しています。";

        $messages['entry_contract_business_line.required'] = "職種は必須です。";
        $messages['entry_contract_business_line.max'] = "職種の文字数が超過しています。";

        $messages['entry_contract_business_status.required'] = "雇用形態は必須です。";
        $messages['entry_contract_business_status.max'] = "雇用形態の文字数が超過しています。";

        $messages['entry_contract_business_year.required'] = "勤続年数は必須です。";
        $messages['entry_contract_business_year.integer'] = "勤続年数の形式が不正です。";

        $messages['entry_contract_income.required'] = "年収は必須です。";
        $messages['entry_contract_income.integer'] = "年収の形式が不正です。";

        $messages['entry_contract_insurance_type_id.required'] = "健康保険は必須です。";
        $messages['entry_contract_insurance_type_id.integer'] = "健康保険の形式が不正です。";

        // 同居人
        $messages['housemate_name.max'] = "同居人名の文字数が超過しています。";
        $messages['housemate_ruby.max'] = "同居人名カナの文字数が超過しています。";
        $messages['housemate_birthday.date'] = "生年月日の形式が不正です。";
        $messages['housemate_birthday.max'] = "生年月日の文字数が超過しています。";
        $messages['housemate_age.max'] = "年齢の文字数が超過しています。";
        $messages['housemate_home_tel.jptel'] = "電話番号の形式が不正です。";
        $messages['housemate_mobile_tel.jptel'] = "電話番号2の形式が不正です。";

        // 緊急連絡先
        $messages['emergency_name.required'] = "緊急連絡先は必須です。";
        $messages['emergency_name.max'] = "緊急連絡先の文字数が超過しています。";

        $messages['emergency_ruby.required'] = "緊急連絡先名カナは必須です。";
        $messages['emergency_ruby.max'] = "緊急連絡先名カナの文字数が超過しています。";
        
        $messages['emergency_birthday.required'] = "生年月日は必須です。";
        $messages['emergency_birthday.date'] = "生年月日の形式が不正です。";
        $messages['emergency_birthday.max'] = "生年月日の文字数が超過しています。";

        $messages['emergency_age.required'] = "年齢は必須です。";
        $messages['emergency_age.max'] = "年齢の文字数が超過しています。";

        $messages['emergency_post_number.required'] = "郵便番号は必須です。";
        $messages['emergency_post_number.zip'] = "郵便番号の形式が不正です。";

        $messages['emergency_address.required'] = "住所は必須です。";
        $messages['emergency_address.max'] = "住所の文字数が超過しています。";
        
        $messages['emergency_home_tel.required'] = "電話番号は必須です。";
        $messages['emergency_home_tel.jptel'] = "電話番号の形式が不正です。";

        $messages['emergency_mobile_tel.jptel'] = "電話番号2の形式が不正です。";

        /**
         * 連帯保証人
         */
        // 1=連帯保証人有りの場合のみ必須にする
        if($guarantor_flag == 1){

            Log::debug('連帯保証人有りの場合の処理');

            $messages['guarantor_name.required'] = "連帯保証人は必須です。";
            $messages['guarantor_name.max'] = "連帯保証人の文字数が超過しています。";
    
            $messages['guarantor_ruby.required'] = "連帯保証人カナは必須です。";
            $messages['guarantor_ruby.max'] = "連帯保証人カナの文字数が超過しています。";
    
            $messages['guarantor_post_number.required'] = "郵便番号は必須です。";
            $messages['guarantor_post_number.zip'] = "郵便番号の形式が不正です。";
    
            $messages['guarantor_address.required'] = "住所は必須です。";
            $messages['guarantor_address.max'] = "住所の文字数が超過しています。";
    
            $messages['guarantor_birthday.required'] = "生年月日は必須です。";
            $messages['guarantor_birthday.date'] = "生年月日の形式が不正です。";
    
            $messages['guarantor_age.required'] = "年齢は必須です。";
            $messages['guarantor_age.integer'] = "年齢の形式が不正です。";
    
            $messages['guarantor_home_tel.required'] = "電話番号は必須です。";
            $messages['guarantor_home_tel.jptel'] = "電話番号の形式が不正です。";
    
            $messages['guarantor_mobile_tel.jptel'] = "電話番号2の形式が不正です。";
    
            $messages['guarantor_business_name.required'] = "勤務先名は必須です。";
            $messages['guarantor_business_name.max'] = "勤務先名の文字数が超過しています。";
    
            $messages['guarantor_business_ruby.required'] = "勤務先カナは必須です。";
            $messages['guarantor_business_ruby.max'] = "勤務先名カナの文字数が超過しています。";
    
            $messages['guarantor_business_post_number.required'] = "郵便番号は必須です。";
            $messages['guarantor_business_post_number.zip'] = "郵便番号の形式が不正です。";
    
            $messages['guarantor_business_address.required'] = "所在地は必須です。";
            $messages['guarantor_business_address.max'] = "所在地の文字数が超過しています。";
    
            $messages['guarantor_business_tel.required'] = "勤務先電話番号は必須です。";
            $messages['guarantor_business_tel.jptel'] = "勤務先電話番号の形式が不正です。";
    
            $messages['guarantor_business_type.required'] = "業種は必須です。";
            $messages['guarantor_business_type.max'] = "業種の文字数が超過しています。";
    
            $messages['guarantor_business_line.required'] = "職種は必須です。";
            $messages['guarantor_business_line.max'] = "職種の文字数が超過しています。";
    
            $messages['guarantor_business_years.required'] = "勤続年数は必須です。";
            $messages['guarantor_business_years.integer'] = "勤続年数の形式が不正です。";
    
            $messages['guarantor_business_status.required'] = "雇用形態は必須です。";
            $messages['guarantor_business_status.max'] = "雇用形態の文字数が超過しています。";
    
            $messages['guarantor_income.required'] = "年数は必須です。";
            $messages['guarantor_income.integer'] = "年収の形式が不正です。";

        }

         // 2=連帯保証人無しの場合の処理
        if($guarantor_flag == 2){

            Log::debug('連帯保証人無しの場合の処理');

            $messages['guarantor_name.max'] = "連帯保証人の文字数が超過しています。";
    
            $messages['guarantor_ruby.max'] = "連帯保証人カナの文字数が超過しています。";
    
            $messages['guarantor_post_number.zip'] = "郵便番号の形式が不正です。";
    
            $messages['guarantor_address.max'] = "住所の文字数が超過しています。";
    
            $messages['guarantor_birthday.date'] = "生年月日の形式が不正です。";
    
            $messages['guarantor_age.integer'] = "年齢の形式が不正です。";
    
            $messages['guarantor_home_tel.jptel'] = "電話番号の形式が不正です。";
    
            $messages['guarantor_mobile_tel.jptel'] = "電話番号2の形式が不正です。";
    
            $messages['guarantor_business_name.max'] = "勤務先名の文字数が超過しています。";
    
            $messages['guarantor_business_ruby.max'] = "勤務先名カナの文字数が超過しています。";
    
            $messages['guarantor_business_post_number.zip'] = "郵便番号の形式が不正です。";
    
            $messages['guarantor_business_address.max'] = "所在地の文字数が超過しています。";
    
            $messages['guarantor_business_tel.jptel'] = "自宅Telの形式が不正です。";
    
            $messages['guarantor_business_type.max'] = "業種の文字数が超過しています。";
    
            $messages['guarantor_business_line.max'] = "職種の文字数が超過しています。";
    
            $messages['guarantor_business_years.integer'] = "勤続年数の形式が不正です。";
    
            $messages['guarantor_business_status.max'] = "雇用形態の文字数が超過しています。";
    
            $messages['guarantor_income.integer'] = "年収の形式が不正です。";
        }


        $img_file = $request->file('img_file');
        Log::debug('バリデーション_img_file:' .$img_file);

        if($img_file !== null){

            Log::debug('画像が添付されています');
            $messages['img_file.mimes'] = "画像ファイル(jpg.jpeg.png)でアップロードして下さい。";

        }
    
        $messages['img_text.max'] = "備考の文字数が超過しています。";
    
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
             * 不動産業者(status:OK=1 NG=0/application_id:新規登録のid)
             */
            $app_info = $this->insertApplication($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $app_info['status'];

            // 新規登録のapplication_idを取得
            $application_id = $app_info['application_id'];

            /**
             * 契約者(status:OK=1 NG=0)
             */
            $contract_info = $this->inserEntryContract($request ,$application_id);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $contract_info['status'];

            /**
             * 緊急連絡先(status:OK=1 NG=0)
             */
            $emergency_info = $this->inserEmergency($request ,$application_id);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $emergency_info['status'];

            /**
             * 連帯保証人(status:OK=1 NG=0)
             */
            $guarantor_info = $this->inserGuarantor($request ,$application_id);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $guarantor_info['status'];

            /**
             * 付属書類
             */
            $img_info = $this->insertImg($request ,$application_id);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $img_info['status'];

            // コミット
            DB::commit();

        // 例外処理
        } catch (\Throwable $e) {

            // ロールバック
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
     * 不動産業者(登録)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function insertApplication(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $contract_progress_id = $request->input('contract_progress_id');
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
            $private_or_company_id = $request->input('private_or_company_id');
            $guarantor_flag = $request->input('guarantor_flag');
            
            // 現在の日付取得
            $date = now() .'.000';
    
            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // 進捗状況
            if($contract_progress_id == null){
                $contract_progress_id =0;
            }

            // 個人又は法人
            if($private_or_company_id == null){
                $private_or_company_id =0;
            }
            
            // 申込区分
            if($application_type_id == null){
                $application_type_id =0;
            }

            // 申込種別
            if($application_use_id == null){
                $application_use_id =0;
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

            // ペット飼育数
            if($pet_bleed == null){
                $pet_bleed = 0;
            }

            // ペット種類
            if($pet_kind == null){
                $pet_kind = '';
            }

            // 駐輪台数
            if($bicycle_number == null){
                $bicycle_number =0;
            }

            // 駐車台数
            if($car_number_number == null){
                $car_number_number =0;
            }

            // 保証金
            if($deposit_fee == null){
                $deposit_fee =0;
            }

            // 解約引
            if($refund_fee == null){
                $refund_fee =0;
            }

            // 敷金
            if($security_fee == null){
                $security_fee =0;
            }

            // 礼金
            if($key_fee == null){
                $key_fee = 0;
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
            if($total_fee == null){
                $total_fee =0;
            }

            if($guarantor_flag == null){
                $guarantor_flag =0;
            }
            

            // 登録
            $str = "insert into "
            ."applications( "
            ."create_user_id, "
            ."application_type_id, "
            ."application_use_id, "
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
            ."private_or_company_id, "
            ."guarantor_flag, "
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
            ."$private_or_company_id, "
            ."$guarantor_flag, "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";
            Log::debug('insert_sql:'.$str);

            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

            // 登録したapplication_id取得
            $str = "select * "
            ."from applications "
            ."where "
            ."real_estate_name = '$real_estate_name' "
            ."and "
            ."entry_date = '$date' ";
            Log::debug('select_sql:'.$str);

            // ログ
            $app_info = DB::select($str);
            Log::debug($app_info);

            $ret['application_id'] = $app_info[0]->application_id;

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
     * 契約者(登録)
     *
     * @param Request $request
     * @return void
     */
    private function inserEntryContract(Request $request ,$application_id){
        Log::debug('log_start:'.__FUNCTION__);

        try {
            // returnの初期値
            $ret = [];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $entry_contract_name = $request->input('entry_contract_name');
            $entry_contract_ruby = $request->input('entry_contract_ruby');
            $entry_contract_post_number = $request->input('entry_contract_post_number');
            $entry_contract_address = $request->input('entry_contract_address');
            $entry_contract_sex_id = $request->input('entry_contract_sex_id');
            $entry_contract_birthday = $request->input('entry_contract_birthday');
            $entry_contract_age = $request->input('entry_contract_age');
            $entry_contract_home_tel = $request->input('entry_contract_home_tel');
            $entry_contract_mobile_tel = $request->input('entry_contract_mobile_tel');
            $entry_contract_business_name = $request->input('entry_contract_business_name');
            $entry_contract_business_ruby = $request->input('entry_contract_business_ruby');
            $entry_contract_business_post_number = $request->input('entry_contract_business_post_number');
            $entry_contract_business_address = $request->input('entry_contract_business_address');
            $entry_contract_business_tel = $request->input('entry_contract_business_tel');
            $entry_contract_business_type = $request->input('entry_contract_business_type');
            $entry_contract_business_line = $request->input('entry_contract_business_line');
            $entry_contract_business_status = $request->input('entry_contract_business_status');
            $entry_contract_business_year = $request->input('entry_contract_business_year');
            $entry_contract_income = $request->input('entry_contract_income');
            $entry_contract_insurance_type_id = $request->input('entry_contract_insurance_type_id');
            // 現在の日付取得
            $date = now() .'.000';

            // 契約者名
            if($entry_contract_name == null){
                $entry_contract_name = "";
            }

            // 契約者フリガナ
            if($entry_contract_ruby == null){
                $entry_contract_ruby = "";
            }

            // 郵便番号
            if($entry_contract_post_number == null){
                $entry_contract_post_number = '';
            }

            // 住所
            if($entry_contract_address == null){
                $entry_contract_address = "";
            }

            // 性別
            if($entry_contract_sex_id == null){
                $entry_contract_sex_id = 0;
            }

            // 生年月日
            if($entry_contract_birthday == null){
                $entry_contract_birthday = '';
            }

            // 年齢
            if($entry_contract_age == null){
                $entry_contract_age = 0;
            }

            // 自宅電話番号
            if($entry_contract_home_tel == null){
                $entry_contract_home_tel = "";
            }

            // 携帯電話番号
            if($entry_contract_mobile_tel == null){
                $entry_contract_mobile_tel = "";
            }

            // 勤務先名
            if($entry_contract_business_name == null){
                $entry_contract_business_name = "";
            }

            // 勤務先名カナ
            if($entry_contract_business_ruby == null){
                $entry_contract_business_ruby = "";
            }

            // 勤務先郵便番号
            if($entry_contract_business_post_number == null){
                $entry_contract_business_post_number = '';
            }

            // 勤務先住所
            if($entry_contract_business_address == null){
                $entry_contract_business_address = "";
            }

            // 勤務先電話番号
            if($entry_contract_business_tel == null){
                $entry_contract_business_tel = "";
            }

            // 業種
            if($entry_contract_business_type == null){
                $entry_contract_business_type = "";
            }

            // 職種
            if($entry_contract_business_line == null){
                $entry_contract_business_line = "";
            }

            // 雇用形態
            if($entry_contract_business_status == null){
                $entry_contract_business_status = "";
            }

            // 勤続年数
            if($entry_contract_business_year == null){
                $entry_contract_business_year = 0;
            }

            // 収入
            if($entry_contract_income == null){
                $entry_contract_income = 0;
            }

            // 保険種別
            if($entry_contract_insurance_type_id == null){
                $entry_contract_insurance_type_id = 0;
            }
    
            $str = "insert into entry_contracts "
            ."( "
            ."application_id, "
            ."entry_contract_name, "
            ."entry_contract_ruby, "
            ."entry_contract_sex_id, "
            ."entry_contract_birthday, "
            ."entry_contract_age, "
            ."entry_contract_post_number, "
            ."entry_contract_address, "
            ."entry_contract_home_tel, "
            ."entry_contract_mobile_tel, "
            ."entry_contract_business_name, "
            ."entry_contract_business_ruby, "
            ."entry_contract_business_post_number, "
            ."entry_contract_business_address, "
            ."entry_contract_business_tel, "
            ."entry_contract_business_type, "
            ."entry_contract_business_line, "
            ."entry_contract_business_year, "
            ."entry_contract_business_status, "
            ."entry_contract_income, "
            ."entry_contract_insurance_type_id, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$application_id, "
            ."'$entry_contract_name', "
            ."'$entry_contract_ruby', "
            ."$entry_contract_sex_id, "
            ."'$entry_contract_birthday', "
            ."$entry_contract_age, "
            ."'$entry_contract_post_number', "
            ."'$entry_contract_address', "
            ."'$entry_contract_home_tel', "
            ."'$entry_contract_mobile_tel', "
            ."'$entry_contract_business_name', "
            ."'$entry_contract_business_ruby', "
            ."'$entry_contract_business_post_number', "
            ."'$entry_contract_business_address', "
            ."'$entry_contract_business_tel', "
            ."'$entry_contract_business_type', "
            ."'$entry_contract_business_line', "
            ."'$entry_contract_business_year', "
            ."'$entry_contract_business_status', "
            ."'$entry_contract_income', "
            ."$entry_contract_insurance_type_id, "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";
            Log::debug('sql:'.$str);
            
            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

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
     * 緊急連絡先(登録)
     *
     * @param Request $request
     * @return void
     */
    private function inserEmergency(Request $request ,$application_id){
        Log::debug('log_start:'.__FUNCTION__);

        try {
            // returnの初期値
            $ret = [];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $emergency_name = $request->input('emergency_name');
            $emergency_ruby = $request->input('emergency_ruby');
            $emergency_sex_id = $request->input('emergency_sex_id');
            $emergency_link_id = $request->input('emergency_link_id');
            $emergency_birthday = $request->input('emergency_birthday');
            $emergency_age = $request->input('emergency_age');
            $emergency_post_number = $request->input('emergency_post_number');
            $emergency_address = $request->input('emergency_address');
            $emergency_home_tel = $request->input('emergency_home_tel');
            $emergency_mobile_tel = $request->input('emergency_mobile_tel');

            // 現在の日付取得
            $date = now() .'.000';

            // 緊急連絡先名
            if($emergency_name == null){
                $emergency_name = "";
            }

            // 緊急連絡先フリガナ
            if($emergency_ruby == null){
                $emergency_ruby = "";
            }

            // 性別
            if($emergency_sex_id == null){
                $emergency_sex_id = 0;
            }

            // 続柄
            if($emergency_link_id == null){
                $emergency_link_id = 0;
            }

            // 生年月日
            if($emergency_birthday == null){
                $emergency_birthday = '';
            }

            // 年齢
            if($emergency_age == null){
                $emergency_age = 0;
            }

            // 郵便番号
            if($emergency_post_number == null){
                $emergency_post_number = '';
            }

            // 住所
            if($emergency_address == null){
                $emergency_address = "";
            }

            // 自宅電話番号
            if($emergency_home_tel == null){
                $emergency_home_tel = "";
            }

            // 携帯電話番号
            if($emergency_mobile_tel == null){
                $emergency_mobile_tel = "";
            }

            $str = "insert into emergencies( "
            ."application_id, "
            ."emergency_name, "
            ."emergency_ruby, "
            ."emergency_link_id, "
            ."emergency_sex_id, "
            ."emergency_birthday, "
            ."emergency_age, "
            ."emergency_post_number, "
            ."emergency_address, "
            ."emergency_home_tel, "
            ."emergency_mobile_tel, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$application_id, "
            ."'$emergency_name', "
            ."'$emergency_ruby', "
            ."$emergency_link_id, "
            ."$emergency_sex_id, "
            ."'$emergency_birthday', "
            ."$emergency_age, "
            ."'$emergency_post_number', "
            ."'$emergency_address', "
            ."'$emergency_home_tel', "
            ."'$emergency_mobile_tel', "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";
            
            Log::debug('sql:'.$str);
            
            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

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
     * 連帯保証人(登録)
     *
     * @param Request $request
     * @return void
     */
    private function inserGuarantor(Request $request ,$application_id){
        Log::debug('log_start:'.__FUNCTION__);

        try {
            // returnの初期値
            $ret = [];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $guarantor_name = $request->input('guarantor_name');
            $guarantor_ruby = $request->input('guarantor_ruby');
            $guarantor_post_number = $request->input('guarantor_post_number');
            $guarantor_address = $request->input('guarantor_address');
            $guarantor_sex_id = $request->input('guarantor_sex_id');
            $guarantor_birthday = $request->input('guarantor_birthday');
            $guarantor_age = $request->input('guarantor_age');
            $guarantor_link_id = $request->input('guarantor_link_id');
            $guarantor_home_tel = $request->input('guarantor_home_tel');
            $guarantor_mobile_tel = $request->input('guarantor_mobile_tel');
            $guarantor_business_name = $request->input('guarantor_business_name');
            $guarantor_business_ruby = $request->input('guarantor_business_ruby');
            $guarantor_business_post_number = $request->input('guarantor_business_post_number');
            $guarantor_business_address = $request->input('guarantor_business_address');
            $guarantor_business_tel = $request->input('guarantor_business_tel');
            $guarantor_business_type = $request->input('guarantor_business_type');
            $guarantor_business_line = $request->input('guarantor_business_line');
            $guarantor_business_status = $request->input('guarantor_business_status');
            $guarantor_business_years = $request->input('guarantor_business_years');
            $guarantor_income = $request->input('guarantor_income');
            $guarantor_insurance_type_id = $request->input('guarantor_insurance_type_id');
            // 現在の日付取得
            $date = now() .'.000';

            // 連帯保証人名
            if($guarantor_name == null){
                $guarantor_name = "";
            }

            // 連帯保証人カナ
            if($guarantor_ruby == null){
                $guarantor_ruby = "";
            }

            // 郵便番号
            if($guarantor_post_number == null){
                $guarantor_post_number = '';
            }

            // 住所
            if($guarantor_address == null){
                $guarantor_address = "";
            }

            // 性別
            if($guarantor_sex_id == null){
                $guarantor_sex_id = 0;
            }

            // 生年月日
            if($guarantor_birthday == null){
                $guarantor_birthday = '';
            }

            // 年齢
            if($guarantor_age == null){
                $guarantor_age = 0;
            }

            // 続柄
            if($guarantor_link_id == null){
                $guarantor_link_id = 0;
            }

            // 自宅電話番号
            if($guarantor_home_tel == null){
                $guarantor_home_tel = "";
            }

            // 携帯電話番号
            if($guarantor_mobile_tel == null){
                $guarantor_mobile_tel = "";
            }

            // 勤務先名
            if($guarantor_business_name == null){
                $guarantor_business_name = "";
            }

            // 勤務先カナ
            if($guarantor_business_ruby == null){
                $guarantor_business_ruby = "";
            }

            // 勤務先郵便番号
            if($guarantor_business_post_number == null){
                $guarantor_business_post_number = "";
            }

            // 勤務先住所
            if($guarantor_business_address == null){
                $guarantor_business_address = "";
            }

            // 勤務先電話番号
            if($guarantor_business_tel == null){
                $guarantor_business_tel = "";
            }

            // 業種
            if($guarantor_business_type == null){
                $guarantor_business_type = "";
            }

            // 職種
            if($guarantor_business_line == null){
                $guarantor_business_line = "";
            }

            // 雇用形態
            if($guarantor_business_status == null){
                $guarantor_business_status = "";
            }

            // 勤続年数
            if($guarantor_business_years == null){
                $guarantor_business_years = 0;
            }

            // 年収
            if($guarantor_income == null){
                $guarantor_income = "";
            }

            // 健康保険
            if($guarantor_insurance_type_id == null){
                $guarantor_insurance_type_id = 0;
            }

            // sql
            $str = "insert into guarantors "
            ."( "
            ."application_id, "
            ."guarantor_name, "
            ."guarantor_ruby, "
            ."guarantor_link_id, "
            ."guarantor_sex_id, "
            ."guarantor_age, "
            ."guarantor_birthday, "
            ."guarantor_post_number, "
            ."guarantor_address, "
            ."guarantor_home_tel, "
            ."guarantor_mobile_tel, "
            ."guarantor_business_name, "
            ."guarantor_business_ruby, "
            ."guarantor_business_post_number, "
            ."guarantor_business_address, "
            ."guarantor_business_tel, "
            ."guarantor_business_type, "
            ."guarantor_business_line, "
            ."guarantor_business_years, "
            ."guarantor_business_status, "
            ."guarantor_income, "
            ."guarantor_insurance_type_id, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$application_id, "
            ."'$guarantor_name', "
            ."'$guarantor_ruby', "
            ."$guarantor_link_id, "
            ."$guarantor_sex_id, "
            ."$guarantor_age, "
            ."'$guarantor_birthday', "
            ."'$guarantor_post_number', "
            ."'$guarantor_address', "
            ."'$guarantor_home_tel', "
            ."'$guarantor_mobile_tel', "
            ."'$guarantor_business_name', "
            ."'$guarantor_business_ruby', "
            ."'$guarantor_business_post_number', "
            ."'$guarantor_business_address', "
            ."'$guarantor_business_tel', "
            ."'$guarantor_business_type', "
            ."'$guarantor_business_line', "
            ."'$guarantor_business_years', "
            ."'$guarantor_business_status', "
            ."'$guarantor_income', "
            ."$guarantor_insurance_type_id, "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";
            Log::debug('sql:'.$str);
            
            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

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
     * 付属書類(登録)
     *
     * @param Request $request
     * @return void
     */
    private function insertImg(Request $request ,$application_id){
        Log::debug('log_start:'.__FUNCTION__);

        try {

            /**
             * 値取得
             */
            // session_id
            $session_id = $request->session()->get('create_user_id');

            $img_file = $request->file('img_file');
            Log::debug('img_file:'.$img_file);

            if($img_file == null){

                $ret['status'] = 1;
                return $ret;
            }

            // 種別
            $img_type = $request->input('img_type');
            Log::debug('img_type:'.$img_type);

            // 備考
            $img_text = $request->input('img_text');
            Log::debug('img_text:'.$img_text);

            // 現在の日付取得
            $date = now() .'.000';
        
            // idごとのフォルダ作成のためのパス生成
            $dir ='img/' .$application_id;
            
            // 任意のフォルダ作成
            // ※appを入れるとエラーになる
            Storage::makeDirectory('/public/' .$dir);

            /**
             * 画像登録処理
             */
            // ファイル名変更
            $file_name = time() .'.' .$img_file->getClientOriginalExtension();
            Log::debug('ファイル名:'.$file_name);

            // ファイルパス+ファイル名
            $tmp_file_path = $dir .'/' .$file_name;
            Log::debug('tmp_file_path :'.$tmp_file_path);

            InterventionImage::make($img_file)->resize(380, null,
            function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path('/public/' .$tmp_file_path));

            // 種別
            if($img_type == null){
                $img_type = 0;
            }

            /**
             * 画像データ(insert)
             */
            $str = "insert into imgs( "
            ."application_id, "
            ."img_type_id, "
            ."img_path, "
            ."img_memo, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$application_id, "
            ."$img_type, "
            ."'$tmp_file_path', "
            ."'$img_text', "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";

            Log::debug('sql:'.$str);

            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

            Log::debug('status:'.$ret);
            
        } catch (\Throwable $e) {

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
     * 編集(表示)
     *
     * @param Request $request
     * @return void
     */
    public function adminAppEditInit(Request $request){

        Log::debug('start:' .__FUNCTION__);

        try {

            // 申込情報取得
            $app_info = $this->getAppEditList($request);
            $app_list = $app_info[0];

            // 入居者一覧取得
            $houseMate_info = $this->getHouseMateList($request);
            $houseMate_list = $houseMate_info;

            // 写真一覧取得
            $img_info = $this->getImgList($request);
            $img_list = $img_info;
            
            // リスト作成
            $common = new Common();

            // 契約進捗状況
            $contract_progress = $common->getContractProgress();
            
            // 申込区分リスト
            $app_types = $common->getApplicationTypes();

            // 申込種別リスト
            $app_uses = $common->getApplicationUses();

            // 続柄リスト
            $app_links = $common->getLinks();

            // 健康保険リスト
            $app_insurances = $common->getInsurances();

            // 性別
            $app_sexes = $common->getSexes();

            // 有無
            $needs = $common->getNeeds();
            // dd($needs);

            // 画像種別
            $img_type = $common->getImgType();

            // 個人又は法人
            $private_or_companies = $common->getPrivateOrCompanies();

            // 保証会社一覧
            $guarantee_companies = $common->getGuaranteeCompanies();
            

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminAppEdit' ,compact('contract_progress' ,'app_types' ,'app_uses' ,'app_links' ,'app_insurances' ,'needs' ,'app_sexes' ,'app_list' ,'houseMate_list' ,'img_type' ,'img_list' ,'private_or_companies' ,'guarantee_companies'));    
    }

    /**
     * 編集(申込情報取得:sql)
     *
     * @return void
     */
    private function getAppEditList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            // 値設定
            $application_id = $request->input('application_id');

            // sql
            $str = "select "
            ."applications.create_user_id as create_user_id, "
            ."applications.application_id as application_id, "
            ."applications.contract_progress_id as contract_progress_id, "
            ."contract_progress.contract_progress_name as contract_progress_name, "
            ."applications.broker_company_name as broker_company_name, "
            ."applications.broker_name as broker_name, "
            ."applications.broker_tel as broker_tel, "
            ."applications.broker_mail as broker_mail, "
            ."applications.application_type_id as application_type_id, "
            ."application_types.application_type_name as application_type_name, "
            ."applications.application_use_id as application_use_id, "
            ."application_uses.application_use_name as application_use_name, "
            ."applications.contract_start_date as contract_start_date, "
            ."applications.real_estate_name as real_estate_name, "
            ."applications.real_estate_ruby as real_estate_ruby, "
            ."applications.room_name as room_name , "
            ."applications.post_number as post_number, "
            ."applications.address as address, "
            ."applications.pet_bleed as pet_bleed, "
            ."applications.pet_kind as pet_kind, "
            ."applications.bicycle_number as bicycle_number, "
            ."applications.car_number as car_number, "
            ."applications.security_fee as security_fee, "
            ."applications.deposit_fee as deposit_fee, "
            ."applications.key_fee as key_fee, "
            ."applications.refund_fee as refund_fee, "
            ."applications.rent_fee as rent_fee, "
            ."applications.service_fee as service_fee, "
            ."applications.water_fee as water_fee, "
            ."applications.ohter_fee as ohter_fee, "
            ."applications.total_fee as total_fee, "
            ."applications.private_or_company_id as private_or_company_id, "
            ."applications.guarantor_flag as guarantor_flag, "
            ."entry_contracts.entry_contract_id, "
            ."entry_contracts.entry_contract_name as entry_contract_name, "
            ."entry_contracts.entry_contract_ruby as entry_contract_ruby, "
            ."entry_contracts.entry_contract_post_number as entry_contract_post_number, "
            ."entry_contracts.entry_contract_address as entry_contract_address, "
            ."entry_contracts.entry_contract_sex_id as entry_contract_sex_id, "
            ."application_sex.sex_name as entry_contract_sex_name, "
            ."entry_contracts.entry_contract_birthday as entry_contract_birthday, "
            ."entry_contracts.entry_contract_age as entry_contract_age, "
            ."entry_contracts.entry_contract_home_tel as entry_contract_home_tel, "
            ."entry_contracts.entry_contract_mobile_tel as entry_contract_mobile_tel, "
            ."entry_contracts.entry_contract_business_name as entry_contract_business_name, "
            ."entry_contracts.entry_contract_business_ruby as entry_contract_business_ruby, "
            ."entry_contracts.entry_contract_business_post_number as entry_contract_business_post_number, "
            ."entry_contracts.entry_contract_business_address as entry_contract_business_address, "
            ."entry_contracts.entry_contract_business_tel as entry_contract_business_tel, "
            ."entry_contracts.entry_contract_business_type as entry_contract_business_type, "
            ."entry_contracts.entry_contract_business_line as entry_contract_business_line, "
            ."entry_contracts.entry_contract_business_status as entry_contract_business_status, "
            ."entry_contracts.entry_contract_business_year as entry_contract_business_year, "
            ."entry_contracts.entry_contract_income as entry_contract_income, "
            ."entry_contracts.entry_contract_insurance_type_id as entry_contract_insurance_type_id, "
            ."entry_contract_insurances.insurance_name, "
            ."housemates.housemate_id as housemate_id, "
            ."housemates.housemate_name as housemate_name, "
            ."housemates.housemate_ruby as housemate_ruby, "
            ."housemates.housemate_sex_id as housemate_sex_id, "
            ."housemate_sex.sex_name as housemate_sex_name, "
            ."housemates.housemate_link_id as housemate_link_id, "
            ."housemate_link.link_name as link_name, "
            ."housemates.housemate_birthday as housemate_birthday, "
            ."housemates.housemate_age as housemate_age, "
            ."housemates.housemate_home_tel as housemate_home_tel, "
            ."housemates.housemate_mobile_tel as housemate_mobile_tel, "
            ."emergencies.emergency_id as emergency_id, "
            ."emergencies.emergency_name as emergency_name, "
            ."emergencies.emergency_ruby as emergency_ruby, "
            ."emergencies.emergency_sex_id as emergency_sex_id, "
            ."emergency_sex.sex_name as emergency_sex_name, "
            ."emergencies.emergency_link_id as emergency_link_id, "
            ."emergencies.emergency_birthday as emergency_birthday, "
            ."emergencies.emergency_age as emergency_age, "
            ."emergency_link.link_name as emergency_link_name, "
            ."emergencies.emergency_post_number as emergency_post_number, "
            ."emergencies.emergency_address as emergency_address, "
            ."emergencies.emergency_home_tel as emergency_home_tel, "
            ."emergencies.emergency_mobile_tel as emergency_mobile_tel, "
            ."guarantors.guarantor_id as guarantor_id, "
            ."guarantors.guarantor_name as guarantor_name, "
            ."guarantors.guarantor_ruby as guarantor_ruby, "
            ."guarantors.guarantor_age as guarantor_age, "
            ."guarantors.guarantor_birthday as guarantor_birthday, "
            ."guarantors.guarantor_link_id as guarantor_link_id, "
            ."guarantor_link.link_name as guarantor_link_name, "
            ."guarantors.guarantor_sex_id as guarantor_sex_id, "
            ."guarantor_sex.sex_name as guarantor_sex_name, "
            ."guarantors.guarantor_post_number as guarantor_post_number, "
            ."guarantors.guarantor_address as guarantor_address, "
            ."guarantors.guarantor_home_tel as guarantor_home_tel, "
            ."guarantors.guarantor_mobile_tel as guarantor_mobile_tel, "
            ."guarantors.guarantor_business_name as guarantor_business_name, "
            ."guarantors.guarantor_business_ruby as guarantor_business_ruby, "
            ."guarantors.guarantor_business_post_number as guarantor_business_post_number, "
            ."guarantors.guarantor_business_address as guarantor_business_address, "
            ."guarantors.guarantor_business_tel as guarantor_business_tel, "
            ."guarantors.guarantor_business_type as guarantor_business_type, "
            ."guarantors.guarantor_business_line as guarantor_business_line, "
            ."guarantors.guarantor_business_status as guarantor_business_status, "
            ."guarantors.guarantor_business_years as guarantor_business_years, "
            ."guarantors.guarantor_income as guarantor_income, "
            ."guarantors.guarantor_insurance_type_id, "
            ."guarantor_insurances.insurance_name "
            ."from applications "
            ."left join entry_contracts "
            ."on entry_contracts.application_id = applications.application_id "
            ."left join sexes as application_sex "
            ."on application_sex.sex_id = entry_contracts.entry_contract_sex_id "
            ."left join insurances as entry_contract_insurances "
            ."on entry_contract_insurances.insurance_id = entry_contracts.entry_contract_insurance_type_id "
            ."left join housemates "
            ."on housemates.application_id = applications.application_id "
            ."left join sexes as housemate_sex "
            ."on housemate_sex.sex_id = housemates.housemate_sex_id "
            ."left join links as housemate_link "
            ."on housemates.housemate_link_id = housemate_link.link_id "
            ."left join emergencies "
            ."on emergencies.application_id = applications.application_id "
            ."left join sexes as emergency_sex "
            ."on emergency_sex.sex_id = emergencies.emergency_sex_id "
            ."left join links as emergency_link "
            ."on emergency_link.link_id = emergencies.emergency_link_id "
            ."left join guarantors "
            ."on guarantors.application_id = applications.application_id "
            ."left join sexes as guarantor_sex "
            ."on guarantor_sex.sex_id = guarantors.guarantor_sex_id "
            ."left join links as guarantor_link "
            ."on guarantor_link.link_id = guarantors.guarantor_link_id "
            ."left join insurances as guarantor_insurances "
            ."on guarantor_insurances.insurance_id = guarantors.guarantor_insurance_type_id "
            ."left join application_types "
            ."on application_types.application_type_id = applications.application_type_id "
            ."left join application_uses "
            ."on application_uses.application_use_id = applications.application_use_id "
            ."left join contract_progress "
            ."on contract_progress.contract_progress_id = applications.contract_progress_id "
            ."left join needs "
            ."on needs.need_id = applications.pet_bleed "
            ."where applications.application_id = $application_id ";
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
     * 編集(入居者一覧取得:sql)
     *
     * @return void
     */
    private function getHouseMateList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            // 値設定
            $application_id = $request->input('application_id');

            $str = "select "
            ."housemates.housemate_id as housemate_id, "
            ."housemates.housemate_name as housemate_name, "
            ."housemates.housemate_ruby as housemate_ruby, "
            ."housemates.housemate_sex_id as housemate_sex_id, "
            ."sexes.sex_name as housemate_sex_name, "
            ."housemates.housemate_link_id as housemate_link_id, "
            ."links.link_name as link_name, "
            ."housemates.housemate_birthday as housemate_birthday, "
            ."housemates.housemate_age, "
            ."housemates.housemate_home_tel, "
            ."housemates.housemate_mobile_tel "
            ."from housemates "
            ."left join sexes "
            ."on housemates.housemate_sex_id = sexes.sex_id "
            ."left join links "
            ."on links.link_id = housemates.housemate_link_id "
            ."where housemates.application_id = $application_id ";

            Log::debug('sql:' .$str);
            
            $ret = DB::select($str);

        // 例外処理
        } catch (\Throwable $e) {

            throw $e;

        } finally {

        }
        
        Log::debug('start:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 編集(画像一覧取得)
     *
     * @param Request $request
     * @return void
     */
    private function getImgList(Request $request){

        Log::debug('start:' .__FUNCTION__);

        try{
            // 値設定
            $application_id = $request->input('application_id');

            $str = "select * "
            ."from imgs "
            ."left join img_types "
            ."on imgs.img_type_id = img_types.img_type_id "
            ."where application_id = $application_id ";
            
            $ret = DB::select($str);

        } catch (\Throwable $e) {

            throw $e;

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
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
            
            // トランザクション
            DB::beginTransaction();
            
            // retrun初期値
            $ret = [];
            $ret['status'] = true;

            /**
             * 不動産業者(status:OK=1 NG=0)
             */
            $app_info = $this->updateApplication($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $app_info['status'];

            /**
             * 契約者(status:OK=1 NG=0)
             */
            $contract_info = $this->updateEntryContract($request);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $contract_info['status'];

            /**
             * 緊急連絡先(status:OK=1 NG=0)
             */
            $emergency_info = $this->updateEmergency($request);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $emergency_info['status'];

            /**
             * 連帯保証人(status:OK=1 NG=0)
             */
            $guarantor_info = $this->updateGuarantor($request);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $guarantor_info['status'];

            /**
             * 同居人
             */ 
            // id取得
            $housemate_id = $request->input('housemate_id');
            
            // 同居人追加フラグ取得(追加=true/追加無=false)
            $housemate_add_flag = $request->input('housemate_add_flag');

            // 同居人追加フラグ:false=0/true=1
            if($housemate_add_flag == 'false'){

                $ret['status'] = 0;    
                
            }else{

                // null = 新規/not null = 編集
                if($housemate_id == null){

                    Log::debug('同居人:新規処理');

                    // insert
                    $houseMate_info = $this->insertHousemate($request);

                    // returnのステータスにtrueを設定
                    $ret['status'] = $houseMate_info['status'];              
                    
                }else{

                    Log::debug('同居人:編集処理');

                    // update
                    $houseMate_info = $this->updateHousemate($request);

                    // returnのステータスにtrueを設定
                    $ret['status'] = $houseMate_info['status'];
                    
                }
            }

            /**
             * 付属書類
             */
            $img_info = $this->updateImg($request);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $img_info['status'];

            // コミット
            DB::commit();

        // 例外処理
        } catch (\Throwable $e) {

            // ロールバック
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
     * 不動産業者(編集)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function updateApplication(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $application_id = $request->input('application_id');
            $contract_progress_id = $request->input('contract_progress_id');
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
            $private_or_company_id = $request->input('private_or_company_id');
            $guarantor_flag = $request->input('guarantor_flag');

            // 現在の日付取得
            $date = now() .'.000';
    
            /**
             * 数値に関してはNULLで値を代入出来ないので0、''を入れる
             */
            // 進捗状況
            if($contract_progress_id == null){
                $contract_progress_id =0;
            }

            // 個人又は法人
            if($private_or_company_id == null){
                $private_or_company_id =0;
            }
            
            // 申込区分
            if($application_type_id == null){
                $application_type_id =0;
            }

            // 申込種別
            if($application_use_id == null){
                $application_use_id =0;
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
                $pet_bleed = 0;
            }

            // ペット種類
            if($pet_kind == null){
                $pet_kind = '';
            }

            // 駐輪台数
            if($bicycle_number == null){
                $bicycle_number =0;
            }

            // 駐車台数
            if($car_number_number == null){
                $car_number_number =0;
            }

            // 保証金
            if($deposit_fee == null){
                $deposit_fee =0;
            }

            // 解約引
            if($refund_fee == null){
                $refund_fee =0;
            }

            // 敷金
            if($security_fee == null){
                $security_fee =0;
            }

            // 礼金
            if($key_fee == null){
                $key_fee = 0;
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
            if($total_fee == null){
                $total_fee =0;
            }

            if($guarantor_flag == null){
                $guarantor_flag =0;
            }
            

            // sql
            $str = "update "
            ."applications "
            ."set "
            ."create_user_id = $session_id, "
            ."application_type_id = $application_type_id, "
            ."application_use_id = $application_use_id, "
            ."contract_start_date = '$contract_start_date', "
            ."real_estate_name = '$real_estate_name', "
            ."real_estate_ruby = '$real_estate_ruby', "
            ."room_name = '$room_name', "
            ."post_number = '$post_number', "
            ."address = '$address', "
            ."pet_bleed = '$pet_bleed', "
            ."pet_kind = '$pet_kind', "
            ."bicycle_number = '$bicycle_number', "
            ."car_number = $car_number_number , "
            ."security_fee = $security_fee, "
            ."deposit_fee = $deposit_fee, "
            ."key_fee = $key_fee, "
            ."refund_fee = $refund_fee, "
            ."rent_fee = $rent_fee, "
            ."service_fee = $service_fee, "
            ."water_fee = $water_fee, "
            ."ohter_fee = $ohter_fee, "
            ."total_fee = $total_fee, "
            ."broker_company_name = '$broker_company_name', "
            ."broker_tel = '$broker_tel', "
            ."broker_mail = '$broker_mail', "
            ."broker_name = '$broker_name', "
            ."contract_progress_id = $contract_progress_id, "
            ."url_send_flag = 0, "
            ."private_or_company_id = $private_or_company_id, "
            ."guarantor_flag = $guarantor_flag, "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."application_id = $application_id; ";
            
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
     * 契約者(編集)
     *
     * @param Request $request
     * @return void
     */
    private function updateEntryContract(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try {
            // returnの初期値
            $ret = [];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $application_id = $request->input('application_id');
            $entry_contract_name = $request->input('entry_contract_name');
            $entry_contract_ruby = $request->input('entry_contract_ruby');
            $entry_contract_post_number = $request->input('entry_contract_post_number');
            $entry_contract_address = $request->input('entry_contract_address');
            $entry_contract_sex_id = $request->input('entry_contract_sex_id');
            $entry_contract_birthday = $request->input('entry_contract_birthday');
            $entry_contract_age = $request->input('entry_contract_age');
            $entry_contract_home_tel = $request->input('entry_contract_home_tel');
            $entry_contract_mobile_tel = $request->input('entry_contract_mobile_tel');
            $entry_contract_business_name = $request->input('entry_contract_business_name');
            $entry_contract_business_ruby = $request->input('entry_contract_business_ruby');
            $entry_contract_business_post_number = $request->input('entry_contract_business_post_number');
            $entry_contract_business_address = $request->input('entry_contract_business_address');
            $entry_contract_business_tel = $request->input('entry_contract_business_tel');
            $entry_contract_business_type = $request->input('entry_contract_business_type');
            $entry_contract_business_line = $request->input('entry_contract_business_line');
            $entry_contract_business_status = $request->input('entry_contract_business_status');
            $entry_contract_business_year = $request->input('entry_contract_business_year');
            $entry_contract_income = $request->input('entry_contract_income');
            $entry_contract_insurance_type_id = $request->input('entry_contract_insurance_type_id');
            // 現在の日付取得
            $date = now() .'.000';

            // 契約者名
            if($entry_contract_name == null){
                $entry_contract_name = "";
            }

            // 契約者フリガナ
            if($entry_contract_ruby == null){
                $entry_contract_ruby = "";
            }

            // 郵便番号
            if($entry_contract_post_number == null){
                $entry_contract_post_number = '';
            }

            // 住所
            if($entry_contract_address == null){
                $entry_contract_address = "";
            }

            // 性別
            if($entry_contract_sex_id == null){
                $entry_contract_sex_id = 0;
            }

            // 生年月日
            if($entry_contract_birthday == null){
                $entry_contract_birthday = '';
            }

            // 年齢
            if($entry_contract_age == null){
                $entry_contract_age = 0;
            }

            // 自宅電話番号
            if($entry_contract_home_tel == null){
                $entry_contract_home_tel = "";
            }

            // 携帯電話番号
            if($entry_contract_mobile_tel == null){
                $entry_contract_mobile_tel = "";
            }

            // 勤務先名
            if($entry_contract_business_name == null){
                $entry_contract_business_name = "";
            }

            // 勤務先名カナ
            if($entry_contract_business_ruby == null){
                $entry_contract_business_ruby = "";
            }

            // 勤務先郵便番号
            if($entry_contract_business_post_number == null){
                $entry_contract_business_post_number = '';
            }

            // 勤務先住所
            if($entry_contract_business_address == null){
                $entry_contract_business_address = "";
            }

            // 勤務先電話番号
            if($entry_contract_business_tel == null){
                $entry_contract_business_tel = "";
            }

            // 業種
            if($entry_contract_business_type == null){
                $entry_contract_business_type = "";
            }

            // 職種
            if($entry_contract_business_line == null){
                $entry_contract_business_line = "";
            }

            // 雇用形態
            if($entry_contract_business_status == null){
                $entry_contract_business_status = "";
            }

            // 勤続年数
            if($entry_contract_business_year == null){
                $entry_contract_business_year = 0;
            }

            // 収入
            if($entry_contract_income == null){
                $entry_contract_income = 0;
            }

            // 保険種別
            if($entry_contract_insurance_type_id == null){
                $entry_contract_insurance_type_id = 0;
            }
            
            // sql
            $str = "update "
            ."entry_contracts "
            ."set "
            ."application_id = $application_id, "
            ."entry_contract_name = '$entry_contract_name', "
            ."entry_contract_ruby = '$entry_contract_ruby', "
            ."entry_contract_sex_id = $entry_contract_sex_id, "
            ."entry_contract_birthday = '$entry_contract_birthday', "
            ."entry_contract_age = '$entry_contract_age', "
            ."entry_contract_post_number = '$entry_contract_post_number', "
            ."entry_contract_address = '$entry_contract_address', "
            ."entry_contract_home_tel = '$entry_contract_home_tel', "
            ."entry_contract_mobile_tel = '$entry_contract_mobile_tel', "
            ."entry_contract_business_name = '$entry_contract_business_name', "
            ."entry_contract_business_ruby = '$entry_contract_business_ruby', "
            ."entry_contract_business_post_number = '$entry_contract_business_post_number', "
            ."entry_contract_business_address = '$entry_contract_business_address', "
            ."entry_contract_business_tel = '$entry_contract_business_tel', "
            ."entry_contract_business_type = '$entry_contract_business_type', "
            ."entry_contract_business_line = '$entry_contract_business_line', "
            ."entry_contract_business_year = '$entry_contract_business_year', "
            ."entry_contract_business_status = '$entry_contract_business_status', "
            ."entry_contract_income = '$entry_contract_income', "
            ."entry_contract_insurance_type_id = $entry_contract_insurance_type_id, "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."application_id = $application_id; ";

            Log::debug('sql:'.$str);
            
            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

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
     * 緊急連絡先(編集)
     *
     * @param Request $request
     * @return void
     */
    private function updateEmergency(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try {
            // returnの初期値
            $ret = [];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $application_id = $request->input('application_id');
            $emergency_name = $request->input('emergency_name');
            $emergency_ruby = $request->input('emergency_ruby');
            $emergency_sex_id = $request->input('emergency_sex_id');
            $emergency_link_id = $request->input('emergency_link_id');
            $emergency_birthday = $request->input('emergency_birthday');
            $emergency_age = $request->input('emergency_age');
            $emergency_post_number = $request->input('emergency_post_number');
            $emergency_address = $request->input('emergency_address');
            $emergency_home_tel = $request->input('emergency_home_tel');
            $emergency_mobile_tel = $request->input('emergency_mobile_tel');

            // 現在の日付取得
            $date = now() .'.000';

            // 緊急連絡先名
            if($emergency_name == null){
                $emergency_name = "";
            }

            // 緊急連絡先フリガナ
            if($emergency_ruby == null){
                $emergency_ruby = "";
            }

            // 性別
            if($emergency_sex_id == null){
                $emergency_sex_id = 0;
            }

            // 続柄
            if($emergency_link_id == null){
                $emergency_link_id = 0;
            }

            // 生年月日
            if($emergency_birthday == null){
                $emergency_birthday = '';
            }

            // 年齢
            if($emergency_age == null){
                $emergency_age = 0;
            }

            // 郵便番号
            if($emergency_post_number == null){
                $emergency_post_number = '';
            }

            // 住所
            if($emergency_address == null){
                $emergency_address = "";
            }

            // 自宅電話番号
            if($emergency_home_tel == null){
                $emergency_home_tel = "";
            }

            // 携帯電話番号
            if($emergency_mobile_tel == null){
                $emergency_mobile_tel = "";
            }

            $str = "update "
            ."emergencies "
            ."set "
            ."application_id = $application_id, "
            ."emergency_name = '$emergency_name', "
            ."emergency_ruby = '$emergency_ruby', "
            ."emergency_link_id = $emergency_link_id, "
            ."emergency_sex_id = $emergency_sex_id, "
            ."emergency_birthday = '$emergency_birthday', "
            ."emergency_age = '$emergency_age', "
            ."emergency_post_number = '$emergency_post_number', "
            ."emergency_address = '$emergency_address', "
            ."emergency_home_tel = '$emergency_home_tel', "
            ."emergency_mobile_tel = '$emergency_mobile_tel', "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."application_id = $application_id; ";
            
            Log::debug('sql:'.$str);
            
            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

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
     * 連帯保証人(編集)
     *
     * @param Request $request
     * @return void
     */
    private function updateGuarantor(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try {
            // returnの初期値
            $ret = [];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $application_id = $request->input('application_id');
            $guarantor_name = $request->input('guarantor_name');
            $guarantor_ruby = $request->input('guarantor_ruby');
            $guarantor_post_number = $request->input('guarantor_post_number');
            $guarantor_address = $request->input('guarantor_address');
            $guarantor_sex_id = $request->input('guarantor_sex_id');
            $guarantor_birthday = $request->input('guarantor_birthday');
            $guarantor_age = $request->input('guarantor_age');
            $guarantor_link_id = $request->input('guarantor_link_id');
            $guarantor_home_tel = $request->input('guarantor_home_tel');
            $guarantor_mobile_tel = $request->input('guarantor_mobile_tel');
            $guarantor_business_name = $request->input('guarantor_business_name');
            $guarantor_business_ruby = $request->input('guarantor_business_ruby');
            $guarantor_business_post_number = $request->input('guarantor_business_post_number');
            $guarantor_business_address = $request->input('guarantor_business_address');
            $guarantor_business_tel = $request->input('guarantor_business_tel');
            $guarantor_business_type = $request->input('guarantor_business_type');
            $guarantor_business_line = $request->input('guarantor_business_line');
            $guarantor_business_status = $request->input('guarantor_business_status');
            $guarantor_business_years = $request->input('guarantor_business_years');
            $guarantor_income = $request->input('guarantor_income');
            $guarantor_insurance_type_id = $request->input('guarantor_insurance_type_id');
            
            // 現在の日付取得
            $date = now() .'.000';

            // 連帯保証人名
            if($guarantor_name == null){
                $guarantor_name = "";
            }

            // 連帯保証人カナ
            if($guarantor_ruby == null){
                $guarantor_ruby = "";
            }

            // 郵便番号
            if($guarantor_post_number == null){
                $guarantor_post_number = '';
            }

            // 住所
            if($guarantor_address == null){
                $guarantor_address = "";
            }

            // 性別
            if($guarantor_sex_id == null){
                $guarantor_sex_id = 0;
            }

            // 生年月日
            if($guarantor_birthday == null){
                $guarantor_birthday = '';
            }

            // 年齢
            if($guarantor_age == null){
                $guarantor_age = 0;
            }

            // 続柄
            if($guarantor_link_id == null){
                $guarantor_link_id = 0;
            }

            // 自宅電話番号
            if($guarantor_home_tel == null){
                $guarantor_home_tel = "";
            }

            // 携帯電話番号
            if($guarantor_mobile_tel == null){
                $guarantor_mobile_tel = "";
            }

            // 勤務先名
            if($guarantor_business_name == null){
                $guarantor_business_name = "";
            }

            // 勤務先カナ
            if($guarantor_business_ruby == null){
                $guarantor_business_ruby = "";
            }

            // 勤務先郵便番号
            if($guarantor_business_post_number == null){
                $guarantor_business_post_number = "";
            }

            // 勤務先住所
            if($guarantor_business_address == null){
                $guarantor_business_address = "";
            }

            // 勤務先電話番号
            if($guarantor_business_tel == null){
                $guarantor_business_tel = "";
            }

            // 業種
            if($guarantor_business_type == null){
                $guarantor_business_type = "";
            }

            // 職種
            if($guarantor_business_line == null){
                $guarantor_business_line = "";
            }

            // 雇用形態
            if($guarantor_business_status == null){
                $guarantor_business_status = "";
            }

            // 勤続年数
            if($guarantor_business_years == null){
                $guarantor_business_years = 0;
            }

            // 年収
            if($guarantor_income == null){
                $guarantor_income = "";
            }

            // 健康保険
            if($guarantor_insurance_type_id == null){
                $guarantor_insurance_type_id = 0;
            }

            // sql
            $str = "update "
            ."guarantors "
            ."set "
            ."application_id = $application_id, "
            ."guarantor_name = '$guarantor_name', "
            ."guarantor_ruby = '$guarantor_ruby', "
            ."guarantor_link_id = $guarantor_link_id, "
            ."guarantor_sex_id = $guarantor_sex_id, "
            ."guarantor_age = $guarantor_age, "
            ."guarantor_birthday = '$guarantor_birthday', "
            ."guarantor_post_number = '$guarantor_post_number', "
            ."guarantor_address = '$guarantor_address', "
            ."guarantor_home_tel = '$guarantor_home_tel', "
            ."guarantor_mobile_tel = '$guarantor_mobile_tel', "
            ."guarantor_business_name = '$guarantor_business_name', "
            ."guarantor_business_ruby = '$guarantor_business_ruby', "
            ."guarantor_business_post_number = '$guarantor_business_post_number', "
            ."guarantor_business_address = '$guarantor_business_address', "
            ."guarantor_business_tel = '$guarantor_business_tel', "
            ."guarantor_business_type = '$guarantor_business_type', "
            ."guarantor_business_line = '$guarantor_business_line', "
            ."guarantor_business_years = $guarantor_business_years, "
            ."guarantor_business_status = '$guarantor_business_status', "
            ."guarantor_income = '$guarantor_income', "
            ."guarantor_insurance_type_id = $guarantor_insurance_type_id, "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."application_id = $application_id; ";
            
            Log::debug('sql:'.$str);
            
            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

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
     * 付属書類(編集)
     *
     * @param Request $request
     * @return void
     */
    private function updateImg(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try {

            /**
             * 値取得
             */
            // session_id
            $session_id = $request->session()->get('create_user_id');

            // 申込id
            $application_id = $request->input('application_id');

            $img_file = $request->file('img_file');
            Log::debug('img_file:'.$img_file);

            if($img_file == null){

                $ret['status'] = 1;
                return $ret;
            }

            // 種別
            $img_type = $request->input('img_type');
            Log::debug('img_type:'.$img_type);

            // 備考
            $img_text = $request->input('img_text');
            Log::debug('img_text:'.$img_text);

            // 現在の日付取得
            $date = now() .'.000';

            // idごとのフォルダ作成のためのパス生成
            $dir ='img/' .$application_id;
        
            // idごとのフォルダ作成のためのパス生成
            Storage::makeDirectory('/public/' .$dir);

            /**
             * 画像登録処理
             */
            // ファイル名変更
            $file_name = time() .'.' .$img_file->getClientOriginalExtension();
            Log::debug('ファイル名:'.$file_name);

            // ファイルパス+ファイル名
            $tmp_file_path = $dir .'/' .$file_name;
            Log::debug('tmp_file_path :'.$tmp_file_path);

            InterventionImage::make($img_file)->resize(380, null,
            function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path('app/public/' .$tmp_file_path));

            // 種別
            if($img_type == null){
                $img_type = 0;
            }

            /**
             * 画像データ(insert)
             */
            $str = "insert into imgs( "
            ."application_id, "
            ."img_type_id, "
            ."img_path, "
            ."img_memo, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$application_id, "
            ."$img_type, "
            ."'$tmp_file_path', "
            ."'$img_text', "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";

            Log::debug('sql:'.$str);

            // OK=1/NG=0
            $ret['status'] = DB::insert($str);

            Log::debug('status:'.$ret);
            
        } catch (\Throwable $e) {

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
     * 同居人(表示:ダブルクリックの処理)
     *
     * @return void
     */
    public function backAppHouseMateInit(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try {
            // 値取得       
            $housemate_id = $request->input('housemate_id');

            // sql
            $str = "select * "
            ."from housemates "
            ."where housemate_id = $housemate_id ";
            Log::debug('sql:'.$str);

            $response['list'] = DB::select($str);

            $arrString = print_r($response , true);
            Log::debug('log_Img:'.$arrString);

        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        }finally{

            Log::debug('log_end:'.__FUNCTION__);
            return response()->json($response);
        }
    }

    /**
     * 同居人(登録)
     *
     * @param Request $request
     * @return void
     */
    private function insertHousemate(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try {

            // returnの初期値
            $ret = [];

            // 値取得
            $application_id = $request->input('application_id');
            $session_id = $request->session()->get('create_user_id');
            $housemate_id = $request->input('housemate_id');
            $housemate_name = $request->input('housemate_name');
            $housemate_ruby = $request->input('housemate_ruby');
            $housemate_sex_id = $request->input('housemate_sex_id');
            $housemate_link_id = $request->input('housemate_link_id');
            $housemate_age = $request->input('housemate_age');
            $housemate_birthday = $request->input('housemate_birthday');
            $housemate_home_tel = $request->input('housemate_home_tel');
            $housemate_mobile_tel = $request->input('housemate_mobile_tel');

            // 現在の日付取得
            $date = now() .'.000';

            // 連帯保証人名
            if($housemate_name == null){
                $housemate_name = "";
            }

            // 連帯保証人カナ
            if($housemate_ruby == null){
                $housemate_ruby = "";
            }

            // 性別
            if($housemate_sex_id == null){
                $housemate_sex_id = 0;
            }

            // 生年月日
            if($housemate_birthday == null){
                $housemate_birthday = '';
            }

            // 年齢
            if($housemate_age == null){
                $housemate_age = 0;
            }

            // 続柄
            if($housemate_link_id == null){
                $housemate_link_id = 0;
            }

            // 自宅電話番号
            if($housemate_home_tel == null){
                $housemate_home_tel = "";
            }

            // 携帯電話番号
            if($housemate_mobile_tel == null){
                $housemate_mobile_tel = "";
            }

            // sql
            $str = "insert into housemates "
            ."( "
            ."application_id, "
            ."housemate_name, "
            ."housemate_ruby, "
            ."housemate_link_id, "
            ."housemate_sex_id, "
            ."housemate_birthday, "
            ."housemate_age, "
            ."housemate_home_tel, "
            ."housemate_mobile_tel, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$application_id, "
            ."'$housemate_name', "
            ."'$housemate_ruby', "
            ."$housemate_link_id, "
            ."$housemate_sex_id, "
            ."'$housemate_birthday', "
            ."$housemate_age, "
            ."'$housemate_home_tel', "
            ."'$housemate_mobile_tel', "
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
     * 同居人(編集)
     *
     * @param Request $request
     * @return void
     */
    private function updateHousemate(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try {
            // returnの初期値
            $ret = [];

            // 値取得
            $application_id = $request->input('application_id');
            $session_id = $request->session()->get('create_user_id');
            $housemate_id = $request->input('housemate_id');
            $housemate_name = $request->input('housemate_name');
            $housemate_ruby = $request->input('housemate_ruby');
            $housemate_sex_id = $request->input('housemate_sex_id');
            $housemate_link_id = $request->input('housemate_link_id');
            $housemate_age = $request->input('housemate_age');
            $housemate_birthday = $request->input('housemate_birthday');
            $housemate_home_tel = $request->input('housemate_home_tel');
            $housemate_mobile_tel = $request->input('housemate_mobile_tel');

            // 現在の日付取得
            $date = now() .'.000';

            // 連帯保証人名
            if($housemate_name == null){
                $housemate_name = "";
            }

            // 連帯保証人カナ
            if($housemate_ruby == null){
                $housemate_ruby = "";
            }

            // 性別
            if($housemate_sex_id == null){
                $housemate_sex_id = 0;
            }

            // 生年月日
            if($housemate_birthday == null){
                $housemate_birthday = '';
            }

            // 年齢
            if($housemate_age == null){
                $housemate_age = 0;
            }

            // 続柄
            if($housemate_link_id == null){
                $housemate_link_id = 0;
            }

            // 自宅電話番号
            if($housemate_home_tel == null){
                $housemate_home_tel = "";
            }

            // 携帯電話番号
            if($housemate_mobile_tel == null){
                $housemate_mobile_tel = "";
            }

            // slq
            $str = "update "
            ."housemates "
            ."set "
            ."application_id = $application_id, "
            ."housemate_name = '$housemate_name', "
            ."housemate_ruby = '$housemate_ruby', "
            ."housemate_link_id = $housemate_link_id, "
            ."housemate_sex_id = $housemate_sex_id, "
            ."housemate_birthday = '$housemate_birthday', "
            ."housemate_age = '$housemate_age', "
            ."housemate_home_tel = '$housemate_home_tel', "
            ."housemate_mobile_tel = '$housemate_mobile_tel', "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."housemate_id = $housemate_id; ";

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
     * 同居人(削除)
     *
     * @param Request $request
     * @return void
     */
    public function backAppHouseMateDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $response = [];

            // $responseの値設定
            $ret = $this->deleteHouseMate($request);

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
    private function deleteHouseMate(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $housemate_id = $request->input('housemate_id');

            $str = "delete "
            ."from "
            ."housemates "
            ."where "
            ."housemate_id = $housemate_id; ";

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
     * 削除(各テーブルに分岐)
     *
     * @param Request $request
     * @return void
     */
    public function backAppDeleteEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // トランザクション
            DB::beginTransaction();

            // return初期値
            $response = [];

            /**
             * 不動産業者
             */
            $app_info = $this->deleteEntryApplication($request);

            // js側での判定のステータス(true:OK/false:NG)
            $ret['status'] = $app_info['status'];

            /**
             * 契約者
             */
            $contract_info = $this->deleteEntryContract($request);

            // js側での判定のステータス(true:OK/false:NG)
            $ret['status'] = $contract_info['status'];

            /**
             * 同居人
             */
            $housemate_info = $this->deleteEntryHouseMate($request);

            // js側での判定のステータス(true:OK/false:NG)
            $ret['status'] = $housemate_info['status'];

            /**
             * 緊急連絡先
             */
            $emergency_info = $this->deleteEntryEmergency($request);

            // js側での判定のステータス(true:OK/false:NG)
            $ret['status'] = $emergency_info['status'];

            /**
             * 連帯保証人
             */
            $guarantor_info = $this->deleteEntryGuarantor($request);

            // js側での判定のステータス(true:OK/false:NG)
            $ret['status'] = $guarantor_info['status'];

            /**
             * 画像
             */
            $img_info = $this->deleteEntryImg($request);

            // js側での判定のステータス(true:OK/false:NG)
            $ret['status'] = $img_info['status'];

            // js側での判定のステータス(true:OK/false:NG)
            $response["status"] = $ret['status'];

            // コミット
            DB::commit();

        // 例外処理
        } catch (\Throwable $e) {

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
     * 削除(不動産業者)
     *
     * @param Request $request
     * @return void
     */
    private function deleteEntryApplication(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $application_id = $request->input('application_id');

            $str = "delete "
            ."from "
            ."applications "
            ."where "
            ."application_id = $application_id; ";

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
     * 削除(契約者)
     *
     * @param Request $request
     * @return void
     */
    private function deleteEntryContract(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $application_id = $request->input('application_id');

            $str = "delete "
            ."from "
            ."entry_contracts "
            ."where "
            ."application_id = $application_id; ";

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
     * 削除(同居人)
     *
     * @param Request $request
     * @return void
     */
    private function deleteEntryHouseMate(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $application_id = $request->input('application_id');

            $str = "delete "
            ."from "
            ."housemates "
            ."where "
            ."application_id = $application_id; ";

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
     * 削除(緊急連絡先)
     *
     * @param Request $request
     * @return void
     */
    private function deleteEntryEmergency(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $application_id = $request->input('application_id');

            $str = "delete "
            ."from "
            ."emergencies "
            ."where "
            ."application_id = $application_id; ";

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
     * 削除(連帯保証人)
     *
     * @param Request $request
     * @return void
     */
    private function deleteEntryGuarantor(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // return初期値
            $ret = [];

            // 値取得
            $application_id = $request->input('application_id');

            $str = "delete "
            ."from "
            ."guarantors "
            ."where "
            ."application_id = $application_id; ";

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
     * 削除(画像)
     *
     * @param Request $request
     * @return void
     */
    public function deleteEntryImg(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // トランザクション
            DB::beginTransaction();

            $ret = [];

            // 値取得
            $application_id = $request->input('application_id');

            /**
             * 画像データの削除
             * 契約者Idごとの画像データ取得->パス取得->フォルダ削除->データ(DB)削除
             */
            $str = "select * from imgs "
            ."where application_id = '$application_id' ";
            Log::debug('select_sql:'.$str);
            $img_list = DB::select($str);

            // デバック
            $arrString = print_r($img_list , true);
            Log::debug('log_Imgs:'.$arrString);

            // 画像データが存在しない場合、削除対象が無のため、return=trueを返却
            if(count($img_list) == 0){

                Log::debug('画像データが存在しない場合の処理');

                $ret['status'] = 1;

                // コミット(記載無しの場合、処理が実行されない)
                DB::commit();

                return $ret;
            }

            // 画像パスを"/"で分解->配列化
            $arr = explode('/', $img_list[0]->img_path);

            // appを除外し文字結合(public/img/214)
            $img_dir_path = $arr[0] ."/" .$arr[1];

            // フォルダ削除
            Storage::deleteDirectory('/public/' .$img_dir_path);

            // 画像データ削除(DB)
            $str = "delete from imgs "
            ."where application_id = '$application_id' ";
            Log::debug('delete_sql:'.$str);

            $ret['status'] = DB::delete($str);
            Log::debug($ret['status']);
            
            // コミット
            DB::commit();

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            DB::rollback();

            throw $e;

        // status:OK=1/NG=0
        } finally {

        }

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 削除(画像:詳細)
     *
     * @param Request $request
     * @return $ret['status'] OK=true/NG=false
     */
    public function backDeleteEntryImgDetail(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // トランザクション
            DB::beginTransaction();

            $response = [];

            // 値取得
            $img_id = $request->input('img_id');

            /**
             * 画像データの削除
             * 契約者Idごとの画像データ取得->パス取得->フォルダ削除->データ(DB)削除
             */
            $str = "select * from imgs "
            ."where img_id = '$img_id' ";
            Log::debug('select_sql:'.$str);
            $img_list = DB::select($str);

            // DBからの取得データをデバック
            $arrString = print_r($img_list , true);
            Log::debug('imgs:'.$arrString);

            // 画像データが存在しない場合、削除対象が無のため、return=trueを返却
            if(count($img_list) == 0){

                Log::debug('画像が存在しない場合の処理');

                $ret['status'] = true;

                // コミット(記載無しの場合、処理が実行されない)
                DB::commit();

                return response()->json($response);

            }
            
            /**
             * 画像ファイル削除
             */
            // 画像パスを"/"で分解->配列化
            $img_name_path = $img_list[0]->img_path;
            Log::debug('img_name_path:'.$img_name_path);

            // ファイル削除(例:Storage::delete('public/img/214/1637578613.jpg');
            Storage::delete('/public/' .$img_name_path);

            /**
             * 画像フォルダ削除
             */
             // 画像パスを"/"で分解->配列化
            $arr = explode('/', $img_list[0]->img_path);
            $img_dir_path = $arr[0] ."/" .$arr[1];

            // フォルダの中身を確認
            $img_arr = Storage::files($img_dir_path);

            // デバック(ファイルの中身を確認)
            Log::debug('img_arr:'.$arrString);
            $arrString = print_r($img_arr , true);

            // 参照の値が空白の場合、フォルダ削除
            if($img_arr == null){

                Log::debug('フォルダの中身がない場合の処理');

                // フォルダ削除
                Storage::deleteDirectory('/public/' .$img_dir_path);
            }

            // 画像データ削除(DB)
            $str = "delete from imgs "
            ."where img_id = '$img_id' ";
            Log::debug('delete_sql:'.$str);

            $response['status'] = DB::delete($str);
            Log::debug($response['status']);
            
            // コミット
            DB::commit();

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            DB::rollback();

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
     * 契約に進む(登録分岐)
     *
     * @param Request $request
     * @return $ret['status'] OK=true/NG=false
     */
    public function backAppNextStageEntry(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{
            // トランザクション
            DB::beginTransaction();

            $response = [];

            /**
             * 申込フラグ(move_in_examinan_flag)のupdate
             */
            $app_info = $this->updateApp($request);

            $response['status'] = $app_info['status'];

            /**
             * 契約テーブルにinsert
             */
            $contract_info = $this->insertContractDetail($request);

            $response['status'] = $contract_info['status'];

            /**
             * 特約事項に空データをinsert
             */
            $special_contract_detail_info = $this->insertSpecialContractDetail($request);

            $response['status'] = $contract_info['status'];

            // コミット
            DB::commit();

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug(__FUNCTION__ .':' .$e);

            DB::rollback();

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
     * 契約に進める(入居者フラグ(move_in_examinan_flag)のupdate)
     *
     * @param Request $request
     * @return void
     */
    private function updateApp(Request $request){

        Log::debug('log_start:'.__FUNCTION__);
    
        try {

            // retrunの初期値
            $ret=[];
            
            /**
             * 値取得
             */
            // 申込id
            $application_id = $request->input('application_id');

            // session_id
            $session_id = $request->session()->get('create_user_id');

            // 現在の日付取得
            $date = now() .'.000';

            /**
             * 入居審査フラグ
             * 0=契約に進めない　1=契約に進める
             */
            $move_in_examinan_flag = 1;

            /**
             * 申込テーブルのupdate
             * 0=契約に進めない　1=契約に進める
             */
            $str = "update "
            ."applications "
            ."set "
            ."move_in_examinan_flag = $move_in_examinan_flag, "
            ."entry_user_id = $session_id, "
            ."entry_date = '$date', "
            ."update_user_id = $session_id, "
            ."update_date = '$date' "
            ."where "
            ."application_id = $application_id; ";

            Log::debug('update_sql:'.$str);
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
     * 契約に進める(契約テーブル登録)
     *
     * @param Request $request
     * @return void
     */
    private function insertContractDetail(Request $request){

        Log::debug('log_start:'.__FUNCTION__);
    
        try {

            // retrunの初期値
            $ret=[];

            // 申込id
            $application_id = $request->input('application_id');

            /**
             * 申込テーブルのデータ取得
             */
            $str = "select "
            ."applications.application_id as application_id, "
            ."real_estate_name as real_estate_name, "
            ."room_name as room_name, "
            ."post_number as real_estate_post_number, "
            ."address as real_estate_address, "
            ."security_fee as security_fee, "
            ."deposit_fee as deposit_fee, "
            ."key_fee as key_fee, "
            ."refund_fee as refund_fee, "
            ."rent_fee as rent_fee, "
            ."service_fee as service_fee, "
            ."water_fee as water_fee, "
            ."ohter_fee as ohter_fee, "
            ."total_fee as total_fee, "
            ."entry_contracts.entry_contract_name as entry_contract_name, "
            ."entry_contracts.entry_contract_ruby as entry_contract_ruby, "
            ."entry_contracts.entry_contract_birthday as entry_contract_birthday, "
            ."entry_contracts.entry_contract_home_tel as entry_contract_home_tel, "
            ."entry_contracts.entry_contract_mobile_tel as entry_contract_mobile_tel "
            ."from "
            ."applications "
            ."left join entry_contracts on "
            ."applications.application_id = entry_contracts.application_id "
            ."where applications.application_id = $application_id ";

            Log::debug('select_sql:'.$str);
            $app_info = DB::select($str);

            /**
             * 申込の値を変数に格納
             */
            // session_id
            $session_id = $request->session()->get('create_user_id');

            // 物件名
            $real_estate_name = $app_info[0]->real_estate_name;

            // 号室
            $room_name = $app_info[0]->room_name;

            // 郵便番号
            $real_estate_post_number = $app_info[0]->real_estate_post_number;

            // 住所
            $real_estate_address = $app_info[0]->real_estate_address;

            // 保証金
            $security_fee = $app_info[0]->security_fee;

            // 保証金が0以外の処理
            if($security_fee !== 0){

                // 保証金
                $security_ordeposit_fee = $app_info[0]->security_fee;

                // 解約引き
                $refund_or_key_fee = $app_info[0]->refund_fee;
            
            // 保証金が0の処理
            }else{

                // 敷金
                $security_ordeposit_fee = $app_info[0]->deposit_fee;

                // 礼金
                $refund_or_key_fee = $app_info[0]->key_fee;

            };

            // 家賃
            $rent_fee = $app_info[0]->rent_fee;

            // 共益費
            $service_fee = $app_info[0]->service_fee;

            // 水道代
            $water_fee = $app_info[0]->water_fee;

            // その他
            $ohter_fee = $app_info[0]->ohter_fee;

            // 合計額
            $total_fee = $app_info[0]->total_fee;

            // 契約者名
            $entry_contract_name = $app_info[0]->entry_contract_name;

            // 契約者フリガナ
            $entry_contract_ruby = $app_info[0]->entry_contract_ruby;

            // 生年月日
            $entry_contract_birthday = $app_info[0]->entry_contract_birthday;

            // 電話番号
            $entry_contract_home_tel = $app_info[0]->entry_contract_home_tel;

            // 携帯番号
            $entry_contract_mobile_tel = $app_info[0]->entry_contract_mobile_tel;

            // 現在の日付取得
            $date = now() .'.000';

            /**
             * 文字列の場合、空白にする
             * 数値の場合、0にする
             */
            if($real_estate_name == null){
                $real_estate_name = '';
            };

            if($room_name == null){
                $room_name = '';
            };

            if($real_estate_post_number == null){
                $real_estate_post_number = '';
            };

            if($real_estate_address == null){
                $real_estate_address = '';
            };

            if($security_ordeposit_fee == null){
                $security_ordeposit_fee = 0;
            };

            if($refund_or_key_fee == null){
                $refund_or_key_fee = 0;
            };

            if($rent_fee == null){
                $rent_fee = 0;
            };

            if($service_fee == null){
                $service_fee = 0;
            };

            if($water_fee == null){
                $water_fee = 0;
            };

            if($ohter_fee == null){
                $ohter_fee = 0;
            };

            if($total_fee == null){
                $total_fee = 0;
            };

            if($entry_contract_name == null){
                $entry_contract_name = '';
            };

            if($entry_contract_ruby == null){
                $entry_contract_ruby = '';
            };

            if($entry_contract_birthday == null){
                $entry_contract_birthday = '';
            };

            if($entry_contract_home_tel == null){
                $entry_contract_home_tel = '';
            };

            if($entry_contract_mobile_tel == null){
                $entry_contract_mobile_tel = '';
            };

            /**
             * 契約者テーブル(insert)
             */
            $str = "insert "
            ."into "
            ."contract_details( "
            ."create_user_id, "
            ."contract_detail_progress_id, "
            ."application_id, "
            ."contract_name, "
            ."contract_ruby, "
            ."contract_date, "
            ."contract_tel, "
            ."real_estate_name, "
            ."real_estate_post_number, "
            ."real_estate_address, "
            ."room_name, "
            ."security_fee, "
            ."key_fee, "
            ."rent_fee, "
            ."service_fee, "
            ."water_fee, "
            ."ohter_fee, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$session_id, "
            ."1, "
            ."$application_id, "
            ."'$entry_contract_name', "
            ."'$entry_contract_ruby', "
            ."'$entry_contract_birthday', "
            ."'$entry_contract_home_tel', "
            ."'$real_estate_name', "
            ."'$real_estate_post_number', "
            ."'$real_estate_address', "
            ."'$room_name', "
            ."$security_ordeposit_fee, "
            ."$refund_or_key_fee, "
            ."$rent_fee, "
            ."$service_fee, "
            ."$water_fee, "
            ."$ohter_fee, "
            ."$session_id, "
            ."'$date', "
            ."$session_id, "
            ."'$date' "
            ."); ";

            Log::debug('update_sql:'.$str);
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
     * 契約に進める(特約事項登録)
     *
     * @param Request $request
     * @return void
     */
    private function insertSpecialContractDetail(Request $request){

        Log::debug('log_start:'.__FUNCTION__);
    
        try {

            // retrunの初期値
            $ret=[];

            // 申込id
            $application_id = $request->input('application_id');

            /**
             * 申込テーブルのデータ取得
             */
            $str = "select * from contract_details "
            ."where application_id = $application_id ";

            Log::debug('select_sql:'.$str);
            $contract_detail_info = DB::select($str);

            /**
             * 申込の値を変数に格納
             */
            // session_id
            $session_id = $request->session()->get('create_user_id');

            // contract_detail_info
            $contract_detail_id = $contract_detail_info[0]->contract_detail_id;

            // 現在の日付取得
            $date = now() .'.000';

            /**
             * 文字列の場合、空白にする
             * 数値の場合、0にする
             */
            if($application_id == null){
                $application_id = 0;
            };

            /**
             * 契約者テーブル(insert)
             */
            $str = "insert "
            ."into "
            ."special_contract_details "
            ."( "
            ."contract_detail_id, "
            ."special_contract_detail_name, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$contract_detail_id, "
            ."'', "
            ."1, "
            ."'$date', "
            ."1, "
            ."'$date' "
            ."); ";

            Log::debug('update_sql:'.$str);
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
     * モーダル開いたときに申込URL生成
     *
     * @param Request $request
     * @return void
     */
    public function backAppModalInit(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {
            // 出力値
            $response = [];

            /**
             * 値取得
             */
            // 不動産id
            $application_id = $request->input('application_id');

            // ユーザid
            $create_user_id = $request->session()->get('create_user_id');

            // 有効期限:初期値(現在の日時)
            $now = date('YmdHis');

            /**
             * 暗号化
             */
            $create_user_id = Crypt::encrypt($create_user_id);

            $application_id = Crypt::encrypt($application_id);

            $now = Crypt::encrypt($now);

            $url = url("/frontAppEditInit?create_user_id=" .$create_user_id ."&application_id=$application_id" ."&date=$now");
            Log::debug('url:'.$url);

            // url生成後、js側に返却
            $response['url'] = $url;

            $response['status'] = 1;

        // 例外処理
        } catch (\Exception $e) { 

            // ログ
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $response['status'] = false;
            
            
        // status=1の場合、true/status=1以外の場合、false
        } finally {

            if($response['status'] == 1){

                $response['status'] = true;
                
            }else{
                
                $response['status'] = false;
            }

        }

        Log::debug('end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 申込URL再発行・メール送信
     *
     * @param Request $request
     * @return void
     */
    public function backAppMailEntry(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {
            // 出力値
            $response = [];

            /**
             * 値取得
             */
            //　自身のメールアドレスをconfigファイルから取得(key:address)
            $from = config('mail.from');
            $from = $from['address'];
            
            // 不動産id
            $application_id = $request->input('application_id');

            // 物件名
            $real_estate_name = $request->input('real_estate_name');

            // 号室
            $room_name = $request->input('room_name');

            // 宛名
            $application_name = $request->input('application_name');

            // アドレス
            $application_mail = $request->input('application_mail');

            // 件名
            $subject_text = $request->input('subject_text');

            // 本文
            $url_text = $request->input('url_text');

            // 本文設定
            $mail_text = "──────────────────────────────────────────────────────────────────────\n"
            ."※※重要※※本メールは申込詳細が記載されています。大切に保管してください。\n"
            ."──────────────────────────────────────────────────────────────────────\n"
            ."$application_name "
            ."様\n\n"
            ."物件名：$real_estate_name\n"
            ."号室：$room_name\n\n"
            ."KASEGUをご利用いただき、誠にありがとうございます。\n"
            ."下記のURLをクリックし、申込詳細をご確認頂けます。\n\n"
            ."$url_text"
            ."\n\n"
            ."編集を行う場合も、こちらのURLを使用して編集してください。\n\n"
            ."────────────────────────────────────────────────────────────────────────────\n"
            ."申込詳細を閲覧出来なかった場合、お手数ですが、仲介業者にご連絡ください。\n"
            ."────────────────────────────────────────────────────────────────────────────\n";

            // メール設定
            Mail::raw($mail_text, function($message) use($application_mail,$from,$subject_text){
                $message->to($application_mail)
                ->from($from)
                ->subject($subject_text);
            });

            $response['status'] = 1;

        // 例外処理
        } catch (\Exception $e) { 

            // ログ
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $response['status'] = false;
            
            
        // status=1の場合、true/status=1以外の場合、false
        } finally {

            if($response['status'] == 1){

                $response['status'] = true;
                
            }else{
                
                $response['status'] = false;
            }

        }

        Log::debug('end:' .__FUNCTION__);
        return response()->json($response);
    }
} 