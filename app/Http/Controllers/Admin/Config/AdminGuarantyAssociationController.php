<?php

namespace App\Http\Controllers\Back\BackGuarantyAssociation;

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

use Common;

/**
 * 保証協会(表示・登録、編集、削除)
 */
class BackGuarantyAssociationController extends Controller
{   
    /**
     *  一覧(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backGuarantyAssociationInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {
            // 情報一覧
            $guaranty_association_info = $this->getList($request);
            
        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backGuarantyAssociation' ,$guaranty_association_info);
    }

    /**
     * 一覧(sql)
     *
     * @return $ret(法務局一覧)
     */
    private function getList(Request $request){
        Log::debug('log_start:'.__FUNCTION__);

        try{

            // フリーワード
            $free_word = $request->input('free_word');
            Log::debug('$free_word:' .$free_word);

            // session_id
            $session_id = $request->session()->get('create_user_id');
            Log::debug('$session_id:' .$session_id);

            $str = "select "
            ."* "
            ."from "
            ."guaranty_associations ";
                        
            // where句
            $where = "";

            // フリーワード
            if($free_word !== null){

                if($where == ""){

                    $where = "where ";
                }else{

                    $where = "and ";
                }

                $where = $where ."ifnull(guaranty_association_name,'') like '%$free_word%'";
            };

            // id
            if($where == ""){

                $where = $where ."where "
                ."guaranty_associations.entry_user_id = '$session_id' ";

            }else{

                $where = $where ."and "
                ."guaranty_associations.entry_user_id = '$session_id' ";
            }

            // order by句
            $order_by = "order by guaranty_association_id ";

            $str = $str .$where .$order_by;
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
     *  新規(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backGuarantyAssociationNewInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {    

            // 保証協会一覧
            $guaranty_association_list = $this->getGuarantyAssociationNewList($request);
            
        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backGuarantyAssociationEdit' ,compact('guaranty_association_list'));
    }

    /**
     * 新規(ダミー値取得)
     *
     * @return $ret(空の配列)
     */
    private function getGuarantyAssociationNewList(){
        Log::debug('log_start:'.__FUNCTION__);
        $obj = new \stdClass();
        
        $obj->guaranty_association_id  = '';
        $obj->guaranty_association_name = '';
        $obj->guaranty_association_post_number = '';
        $obj->guaranty_association_address = '';
        $obj->guaranty_association_tel = '';
        $obj->guaranty_association_fax = '';

        $ret = [];
        $ret = $obj;

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     *  編集(表示)
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function backGuarantyAssociationEditInit(Request $request){ 

        Log::debug('start:' .__FUNCTION__);

        try {
                    
            // 一覧取得
            $guaranty_association_info = $this->getEditList($request);
            $guaranty_association_list = $guaranty_association_info[0];

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('back.backGuarantyAssociationEdit' ,compact('guaranty_association_list'));

    }

    /**
     * 編集(申込情報取得:sql)
     *
     * @return void
     */
    private function getEditList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try{
            // 値設定
            $guaranty_association_id = $request->input('guaranty_association_id');

            // sql
            $str = "select * "
            ."from guaranty_associations "
            ."where guaranty_associations.guaranty_association_id = $guaranty_association_id ";
            Log::debug('sql:' .$str);
            
            $ret = DB::select($str);

        // 例外処理
        } catch (\Exception $e) {

            throw $e;

        } finally {

        }
        
        Log::debug('end:' .__FUNCTION__);
        return $ret;
    }


} 