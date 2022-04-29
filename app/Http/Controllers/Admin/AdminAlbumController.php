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
class AdminAlbumController extends Controller
{   
    /**
     * 表示
     *
     * @param Request $request
     * @return view('admin.adminAlbum',compact(list(画像データ))
     */
    public function adminAlbumInit(Request $request)
    {   
        Log::debug('start:' .__FUNCTION__);

        try {

            // 写真一覧を取得(ページネーション)
            $list_img = $this->getAlbumList();

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminAlbum' ,$list_img);
    }

    /**
     * 表示(sql)
     *
     * @return void
     */
    private function getAlbumList(){
        Log::debug('start:' .__FUNCTION__);

        try {
            
            // 画像データ取得
            $str = "select "
            ."imgs.img_id "
            .",imgs.contract_id "
            .",imgs.img_type "
            .",imgs.img_path "
            .",imgs.img_memo "
            .",reaf.real_estate_name "
            .",reaf.room_name "
            .",imgs.update_user_id "
            .",imgs.update_date "
            .",users.create_user_name "
            ."from "
            ."imgs "
            ."left join users on "
            ."users.create_user_id = imgs.create_user_id "
            ."left join real_estate_agent_forms reaf "
            ."on reaf.contract_id = imgs.contract_id ";
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");

            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->orderByRaw("update_date desc")->paginate(9)->onEachSide(1);

            // resの中に値が代入されている
            $ret = [];
            $ret['res'] = $res;

        // 例外処理
        } catch (\Throwable $e) {

            throw $e;

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return $ret;
    }

    /**
     *  検索
     *
     * @param Request $request(フォームデータ)
     * @return view('application.application','list_user_count','list_app_count','list_picture_count','list_contacts_count','list_access_total','list_access_today');
     */
    public function adminAlbumSearch(Request $request)
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

            // 画像データ取得
            $str = "select "
            ."imgs.img_id "
            .",imgs.contract_id "
            .",imgs.img_type "
            .",imgs.img_path "
            .",imgs.img_memo "
            .",reaf.real_estate_name "
            .",reaf.room_name "
            .",imgs.update_user_id "
            .",imgs.update_date "
            .",users.create_user_name "
            ."from "
            ."imgs "
            ."left join users on "
            ."users.create_user_id = imgs.create_user_id "
            ."left join real_estate_agent_forms reaf "
            ."on reaf.contract_id = imgs.contract_id ";
            
            // where句
            $where = "";
            
             // フリーワード
            if($free_word !== null){

                if($where == ""){

                    $where = "where ";

                }else{

                    $where = "and ";

                }

                $where = $where ."create_user_name like '%$free_word%'";
                $where = $where ."or ifnull(real_estate_name,'') like '%$free_word%'";
            };

            $str = $str .$where;
            Log::debug('$sql:' .$str);

            // query
            $alias = DB::raw("({$str}) as alias");
            // columnの設定、表示件数
            $res = DB::table($alias)->selectRaw("*")->orderByRaw("update_date desc")->paginate(9)->onEachSide(1);
            // resの中に値が代入されている
            $list_img['res'] = $res;

        // 例外処理
        } catch (\Exception $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminAlbum',$list_img);
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