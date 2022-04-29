<?php

namespace App\Http\Controllers\Front\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Crypt;

use App\Config;

use Common;

/**
 * 新着情報
 */
class FrontUserController extends Controller
{   
    /**
     * 表示
     *
     * @param Request $request
     * @return list_info(ページネーションでお知らせをreturnする)
     */
    public function frontUserInit(Request $request)
    {   
        // ユーザ登録
        Log::debug('start:' .__FUNCTION__);
        try {

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }
        Log::debug('end:' .__FUNCTION__);
        return view('front.frontUser',[]);
    }

    /**
     * ユーザ登録(ajax1)
     * @param Request $request リクエスト情報
     * @return object json形式
     */
    public function frontUserEdit(Request $request) {

        Log::debug('start:' .__FUNCTION__);

        try {
            // 出力値
            $response = [];

            // バリデーション
            $response = $this->editValidation($request);

            // true=OK/false=NG
            if($response['status'] == false){
                
                Log::debug('validator_status:falseのif文通過');
                return response()->json($response);

            }

            // insert(status:true=OK false=NG)
            $insert_info = $this->insertData($request);

            // 登録したstatusを取得(仮登録完了のパラ引数に使用のため)
            $response['status'] = $insert_info['status'];

            // 登録したcreate_user_idを取得(仮登録完了のパラ引数に使用のため)
            $response['m_create_user_id'] = $insert_info['m_create_user_id'];

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
     * バリデーション
     *
     * @param Request $request(bladeの項目)
     * @return response(status=NG/msg="入力を確認して下さい/messages=$msgs/$errkeys=$keys)
     */
    private function editValidation(Request $request){

        // 個人情報保護方針にチェック=true/チェック無=false
        $agree = $request->input('agree');

        // returnの出力値
        $response = [];

        // status初期値
        $response["status"] = true;

        // rules
        $rules = [];
        $rules['name'] = "required|max:50";
        $rules['mail'] = "required|email|maildb";
        $rules['post'] = "required|zip";
        $rules['address'] = "required|max:200";
        $rules['tel'] = "required|jptel";
        $rules['fax'] = "nullable|jptel";
        $rules['password'] = "required|alpha_dash|min:8";
        $rules['password_conf'] = "required|alpha_dash|min:8";

        // 個人情報保護方針=ture：false
        if($agree == 'false'){
            $rules['agree'] = "boolean";
        };
        
        // messages
        $messages = [];
        $messages['name.required'] = "名前は必須です。";
        $messages['name.max'] = "名前の文字数が超過しています。";
        $messages['mail.required'] = "メールアドレスは必須です。";
        $messages['mail.email'] = "メールアドレスの形式が不正です。";
        $messages['mail.maildb'] = "メールアドレスが既に登録されています。";
        $messages['post.required'] = "郵便番号は必須です。";
        $messages['post.zip'] = "郵便番号の形式が不正です。";
        $messages['address.required'] = "住所は必須です。";
        $messages['address.max'] = "住所の文字数が超過しています。";
        $messages['tel.required'] = "Telは必須です。";
        $messages['tel.jptel'] = "Telの形式が不正です。";
        $messages['fax.jptel'] = "Faxの形式が不正です-。";
        $messages['password.required'] = "パスワードは必須です。";
        $messages['password.alpha_dash'] = "パスワードは半角英数字で入力して下さい。";
        $messages['password.min'] = "パスワードは8文字以上で入力して下さい。";
        $messages['password_conf.required'] = "パスワード再入力は必須です。";
        $messages['password_conf.alpha_dash'] = "パスワード再入力は半角英数字で入力して下さい。";
        $messages['password_conf.min'] = "パスワード再入力は8文字以上で入力して下さい。";
        
        // 個人情報保護方針=ture：false
        if($agree == 'false'){
            $messages['agree.boolean'] = "個人情報保護方針をチェックして下さい。";
        }

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
             * status:OK=1 NG=0
             */
            // アカウント情報
            $create_user_info = $this->insertCreateUsers($request);
            $ret['status'] = $create_user_info['status'];

            // 登録したcreate_user_idを取得(仮登録完了のパラ引数に使用のため)
            $max_id = $this->maxId($request);
            $ret['m_create_user_id'] = $max_id;
            Log::debug('m_create_user_id:' .$max_id);

            // 免許情報
            $company_license_info = $this->insertCompanyLicenses($request ,$max_id);
            $ret['status'] = $company_license_info['status'];

            // コミット
            DB::commit();

        // 例外処理
        } catch (\Throwable $e) {

            DB::rollback();

            Log::debug(__FUNCTION__ .':' .$e);

            $ret['status'] = 0;

        // status:OK=1/NG=0
        } finally {

        }

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * アカウント情報
     */
    public function insertCreateUsers($request){

        Log::debug('log_start:' .__FUNCTION__);

        try {

            // returnの初期値
            $ret=[];

            // 値取得
            $name = $request->input('name');
            $mail = $request->input('mail');
            $post = $request->input('post');
            $address = $request->input('address');
            $tel = $request->input('tel');
            $fax = $request->input('fax');
            $password = $request->input('password');
            $password_conf = $request->input('password_conf');
            $agree = $request->input('agree');

            $str = "insert into create_users ("
            ."create_user_name, "
            ."create_user_post_number, "
            ."create_user_address, "
            ."create_user_tel, "
            ."create_user_fax, "
            ."create_user_mail, "
            ."password, "
            ."complete_flag, "
            .'admin_user_flag, '
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."'$name', "
            ."'$post', "
            ."'$address', "
            ."'$tel', "
            ."'$fax', "
            ."'$mail', "
            ."'$password', "
            ."0, "
            .'0, '
            ."now(), "
            ."1, "
            ."now()"
            .")";
            Log::debug('log_sql:' .$str);

            $ret['status'] = DB::insert($str);

            // 例外処理
        } catch (\Exception $e) {

            throw $e;

        } finally {

        }

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 免許情報(登録)
     * 
     * @param Request $request
     * @return $ret['application_id(登録のapplication_id)']['status:1=OK/0=NG']''
     */
    private function insertCompanyLicenses(Request $request ,$max_id){
        Log::debug('log_start:' .__FUNCTION__);

        try {
            // returnの初期値
            $ret=[];

            // 値取得
            $session_id = $request->session()->get('create_user_id');
            $date = now() .'.000';

            $str = "insert "
            ."into "
            ."company_licenses "
            ."( "
            ."create_user_id, "
            ."company_license_name, "
            ."company_license_representative, "
            ."company_license_address, "
            ."company_license_tel, "
            ."company_license_fax, "
            ."company_license_number, "
            ."company_license_span, "
            ."company_nick_name, "
            ."company_nick_address, "
            ."user_license_id, "
            ."legal_place_id, "
            ."guaranty_association_id, "
            ."entry_user_id, "
            ."entry_date, "
            ."update_user_id, "
            ."update_date "
            .")values( "
            ."$max_id, "
            ."'', "
            ."'', "
            ."'', "
            ."'', "
            ."'', "
            ."'', "
            ."'', "
            ."'', "
            ."'', "
            ."0, "
            ."0, "
            ."0, "
            ."$max_id, "
            ."'$date', "
            ."$max_id, "
            ."'$date' "
            ."); ";
            
            Log::debug('insert_sql:'.$str);

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
     * idの最大値取得(ユーザid)
     *
     * @param Request $request(edit.blade.phpの各項目)
     * @return $ret(最新の契約者id)
     */
    private function maxId(Request $request){

        Log::debug('log_start:' .__FUNCTION__);

        try {
            // 値取得
            $name = $request->input('name');
            $mail = $request->input('mail');

            $str = "select "
            ."max(create_user_id) as max_create_user_id "
            ."from "
            ."create_users "
            ."where "
            ."create_user_name = '$name' "
            ."and "
            ."create_user_mail = '$mail' ";
            Log::debug('sql:' .__FUNCTION__);

            $dataTable = DB::select($str);

            $list = $dataTable[0];
            $ret = $list->max_create_user_id;

        // 例外処理
        } catch (\Exception $e) {
            
            throw new \Exception(__FUNCTION__ .':' .$e);

        } finally {

        }

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * 認証URL発行(ajax2)
     * @param Request $request リクエスト情報
     * @return object json形式
     */
    public function frontUserMail(Request $request) {

        Log::debug('log_start:' .__FUNCTION__);

        try {
            // 出力値
            $response = [];

            //　自身のメールアドレスをconfigファイルから取得(key:address)
            $from = config('mail.from');
            $from = $from['address'];

            // 値取得
            // mail
            $mail = $request->input('mail');

            // ユーザid
            $create_user_id = $request->input('create_user_id');
            Log::debug('create_user_id:'.$create_user_id);

            // create_user_id暗号化
            $create_user_id = Crypt::encrypt($create_user_id);
            
            // 有効期限:初期値(現在の日時)
            $now = date('YmdHis');
            
            // 認証用URL発行
            $url = url("/frontUserComplete?create_user_id=" .$create_user_id ."&date=$now");

            // 本文設定
            $mail_text = "──────────────────────────────────────────────────────────────────────\n"
            ."本メールは KASEGU をご利用いただいている方に自動配信しています。\n"
            ."──────────────────────────────────────────────────────────────────────\n"
            ."\n"
            ."アカウントの仮登録が完了しました。\n"
            ."※まだ登録が完了していません。\n\n"
            ."下記のURLをクリックし、登録を完了して下さい。\n"
            ."$url"
            ."\n\n\n"
            ."──────────────────────────────────────────────────────────────────────\n"
            ."本メール到着後、1時間以内に本登録が行われない場合は、仮登録が無効となります。\n"
            ."指定の期間内に処理が出来なかった場合、はじめからお手続きお願い申し上げます。\n"
            ."──────────────────────────────────────────────────────────────────────\n";

            // メール設定
            Mail::raw($mail_text, function($message) use($mail,$from){

                $message->to($mail)
                ->from($from)
                ->subject("【仮登録完了のお知らせ】");
            });

            // 出力値
            $response["status"] = true;

        // 例外処理
        } catch (\Exception $e) { 

            // ログ
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $response['status'] = false;
            
        // status=1の場合、true/status=1以外の場合、false
        } finally {
            
        }
        
        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 登録(本登録)
     * 仮登録URLをクリックした時に通過
     *
     * @param Request $request($create_user_id、$decrypted)
     * @return view('mail.mailComplete', []);
     */
    public function frontUserComplete(Request $request){
        
        Log::debug('log_start:' .__FUNCTION__);

        try {

            // create_user_idの値取得
            $create_user_id = $request->input('create_user_id');

            // create_user_idの複合化
            $create_user_id = Crypt::decrypt($create_user_id);
            Log::debug('create_user_id:'.$create_user_id);

            /**
             * 締切の設定
             */
            // リクエストパラメータの時刻取得
            $req_date = $request->input('date');

            // 現在時刻取得- 1時間
            $system_date = date('YmdHis', strtotime('-1 hour'));

            // リクエストの時刻が大きい場合はOK
            if (strtotime($req_date) > strtotime($system_date)) {

                // dd("OK");
                // complete_flag(本登録のフラグ)を1にする
                $str = "update "
                ."create_users "
                ."set "
                ."complete_flag = 1, "
                ."update_user_id = '$create_user_id', "
                ."update_date = now() "
                ."where create_user_id ='$create_user_id'";
                $dataTable = DB::update($str);

                // ユーザ取得
                $list_user = $this->getList($create_user_id);
                
                // 登録完了通知を管理者にmailで送る
                $this->announceManager($list_user);

                Log::debug('時間内の処理');
                return view('front.frontUserResi', []);

            } else {

                Log::debug('時間以外の処理');
                return view('expiry.expiry', []);
            }

        // 例外処理
        } catch (\Exception $e) { 

            // ログ
            Log::debug('error:'.$e);

        // status=1の場合、true/status=1以外の場合、false
        } finally {
            
        }

        Log::debug('log_end:' .__FUNCTION__);
    }

    /**
     * ユーザ情報取得
     *
     * @param [type] $decrypted(ユーザid(複合化))
     * @return return $ret(ユーザ情報)
     */
    private function getList($decrypted){
        Log::debug('log_start:' .__FUNCTION__);

        try {

            $str = "select * "
            ."from create_users "
            ."where create_user_id = "
            ."'$decrypted'";

            $dataTable = DB::select($str);
            $ret = $dataTable[0];

        // 例外処理
        } catch (\Exception $e) { 

            throw new \Exception(__FUNCTION__ .':' .$e);

        // status=1の場合、true/status=1以外の場合、false
        } finally {
            
        }

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }


    /**
     * 本登録されたことを管理者に通知
     *
     * @param [type] $list_user(users)
     * @return void
     */
    private function announceManager($list_user){
        Log::debug('log_start:' .__FUNCTION__);

        try{
            //　自身のメールアドレスをconfigファイルから取得
            $from = config('mail.from');
            $from = $from['address'];

            /**
             * 値取得
             */
            $create_user_id = $list_user->create_user_id;
            $create_user_name = $list_user->create_user_name;
            $address = $list_user->create_user_address;
            $create_user_tel = $list_user->create_user_tel;
            $create_user_fax = $list_user->create_user_fax;
            
            /**
             * mail本文の設定
             */
            $mail_text = "下記ユーザからアカウント登録がありました。\n\n"
            ."名前:" .$create_user_name ."\n"
            ."住所:" .$address ."\n"
            ."Tel:" .$create_user_tel ."\n"
            ."Fax:" .$create_user_fax ."\n";

            /**
             * メール設定
             * use=$messageに対しての引数
             */
            Mail::raw($mail_text, function($message) use($from){
                $message->to('lutan0081.h@gmail.com')
                ->from($from)
                ->subject("【アカウント登録のお知らせ】");
            });

        // 例外処理
        } catch (\Exception $e) { 

            throw new \Exception(__FUNCTION__ .':' .$e);

        // status=1の場合、true/status=1以外の場合、false
        } finally {
            
        }

        Log::debug('log_end:' .__FUNCTION__);
    }
}