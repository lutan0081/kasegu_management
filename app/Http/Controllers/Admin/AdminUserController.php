<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Common;

/**
 * ユーザ情報(表示)
 */
class AdminUserController extends Controller
{   
    /**
     *  ユーザ管理(検索)
     *
     * @param Request $request(フォームデータ)
     * @return view('application.application','list_user_count','list_app_count','list_picture_count','list_contacts_count','list_access_total','list_access_today');
     */
    public function adminUserSearch(Request $request)
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

            $str = "select * from users ";
            
            /**
             * where句
             */
            // フリーワード
            $where = "";

            if($free_word !== null){

                if($where == ""){
                    $where = "where ";
                }else{
                    $where = "and ";
                }
                $where = $where ."create_user_name like '%$free_word%'";
            };

            $str = $str .$where;
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");
            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->paginate(15)->onEachSide(1);
            // resの中に値が代入されている
            $list_user['res'] = $res;

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminUser',$list_user);
    }

    /**
     * 新規(表示)
     *
     * @param Request $request
     * @return void
     */
    public function adminUserNewInit(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        // 新規ダミー配列取得
        $user_info = $this->adminUserNewList();
        $user_list = $user_info[0];

        Log::debug('log_end:'.__FUNCTION__);
        return view('admin.adminUserEdit' ,compact('user_list'));
    }

    /**
     * 新規(ダミー値取得)
     *
     * @return void
     */
    public function adminUserNewList(){
        Log::debug('log_start:'.__FUNCTION__);
        $obj = new \stdClass();
        
        $obj->create_user_name = '';
        $obj->create_user_mail = '';
        $obj->post_number = '';
        $obj->address = '';
        $obj->create_user_tel = '';
        $obj->create_user_fax = '';
        $obj->password = '';
        $obj->create_user_id = '';

        $ret = [];
        $ret[0] = $obj;

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 編集(表示)
     *
     * @param Request $request(フォームデータ)
     * @return void
     */
    public function adminUserEditInit(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * 値取得
         */
        $create_user_id = $request->input('create_user_id');
        Log::debug('create_user_id:'.$create_user_id);

        // 直接URL入力された場合ログイン画面にリダイレクト
        if($create_user_id == ""){

            Log::debug('URL直接入力の処理のためadminUserNewInitに遷移しました。');
            return redirect('adminUserNewInit');
        }
        

        $str = "select * from users "
        ."where create_user_id = '$create_user_id' ";

        $user_info = DB::select($str);
        $user_list = $user_info[0];

        Log::debug('log_end:'.__FUNCTION__);
        return view('admin.adminUserEdit' ,compact('user_list'));
    }

    /**
     * ユーザ登録(editEntry)
     *
     * @param Request $request
     * @return void
     */
    public function adminUserEditEntry(Request $request){

        Log::debug('log_start:'.__FUNCTION__);

        /**
         * 値取得
         */
        // ユーザid
        $create_user_id = $request->input('create_user_id');
        
        // true=登録完了 false=errorMessageを返す
        $response = [];

        // バリデーション(true=OK false=NG)
        $response = $this->validation($request);

        // status:NG=falseをjsに返す
        if($response["status"] == false){

            Log::debug('validator_status:falseのif文通過');
            return response()->json($response);

        }

        /**
         * id=無:新規登録
         * id=有:編集登録
         */
        // 新規登録
        if($create_user_id == null){

            Log::debug('新規登録の処理');

            // responseの値設定
            // 返ってきた値を一度$retに受け取らなければjs側で判定できない
            $ret = $this->insertData($request);
            $response["status"] = $ret["status"];

        // 編集登録
        }else{

            Log::debug('編集の処理');
            
            // responseの値設定
            // 返ってきた値を一度$retに受け取らなければjs側で判定できない
            $ret = $this->updateData($request);
            $response["status"] = $ret["status"];  

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
    private function validation(Request $request){

        // id取得
        // null = 新規/値有 = 編集
        $create_user_id = $request->input('create_user_id');
        Log::debug('create_user_id:'.$create_user_id);

        // returnの出力値
        $response = [];
        // 初期値
        $response["status"] = true;

        /**
         * rules
         */
        // ユーザ情報
        $rules = [];
        $rules['create_user_name'] = "required|max:50";
        $rules['post_number'] = "required|zip";
        $rules['address'] = "required|max:200";
        $rules['create_user_tel'] = "required|jptel";
        $rules['create_user_fax'] = "nullable|jptel";
        if($create_user_id == null){
            $rules['create_user_mail'] = "required|email|maildb";
        }
        $rules['password'] = "required|max:20";

        /**
         * messages
         */
        // ユーザ情報
        $messages = [];
        // 名前
        $messages['create_user_name.required'] = "名前は必須です。";
        $messages['create_user_name.max'] = "名前の文字数が超過しています。";
        // 郵便番号
        $messages['post_number.required'] = "郵便番号は必須です。";
        $messages['post_number.zip'] = "郵便番号の形式が不正です。";
        // 住所
        $messages['address.required'] = "住所は必須です。";
        $messages['address.max'] = "住所の文字数が超過しています。";
        // Tel
        $messages['create_user_tel.required'] = "Telは必須です。";
        $messages['create_user_tel.jptel'] = "Telの形式が不正です。";
        // Fax
        $messages['create_user_fax.required'] = "Faxは必須です。";
        $messages['create_user_fax.jptel'] = "Faxの形式が不正です。";
        // Mail
        if($create_user_id == null){
            $messages['create_user_mail.required'] = "E-mailは必須です。";
            $messages['create_user_mail.email'] = "E-mailの形式が不正です。";
            $messages['create_user_mail.maildb'] = "E-mailが既に登録されています。";
        }
        // 年齢
        $messages['password.required'] = "Passwordは必須です。";
        $messages['password.max'] = "Passwordの文字数が超過しています。";

        // validationの実行
        $validator = Validator::make($request->all(), $rules, $messages);

        // 実行後、エラーがある場合処理
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
     * 登録(新規)
     *
     * @param Request $request
     * @return void
     */
    private function insertData(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        
        try { 

            DB::beginTransaction();
            
            // 初期値:status
            // $ret = true;
            $ret = [];
            $ret['status'] = true;

            $ret['status'] = $this->insertUser($request);

            DB::commit();

        // 例外処理
        } catch (\Exception $e) {

            // ロールバック
            DB::rollback();

            // sqlエラーをログに記録
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $ret['status'] = false;

        // updateが完了=1の為trueを代入、その他false
        } finally {

            if($ret['status'] == 1){

                $ret['status'] = true;

            }else{

                $ret['status'] = false;
            }

            Log::debug('log_end:'.__FUNCTION__);
            return $ret;
        }
    }

    /**
     * 新規(sql)
     *
     * @param Request $request(フォームデータ)
     * @return $ret(1=OK:0=NG)
     */
    private function insertUser(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * 値取得
         */
        // ユーザid
        $create_user_id = $request->session()->get('create_user_id');
        // ユーザ名
        $create_user_name = $request->input('create_user_name');
        // メール
        $create_user_mail = $request->input('create_user_mail');
        // 郵便番号
        $post_number = $request->input('post_number');
        // 住所
        $address = $request->input('address');
        // 電話番号
        $create_user_tel = $request->input('create_user_tel');
        // ファックス番号
        $create_user_fax = $request->input('create_user_fax');
        // パスワード
        $password = $request->input('password');

        /**
         * sql
         */
        $str = "insert "
        ."into "
        ."kasegu_management.users( "
        ."create_user_name, "
        ."post_number, "
        ."address, "
        ."create_user_tel, "
        ."create_user_fax, "
        ."create_user_mail, "
        ."password, "
        ."complete_flag, "
        ."admin_user_flag, "
        ."create_date, "
        ."update_user_id, "
        ."update_date "
        .")values( "
        ."'$create_user_name', "
        ."'$post_number', "
        ."'$address', "
        ."'$create_user_tel', "
        ."'$create_user_fax', "
        ."'$create_user_mail', "
        ."'$password', "
        ."1, "
        ."0, "
        ."now(), "
        ."$create_user_id, "
        ."now() "
        .");";
        Log::debug('sql:'.$str);

        // OK=1/NG=0
        $ret = DB::insert($str);
        
        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 削除(Entry)
     *
     * @param Request $request
     * @return void
     */
    private function updateData(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        
        try { 

            DB::beginTransaction();
            
            // 初期値:status
            // $ret = true;
            $ret = [];
            $ret['status'] = true;

            $ret['status'] = $this->updateUser($request);

            DB::commit();

        // 例外処理
        } catch (\Exception $e) {

            // ロールバック
            DB::rollback();

            // sqlエラーをログに記録
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $ret['status'] = false;

        // updateが完了=1の為trueを代入、その他false
        } finally {

            if($ret['status'] == 1){

                $ret['status'] = true;

            }else{

                $ret['status'] = false;
            }

            Log::debug('log_end:'.__FUNCTION__);
            return $ret;
        }
    }

    /**
     * 編集(sql)
     *
     * @param Request $request
     * @return void
     */
    private function updateUser(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        /**
         * 値取得
         */
        // ユーザid
        $create_user_id = $request->input('create_user_id');
        // ユーザ名
        $create_user_name = $request->input('create_user_name');
        // メール
        $create_user_mail = $request->input('create_user_mail');
        // 郵便番号
        $post_number = $request->input('post_number');
        // 住所
        $address = $request->input('address');
        // 電話番号
        $create_user_tel = $request->input('create_user_tel');
        // ファックス番号
        $create_user_fax = $request->input('create_user_fax');
        // パスワード
        $password = $request->input('password');

        /**
         * sql
         */
        $str = "update "
        ."kasegu_management.users "
        ."set "
        ."create_user_name = '$create_user_name', "
        ."post_number = '$post_number', "
        ."address = '$address', "
        ."create_user_tel = '$create_user_tel', "
        ."create_user_fax = '$create_user_fax', "
        ."create_user_mail = '$create_user_mail', "
        ."password = '$password', "
        ."complete_flag = 1, "
        ."admin_user_flag = 0, "
        ."create_date = now(), "
        ."update_user_id = 1, "
        ."update_date = now() "
        ."where "
        ."create_user_id = '$create_user_id'; ";
        
        Log::debug('sql:'.$str);

        // OK=1/NG=0
        $ret = DB::update($str);
        Log::debug('log_end:'.__FUNCTION__);

        return $ret;
    }

    /**
     * 削除(deleteEntry)
     *
     * @param Request $request
     * @return void
     */
    public function adminUserDeleteEntry(Request $request){

        Log::debug('log_start:'.__FUNCTION__);

        try {

            // true=登録完了 false=errorMessageを返す
            $response = [];

            // responseの値設定
            // 返ってきた値を一度$retに受け取らなければjs側で判定できない
            $user_info = $this->deleteUser($request); 
            $response["status"] = $user_info["status"];

        } catch (\Exception $e) {

            // sqlエラーをログに記録
            Log::debug('error:'.$e);

            // 失敗の場合falseを返す
            $response['status'] = false;

        } finally {

            if($response['status'] == 1){

                $response['status'] = true;
                
            }else{

                $response['status'] = false;
            }

            Log::debug('log_end:'.__FUNCTION__);
            return response()->json($response);
        }
    }

    /**
     * 削除(sql)
     *
     * @param Request $request
     * @return $ret(1=OK/0=NG)
     */
    public function deleteUser(Request $request){
        Log::debug('log_start:' .__FUNCTION__);
        
        // 値取得
        $create_user_id = $request->input('create_user_id');
        Log::debug('create_user_id:'.$create_user_id);

        // db接続
        $str = "delete from users "
        ."where create_user_id = '$create_user_id' ";
        Log::debug('delete_sql:'.$str);

        // OK = 1/NG = 0
        $ret = [];
        $ret['status'] = DB::delete($str);

        Log::debug('log_end:' .__FUNCTION__);
        return $ret;
    }
}