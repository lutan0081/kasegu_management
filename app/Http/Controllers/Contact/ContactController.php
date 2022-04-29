<?php

namespace App\Http\Controllers\Contact;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

// 暗号化に必要
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Mail;

/**
 * お問合せ(表示、登録)
 */
class ContactController extends Controller
{
    /**
     * お問合せ(表示)
     */
    public function contactInit(Request $request)
    {
        return view('front.frontContact', []);
    }

    /**
     * お問合せ(登録)
     * 入力項目validation->insert->trueを返す
     * 初期値;true 
     */
    public function contactEntry(Request $request)
    {
        Log::debug('log_start:' .__FUNCTION__);
        try {
            DB::beginTransaction();

            /**
             * requestの値取得
             */
            $contact_name = $request->input('contact_name');
            $contact_email = $request->input('contact_mail');
            $title = $request->input('title');
            $contact_contents = $request->input('contact_contents');

            /**
             * validation
             */
            // returnの出力値
            $response = [];
            // status初期値
            $response["status"] = true;
    
            // rules
            $rules = [];
            $rules['contact_name'] = "required|max:100";
            $rules['contact_mail'] = "required|email";
            $rules['title'] = "required|max:100";
            $rules['contact_contents'] = "required|max:200";

            /**
             * messages
             */
            $messages = [];
            // 氏名
            $messages['contact_name.required'] = "名前は必須です。";
            $messages['contact_name.max'] = "名前の文字数が超過しています。";
            // E-mail
            $messages['contact_mail.required'] = "メールアドレスは必須です。";
            $messages['contact_mail.email'] = "メールアドレスの形式が不正です。";
            // タイトル
            $messages['title.required'] = "タイトルは必須です。";
            $messages['title.max'] = "タイトルの文字数が超過しています。";
            // お問合わせ内容
            $messages['contact_contents.required'] = "メッセージは必須です。";
            $messages['contact_contents.max'] = "メッセージの文字数が超過しています。";

            // validation判定
            $validator = Validator::make($request->all(), $rules, $messages);

            // error処理
            if ($validator->fails()) {
                Log::debug('validator->fails');
                // ajax返却定数
                $keys = [];
                $msgs = [];
                // errorsをjson形式に変換(trueの場合、連想配列)
                $ary = json_decode($validator->errors(), true);
                
                // ループ&値をvalueに設定
                foreach ($ary as $key => $value) {
                    // キーを配列に設定
                    $keys[] = $key;
                    // 値(メッセージ)を設定
                    $msgs[] = $value;
                }

                // response値設定
                // status = falseの場合js側でerrorメッセージ表示
                $response["status"] = false;
                $response['msg'] = "入力を確認して下さい。";
                $response["messages"] = $msgs;
                $response["errkeys"] = $keys;
                
                return response()->json($response);
            }

            /**
             * insert(true=OK/false=NG)
             */
            $response["status"] = $this->insert($request);

            /**
             * 登録したデータをresponseに値設定、responseでjsに返却
             * 暗号化
             */
            $contact_info = $this->select($request);
            // id
            $response['contact_id'] = Crypt::encrypt($contact_info['contact_id']);
            // 名前
            $response['contact_name'] = Crypt::encrypt($contact_info['contact_name']);
            // メール
            $response['contact_mail'] = Crypt::encrypt($contact_info['contact_mail']);
            // タイトル
            $response['title'] = Crypt::encrypt($contact_info['title']);
            // 内容
            $response['contact_contents'] = Crypt::encrypt($contact_info['contact_contents']);
            // コミット
            DB::commit();

        // 例外処理
        } catch (\Exception $e) {
            // ロールバック
            DB::rollback();
            // sqlエラーをログに記録
            Log::debug('error:'.$e);
            // 失敗の場合falseを返す
            $response['status'] = false;
        }
        // status=1の場合、true/status=1以外の場合、false
        finally{

            if($response['status'] == 1){
                $response['status'] = true;
            }else{
                $response['status'] = false;
            }
            Log::debug('log_end:'.__FUNCTION__);
            return $response;
        }
        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }

    /**
     * 登録(insert)
     *
     * @param Request $request
     * @return void
     */
    private function insert(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * requestの値取得
         */
        $contact_name = $request->input('contact_name');
        $contact_mail = $request->input('contact_mail');
        $title = $request->input('title');
        $contact_contents = $request->input('contact_contents');

        // 現在日
        $date = now();

        /**
         * insert
         */
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

        Log::debug('sql_contacts:'.$str);
        $ret = DB::insert($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * insertしたデータを取得
     *
     * @param Request $request(お問合わせフォームのデータ)
     * @return $ret(insertしたデータ)
     */
    private function select(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        /**
         * requestの値取得
         * 複合化
         */
        $contact_name = $request->input('contact_name');
        $contact_mail = $request->input('contact_mail');
        $title = $request->input('title');
        $contact_contents = $request->input('contact_contents');

        $str = "select * from contacts "
        ."where "
        ."contact_name = '$contact_name' "
        ."and "
        ."contact_mail ='$contact_mail' "
        ."and "
        ."title ='$title' ";

        Log::debug('sql_select:'.$str);
        $contact_info = DB::select($str);

        // 連想配列の初期値
        $ret = [];
        // id
        $ret['contact_id'] = $contact_info[0]->contact_id;
        // 名前
        $ret['contact_name'] = $contact_info[0]->contact_name;
        // メール
        $ret['contact_mail'] = $contact_info[0]->contact_mail;
        // タイトル
        $ret['title'] = $contact_info[0]->title;
        // 内容
        $ret['contact_contents'] = $contact_info[0]->contact_contents;
        
        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }

    /**
     * お問合わせ完了(メール送信)
     * newInitにsession_idを暗号化してmail.blade.phpに渡しメールを送信する
     *
     * @param Request $request()
     * @return view(mail)
     */
    public function contactMailEntry(Request $request) {
        Log::debug('log_start:' .__FUNCTION__);

        /**
         * requestの値取得->複合化
         */
        // mail
        $contact_mail = Crypt::decrypt($request->input('contact_mail'));
        // 名前
        $contact_name = Crypt::decrypt($request->input('contact_name'));
        // タイトル
        $title = Crypt::decrypt($request->input('title'));
        // 問合内容
        $contact_contents = Crypt::decrypt($request->input('contact_contents'));
        
        /**
         * mail本文の設定
         */
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
        Mail::raw($mail_text, function($message){
            $message->to('lutan0081.h@gmail.com')
            ->from('kasegu0081@gmail.com')
            ->subject("【お問合せのお知らせ】");
        });

        Log::debug('log_end:' .__FUNCTION__);
        return view('contact.contactComplete', []);
    }


}