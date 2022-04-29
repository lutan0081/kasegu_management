<?php

namespace App\Http\Controllers\Front\Contact;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

// 暗号化に必要
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Mail;

use Common;

/**
 * お問合せ(表示、登録)
 */
class FrontContactController extends Controller
{
    /**
     * 表示
     */
    public function frontContactInit(Request $request)
    {
        return view('front.frontContact', []);
    }

    /**
     * Entry(登録)
     *
     * @param Request $request
     * @return void
     */
    public function frontContactEntry(Request $request)
    {
        Log::debug('log_start:' .__FUNCTION__);

        try {

            // 出力値
            $response = [];

            // バリデーション
            $response = $this->editValidation($request);

            // デバック(バリデーションのログ)
            $arrKeys = print_r($response , true);
            Log::debug('validation_respnse:'.$arrKeys);

            // true=OK/false=NG
            if($response["status"] == false){
                
                Log::debug('validator_status:falseのif文通過');
                return response()->json($response);
            }

            // insert(true=OK/false=NG)
            $response["status"] = $this->insert($request);


        // 例外処理
        } catch (\Exception $e) {

            // sqlエラーをログに記録
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $response['status'] = false;

        }finally{

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
     * バリデーション
     *
     * @param Request $request(bladeの項目)
     * @return response(status=NG/msg="入力を確認して下さい/messages=$msgs/$errkeys=$keys)
     */
    private function editValidation(Request $request){

        try{
            // returnの出力値
            $response = [];

            // status初期値
            $response["status"] = true;

            // rules
            $rules = [];
            $rules['name'] = "required|max:50";
            $rules['mail'] = "required|email";
            $rules['title'] = "required|max:50";
            $rules['message'] = "required|max:200";
            
            // messages
            $messages = [];
            $messages['name.required'] = "名前は必須です。";
            $messages['name.max'] = "名前の文字数が超過しています。";
            $messages['mail.required'] = "メールアドレスは必須です。";
            $messages['mail.email'] = "メールアドレスの形式が不正です。";
            $messages['title.required'] = "タイトルは必須です。";
            $messages['title.max'] = "タイトルの文字数が超過しています。";
            
            // 配列デバック
            $arrString = print_r($messages , true);
            Log::debug('messages:' .$arrString);

            // validation判定
            $validator = Validator::make($request->all(), $rules, $messages);

            // エラーがある場合処理
            if ($validator->fails()) {
                Log::debug('validator失敗の処理');

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

            // 例外処理
            } catch (\Exception $e) {

                throw new \Exception(__FUNCTION__ .':' .$e);

            } finally {

            }

        Log::debug('log_end:' .__FUNCTION__);
        return $response;
    }

    /**
     * 登録
     *
     * @param Request $request
     * @return ret(1=OK/0=NG)
     */
    private function insert(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try {

            // 値取得
            $contact_name = $request->input('name');
            $contact_mail = $request->input('mail');
            $title = $request->input('title');
            $contact_contents = $request->input('message');
            $date = now();

            // sql
            $str = "insert into contacts( "
            ."contact_name, "
            ."contact_mail, "
            ."title, "
            ."contact_contents, "
            ."create_date, "
            ."send_receive_flag, "
            ."contact_read_flag "
            .")VALUES( "
            ."'$contact_name', "
            ."'$contact_mail', "
            ."'$title', "
            ."'$contact_contents', "
            ."'$date', "
            ."0, "
            ."0 "
            ."); ";
            Log::debug('sql:'.$str);
            $ret = DB::insert($str);

        // 例外処理
        } catch (\Exception $e) {
            // ログ
            throw new \Exception(__FUNCTION__ .':' .$e);

        } finally {

        }

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 管理者に完了通知を送信
     *
     * @param Request $request()
     * @return view(mail)
     */
    public function frontContactMailEntry(Request $request) {

        Log::debug('log_start:' .__FUNCTION__);

        try {
            // 出力値
            $response = [];

            // 出力値
            $response["status"] = true;
            
            // mail
            $contact_mail = $request->input('contact_mail');

            // 名前
            $contact_name = $request->input('contact_name');

            // タイトル
            $title = $request->input('title');

            // 問合内容
            $contact_contents = $request->input('contact_contents');
            
            // 自身のメールアドレスをconfigファイルから取得(key:address)
            $from = config('mail.from');
            $from = $from['address'];

            // mail本文の設定
            $mail_text = "──────────────────────────────────────────────────────────────────────\n"
            ."本メールは KASEGU をご利用いただいている方に自動配信しています。\n"
            ."──────────────────────────────────────────────────────────────────────\n"
            ."\n"
            ."下記お問合わせがありました。\n\n"
            ."氏名: " ."$contact_name\n"
            ."E-mail: " ."$contact_mail\n"
            ."タイトル: " ."$title\n"
            ."メッセージ: "
            ."$contact_contents"
            ."\n\n"
            ."──────────────────────────────────────────────────────────────────────\n"
            ."メッセージのご返信は、記載のメールアドレス、管理画面より返信してください。\n"
            ."──────────────────────────────────────────────────────────────────────\n";

            /**
             * メール設定
             */
            Mail::raw($mail_text, function($message) use($from){
                $message->to('lutan0081.h@gmail.com')
                ->from($from)
                ->subject("【お問合せがありました。】");
            });

        // 例外処理
        } catch (\Exception $e) { 

            // sqlエラーをログに記録
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $response['status'] = false;
            
        // status=1の場合、true/status=1以外の場合、false
        } finally {
            
        }

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }
}