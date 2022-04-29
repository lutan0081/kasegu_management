<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
// mailに必要
use Illuminate\Support\Facades\Mail;
// 暗号化に必要
use Illuminate\Support\Facades\Crypt;

/**
 * main画面表示の処理
 */
class ApplicationController extends Controller
{   
    /**
     *  URL発行画面(表示)
     *
     * @param Request $request
     * @return view('application.application',[]);
     */
    public function applicationInit(Request $request)
    {   
        return view('application.application',[]);
    }

    /**
     *  URL発行(新規登録/ajax)
     *
     * @param Request $request
     * @return view('application.application',[]);
     */
    public function applicationEntry(Request $request)
    { 
        Log::debug('log_start:' .__FUNCTION__);

        try {
            //　自身のメールアドレスをconfigファイルから取得
            $from = config('mail.from');
            $from = $from['address'];

            // true=登録完了 false=errorMessageを返す
            $response = [];

            // returnの初期値
            $response["status"] = true;

            /**
             * // バリデーション(戻り値status、エラーメッセージ等)
             */
            $response = $this->applicationValidation($request);

            // 返却された値がfalseの場合、bladeにfalseを返す
            if($response["status"] == false){

                Log::debug('validator_status:falseのif文通過');
                return response()->json($response);

            }

            // session_id取得
            $session_id = $request->session()->get('create_user_id');
            // 暗号化
            $session_id = Crypt::encrypt($session_id);
            // 依頼者
            $application_name = $request->input('application_name');
            // mail
            $application_mail = $request->input('application_mail');
            Log::debug('application_mail:'.$application_mail);

            // 有効期限:初期値(現在の日時)
            $now = date('YmdHis');

            // 新規登録用のURL発行
            $url = url("/newInit?session_id=" .$session_id ."&deadline=$now" ."&application_Flag=true");

            /**
             * mail本文作成
             */
            $mail_text = "──────────────────────────────────────────────────────────────────────\n"
            ."本メールは KASEGU をご利用いただいている方に自動配信しています。\n"
            ."──────────────────────────────────────────────────────────────────────\n"
            ."\n"
            ."$application_name" ." 様\n"
            ."\n"
            ."新規申込URLが発行されました。\n"
            ."下記のURLをクリックし、申込手続きを完了させてください。\n"
            ."$url"
            ."\n\n"
            ."注意事項\n"
            ."※必須箇所に入力頂き登録してください。\n"
            ."　任意箇所は後からでも編集可能です。\n"
            ."※申込後、編集をする場合は申込完了後に届くURLから編集してください。"
            ."\n\n\n"
            ."──────────────────────────────────────────────────────────────────────\n"
            ."本メール到着後、3日間以内に申込の手続きが未完了の場合は、申込が無効となります。\n"
            ."指定の期間内に処理が出来なかった場合、はじめからお手続きお願い申し上げます。\n"
            ."──────────────────────────────────────────────────────────────────────\n";
            
            Log::debug('mail_text:'.$mail_text);

            /**
             * mail設定
             * use=$messageに対しての引数
             */
            Mail::raw($mail_text, function($message) use($application_mail,$from){
                $message->to($application_mail)
                ->from($from)
                ->subject("【新規申込URL発行のお知らせ】");
            });

        } catch (\Exception $e) {

            Log::debug('error:'.$e);
            // 失敗の場合falseを返す
            $response["status"] = false;

        }finally{
            Log::debug('log_end:'.__FUNCTION__);
            return response()->json($response);
        }
    }

    /**
     *  新規登録→編集登録のURL発行(完了通知)
     *
     * @param Request $request
     * @return view('application.application',[]);
     */
    public function applicationCompleteInit(Request $request)
    {   
        Log::debug('log_start:' .__FUNCTION__);

        //　自身のメールアドレスをconfigファイルから取得
        $from = config('mail.from');
        $from = $from['address'];

        /**
         * 暗号化
         */
        // 不動産業者id　
        $application_form_id = Crypt::encrypt($request->input('application_form_id'));

        /**
         * session_id
         */
        $create_user_id = $request->input('create_user_id');
        // dd($create_user_id);
        $create_user_id = Crypt::encrypt($request->input('create_user_id'));

        // 仲介業者名
        $broker_name = $request->input('broker_name');
        // メールアドレス
        $broker_mail = $request->input('broker_mail');

        // 有効期限:初期値(現在の日時)
        $now = date('YmdHis');

        // 編集用のURL発行
        $url = url("/editInit?application_form_id=" .$application_form_id ."&create_user_id=" .$create_user_id ."&application_Flag=true" ."&deadline=$now");

        /**
         * mail本文作成
         */
        $mail_text = "──────────────────────────────────────────────────────────────────────\n"
        ."本メールは編集用のURLです。今後こちらのURLより登録情報の変更が出来ます。\n"
        ."※大切に保管してください。\n"
        ."──────────────────────────────────────────────────────────────────────\n"
        ."\n"
        ."$broker_name" ." 様\n"
        ."\n"
        ."編集用URLが発行されました。\n"
        ."下記のURLをクリックし、編集してください。\n"
        ."$url"
        ."\n\n"
        ."注意事項\n"
        ."①不動産業者(全項目)※金額は除く\n"
        ."②賃借人(申込者、申込者カナ)\n"
        ."上記は入力必須項目です。\n\n"
        ."※本メールは編集用のURLになります。新規のお申込は窓口の不動産会社にご連絡御願い申し上げます。"
        ."\n\n\n"
        ."──────────────────────────────────────────────────────────────────────\n"
        ."本メール到着後、1ヵ月以内に申込の手続きが未完了の場合は、申込が無効となります。\n"
        ."指定の期間内に処理が出来なかった場合、はじめからお手続きお願い申し上げます。\n"
        ."──────────────────────────────────────────────────────────────────────\n";

        Log::debug('mail_text:'.$mail_text);

        /**
         * mail設定
         * use=$messageに対しての引数
         */
        Mail::raw($mail_text, function($message) use($broker_mail,$from){
            $message->to($broker_mail)
            ->from($from)
            ->subject("【編集用のURL発行のお知らせ】");
        });

        Log::debug('log_end:' .__FUNCTION__);
        return view('application.applicationComplete',[]);
    }

    /**
     * バリデーション
     *
     * @param Request $request(bladeの項目)
     * @return response(status=NG/msg="入力を確認して下さい/messages=$msgs/$errkeys=$keys)
     */
    private function applicationValidation(Request $request){
        // returnの出力値
        $response = [];
        // 初期値
        $response["status"] = true;

        /**
         * rules
         */
        $rules = [];
        $rules['application_name'] = "required";
        $rules['application_mail'] = "required|email";

        /**
         * messages
         */
        $messages = [];
        // 依頼者
        $messages['application_name.required'] = "依頼者は必須です。";
        // email
        $messages['application_mail.required'] = "メールアドレスは必須です。";
        $messages['application_mail.email'] = "メールアドレスの形式が不正です。";

        // validation判定
        $validator = Validator::make($request->all(), $rules, $messages);

        // エラーがある場合処理
        if ($validator->fails()) {
            Log::debug('validator:失敗');

            // responseの定数
            $keys = [];
            $msgs = [];
            // エラー内容をjson形式に変換(true=連想配列)
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

}