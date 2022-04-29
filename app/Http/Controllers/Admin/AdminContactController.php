<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Common;

/**
 * お問合せ
 */
class AdminContactController extends Controller
{   
    /**
     *  表示
     *
     * @param Request $request(フォームデータ)
     * @return view('admin.adminContact',$list_contact(ページネーション));
     */
    public function adminContactInit(Request $request)
    {   
        // 進捗情報のデータ取得
        Log::debug('start:' .__FUNCTION__);

        try {

            $free_word = $request->input('free_word');
            Log::debug('$free_word:' .$free_word);

            /**
             * ページネーションで値取得
             */
            $response = [];
            $str = "select * from contacts ";
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");

            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->orderByRaw("create_date desc")->paginate(15)->onEachSide(1);
            
            // resの中に値が代入されている
            $list_contact['res'] = $res;

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminContact',$list_contact);
    }

    /**
     *  検索
     *
     * @param Request $request(フォームデータ)
     * @return view('admin.adminContact',$list_contact(ページネーション));
     */
    public function adminContactSearch(Request $request)
    {   
        // 進捗情報のデータ取得
        Log::debug('start:' .__FUNCTION__);

        try {

            /**
             * 値取得
             */
            $free_word = $request->input('free_word');
            Log::debug('$free_word:' .$free_word);

            // 選択されたvalue値が格納されている
            $radio = $request->input('radio');

            // ラジオボタンが選択されている場合の処理
            if($radio !== null){

                // no_readed=未読/readed=既読
                if($radio == 'no_readed'){

                    Log::debug('未読のラジオボタンの処理');
                    $radio = 0;

                }else{

                    Log::debug('既読のラジオボタンの処理');
                    $radio = 1;

                };

            }

            // ページネーション
            $response = [];

            $str = "select * from contacts ";

            // Where
            // フリーワード
            $where = "";

            if($free_word !== null){

                if($where == ""){
                    $where = "where ";
                }else{
                    $where = "and ";
                }

                $where = $where ."ifnull(contact_name,'') like '%$free_word%'";
                $where = $where ."or ifnull(contact_mail,'') like '%$free_word%'";

            };

            // ラジオボタン(未読=0/既読=1)
            if($radio !== null){

                if($where == ""){
                    $where = "where ";
                }else{
                    $where = "and ";
                }

                $where = $where ."contact_read_flag = $radio";

            };

            $str = $str .$where;
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");
            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->orderByRaw("create_date desc")->paginate(15)->onEachSide(1);
            // resの中に値が代入されている
            $list_contact['res'] = $res;

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {
        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminContact',$list_contact);
    }

    /**
     * 新規(表示)
     *
     * @param Request $request
     * @return void
     */
    public function adminContactNewInit(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // ダミー配列取得
        $contact_info = $this->adminContactNewList();
        $contact_list = $contact_info[0];

        Log::debug('log_end:'.__FUNCTION__);
        return view('admin.adminContactEdit' ,compact('contact_list'));
    }

    /**
     * 新規(ダミー配列取得)
     *
     * @return void
     */
    public function adminContactNewList(){
        Log::debug('log_start:'.__FUNCTION__);
        $obj = new \stdClass();
        
        $obj->contact_name = '';
        $obj->contact_mail = '';
        $obj->title = '';
        $obj->contact_contents = '';
        $obj->contact_read_flag = '';
        $obj->create_date = '';

        $ret = [];
        $ret[0] = $obj;

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 詳細表示(編集)
     *
     * @param Request $request
     * @return void
     */
    public function adminContactEditInit(Request $request){

        Log::debug('log_start:'.__FUNCTION__);

        try {
            // トランザクション
            DB::beginTransaction();

            // 値取得
            $contact_id = $request->input('contact_id');
            Log::debug('contact_id:'.$contact_id);

            // 直接URL入力された場合ログイン画面にリダイレクト
            if($contact_id == ""){
                Log::debug('URL直接入力の処理のためadminContactEditInitに遷移しました。');
                return redirect('adminContactEditInit');
            }
            
            // お問合せ取得
            $str = "select * from contacts "
            ."where contact_id = '$contact_id' ";
            $contact_info = DB::select($str);

            // 配列に格納
            $contact_list = $contact_info[0];

            // 未読の場合、既読(フラグ1)に変更
            // 初期化
            $str = "";
            // sql
            $str = "update "
            ."contacts "
            ."set "
            ."contact_read_flag = 1 "
            ."where "
            ."contact_id = $contact_id ";
            // insert
            DB::insert($str);

            // コミット
            DB::commit();

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

            DB::rollback();

        } finally {

        }

        Log::debug('log_end:'.__FUNCTION__);
        return view('admin.adminContactEdit' ,compact('contact_list'));

    }

    /**
     * 詳細(DB登録)
     *
     * @param Request $request
     * @return void
     */
    public function adminContactEditEntry(Request $request){
        try {
            Log::debug('log_start:'.__FUNCTION__);

            // true=登録完了 false=errorMessageを返す
            $response = [];

            // バリデーション(true=OK/false=NG)
            $response = $this->editValidation($request);

            if($response["status"] == false){
                Log::debug('バリデーション失敗のif文通過');
                return response()->json($response);
            }

            // insert
            $ret = $this->insertData($request);


            // js側での判定のステータス(true:OK/false:NG)
            $response["status"] = $ret['status'];

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $response['status'] = false;

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
        $rules['contact_name'] = "required|max:100";
        $rules['contact_mail'] = "required|email";
        $rules['title'] = "required|max:50";
        $rules['contact_contents'] = "required|max:200";

        /**
         * messages
         */
        $messages['contact_name.required'] = "依頼者は必須です。";
        $messages['contact_name.max'] = "依頼者の文字数が超過しています。";
        $messages['contact_mail.required'] = "E-meilの形式が不正です。";
        $messages['contact_mail.email'] = "E-meilの形式が不正です。";
        $messages['title.required'] = "タイトルは必須です。";
        $messages['title.max'] = "タイトルの文字数が超過しています。";
        $messages['contact_contents.required'] = "内容は必須です。";
        $messages['contact_contents.max'] = "内容の文字数が超過しています。";
    
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
            
            Log::debug('log_end:' .__FUNCTION__);
        }
        return $response;
    }

    /**
     * 新規登録の処理
     *
     * @param Request $request(edit.blade.phpの各項目)
     * @return ret(true:登録OK/false:登録NG、maxId(contract_id)、session_id(create_user_id))
     */
    private function insertData(Request $request){
        Log::debug('log_start:' .__FUNCTION__);

        try {            

            // $ret = true;
            $ret = [];
            $ret['status'] = true;

            // id
            $contact_info = $this->insertContact($request);

            // returnのステータスにtrueを設定
            $ret['status'] = $contact_info['status'];

        // 例外処理
        } catch (\Exception $e) {
            // ログ
            throw new \Exception(__FUNCTION__ .':' .$e);

        // updateが完了=1の為trueを代入、その他false
        } finally {

        }
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * お問合せ(insert)
     *
     * @param Request $request
     * @return void
     */
    private function insertContact(Request $request){

        Log::debug('log_start:'.__FUNCTION__);

        try {  
            
            // 値取得
            $contact_name = $request->input('contact_name');
            $contact_mail = $request->input('contact_mail');
            $title = $request->input('title');
            $contact_contents = $request->input('contact_contents');
            // 現在日
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
            ."1 "
            ."); ";

            Log::debug('sql:'.$str);
            $db_ret = DB::insert($str);

            $ret = [];
            $ret = Common::is_db_entry_status($db_ret);

        // 例外処理
        } catch (\Exception $e) {

            throw new \Exception(__FUNCTION__ .':' .$e);

        } finally {

        }

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }
}