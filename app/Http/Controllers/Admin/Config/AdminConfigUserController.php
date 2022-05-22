<?php

namespace App\Http\Controllers\Admin\Config;

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
 * 表示・登録、編集、削除
 */
class AdminConfigUserController extends Controller
{   
    /**
     *  アカウント表示
     *
     * @param Request $request(フォームデータ)
     * @return
     */
    public function adminConfigUserInit(Request $request){   
        Log::debug('start:' .__FUNCTION__);

        try {            
            // アカウント情報
            $user_info = $this->getUserList($request);
            $user_list = $user_info[0];

            $common = new Common();

            // 宅地建物取引士(コンボボックス)
            $user_license_list = $common->getUserLicense($request);

            // 保証協会一覧
            $guaranty_association_list = $common->getGuarantyAssociation($request);
            
            // 法務局リスト
            $legal_place_list = $common->getLegalPlace($request);

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return view('admin.adminConfigUser' ,compact('user_license_list' ,'guaranty_association_list' ,'legal_place_list' ,'user_list'));
    }

    /**
     * アカウント情報(取得)
     *
     * @param Request $request
     * @return $ret(アカウント情報)
     */
    public function getUserList(Request $request){
        Log::debug('start:' .__FUNCTION__);

        try {

            // 値取得
            $session_id = $request->session()->get('create_user_id');

            $str = "select "
            ."create_users.create_user_id as create_user_id, "
            ."create_users.create_user_name as create_user_name, "
            ."create_users.create_user_post_number as create_user_post_number, "
            ."create_users.create_user_address as create_user_address, "
            ."create_users.create_user_tel as create_user_tel, "
            ."create_users.create_user_fax as create_user_fax, "
            ."create_users.create_user_mail as create_user_mail, "
            ."create_users.password as password, "
            ."company_licenses.company_license_id as company_license_id, "
            ."company_licenses.company_license_name as company_license_name, "
            ."company_licenses.company_license_representative as company_license_representative, "
            ."company_licenses.company_license_address as company_license_address, "
            ."company_licenses.company_license_tel as company_license_tel, "
            ."company_licenses.company_license_fax as company_license_fax, "
            ."company_licenses.company_license_number as company_license_number, "
            ."company_licenses.company_license_span as company_license_span, "
            ."company_licenses.company_nick_name as company_nick_name, "
            ."company_licenses.company_nick_address as company_nick_address, "
            ."company_licenses.user_license_id as full_time_user_license_id, "
            ."user_licenses.user_license_name as full_time_user_license_name, "
            ."user_licenses.user_license_number as full_time_user_license_number, "
            ."company_licenses.legal_place_id as legal_place_id, "
            ."legal_places.legal_place_name as legal_place_name, "
            ."legal_places.legal_place_post_number as legal_place_post_number, "
            ."legal_places.legal_place_address as legal_place_address, "
            ."legal_places.legal_place_tel as legal_place_tel, "
            ."legal_places.legal_place_fax as legal_place_fax, "
            ."company_licenses.guaranty_association_id as guaranty_association_id, "
            ."guaranty_associations.guaranty_association_name as guaranty_association_name, "
            ."guaranty_associations.guaranty_association_post_number as guaranty_association_post_number, "
            ."guaranty_associations.guaranty_association_address as guaranty_association_address, "
            ."guaranty_associations.guaranty_association_tel as guaranty_association_tel, "
            ."guaranty_associations.guaranty_association_fax as guaranty_association_fax, "
            ."guaranty_association_region.guaranty_association_id as guaranty_association_region_id, "
            ."guaranty_association_region.guaranty_association_name as guaranty_association_region_name, "
            ."guaranty_association_region.guaranty_association_post_number as guaranty_association_region_post_number, "
            ."guaranty_association_region.guaranty_association_address as guaranty_association_region_address, "
            ."guaranty_association_region.guaranty_association_tel as guaranty_association_region_tel, "
            ."guaranty_association_region.guaranty_association_fax as guaranty_association_region_fax, "
            ."create_users.entry_date, "
            ."create_users.update_user_id, "
            ."create_users.update_date "
            ."from "
            ."create_users "
            ."left join company_licenses on "
            ."company_licenses.create_user_id = create_users.create_user_id "
            ."left join user_licenses on "
            ."user_licenses.user_license_id = company_licenses.user_license_id "
            ."left join legal_places on "
            ."legal_places.legal_place_id = company_licenses.legal_place_id "
            ."left join guaranty_associations on "
            ."guaranty_associations.guaranty_association_id = company_licenses.guaranty_association_id "
            ."left join guaranty_associations as guaranty_association_region on "
            ."guaranty_association_region.guaranty_association_id = company_licenses.guaranty_association_region_id "
            ."where "
            ."create_users.create_user_id = $session_id ";

            Log::debug('$str:'.$str);
            $ret = DB::select($str);

        // 例外処理
        } catch (\Throwable $e) {

            Log::debug('error:'.$e);

        } finally {

        }

        Log::debug('end:' .__FUNCTION__);
        return $ret;
    }
} 