<?php

namespace App\Http\Controllers\Front\App;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

// 暗号化
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Mail;

// データ縮小
use InterventionImage;

use Storage;

use App\Config;

use Common;

/**
 * 申込(フロント)
 */
class FrontAppController extends Controller
{   
    /**
     * 申込URL(発行)
     *
     * @return void
     */
    public function frontAppUrlEntry(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // 出力値
            $response = [];

            // バリデーション
            $response = $this->urlValidation($request);

            // デバック(バリデーション)
            $arrKeys = print_r($response , true);
            Log::debug('validation_respnse:'.$arrKeys);

            // true=OK/false=NG
            if($response['status'] == false){
                
                Log::debug('validator_status:falseのif文通過');

                return response()->json($response);

            }

            // メール送信:OK=1/NG=0
            $app_info = $this->urlAppInsert($request);

            $response['status'] = $app_info['status'];

        // 例外処理
        } catch (\Exception $e) {

            // sqlエラーをログに記録
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $response['status'] = false;

        }finally{

            // status:OK=1/NG=0
            if($response['status'] == 1){

                $response['status'] = true;

            }else{

                $response['status'] = false;
            }

        }

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * バリデーション(url)
     *
     * @param Request $request(bladeの項目)
     * @return response(status=NG/msg="入力を確認して下さい/messages=$msgs/$errkeys=$keys)
     */
    private function urlValidation(Request $request){

        // returnの出力値
        $response = [];

        // status初期値
        $response["status"] = true;

        // rules
        $rules = [];
        $rules['application_name'] = "required|max:50";
        $rules['application_mail'] = "required|email";
        
        // messages
        $messages = [];
        $messages['application_name.required'] = "業者名は必須です。";
        $messages['application_name.max'] = "業者名の文字数が超過しています。";
        
        $messages['application_mail.required'] = "メールアドレスは必須です。";
        $messages['application_mail.email'] = "メールアドレスの形式が不正です。";

        // 配列デバック
        $arrString = print_r($messages , true);
        Log::debug('messages:' .$arrString);

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
            
        }

        Log::debug('log_end:' .__FUNCTION__);
        return $response;
    }

    /**
     * 登録・URL発行
     *
     * @param Request $request
     * @return void
     */
    private function urlAppInsert(Request $request) {

        Log::debug('log_start:'.__FUNCTION__);

        try {

            DB::beginTransaction();

            /**
             * 不動産業者(status:OK=1 NG=0)
             */
            $app_info = $this->insertApplication($request);

            // 登録した直後のapplication_idを取得
            $application_id = $app_info['application_id'];
            Log::debug('application_id:'.$application_id);

            // 1=OK 0=NG
            $ret['status'] = $app_info['status'];

            /**
             * 契約者(status:OK=1 NG=0)
             */
            $contract_info = $this->inserEntryContract($request ,$application_id);
            
            // 1=OK 0=NG
            $ret['status'] = $contract_info['status'];

            /**
             * 同居人
             */
            $emergency_info = $this->inserEmergency($request ,$application_id);
            
            // 1=OK 0=NG
            $ret['status'] = $emergency_info['status'];

            /**
             * 保証人
             */
            $guarantor_info = $this->inserGuarantor($request ,$application_id);
            
            // 1=OK 0=NG
            $ret['status'] = $guarantor_info['status'];

            /**
             * メール送信
             */
            //　自身のメールアドレスをconfigファイルから取得(key:address)
            $from = config('mail.from');
            $from = $from['address'];

            // 名前
            $application_name = $request->input('application_name');

            // メールアドレス
            $application_mail = $request->input('application_mail');

            // 物件名
            $real_estate_name = $request->input('real_estate_name');

            // 号室
            $room_name = $request->input('room_name');
            
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
            
            // 認証用URL発行
            $url = url("/frontAppEditInit?create_user_id=" .$create_user_id ."&application_id=$application_id" ."&date=$now");

            // 本文設定
            $mail_text = "──────────────────────────────────────────────────────────────────────\n"
            ."本メールは KASEGU をご利用いただいている方に自動配信しています。\n"
            ."──────────────────────────────────────────────────────────────────────\n"
            ."$application_name "
            ."様\n\n"
            ."物件名：$real_estate_name\n"
            ."号室：$room_name\n\n"
            ."KASEGUをご利用いただき、誠にありがとうございます。\n"
            ."下記のURLをクリックし、申込手続きを完了させてください。\n\n"
            ."$url"
            ."\n\n"
            ."編集を行う場合もこちらのURLを使用して編集してください。\n\n"
            ."────────────────────────────────────────────────────────────────────────────\n"
            ."申込処理が出来なかった場合、仲介業者にご連絡ください。\n"
            ."────────────────────────────────────────────────────────────────────────────\n";

            // メール設定
            Mail::raw($mail_text, function($message) use($application_mail,$from){

                $message->to($application_mail)
                ->from($from)
                ->subject("【申込URL発行のお知らせ】");

            });

            // コミット
            DB::commit();

            // 例外処理
        } catch (\Throwable $e) {

            // ロールバック
            DB::rollback();

            throw $e;

        // status:OK=1/NG=0
        } finally {
        }

        Log::debug('log_end:'.__FUNCTION__);

        return $ret;
    }

    /**
     * 不動産(登録)
     *
     * @param Request $request
     * @return void
     */
    private function insertApplication(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {

            // returnの初期値
            $ret=[];

            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->session()->get('create_user_id');

            // 値取得
            $contract_progress_id = $request->input('contract_progress_id');
            $broker_company_name = $request->input('broker_company_name');
            $broker_tel = $request->input('broker_tel');
            $broker_mail = $request->input('application_mail');
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

            // 個人又は法人
            if($private_or_company_id == null){
                $private_or_company_id =0;
            }

            // 連帯保証人有無フラグ
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
            ."1, "
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
            
            // 申込id
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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->session()->get('create_user_id');
            Log::debug('session_id:'.$session_id);

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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->input('session_id');
            Log::debug('session_id:'.$session_id);

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
            $dir ='public/img/' .$application_id;
            
            // 任意のフォルダ作成
            Storage::makeDirectory($dir);

            /**
             * 画像登録処理
             */
            // ファイル名変更
            $file_name = time() .'.' .$img_file->getClientOriginalExtension();
            Log::debug('ファイル名:'.$file_name);

            // ファイルパス+ファイル名
            $tmp_file_path = 'app/' .$dir .'/' .$file_name;
            Log::debug('tmp_file_path :'.$tmp_file_path);

            InterventionImage::make($img_file)->resize(380, null,
            function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path($tmp_file_path));

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
     * 新規表示
     *
     * @param Request $request
     * @return void
     */
    public function frontAppNewInit(Request $request) {
        Log::debug('start:' .__FUNCTION__);

        try { 

            // 申込情報取得(新規:空データ)
            $app_info = $this->getAppNewList();
            $app_list = $app_info;

            // 入居者一覧取得(空配列)
            $houseMate_list = [];

            // session_id
            $session_id = $request->input('create_user_id');
            // 複合化
            // $session_id = Crypt::decrypt($session_id);

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
        
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        return view('front.frontAppEdit',compact('contract_progress' ,'app_types' ,'app_uses' ,'app_links' ,'app_insurances' ,'needs' ,'app_sexes' ,'app_list' ,'houseMate_list' ,'img_type' ,'img_list' ,'session_id' ,'private_or_companies' ,'guarantee_companies'));
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
        $obj->security_fee = '';
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
     * 登録(フロント:新規・編集に分岐)
     *
     * @return void
     */
    public function frontAppEditEntry(Request $request){
        
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
         * application_id=無:insert
         * application_id=有:update
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
            $rules['img_file'] = "nullable|mimes:jpeg,png,jpg,pdf";

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
            $messages['img_file.mimes'] = "画像ファイル(jpg.jpeg.png.pdf)でアップロードして下さい。";

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
     * 編集(表示)
     *
     * @param Request $request
     * @return void
     */
    public function frontAppEditInit(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {

            /**
             * 値取得
             */
            $session_id = $request->input('create_user_id');

            $date = $request->input('date');

            // 有効期限:初期値(現在の日時)
            $now = date('YmdHis');

            /**
             * 複合化
             */
            $session_id = Crypt::decrypt($session_id);
            Log::debug('session_id:'.$session_id);


            $date = Crypt::decrypt($date);
            Log::debug('date:'.$date);

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

            // 画像種別
            $img_type = $common->getImgType();

            // 個人又は法人
            $private_or_companies = $common->getPrivateOrCompanies();

            // 保証会社一覧
            $guarantee_companies = $common->getGuaranteeCompanies();

        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        return view('front.frontAppEdit',compact('contract_progress' ,'app_types' ,'app_uses' ,'app_links' ,'app_insurances' ,'needs' ,'app_sexes' ,'app_list' ,'houseMate_list' ,'img_type' ,'img_list' ,'session_id' ,'private_or_companies' ,'guarantee_companies'));    
    }

    /**
     * 編集(申込情報取得:sql)
     *
     * @return void
     */
    private function getAppEditList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            /**
             * 値取得
             */
            $create_user_id = $request->input('create_user_id');

            $application_id = $request->input('application_id');

            $date = $request->input('date');

            // 有効期限:初期値(現在の日時)
            $now = date('YmdHis');

            /**
             * 複合化
             */
            $create_user_id = Crypt::decrypt($create_user_id);
            Log::debug('create_user_id:'.$create_user_id);
            
            $application_id = Crypt::decrypt($application_id);
            Log::debug('application_id:'.$application_id);

            $date = Crypt::decrypt($date);
            Log::debug('date:'.$date);

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
            /**
             * 値取得
             */
            $create_user_id = $request->input('create_user_id');

            $application_id = $request->input('application_id');

            $date = $request->input('date');

            // 有効期限:初期値(現在の日時)
            $now = date('YmdHis');

            /**
             * 複合化
             */
            $create_user_id = Crypt::decrypt($create_user_id);
            Log::debug('create_user_id:'.$create_user_id);
            
            $application_id = Crypt::decrypt($application_id);
            Log::debug('application_id:'.$application_id);

            $date = Crypt::decrypt($date);
            Log::debug('date:'.$date);

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
            /**
             * 値取得
             */
            $create_user_id = $request->input('create_user_id');

            $application_id = $request->input('application_id');

            $date = $request->input('date');

            // 有効期限:初期値(現在の日時)
            $now = date('YmdHis');

            /**
             * 複合化
             */
            $create_user_id = Crypt::decrypt($create_user_id);
            Log::debug('create_user_id:'.$create_user_id);
            
            $application_id = Crypt::decrypt($application_id);
            Log::debug('application_id:'.$application_id);

            $date = Crypt::decrypt($date);
            Log::debug('date:'.$date);

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

            // returnのステータスにapplication_idを設定
            $ret['application_id'] = $app_info['application_id'];

            // returnのステータスにcreate_user_idを設定
            $ret['create_user_id'] = $app_info['create_user_id'];

            // real_estate_name
            $ret['real_estate_name'] = $app_info['real_estate_name'];

            // room_name
            $ret['room_name'] = $app_info['room_name'];

            // broker_company_name
            $ret['broker_company_name'] = $app_info['broker_company_name'];

            // broker_mail
            $ret['broker_mail'] = $app_info['broker_mail'];

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
            Log::debug('housemate_add_flag:' .$housemate_add_flag);

            // 同居人追加フラグ:false=0/true=1
            if($housemate_add_flag == 'false'){

                Log::debug('同居人フラグfalseの処理');
                
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

            /**
             * 登録後メールで通知
             */
            $mail_info = $this->completeMail($request);
            
            // returnのステータスにtrueを設定
            $ret['status'] = $mail_info['status'];

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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->input('session_id');
            Log::debug('session_id:'.$session_id);

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

            // 登録したapplication_id取得
            $str = "select * "
            ."from applications "
            ."where "
            ."application_id = '$application_id' ";
            Log::debug('select_sql:'.$str);

            // ログ
            $app_info = DB::select($str);
            Log::debug($app_info);
            
            // 申込id
            $ret['application_id'] = $app_info[0]->application_id;

            // create_user_id
            $ret['create_user_id'] = $app_info[0]->create_user_id;

            // real_estate_name
            $ret['real_estate_name'] = $app_info[0]->real_estate_name;

            // room_name
            $ret['room_name'] = $app_info[0]->real_estate_name;

            // broker_company_name
            $ret['broker_company_name'] = $app_info[0]->broker_company_name;

            // broker_mail
            $ret['broker_mail'] = $app_info[0]->broker_mail;

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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->input('session_id');
            Log::debug('session_id:'.$session_id);

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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->input('session_id');
            Log::debug('session_id:'.$session_id);

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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->input('session_id');
            Log::debug('session_id:'.$session_id);

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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->input('session_id');
            Log::debug('session_id:'.$session_id);

            // 申込id
            $application_id = $request->input('application_id');

            $img_file = $request->file('img_file');
            Log::debug('img_file:'.$img_file);

            if($img_file == null){

                $ret['status'] = 1;
                return $ret;
            }

            // 拡張子取得
            $file_extension = $img_file->getClientOriginalExtension();
            Log::debug('file_extension:'.$file_extension);

            // 種別
            $img_type = $request->input('img_type');
            Log::debug('img_type:'.$img_type);

            // 備考
            $img_text = $request->input('img_text');
            Log::debug('img_text:'.$img_text);

            // 現在の日付取得
            $date = now() .'.000';
        
            // idごとのフォルダ作成のためのパス生成
            $dir ='public/img/' .$application_id;
            
            // 任意のフォルダ作成
            Storage::makeDirectory($dir);

            /**
             * 画像登録処理
             */
            // ファイル名変更
            $file_name = time() .'.' .$img_file->getClientOriginalExtension();
            Log::debug('ファイル名:'.$file_name);

            // ファイルパス+ファイル名
            $tmp_file_path = 'app/' .$dir .'/' .$file_name;
            Log::debug('tmp_file_path :'.$tmp_file_path);

            // pdfの場合、通常の保存をする
            if($file_extension == 'pdf'){

                // 第一引数=ディレクトリ,第二引数=ファイル名
                Log::debug('PDFの処理');
                $img_file->storeAs($dir, $file_name);

            }else{

                InterventionImage::make($img_file)->resize(380, null,
                function ($constraint) {
                    $constraint->aspectRatio();
                })->save(storage_path($tmp_file_path));

            }

            // 種別
            if($img_type == null){
                $img_type = 0;
            }

            // db登録用のファイルパスを生成
            $tmp_file_path = explode("/",$tmp_file_path);
            $tmp_file_path = $tmp_file_path[2] .'/' .$tmp_file_path[3] .'/' .$tmp_file_path[4];
            Log::debug('tmp_file_path :'.$tmp_file_path);

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
     * 登録後の完了メール送信
     *
     * @param Request $request
     * @return void
     */
    private function completeMail(Request $request){

        Log::debug('log_start:'.__FUNCTION__);

        $ret = [];

        /**
         * 値取得
         */
        //　自身のメールアドレスをconfigファイルから取得(key:address)
        $from = config('mail.from');
        $from = $from['address'];

        /**
         * ユーザ情報取得
         */
        $create_user_id = $request->input('session_id');
        Log::debug('create_user_id:'.$create_user_id);

        $str = "select "
        ."* "
        ."from "
        ."create_users "
        ."where "
        ."create_users.create_user_id = '$create_user_id' ";
        Log::debug('$sql:' .$str);

        $user_info = DB::select($str)[0];

        // アカウント名
        $create_user_name = $user_info->create_user_name;

        // メールアドレス
        $create_user_mail = $user_info->create_user_mail;

        // 物件名
        $real_estate_name = $request->input('real_estate_name');

        // 号室
        $room_name = $request->input('room_name');

        // 不動産id
        $application_id = $request->input('application_id');
        
        // URL発行
        $url = url("/backAppEditInit?create_user_id=" .$create_user_id ."&application_id=$application_id");

        // 本文設定
        $mail_text = "──────────────────────────────────────────────────────────────────────\n"
        ."本メールは KASEGU をご利用いただいている方に自動配信しています。\n"
        ."──────────────────────────────────────────────────────────────────────\n"
        ."$create_user_name "
        ."様\n\n"
        ."KASEGUをご利用いただき、誠にありがとうございます。\n"
        ."下記物件の登録・編集がありました。\n\n"
        ."物件名：$real_estate_name\n"
        ."号室：$room_name\n\n"
        ."$url\n\n"
        ."────────────────────────────────────────────────────────────────────────────\n"
        ."URLを閲覧出来なかった場合、システム管理者にご連絡ください。\n"
        ."────────────────────────────────────────────────────────────────────────────\n";

        // メール設定
        Mail::raw($mail_text, function($message) use($create_user_mail,$from){

            $message->to($create_user_mail)
            ->from($from)
            ->subject("【登録・変更のお知らせ】申込一覧/KASEGU");

        });

        $ret['status'] = 1;

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->input('session_id');
            Log::debug('session_id:'.$session_id);

            $application_id = $request->input('application_id');
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
            // URLからの場合sessionに値が保持されていないため、フォームから取得する
            $session_id = $request->input('session_id');
            Log::debug('session_id:'.$session_id);

            $application_id = $request->input('application_id');
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
     * 同居人(表示:ダブルクリックの処理)
     *
     * @return void
     */
    public function frontAppHouseMateInit(Request $request){
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
     * 同居人(削除)
     *
     * @param Request $request
     * @return void
     */
    public function frontAppHouseMateDeleteEntry(Request $request){
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
     * 削除(画像:詳細)
     *
     * @param Request $request
     * @return $ret['status'] OK=true/NG=false
     */
    public function frontDeleteEntryImgDetail(Request $request){
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
            // 取得データを"/"で分解、appを除外し文字結合(public/img/214)
            $arr = explode('/', $img_list[0]->img_path);
            $img_dir_path = $arr[0] ."/" .$arr[1];

            // フォルダの中身を確認
            $img_arr = Storage::files('/public/' .$img_dir_path);

            // デバック(ファイルの中身を確認)
            Log::debug('img_arr:'.$arrString);
            $arrString = print_r($img_arr , true);

            // 参照の値が空白の場合、フォルダ削除
            if(empty($img_arr)){

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

}