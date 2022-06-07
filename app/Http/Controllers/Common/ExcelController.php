<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;

use Common;

// Excelの場合必要
use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Shared\File;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 画像読込
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;



/**
 * Excelの帳票作成のプログラム
 */
class ExcelController extends Controller
{     
    /**
     * 申込書作成
     * 連帯保証人有無の分岐->保証会社で分岐
     *
     * @param Request $request(application_form_id)
     * @return download(Excelダウンロード)
     */
    public function backApplicationExcelEntry(Request $request)
    {   
        Log::debug('log_start:' .__FUNCTION__);

        // 申込id
        $application_id = $request->input('application_id');

        // 保証人の有無
        $guarantor_need_id = $request->input('guarantor_need_id');
        Log::debug('guarantor_need_id:' .$guarantor_need_id);
        
        // 保証会社id
        $guarantee_company_id = $request->input('guarantee_company_id');

        /**
         * 連帯保証人有無の判定
         * 1 = 有
         * 2 = 無
         */
        // 連帯保証人有
        if($guarantor_need_id == 1){

            Log::debug('連帯保証人有');

            switch ($guarantee_company_id){

                case '1':
                    Log::debug('オリジナル');
                    $template_flg = "1";
                    break;

                case '2':
                    Log::debug('日本セーフティー');
                    $template_flg = "3";
                    break;

                default:
                Log::debug('例外の処理');
            }
            
        }

        // 連帯保証人無
        if($guarantor_need_id == 2){
            Log::debug('連帯保証人無');

            switch ($guarantee_company_id){

                case '1':
                    Log::debug('オリジナル保証人無');
                    $template_flg = "2";
                    break;

                case '2':
                    Log::debug('日本セーフティー保証人無');
                    $template_flg = "3";
                    break;

                default:
                Log::debug('例外の処理');
            }    
        }

        Log::debug('template_flg:' .$template_flg);

        // ★1 フラグにより、どのテンプレートファイルを読み込むかを指定
        // オリジナル（連帯保証人有）
        if ($template_flg == '1') {

            Log::debug('オリジナル（連帯保証人有）');
            $template_file = "application_yes_guarantor_original.xlsx";
        
        // オリジナル（連帯保証人無し）
        } elseif ($template_flg == '2') {

            Log::debug('オリジナル（連帯保証人無）');
            $template_file = "application_none_guarantor_original.xlsx";

        } elseif($template_flg == '3'){

            Log::debug('日本セーフティー株式会社');
            $template_file = "nihon_safety_application.xlsx";
            
        }

        /**
         * ★2 エクセル設定データ取得
         */
        $app_list = $this->getListData($request);

        // 指定したテンプレートのExcelを開く
        $spreadsheet = IOFactory::load(public_path() .'/excel/' .$template_file);

        // シートをアクティブにする
        $sheet = $spreadsheet->getActiveSheet();

        /**
         * ★3データ設定処理の呼び出し
         */
        if ($template_flg == '1') {

            // オリジナル（連帯保証人有り）
            Log::debug('オリジナル（連帯保証人有）');
            $sheet = $this->entryNoneAndYesGuarantor($app_list, $sheet);

        } elseif ($template_flg == '2') {

            // オリジナル（連帯保証人無し）
            Log::debug('オリジナル（連帯保証人無）');
            $sheet = $this->entryNoneAndNoneGuarantor($app_list, $sheet);
            
        } elseif($template_flg == '3'){

            Log::debug('日本セーフティー');
            $sheet = $this->entryNihonSafety($app_list, $sheet);
            
        }

        // application_idでsortした画像リストを取得
        $imgs = $this->getImgData($request);
        $arrString = print_r($imgs , true);
        Log::debug('imgs:'.$arrString);

        // 要素数を取得
        $cnt = count($imgs);
        Log::debug('count($imgs):' .$cnt);

        // forで画像数をループ
        for($i = 0; $i < $cnt; $i++){

            // 画像種別idを保存
            $img_type_id = $imgs[$i]->img_type_id;
            Log::debug('img_type_id:' .$img_type_id);

            // 画像パス生成
            $img_path = $imgs[$i]->img_path;
            Log::debug('img_path:' .$img_path);

            $tmp_file_path = '/app/public/' .$img_path;
            Log::debug('tmp_file_path:' .$tmp_file_path);

            // 身分証明証(表)
            if($img_type_id == 2){

                Log::debug('身分証表の処理');

                // 画像貼り付け
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(storage_path() .$tmp_file_path); // 画像のファイルパス
                $drawing->setHeight(250); // 高さpx
                $drawing->setCoordinates('A56'); // 貼り付け場所
                $drawing->setWorksheet($sheet); // 対象シート（インスタンスを指定

            };
            
            // 身分証明証(裏)
            if($img_type_id == 3){

                Log::debug('身分証裏の処理');

                // 画像貼り付け
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(storage_path() .$tmp_file_path); // 画像のファイルパス
                $drawing->setHeight(250); // 高さpx
                $drawing->setCoordinates('A71'); // 貼り付け場所
                $drawing->setWorksheet($sheet); // 対象シート（インスタンスを指定

            };

        };

        // ★4 putput.xlsxに書き込み。Excelに書き込み
        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path() . '/excel/output.xlsx');

        /**
         * ファイル名
         */
        $real_estate_name = $app_list[0]->real_estate_name;

        $room_name = $app_list[0]->room_name;

        // ファイル名作成
        $now = date('Y/m/d');
        $replace_now = str_replace('/', '_', $now);
        $file_name = $replace_now ."_" .$real_estate_name ."_" .$room_name .".xlsx";

        Log::debug('log_end:' .__FUNCTION__);

        //一時ファイルを保存する(public/excel)第1引数/excel/output.xlsx、第2引数はファイル名、第3引数はオプション
        return response()->download(public_path() . '/excel/output.xlsx', $file_name,
        ['content-type' => 'application/vnd.ms-excel'])
        // 一時ファイルのdelete
        ->deleteFileAfterSend(true);

    }

    /**
     * 申込書作成(連帯保証人有:オリジナル)
     *
     * @param [type] $request
     * @return $sheet（書き込み後）
     */
    private function entryNoneAndYesGuarantor($app_list, $sheet){
        
        Log::debug('log_start:' .__FUNCTION__);

        // 申込id
        $application_id = $app_list[0]->application_id;
        Log::debug('application_id:' .$application_id);

        // 申込日
        $now = date('Y/m/d');
        
        // 入居予定日
        $contract_start_date = $app_list[0]->contract_start_date;

        // 申込区分id
        $application_type_id = $app_list[0]->application_type_id;

        // 申込区分名
        $application_type_name = $app_list[0]->application_type_name;

        // 物件用途
        $application_use_id = $app_list[0]->application_use_id;

        // 物件用途名
        $application_use_name = $app_list[0]->application_use_name;

        // 物件名
        $real_estate_name = $app_list[0]->real_estate_name;

        // 物件カナ
        $real_estate_ruby = $app_list[0]->real_estate_ruby;

        // 号室
        $room_name = $app_list[0]->room_name;
    
        // 郵便番号
        $post_number = $app_list[0]->post_number;

        // 住所
        $address = $app_list[0]->address;

        // 仲介業者名
        $broker_company_name = $app_list[0]->broker_company_name;

        // 担当者
        $broker_name = $app_list[0]->broker_name;

        // 電話番号
        $broker_tel = $app_list[0]->broker_tel;

        // ペット飼育数
        $pet_bleed = $app_list[0]->pet_bleed;

        // ペット種類
        $pet_kind = $app_list[0]->pet_kind;

        // 駐輪台数
        $bicycle_number = $app_list[0]->bicycle_number;

        // 駐車台数
        $car_number = $app_list[0]->car_number;

        // 敷金
        $security_fee = $app_list[0]->security_fee;

        // 保証金
        $deposit_fee = $app_list[0]->deposit_fee;

        // 礼金
        $key_fee = $app_list[0]->key_fee;

        // 解約引き
        $refund_fee = $app_list[0]->refund_fee;

        // 家賃
        $rent_fee = $app_list[0]->rent_fee;

        // 共益費
        $service_fee = $app_list[0]->service_fee;
    
        // 水道代
        $water_fee = $app_list[0]->water_fee;

        // その他
        $ohter_fee = $app_list[0]->ohter_fee;

        // 合計
        $total_fee = $app_list[0]->total_fee;

        /**
         * 契約者
         */
        // 契約者名
        $entry_contract_name = $app_list[0]->entry_contract_name;

        // 契約者フリガナ
        $entry_contract_ruby = $app_list[0]->entry_contract_ruby;

        // 性別
        $entry_contract_sex_name = $app_list[0]->entry_contract_sex_name;
    
        // 年齢
        $entry_contract_age = $app_list[0]->entry_contract_age;

        // 生年月日
        $entry_contract_birthday = $app_list[0]->entry_contract_birthday;

        // 郵便番号
        $entry_contract_post_number = $app_list[0]->entry_contract_post_number;

        // 住所
        $entry_contract_address = $app_list[0]->entry_contract_address;

        // 自宅TEL
        $entry_contract_home_tel = $app_list[0]->entry_contract_home_tel;

        // 携帯TEL
        $entry_contract_mobile_tel = $app_list[0]->entry_contract_mobile_tel;
    
        // 携帯TEL
        $entry_contract_mobile_tel = $app_list[0]->entry_contract_mobile_tel;

        // 勤務先名
        $entry_contract_business_name = $app_list[0]->entry_contract_business_name;

        // 勤務先フリガナ
        $entry_contract_business_ruby = $app_list[0]->entry_contract_business_ruby;

        // 勤務先所在地
        $entry_contract_business_address = $app_list[0]->entry_contract_business_address;

        // TEL
        $entry_contract_business_tel = $app_list[0]->entry_contract_business_tel;
        
        // 業種
        $entry_contract_business_type = $app_list[0]->entry_contract_business_type;
        
        // 職種
        $entry_contract_business_line = $app_list[0]->entry_contract_business_line;

        // 勤務年数
        $entry_contract_business_year = $app_list[0]->entry_contract_business_year;

        // 雇用形態
        $entry_contract_business_status = $app_list[0]->entry_contract_business_status;

        // 年収
        $entry_contract_income = $app_list[0]->entry_contract_income;

        // 健康保険
        $insurance_name = $app_list[0]->insurance_name;

        /**
         * 保証人
         */
        // 保証人名
        $guarantor_name = $app_list[0]->guarantor_name;

        // 保証人フリガナ
        $guarantor_ruby = $app_list[0]->guarantor_ruby;

        // 性別
        $guarantor_sex_name = $app_list[0]->guarantor_sex_name;

        // 年齢
        $guarantor_age = $app_list[0]->guarantor_age;

        // 生年月日
        $guarantor_birthday = $app_list[0]->guarantor_birthday;

        // 郵便番号
        $guarantor_post_number = $app_list[0]->guarantor_post_number;

        // 住所
        $guarantor_address = $app_list[0]->guarantor_address;

        // TEL
        $guarantor_home_tel = $app_list[0]->guarantor_home_tel;

        // 携帯
        $guarantor_mobile_tel = $app_list[0]->guarantor_mobile_tel;

        // 勤務先
        $guarantor_business_name = $app_list[0]->guarantor_business_name;

        // 勤務先フリガナ
        $guarantor_business_ruby = $app_list[0]->guarantor_business_ruby;

        // 勤務先住所
        $guarantor_business_address = $app_list[0]->guarantor_business_address;

        // 勤務先TEL
        $guarantor_business_tel = $app_list[0]->guarantor_business_tel;
        
        // 職種
        $guarantor_business_type = $app_list[0]->guarantor_business_type;
        
        // 業種
        $guarantor_business_line = $app_list[0]->guarantor_business_line;
        
        // 勤続年数
        $guarantor_business_years = $app_list[0]->guarantor_business_years;

        // 雇用形態
        $guarantor_business_status = $app_list[0]->guarantor_business_status;

        // 年数
        $guarantor_income = $app_list[0]->guarantor_income;

        // 健康保険
        $guarantor_income = $app_list[0]->guarantor_income;

        // 取扱店
        $create_user_name = $app_list[0]->create_user_name;

        // TEL
        $create_user_tel = $app_list[0]->create_user_tel;

        // FAX
        $create_user_fax = $app_list[0]->create_user_fax;

        // 住所
        $create_user_address = $app_list[0]->create_user_address;

        // 担当者
        $create_user_address = $app_list[0]->create_user_address;

        /**
         * セルに値挿入
         */
        // 申込日
        $sheet->setCellValue('I1', "申込日 : " .$now);

        // 入居予定日
        $sheet->setCellValue('H3', $contract_start_date);

        // 物件名
        $sheet->setCellValue('B6', $real_estate_name);

        // 物件名カナ
        $sheet->setCellValue('B5', $real_estate_ruby);
        
        // 号室
        $sheet->setCellValue('J5', $room_name);

        // 郵便番号
        $sheet->setCellValue('B7', '〒' .$post_number);

        // 住所
        $sheet->setCellValue('B8', $address);

        // 仲介業者
        $sheet->setCellValue('B9', $broker_company_name);

        // 担当者
        $sheet->setCellValue('J9', $broker_name);

        // TEL
        $sheet->setCellValue('G9', $broker_tel);

        /**
         * 申込区分
         * 1=新規申込
         * 2=入居中申込
         */
        if($application_type_id == 1){

            $sheet->setCellValue('B3', '■' .$application_type_name);

        }else{

            $sheet->setCellValue('B4', '■' .$application_type_name);

        };

        /**
         * 物件用途
         * 1=住居
         * 2=店舗
         * 3=事務所
         * 4=駐車場
         * 5=その他
         */
        if($application_use_id == 1){

            $sheet->setCellValue('D3', '■' .$application_use_name);

        }elseif($application_use_id == 2){

            $sheet->setCellValue('E3', '■' .$application_use_name);

        }elseif($application_use_id == 3){

            $sheet->setCellValue('F3', '■' .$application_use_name);

        }elseif($application_use_id == 4){

            $sheet->setCellValue('D4', '■' .$application_use_name);

        }elseif($application_use_id == 5){

            $sheet->setCellValue('E4', '■' .$application_use_name);

        }

        // 飼育頭数
        $sheet->setCellValue('B10',$pet_bleed);

        // 種類
        $sheet->setCellValue('E10',$pet_kind);

        // 駐輪台数
        $sheet->setCellValue('H10',$bicycle_number);

        // 駐車台数
        $sheet->setCellValue('J10',$car_number);

        // 敷金
        $sheet->setCellValue('B11',$security_fee);
        
        // 保証金
        $sheet->setCellValue('B12',$deposit_fee);

        // 礼金
        $sheet->setCellValue('B13',$key_fee);

        // 解約引き
        $sheet->setCellValue('B14',$refund_fee);

        // 家賃
        $sheet->setCellValue('G11',$rent_fee);

        // 共益費
        $sheet->setCellValue('G12',$service_fee);

        // 水道代
        $sheet->setCellValue('G13',$water_fee);

        // その他
        $sheet->setCellValue('G14',$ohter_fee);

        // 合計
        $sheet->setCellValue('G16',$total_fee);
        
        // 契約者名
        $sheet->setCellValue('C19',$entry_contract_name);

        // 契約者フリガナ
        $sheet->setCellValue('C18',$entry_contract_ruby);

        // 性別
        $sheet->setCellValue('F19',$entry_contract_sex_name);

        // 年齢
        $sheet->setCellValue('G19',$entry_contract_age);

        // 生年月日
        $sheet->setCellValue('H19',$entry_contract_birthday);
        
        // 郵便番号
        $sheet->setCellValue('C20', '〒' .$entry_contract_post_number);
    
        // 住所
        $sheet->setCellValue('C21',$entry_contract_address);

        // TEL
        $sheet->setCellValue('H20',$entry_contract_home_tel);
        
        // 携帯
        $sheet->setCellValue('H21',$entry_contract_mobile_tel);

        // 勤務先フリガナ
        $sheet->setCellValue('C22', $entry_contract_business_ruby);

        // 勤務先名称
        $sheet->setCellValue('C23', $entry_contract_business_name);

        // 勤務先所在地
        $sheet->setCellValue('G22', $entry_contract_business_address);

        // 電話番号
        $sheet->setCellValue('G23', $entry_contract_business_tel);

        // 業種
        $sheet->setCellValue('C24', $entry_contract_business_type);

        // 職種
        $sheet->setCellValue('E24', $entry_contract_business_line);
    
        // 勤務年数
        $sheet->setCellValue('G24', $entry_contract_business_year);

        // 雇用形態
        $sheet->setCellValue('I24', $entry_contract_business_status);
        
        // 年収
        $sheet->setCellValue('C25', $entry_contract_income);

        // 健康保険
        $sheet->setCellValue('G25', $insurance_name);

        /**
         * 連帯保証人
         */
        // 氏名
        $sheet->setCellValue('C33', $guarantor_name);

        // フリガナ
        $sheet->setCellValue('C32', $guarantor_ruby);

        // 性別
        $sheet->setCellValue('F33', $guarantor_sex_name);
        
        // 年齢
        $sheet->setCellValue('G33', $guarantor_age);

        // 生年月日
        $sheet->setCellValue('H33', $guarantor_birthday);

        // 郵便番号
        $sheet->setCellValue('C34', '〒' .$guarantor_post_number);
        
        // 住所
        $sheet->setCellValue('C35', $guarantor_address);

        // TEL
        $sheet->setCellValue('H34', $guarantor_home_tel);

        // 携帯TEL
        $sheet->setCellValue('H35', $guarantor_mobile_tel);

        // 勤務先名
        $sheet->setCellValue('C37', $guarantor_business_name);

        // 勤務先フリガナ
        $sheet->setCellValue('C36', $guarantor_business_ruby);
        
        // 勤務先住所
        $sheet->setCellValue('G36', $guarantor_business_address);
        
        // 勤務先TEL
        $sheet->setCellValue('G37', $guarantor_business_tel);

        // 職種
        $sheet->setCellValue('C38', $guarantor_business_type);

        // 業種
        $sheet->setCellValue('E38', $guarantor_business_line);

        // 勤続年数
        $sheet->setCellValue('G38', $guarantor_business_years);

        // 雇用形態
        $sheet->setCellValue('I38', $guarantor_business_status);
        
        // 年収
        $sheet->setCellValue('C39', $guarantor_income);

        // 雇用保険
        $sheet->setCellValue('G39', $insurance_name);

        // 取扱店
        $sheet->setCellValue('C41', $create_user_name);

        // 住所
        $sheet->setCellValue('C42', $create_user_address);

        // TEL
        $sheet->setCellValue('I41', $create_user_tel);

        // FAX
        $sheet->setCellValue('I42', $create_user_fax);

        /**
         * 同居人
         */
        // 値取得
        $housemate_list = $this->entryHousemate($application_id);
        // デバック
        $arrString = print_r($housemate_list , true);
        Log::debug('messages:'.$arrString);

        $common = new Common();

        /**
         * 同居人
         */
        // 同居人の配列数取得
        $cnt = count($housemate_list);
        Log::debug('cnt:'.$cnt);

        // 同居人名、その他初期値
        $housemate_etc_cell = 28;
        Log::debug('housemate_etc_cell:'.$housemate_etc_cell);

        // 同居人名フリガナ
        $housemate_ruby_cell = 27;
        Log::debug('housemate_ruby_cell:'.$housemate_ruby_cell);

        // 同居人名、その他初期値（2P）
        $housemate_etc_cell_2 = 45;
        Log::debug('housemate_etc_cell_2:'.$housemate_etc_cell_2);

        // 同居人名フリガナ（2P）
        $housemate_ruby_cell_2 = 44;
        Log::debug('housemate_ruby_cell_2:'.$housemate_ruby_cell_2);


        for($i = 0; $i < $cnt; $i++){

            /**
             * 値取得
             */
            // 同居人名
            $housemate_name = $housemate_list[$i]->housemate_name;

            // フリガナ
            $housemate_ruby = $housemate_list[$i]->housemate_ruby;

            // 続柄
            $sex_name = $housemate_list[$i]->sex_name;

            // TEL
            $housemate_mobile_tel = $housemate_list[$i]->housemate_mobile_tel;

            // 生年月日
            $housemate_birthday = $common->format_date_jp($housemate_list[$i]->housemate_birthday);

            /**
             * テンプレートの1ページ目には2人目しか記載出来ないため
             * 3人目からは2ページ目に記載する
             */

            if($i < 2){
                /**
                 * Excelに値設定
                 */
                // 同居人名
                $sheet->setCellValue('C' .$housemate_etc_cell, $housemate_name);

                // 続柄
                $sheet->setCellValue('F' .$housemate_etc_cell, $sex_name);

                // 生年月日
                $sheet->setCellValue('G' .$housemate_etc_cell, $housemate_birthday);

                // 携帯番号
                $sheet->setCellValue('I' .$housemate_etc_cell, $housemate_mobile_tel);

                // フリガナ
                $sheet->setCellValue('C' .$housemate_ruby_cell, $housemate_ruby);

                // セルの値を足し算
                $housemate_etc_cell = $housemate_etc_cell + 2;

                $housemate_ruby_cell = $housemate_ruby_cell + 2;

            }else{

                // 同居人名(2P)
                $sheet->setCellValue('C' .$housemate_etc_cell_2, $housemate_name);
                // 続柄
                $sheet->setCellValue('F' .$housemate_etc_cell_2, $sex_name);
                // 生年月日
                $sheet->setCellValue('G' .$housemate_etc_cell_2, $housemate_birthday);
                // 携帯番号
                $sheet->setCellValue('I' .$housemate_etc_cell_2, $housemate_mobile_tel);
                // フリガナ
                $sheet->setCellValue('C' .$housemate_ruby_cell_2, $housemate_ruby);

                // セルの値を足し算
                $housemate_etc_cell_2 = $housemate_etc_cell_2 + 2;

                $housemate_ruby_cell_2 = $housemate_ruby_cell_2 + 2;
            }

        }

        Log::debug('log_end:' .__FUNCTION__);

        return $sheet;
    }

    /**
     * 申込書作成(連帯保証人無:オリジナル)
     *
     * @param [type] $request
     * @return $sheet（書き込み後）
     */
    private function entryNoneAndNoneGuarantor($app_list, $sheet){

        Log::debug('log_start:' .__FUNCTION__);

        // 申込id
        $application_id = $app_list[0]->application_id;
        Log::debug('application_id:' .$application_id);

        // 申込日
        $now = date('Y/m/d');
        
        // 入居予定日
        $contract_start_date = $app_list[0]->contract_start_date;

        // 申込区分id
        $application_type_id = $app_list[0]->application_type_id;

        // 申込区分名
        $application_type_name = $app_list[0]->application_type_name;

        // 物件用途
        $application_use_id = $app_list[0]->application_use_id;

        // 物件用途名
        $application_use_name = $app_list[0]->application_use_name;

        // 物件名
        $real_estate_name = $app_list[0]->real_estate_name;

        // 物件カナ
        $real_estate_ruby = $app_list[0]->real_estate_ruby;

        // 号室
        $room_name = $app_list[0]->room_name;
    
        // 郵便番号
        $post_number = $app_list[0]->post_number;

        // 住所
        $address = $app_list[0]->address;

        // 仲介業者名
        $broker_company_name = $app_list[0]->broker_company_name;

        // 担当者
        $broker_name = $app_list[0]->broker_name;

        // 電話番号
        $broker_tel = $app_list[0]->broker_tel;

        // ペット飼育数
        $pet_bleed = $app_list[0]->pet_bleed;

        // ペット種類
        $pet_kind = $app_list[0]->pet_kind;

        // 駐輪台数
        $bicycle_number = $app_list[0]->bicycle_number;

        // 駐車台数
        $car_number = $app_list[0]->car_number;

        // 敷金
        $security_fee = $app_list[0]->security_fee;

        // 保証金
        $deposit_fee = $app_list[0]->deposit_fee;

        // 礼金
        $key_fee = $app_list[0]->key_fee;

        // 解約引き
        $refund_fee = $app_list[0]->refund_fee;

        // 家賃
        $rent_fee = $app_list[0]->rent_fee;

        // 共益費
        $service_fee = $app_list[0]->service_fee;
    
        // 水道代
        $water_fee = $app_list[0]->water_fee;

        // その他
        $ohter_fee = $app_list[0]->ohter_fee;

        // 合計
        $total_fee = $app_list[0]->total_fee;

        /**
         * 契約者
         */
        // 契約者名
        $entry_contract_name = $app_list[0]->entry_contract_name;

        // 契約者フリガナ
        $entry_contract_ruby = $app_list[0]->entry_contract_ruby;

        // 性別
        $entry_contract_sex_name = $app_list[0]->entry_contract_sex_name;
    
        // 年齢
        $entry_contract_age = $app_list[0]->entry_contract_age;

        // 生年月日
        $entry_contract_birthday = $app_list[0]->entry_contract_birthday;

        // 郵便番号
        $entry_contract_post_number = $app_list[0]->entry_contract_post_number;

        // 住所
        $entry_contract_address = $app_list[0]->entry_contract_address;

        // 自宅TEL
        $entry_contract_home_tel = $app_list[0]->entry_contract_home_tel;

        // 携帯TEL
        $entry_contract_mobile_tel = $app_list[0]->entry_contract_mobile_tel;
    
        // 携帯TEL
        $entry_contract_mobile_tel = $app_list[0]->entry_contract_mobile_tel;

        // 勤務先名
        $entry_contract_business_name = $app_list[0]->entry_contract_business_name;

        // 勤務先フリガナ
        $entry_contract_business_ruby = $app_list[0]->entry_contract_business_ruby;

        // 勤務先所在地
        $entry_contract_business_address = $app_list[0]->entry_contract_business_address;

        // TEL
        $entry_contract_business_tel = $app_list[0]->entry_contract_business_tel;
        
        // 業種
        $entry_contract_business_type = $app_list[0]->entry_contract_business_type;
        
        // 職種
        $entry_contract_business_line = $app_list[0]->entry_contract_business_line;

        // 勤務年数
        $entry_contract_business_year = $app_list[0]->entry_contract_business_year;

        // 雇用形態
        $entry_contract_business_status = $app_list[0]->entry_contract_business_status;

        // 年収
        $entry_contract_income = $app_list[0]->entry_contract_income;

        // 健康保険
        $insurance_name = $app_list[0]->insurance_name;

        /**
         * 緊急連絡先
         */
        // 緊急連絡先名
        $emergency_name = $app_list[0]->emergency_name;

        // フリガナ
        $emergency_ruby = $app_list[0]->emergency_ruby;

        // 性別
        $emergency_sex_name = $app_list[0]->emergency_sex_name;
        
        // 性別
        $emergency_link_name = $app_list[0]->emergency_link_name;

        // 年齢
        $emergency_age = $app_list[0]->emergency_age;

        // 生年月日
        $emergency_birthday = $app_list[0]->emergency_birthday;

        // 郵便番号
        $emergency_post_number = $app_list[0]->emergency_post_number;

        // 住所
        $emergency_address = $app_list[0]->emergency_address;

        // TEL
        $emergency_home_tel = $app_list[0]->emergency_home_tel;

        // 携帯TEL
        $emergency_mobile_tel = $app_list[0]->emergency_mobile_tel;

        // 取扱店
        $create_user_name = $app_list[0]->create_user_name;

        // TEL
        $create_user_tel = $app_list[0]->create_user_tel;

        // FAX
        $create_user_fax = $app_list[0]->create_user_fax;

        // 住所
        $create_user_address = $app_list[0]->create_user_address;

        /**
         * セルに値挿入
         */
        // 申込日
        $sheet->setCellValue('I1', "申込日 : " .$now);

        // 入居予定日
        $sheet->setCellValue('H3', $contract_start_date);

        // 物件名
        $sheet->setCellValue('B6', $real_estate_name);

        // 物件名カナ
        $sheet->setCellValue('B5', $real_estate_ruby);
        
        // 号室
        $sheet->setCellValue('J5', $room_name);

        // 郵便番号
        $sheet->setCellValue('B7', '〒' .$post_number);

        // 住所
        $sheet->setCellValue('B8', $address);

        // 仲介業者
        $sheet->setCellValue('B9', $broker_company_name);

        // 担当者
        $sheet->setCellValue('J9', $broker_name);

        // TEL
        $sheet->setCellValue('G9', $broker_tel);

        /**
         * 申込区分
         * 1=新規申込
         * 2=入居中申込
         */
        if($application_type_id == 1){

            $sheet->setCellValue('B3', '■' .$application_type_name);

        }else{

            $sheet->setCellValue('B4', '■' .$application_type_name);

        };

        /**
         * 物件用途
         * 1=住居
         * 2=店舗
         * 3=事務所
         * 4=駐車場
         * 5=その他
         */
        if($application_use_id == 1){

            $sheet->setCellValue('D3', '■' .$application_use_name);

        }elseif($application_use_id == 2){

            $sheet->setCellValue('E3', '■' .$application_use_name);

        }elseif($application_use_id == 3){

            $sheet->setCellValue('F3', '■' .$application_use_name);

        }elseif($application_use_id == 4){

            $sheet->setCellValue('D4', '■' .$application_use_name);

        }elseif($application_use_id == 5){

            $sheet->setCellValue('E4', '■' .$application_use_name);

        }

        // 飼育頭数
        $sheet->setCellValue('B10',$pet_bleed);

        // 種類
        $sheet->setCellValue('E10',$pet_kind);

        // 駐輪台数
        $sheet->setCellValue('H10',$bicycle_number);

        // 駐車台数
        $sheet->setCellValue('J10',$car_number);

        // 敷金
        $sheet->setCellValue('B11',$security_fee);
        
        // 保証金
        $sheet->setCellValue('B12',$deposit_fee);

        // 礼金
        $sheet->setCellValue('B13',$key_fee);

        // 解約引き
        $sheet->setCellValue('B14',$refund_fee);

        // 家賃
        $sheet->setCellValue('G11',$rent_fee);

        // 共益費
        $sheet->setCellValue('G12',$service_fee);

        // 水道代
        $sheet->setCellValue('G13',$water_fee);

        // その他
        $sheet->setCellValue('G14',$ohter_fee);

        // 合計
        $sheet->setCellValue('G16',$total_fee);
        
        // 契約者名
        $sheet->setCellValue('C19',$entry_contract_name);

        // 契約者フリガナ
        $sheet->setCellValue('C18',$entry_contract_ruby);

        // 性別
        $sheet->setCellValue('F19',$entry_contract_sex_name);

        // 年齢
        $sheet->setCellValue('G19',$entry_contract_age);

        // 生年月日
        $sheet->setCellValue('H19',$entry_contract_birthday);
        
        // 郵便番号
        $sheet->setCellValue('C20', '〒' .$entry_contract_post_number);
    
        // 住所
        $sheet->setCellValue('C21',$entry_contract_address);

        // TEL
        $sheet->setCellValue('H20',$entry_contract_home_tel);
        
        // 携帯
        $sheet->setCellValue('H21',$entry_contract_mobile_tel);

        // 勤務先フリガナ
        $sheet->setCellValue('C22', $entry_contract_business_ruby);

        // 勤務先名称
        $sheet->setCellValue('C23', $entry_contract_business_name);

        // 勤務先所在地
        $sheet->setCellValue('G22', $entry_contract_business_address);

        // 電話番号
        $sheet->setCellValue('G23', $entry_contract_business_tel);

        // 業種
        $sheet->setCellValue('C24', $entry_contract_business_type);

        // 職種
        $sheet->setCellValue('E24', $entry_contract_business_line);
    
        // 勤務年数
        $sheet->setCellValue('G24', $entry_contract_business_year);

        // 雇用形態
        $sheet->setCellValue('I24', $entry_contract_business_status);
        
        // 年収
        $sheet->setCellValue('C25', $entry_contract_income);

        // 健康保険
        $sheet->setCellValue('G25', $insurance_name);

        /**
         * 緊急連絡先
         */
        // 緊急連絡先名
        $sheet->setCellValue('C33', $emergency_name);

        // フリガナ
        $sheet->setCellValue('C32', $emergency_ruby);

        // 続柄
        $sheet->setCellValue('F33', $emergency_sex_name);
        
        // 性別
        $sheet->setCellValue('G33', $emergency_link_name);

        // 年齢
        $sheet->setCellValue('H33', $emergency_age);

        // 生年月日
        $sheet->setCellValue('I33', $emergency_birthday);
        
        // 郵便番号
        $sheet->setCellValue('C34', '〒' .$emergency_post_number);
        
        // 住所
        $sheet->setCellValue('C35', $emergency_address);

        // TEL
        $sheet->setCellValue('H34', $emergency_home_tel);

        //携帯番号
        $sheet->setCellValue('H35', $emergency_mobile_tel);

        // 取扱店
        $sheet->setCellValue('C37', $create_user_name);

        // 住所
        $sheet->setCellValue('C38', $create_user_address);

        // TEL
        $sheet->setCellValue('I37', $create_user_tel);

        // FAX
        $sheet->setCellValue('I38', $create_user_fax);
        
        /**
         * 同居人
         */
        // 値取得
        $housemate_list = $this->entryHousemate($application_id);

        // デバック
        $arrString = print_r($housemate_list , true);
        Log::debug('messages:'.$arrString);

        $common = new Common();

        /**
         * 同居人
         */
        // 同居人の配列数取得
        $cnt = count($housemate_list);
        Log::debug('cnt:'.$cnt);

        // 同居人名、その他初期値
        $housemate_etc_cell = 28;
        Log::debug('housemate_etc_cell:'.$housemate_etc_cell);

        // 同居人名フリガナ
        $housemate_ruby_cell = 27;
        Log::debug('housemate_ruby_cell:'.$housemate_ruby_cell);

        // 同居人名、その他初期値（2P）
        $housemate_etc_cell_2 = 45;
        Log::debug('housemate_etc_cell_2:'.$housemate_etc_cell_2);

        // 同居人名フリガナ（2P）
        $housemate_ruby_cell_2 = 44;
        Log::debug('housemate_ruby_cell_2:'.$housemate_ruby_cell_2);


        for($i = 0; $i < $cnt; $i++){

            /**
             * 値取得
             */
            // 同居人名
            $housemate_name = $housemate_list[$i]->housemate_name;

            // フリガナ
            $housemate_ruby = $housemate_list[$i]->housemate_ruby;

            // 続柄
            $sex_name = $housemate_list[$i]->sex_name;

            // TEL
            $housemate_mobile_tel = $housemate_list[$i]->housemate_mobile_tel;

            // 生年月日
            $housemate_birthday = $common->format_date_jp($housemate_list[$i]->housemate_birthday);

            /**
             * テンプレートの1ページ目には2人目しか記載出来ないため
             * 3人目からは2ページ目に記載する
             */

            if($i < 2){
                /**
                 * Excelに値設定
                 */
                // 同居人名
                $sheet->setCellValue('C' .$housemate_etc_cell, $housemate_name);

                // 続柄
                $sheet->setCellValue('F' .$housemate_etc_cell, $sex_name);

                // 生年月日
                $sheet->setCellValue('G' .$housemate_etc_cell, $housemate_birthday);

                // 携帯番号
                $sheet->setCellValue('I' .$housemate_etc_cell, $housemate_mobile_tel);

                // フリガナ
                $sheet->setCellValue('C' .$housemate_ruby_cell, $housemate_ruby);

                // セルの値を足し算
                $housemate_etc_cell = $housemate_etc_cell + 2;

                $housemate_ruby_cell = $housemate_ruby_cell + 2;

            }else{

                // 同居人名(2P)
                $sheet->setCellValue('C' .$housemate_etc_cell_2, $housemate_name);
                // 続柄
                $sheet->setCellValue('F' .$housemate_etc_cell_2, $sex_name);
                // 生年月日
                $sheet->setCellValue('G' .$housemate_etc_cell_2, $housemate_birthday);
                // 携帯番号
                $sheet->setCellValue('I' .$housemate_etc_cell_2, $housemate_mobile_tel);
                // フリガナ
                $sheet->setCellValue('C' .$housemate_ruby_cell_2, $housemate_ruby);

                // セルの値を足し算
                $housemate_etc_cell_2 = $housemate_etc_cell_2 + 2;

                $housemate_ruby_cell_2 = $housemate_ruby_cell_2 + 2;
            }

        }

        Log::debug('log_end:' .__FUNCTION__);

        return $sheet;
    }

    /**
     * 申込書作成(日本セーフティー)
     *
     * @param [type] $request
     * @return $sheet（書き込み後）
     */
    private function entryNihonSafety($app_list, $sheet){
        
        Log::debug('log_start:' .__FUNCTION__);

        // 申込id
        $application_id = $app_list[0]->application_id;
        Log::debug('application_id:' .$application_id);

        // 申込日
        $now = date('Y/m/d');
        
        // 入居予定日
        $contract_start_date = $app_list[0]->contract_start_date;

        // 申込区分id
        $application_type_id = $app_list[0]->application_type_id;

        // 申込区分名
        $application_type_name = $app_list[0]->application_type_name;

        // 物件用途
        $application_use_id = $app_list[0]->application_use_id;

        // 物件用途名
        $application_use_name = $app_list[0]->application_use_name;

        // 物件名
        $real_estate_name = $app_list[0]->real_estate_name;

        // 物件カナ
        $real_estate_ruby = $app_list[0]->real_estate_ruby;

        // 号室
        $room_name = $app_list[0]->room_name;
    
        // 郵便番号
        $post_number = $app_list[0]->post_number;

        // 住所
        $address = $app_list[0]->address;

        // 仲介業者名
        $broker_company_name = $app_list[0]->broker_company_name;

        // 担当者
        $broker_name = $app_list[0]->broker_name;

        // 電話番号
        $broker_tel = $app_list[0]->broker_tel;

        // ペット飼育数
        $pet_bleed = $app_list[0]->pet_bleed;

        // ペット種類
        $pet_kind = $app_list[0]->pet_kind;

        // 駐輪台数
        $bicycle_number = $app_list[0]->bicycle_number;

        // 駐車台数
        $car_number = $app_list[0]->car_number;

        // 敷金
        $security_fee = $app_list[0]->security_fee;

        // 保証金
        $deposit_fee = $app_list[0]->deposit_fee;

        // 礼金
        $key_fee = $app_list[0]->key_fee;

        // 解約引き
        $refund_fee = $app_list[0]->refund_fee;

        // 家賃
        $rent_fee = $app_list[0]->rent_fee;

        // 共益費
        $service_fee = $app_list[0]->service_fee;
    
        // 水道代
        $water_fee = $app_list[0]->water_fee;

        // その他
        $ohter_fee = $app_list[0]->ohter_fee;

        // 合計
        $total_fee = $app_list[0]->total_fee;

        /**
         * 契約者
         */
        // 契約者名
        $entry_contract_name = $app_list[0]->entry_contract_name;

        // 契約者フリガナ
        $entry_contract_ruby = $app_list[0]->entry_contract_ruby;

        // 性別
        $entry_contract_sex_name = $app_list[0]->entry_contract_sex_name;
    
        // 年齢
        $entry_contract_age = $app_list[0]->entry_contract_age;

        // 生年月日
        $entry_contract_birthday = $app_list[0]->entry_contract_birthday;

        // 郵便番号
        $entry_contract_post_number = $app_list[0]->entry_contract_post_number;

        // 住所
        $entry_contract_address = $app_list[0]->entry_contract_address;

        // 自宅TEL
        $entry_contract_home_tel = $app_list[0]->entry_contract_home_tel;

        // 携帯TEL
        $entry_contract_mobile_tel = $app_list[0]->entry_contract_mobile_tel;
    
        // 携帯TEL
        $entry_contract_mobile_tel = $app_list[0]->entry_contract_mobile_tel;

        // 勤務先名
        $entry_contract_business_name = $app_list[0]->entry_contract_business_name;

        // 勤務先フリガナ
        $entry_contract_business_ruby = $app_list[0]->entry_contract_business_ruby;

        // 勤務先所在地
        $entry_contract_business_address = $app_list[0]->entry_contract_business_address;

        // TEL
        $entry_contract_business_tel = $app_list[0]->entry_contract_business_tel;
        
        // 業種
        $entry_contract_business_type = $app_list[0]->entry_contract_business_type;
        
        // 職種
        $entry_contract_business_line = $app_list[0]->entry_contract_business_line;

        // 勤務年数
        $entry_contract_business_year = $app_list[0]->entry_contract_business_year;

        // 雇用形態
        $entry_contract_business_status = $app_list[0]->entry_contract_business_status;

        // 年収
        $entry_contract_income = $app_list[0]->entry_contract_income;

        // 健康保険
        $insurance_name = $app_list[0]->insurance_name;

        /**
         * 保証人
         */
        // 保証人名
        $guarantor_name = $app_list[0]->guarantor_name;

        // 保証人フリガナ
        $guarantor_ruby = $app_list[0]->guarantor_ruby;

        // 性別
        $guarantor_sex_name = $app_list[0]->guarantor_sex_name;

        // 年齢
        $guarantor_age = $app_list[0]->guarantor_age;

        // 生年月日
        $guarantor_birthday = $app_list[0]->guarantor_birthday;

        // 郵便番号
        $guarantor_post_number = $app_list[0]->guarantor_post_number;

        // 住所
        $guarantor_address = $app_list[0]->guarantor_address;

        // TEL
        $guarantor_home_tel = $app_list[0]->guarantor_home_tel;

        // 携帯
        $guarantor_mobile_tel = $app_list[0]->guarantor_mobile_tel;

        // 勤務先
        $guarantor_business_name = $app_list[0]->guarantor_business_name;

        // 勤務先フリガナ
        $guarantor_business_ruby = $app_list[0]->guarantor_business_ruby;

        // 勤務先住所
        $guarantor_business_address = $app_list[0]->guarantor_business_address;

        // 勤務先TEL
        $guarantor_business_tel = $app_list[0]->guarantor_business_tel;
        
        // 職種
        $guarantor_business_type = $app_list[0]->guarantor_business_type;
        
        // 業種
        $guarantor_business_line = $app_list[0]->guarantor_business_line;
        
        // 勤続年数
        $guarantor_business_years = $app_list[0]->guarantor_business_years;

        // 雇用形態
        $guarantor_business_status = $app_list[0]->guarantor_business_status;

        // 年数
        $guarantor_income = $app_list[0]->guarantor_income;

        // 健康保険
        $guarantor_income = $app_list[0]->guarantor_income;

        // 取扱店
        $create_user_name = $app_list[0]->create_user_name;

        // TEL
        $create_user_tel = $app_list[0]->create_user_tel;

        // FAX
        $create_user_fax = $app_list[0]->create_user_fax;

        // 住所
        $create_user_address = $app_list[0]->create_user_address;

        // 担当者
        $create_user_address = $app_list[0]->create_user_address;

        /**
         * セルに値挿入
         */
        // 申込日
        $sheet->setCellValue('I1', "申込日 : " .$now);

        // 入居予定日
        $sheet->setCellValue('H3', $contract_start_date);

        // 物件名
        $sheet->setCellValue('B6', $real_estate_name);

        // 物件名カナ
        $sheet->setCellValue('B5', $real_estate_ruby);
        
        // 号室
        $sheet->setCellValue('J5', $room_name);

        // 郵便番号
        $sheet->setCellValue('B7', '〒' .$post_number);

        // 住所
        $sheet->setCellValue('B8', $address);

        // 仲介業者
        $sheet->setCellValue('B9', $broker_company_name);

        // 担当者
        $sheet->setCellValue('J9', $broker_name);

        // TEL
        $sheet->setCellValue('G9', $broker_tel);

        /**
         * 申込区分
         * 1=新規申込
         * 2=入居中申込
         */
        if($application_type_id == 1){

            $sheet->setCellValue('B3', '■' .$application_type_name);

        }else{

            $sheet->setCellValue('B4', '■' .$application_type_name);

        };

        /**
         * 物件用途
         * 1=住居
         * 2=店舗
         * 3=事務所
         * 4=駐車場
         * 5=その他
         */
        if($application_use_id == 1){

            $sheet->setCellValue('D3', '■' .$application_use_name);

        }elseif($application_use_id == 2){

            $sheet->setCellValue('E3', '■' .$application_use_name);

        }elseif($application_use_id == 3){

            $sheet->setCellValue('F3', '■' .$application_use_name);

        }elseif($application_use_id == 4){

            $sheet->setCellValue('D4', '■' .$application_use_name);

        }elseif($application_use_id == 5){

            $sheet->setCellValue('E4', '■' .$application_use_name);

        }

        // 飼育頭数
        $sheet->setCellValue('B10',$pet_bleed);

        // 種類
        $sheet->setCellValue('E10',$pet_kind);

        // 駐輪台数
        $sheet->setCellValue('H10',$bicycle_number);

        // 駐車台数
        $sheet->setCellValue('J10',$car_number);

        // 敷金
        $sheet->setCellValue('B11',$security_fee);
        
        // 保証金
        $sheet->setCellValue('B12',$deposit_fee);

        // 礼金
        $sheet->setCellValue('B13',$key_fee);

        // 解約引き
        $sheet->setCellValue('B14',$refund_fee);

        // 家賃
        $sheet->setCellValue('G11',$rent_fee);

        // 共益費
        $sheet->setCellValue('G12',$service_fee);

        // 水道代
        $sheet->setCellValue('G13',$water_fee);

        // その他
        $sheet->setCellValue('G14',$ohter_fee);

        // 合計
        $sheet->setCellValue('G16',$total_fee);
        
        // 契約者名
        $sheet->setCellValue('C19',$entry_contract_name);

        // 契約者フリガナ
        $sheet->setCellValue('C18',$entry_contract_ruby);

        // 性別
        $sheet->setCellValue('F19',$entry_contract_sex_name);

        // 年齢
        $sheet->setCellValue('G19',$entry_contract_age);

        // 生年月日
        $sheet->setCellValue('H19',$entry_contract_birthday);
        
        // 郵便番号
        $sheet->setCellValue('C20', '〒' .$entry_contract_post_number);
    
        // 住所
        $sheet->setCellValue('C21',$entry_contract_address);

        // TEL
        $sheet->setCellValue('H20',$entry_contract_home_tel);
        
        // 携帯
        $sheet->setCellValue('H21',$entry_contract_mobile_tel);

        // 勤務先フリガナ
        $sheet->setCellValue('C22', $entry_contract_business_ruby);

        // 勤務先名称
        $sheet->setCellValue('C23', $entry_contract_business_name);

        // 勤務先所在地
        $sheet->setCellValue('G22', $entry_contract_business_address);

        // 電話番号
        $sheet->setCellValue('G23', $entry_contract_business_tel);

        // 業種
        $sheet->setCellValue('C24', $entry_contract_business_type);

        // 職種
        $sheet->setCellValue('E24', $entry_contract_business_line);
    
        // 勤務年数
        $sheet->setCellValue('G24', $entry_contract_business_year);

        // 雇用形態
        $sheet->setCellValue('I24', $entry_contract_business_status);
        
        // 年収
        $sheet->setCellValue('C25', $entry_contract_income);

        // 健康保険
        $sheet->setCellValue('G25', $insurance_name);

        /**
         * 連帯保証人
         */
        // 氏名
        $sheet->setCellValue('C33', $guarantor_name);

        // フリガナ
        $sheet->setCellValue('C32', $guarantor_ruby);

        // 性別
        $sheet->setCellValue('F33', $guarantor_sex_name);
        
        // 年齢
        $sheet->setCellValue('G33', $guarantor_age);

        // 生年月日
        $sheet->setCellValue('H33', $guarantor_birthday);

        // 郵便番号
        $sheet->setCellValue('C34', '〒' .$guarantor_post_number);
        
        // 住所
        $sheet->setCellValue('C35', $guarantor_address);

        // TEL
        $sheet->setCellValue('H34', $guarantor_home_tel);

        // 携帯TEL
        $sheet->setCellValue('H35', $guarantor_mobile_tel);

        // 勤務先名
        $sheet->setCellValue('C37', $guarantor_business_name);

        // 勤務先フリガナ
        $sheet->setCellValue('C36', $guarantor_business_ruby);
        
        // 勤務先住所
        $sheet->setCellValue('G36', $guarantor_business_address);
        
        // 勤務先TEL
        $sheet->setCellValue('G37', $guarantor_business_tel);

        // 職種
        $sheet->setCellValue('C38', $guarantor_business_type);

        // 業種
        $sheet->setCellValue('E38', $guarantor_business_line);

        // 勤続年数
        $sheet->setCellValue('G38', $guarantor_business_years);

        // 雇用形態
        $sheet->setCellValue('I38', $guarantor_business_status);
        
        // 年収
        $sheet->setCellValue('C39', $guarantor_income);

        // 雇用保険
        $sheet->setCellValue('G39', $insurance_name);

        // 取扱店
        $sheet->setCellValue('C41', $create_user_name);

        // 住所
        $sheet->setCellValue('C42', $create_user_address);

        // TEL
        $sheet->setCellValue('I41', $create_user_tel);

        // FAX
        $sheet->setCellValue('I42', $create_user_fax);

        /**
         * 同居人
         */
        // 値取得
        $housemate_list = $this->entryHousemate($application_id);
        // デバック
        $arrString = print_r($housemate_list , true);
        Log::debug('messages:'.$arrString);

        $common = new Common();

        /**
         * 同居人
         */
        // 同居人の配列数取得
        $cnt = count($housemate_list);
        Log::debug('cnt:'.$cnt);

        // 同居人名、その他初期値
        $housemate_etc_cell = 28;
        Log::debug('housemate_etc_cell:'.$housemate_etc_cell);

        // 同居人名フリガナ
        $housemate_ruby_cell = 27;
        Log::debug('housemate_ruby_cell:'.$housemate_ruby_cell);

        // 同居人名、その他初期値（2P）
        $housemate_etc_cell_2 = 45;
        Log::debug('housemate_etc_cell_2:'.$housemate_etc_cell_2);

        // 同居人名フリガナ（2P）
        $housemate_ruby_cell_2 = 44;
        Log::debug('housemate_ruby_cell_2:'.$housemate_ruby_cell_2);


        for($i = 0; $i < $cnt; $i++){

            /**
             * 値取得
             */
            // 同居人名
            $housemate_name = $housemate_list[$i]->housemate_name;

            // フリガナ
            $housemate_ruby = $housemate_list[$i]->housemate_ruby;

            // 続柄
            $sex_name = $housemate_list[$i]->sex_name;

            // TEL
            $housemate_mobile_tel = $housemate_list[$i]->housemate_mobile_tel;

            // 生年月日
            $housemate_birthday = $common->format_date_jp($housemate_list[$i]->housemate_birthday);

            /**
             * テンプレートの1ページ目には2人目しか記載出来ないため
             * 3人目からは2ページ目に記載する
             */

            if($i < 2){
                /**
                 * Excelに値設定
                 */
                // 同居人名
                $sheet->setCellValue('C' .$housemate_etc_cell, $housemate_name);

                // 続柄
                $sheet->setCellValue('F' .$housemate_etc_cell, $sex_name);

                // 生年月日
                $sheet->setCellValue('G' .$housemate_etc_cell, $housemate_birthday);

                // 携帯番号
                $sheet->setCellValue('I' .$housemate_etc_cell, $housemate_mobile_tel);

                // フリガナ
                $sheet->setCellValue('C' .$housemate_ruby_cell, $housemate_ruby);

                // セルの値を足し算
                $housemate_etc_cell = $housemate_etc_cell + 2;

                $housemate_ruby_cell = $housemate_ruby_cell + 2;

            }else{

                // 同居人名(2P)
                $sheet->setCellValue('C' .$housemate_etc_cell_2, $housemate_name);
                // 続柄
                $sheet->setCellValue('F' .$housemate_etc_cell_2, $sex_name);
                // 生年月日
                $sheet->setCellValue('G' .$housemate_etc_cell_2, $housemate_birthday);
                // 携帯番号
                $sheet->setCellValue('I' .$housemate_etc_cell_2, $housemate_mobile_tel);
                // フリガナ
                $sheet->setCellValue('C' .$housemate_ruby_cell_2, $housemate_ruby);

                // セルの値を足し算
                $housemate_etc_cell_2 = $housemate_etc_cell_2 + 2;

                $housemate_ruby_cell_2 = $housemate_ruby_cell_2 + 2;
            }

        }

        Log::debug('log_end:' .__FUNCTION__);

        return $sheet;
    }
    

    /**
     * データ取得(sql)
     *
     * @param $application_form_id(不動産業者id)
     * @return $data(顧客一覧)
     */
    private function getListData($request){

        Log::debug('log_start:' .__FUNCTION__);

        // 値設定
        $application_id = $request->input('application_id');

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
        ."guarantor_insurances.insurance_name, "
        ."applications.application_type_id as application_type_id, "
        ."application_types.application_type_name as application_type_name, "
        ."applications.application_use_id as application_use_id, "
        ."application_uses.application_use_name as application_use_name, "
        ."create_users.create_user_name as create_user_name, "
        ."create_users.create_user_tel as create_user_tel, "
        ."create_users.create_user_fax as create_user_fax, "
        ."create_users.create_user_address as create_user_address "
        ."from "
        ."applications "
        ."left join entry_contracts on "
        ."entry_contracts.application_id = applications.application_id "
        ."left join sexes as application_sex on "
        ."application_sex.sex_id = entry_contracts.entry_contract_sex_id "
        ."left join insurances as entry_contract_insurances on "
        ."entry_contract_insurances.insurance_id = entry_contracts.entry_contract_insurance_type_id "
        ."left join housemates on "
        ."housemates.application_id = applications.application_id "
        ."left join sexes as housemate_sex on "
        ."housemate_sex.sex_id = housemates.housemate_sex_id "
        ."left join links as housemate_link on "
        ."housemates.housemate_link_id = housemate_link.link_id "
        ."left join emergencies on "
        ."emergencies.application_id = applications.application_id "
        ."left join sexes as emergency_sex on "
        ."emergency_sex.sex_id = emergencies.emergency_sex_id "
        ."left join links as emergency_link on "
        ."emergency_link.link_id = emergencies.emergency_link_id "
        ."left join guarantors on "
        ."guarantors.application_id = applications.application_id "
        ."left join sexes as guarantor_sex on "
        ."guarantor_sex.sex_id = guarantors.guarantor_sex_id "
        ."left join links as guarantor_link on "
        ."guarantor_link.link_id = guarantors.guarantor_link_id "
        ."left join insurances as guarantor_insurances on "
        ."guarantor_insurances.insurance_id = guarantors.guarantor_insurance_type_id "
        ."left join application_types on "
        ."application_types.application_type_id = applications.application_type_id "
        ."left join application_uses on "
        ."application_uses.application_use_id = applications.application_use_id "
        ."left join contract_progress on "
        ."contract_progress.contract_progress_id = applications.contract_progress_id "
        ."left join needs on "
        ."needs.need_id = applications.pet_bleed "
        ."left join create_users on "
        ."create_users.create_user_id = applications.create_user_id "
        ."where applications.application_id = $application_id ";
        Log::debug('sql:' .$str);
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 画像データ取得
     *
     * @param [type] $request
     * @return void
     */
    private function getImgData($request){

        Log::debug('log_start:' .__FUNCTION__);

        $application_id = $request->input('application_id');

        $str = "select "
        ."* "
        ."from "
        ."imgs "
        ."where "
        ."( "
        ."img_type_id = 2 "
        ."or "
        ."img_type_id = 3 "
        .") "
        ."and "
        ."( "
        ."application_id = $application_id "
        .") ";
        
        Log::debug('str:' .$str);
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;

    }

    /**
     * 同居人取得(sql)
     *
     * @param [type] $application_id
     * @return void
     */
    private function entryHousemate($application_id){
        Log::debug('log_start:' .__FUNCTION__);

        $str = "select * from housemates "
        ."left join links on "
        ."housemates.housemate_link_id = links.link_id "
        ."left join sexes on "
        ."sexes.sex_id = housemates.housemate_sex_id "
        ."where application_id = $application_id; ";
        Log::debug('sql:' .$str);
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 契約書作成
     *
     * @param Request $request
     * @return download(Excelダウンロード)
     */
    public function backContractExcelEntry(Request $request){
        
        /**
         * 契約詳細取得
         */
        $contract_detail_list = $this->getContractDetailList($request);

        // 配列デバック
        $arrString = print_r($contract_detail_list , true);
        Log::debug('contract_detail_list:'.$arrString);

        /**
         * 同居人取得
         */
        $housemate_detail_list = $this->getHousemateDetailList($request);
        
        // 配列デバック
        $arrString = print_r($housemate_detail_list , true);
        Log::debug('housemate_detail_list:'.$arrString);

        // 同居人の配列数取得
        $cnt = count($housemate_detail_list);
        Log::debug('cnt:'.$cnt);

        $common = new Common();

        $cnt = count($housemate_detail_list);
        Log::debug('cnt:'.$cnt);
        
        /**
         * 取得した値を代入
         */
        // 名称
        $company_licenses = $contract_detail_list[0]->company_licenses;

        // 代表者
        $company_license_representative = $contract_detail_list[0]->company_license_representative;
        
        // 住所
        $company_license_address = $contract_detail_list[0]->company_license_address;
        
        // 免許番号
        $company_license_number = $contract_detail_list[0]->company_license_number;

        // 免許年月日
        $company_license_span = $contract_detail_list[0]->company_license_span;

        // 取扱店名称
        $company_nick_name = $contract_detail_list[0]->company_nick_name;

        // 取扱店所在地
        $company_nick_address = $contract_detail_list[0]->company_nick_address;

        // 担当者
        $manager_name = $contract_detail_list[0]->manager_name;

        // 宅地建物取引士
        $user_license_name = $contract_detail_list[0]->user_license_name;

        // 登録番号
        $user_license_number = $contract_detail_list[0]->user_license_number;

        // tel
        $company_license_tel = $contract_detail_list[0]->company_license_tel;

        // fax
        $company_license_fax = $contract_detail_list[0]->company_license_fax;

        // 管理の委託先(共有：名称)
        $m_share_name = $contract_detail_list[0]->m_share_name;

        // 管理の委託先(共有：住所)
        $m_share_address = $contract_detail_list[0]->m_share_address;

        // 管理の委託先(共有：電話番号)
        $m_share_tel = $contract_detail_list[0]->m_share_tel;

        // 管理の委託先(専有：名称)
        $m_own_name = $contract_detail_list[0]->m_own_name;

        // 管理の委託先(専有：住所)
        $m_own_address = $contract_detail_list[0]->m_own_address;

        // 管理の委託先(専有：電話番号)
        $m_own_tel = $contract_detail_list[0]->m_own_tel;

        // 法務局名称
        $legal_place_name = $contract_detail_list[0]->legal_place_name;

        // 法務局住所
        $legal_place_address = $contract_detail_list[0]->legal_place_address;
        
        // 保証協会名称
        $guaranty_association_name = $contract_detail_list[0]->guaranty_association_name;   

        // 保証協会住所
        $guaranty_association_address = $contract_detail_list[0]->guaranty_association_address;
        
        // 保証協会所属地方名称
        $guaranty_association_region_name = $contract_detail_list[0]->guaranty_association_region_name;   

        // 保証協会所属地方住所
        $guaranty_association_region_address = $contract_detail_list[0]->guaranty_association_region_address;

        // 物件名
        $real_estate_name = $contract_detail_list[0]->real_estate_name;       
    
        // 号室
        $room_name = $contract_detail_list[0]->room_name; 

        // 契約面積
        $room_size = $contract_detail_list[0]->room_size; 

        // 住所
        $real_estate_address = $contract_detail_list[0]->real_estate_address; 

        // 構造
        $real_estate_structure_name = $contract_detail_list[0]->real_estate_structure_name; 

        // 地上階数
        $real_estate_floor = $contract_detail_list[0]->real_estate_floor;

        // 間取数
        $room_layout_count = $contract_detail_list[0]->room_layout_count;

        // 間取タイプ
        $room_layout_name = $contract_detail_list[0]->room_layout_name;

        // 間取作成
        $room_layout_type = $room_layout_count .$room_layout_name;
        Log::debug('room_layout_type:'.$room_layout_type);

        // 築年数
        $real_estate_age = $common->format_date_jp($contract_detail_list[0]->real_estate_age);

        // 取引形態
        $trade_type_name = $contract_detail_list[0]->trade_type_name;

        // 貸主
        $owner_name = $contract_detail_list[0]->owner_name;

        // 貸主住所
        $owner_address = $contract_detail_list[0]->owner_address;

        // 石綿使用調査記録
        $report_asbestos_need_name = $contract_detail_list[0]->report_asbestos_need_name;

        // 耐震診断
        $report_earthquake_need_name = $contract_detail_list[0]->report_earthquake_need_name;

        // 造成宅地防災区域内か否か
        $land_disaster_prevention_area_name = $contract_detail_list[0]->land_disaster_prevention_area_name;
        
        // 津波災害警戒区域内か否か
        $tsunami_disaster_alert_area_name = $contract_detail_list[0]->tsunami_disaster_alert_area_name;

        // 土砂災害区域内か否か
        $sediment_disaster_area_name = $contract_detail_list[0]->sediment_disaster_area_name;

        // 登記事項に記載された事項(所有権)
        $regi_name = $contract_detail_list[0]->regi_name;

        // 所有権に関する権利の事項
        $regi_right_need_name = $contract_detail_list[0]->regi_right_need_name;
        
        // ハザードマップ
        $hazard_map_need_name = $contract_detail_list[0]->hazard_map_need_name;

        // 洪水
        $warning_flood_need_name = $contract_detail_list[0]->warning_flood_need_name;
        
        // 高潮
        $warning_storm_surge_need_name = $contract_detail_list[0]->warning_storm_surge_need_name;
        
        // 雨水出水
        $warning_rain_water_need_name = $contract_detail_list[0]->warning_rain_water_need_name;

        // 抵当権設定
        $regi_mortgage_need_name = $contract_detail_list[0]->regi_mortgage_need_name;
        Log::debug('regi_mortgage_need_name:'.$regi_mortgage_need_name);

        // 貸主が所有者と違う場合
        $regi_difference_owner = $contract_detail_list[0]->regi_difference_owner;
        
        $completion_date = $common->format_date_jp($contract_detail_list[0]->completion_date);
        
        // 未完成物件の時
        if($completion_date == '1970年01月01日'){

            $completion_date = '';

        }else{

            $completion_date = $common->format_date_jp($contract_detail_list[0]->completion_date);

        }
        
        // 敷金、保証金
        $security_fee = $contract_detail_list[0]->security_fee;

        // 礼金・敷金引き
        $key_fee = $contract_detail_list[0]->key_fee;

        // 家賃
        $rent_fee = $contract_detail_list[0]->rent_fee;

        // 共益費
        $service_fee = $contract_detail_list[0]->service_fee;

        // 水道代
        $water_fee = $contract_detail_list[0]->water_fee;
        
        // その他
        $ohter_fee = $contract_detail_list[0]->ohter_fee;

        // 駐輪代
        $bicycle_fee = $contract_detail_list[0]->bicycle_fee;

        // 住宅保険料
        $fire_insurance_span = $contract_detail_list[0]->fire_insurance_span;

        // 住宅保険料
        $fire_insurance_fee = $contract_detail_list[0]->fire_insurance_fee;

        // 保証会社初回保証料
        $guarantee_fee = $contract_detail_list[0]->guarantee_fee;
        
        // 保証会社更新期間
        $guarantee_update_span_name = $contract_detail_list[0]->guarantee_update_span_name;

        // 保証会社更新料
        $guarantee_update_fee = $contract_detail_list[0]->guarantee_update_fee;
        
        // 安心サポート
        $support_fee = $contract_detail_list[0]->support_fee;

        // 防虫抗菌
        $disinfect_fee = $contract_detail_list[0]->disinfect_fee;

        // その他項目①
        $other_name1 = $contract_detail_list[0]->other_name1;

        // その他費用①
        $other_fee1 = $contract_detail_list[0]->other_fee1;

        // その他項目②
        $other_name2 = $contract_detail_list[0]->other_name2;

        // その他費用②
        $other_fee2 = $contract_detail_list[0]->other_fee2;

        // 仲介手数料
        $broker_fee = $contract_detail_list[0]->broker_fee;

        // 仲介手数料(駐車場)
        $car_broker_fee = $contract_detail_list[0]->car_broker_fee;
    
        // 駐車場代
        $car_fee = $contract_detail_list[0]->car_fee;

        // 駐車場保証金
        $car_deposit_fee = $contract_detail_list[0]->car_deposit_fee;

        // 本日の預金
        $today_account_fee = $contract_detail_list[0]->today_account_fee;

        // 決済予定日
        $payment_date = $common->format_date_jp($contract_detail_list[0]->payment_date);

        // 支払金預金の保全措置
        $keep_account_fee_need_name = $contract_detail_list[0]->keep_account_fee_need_name;

        // 金銭賃借の斡旋
        $introduction_fee_need_name = $contract_detail_list[0]->introduction_fee_need_name;

        // 飲用水
        $water_name = $contract_detail_list[0]->water_name;

        // 飲用水(備考)
        $water_type_name = $contract_detail_list[0]->water_type_name;

        // 電気
        $electricity = $contract_detail_list[0]->electricity;

        // 電気(備考)
        $electricity_type_name = $contract_detail_list[0]->electricity_type_name;

        // ガス
        $gas_name = $contract_detail_list[0]->gas_name;

        // ガス(備考)
        $gas_type_name = $contract_detail_list[0]->gas_type_name;

        // 排水
        $waste_water_name = $contract_detail_list[0]->waste_water_name;

        // 排水(備考)
        $waste_water_type_name = $contract_detail_list[0]->waste_water_type_name;

        // 台所
        $kitchen_need_name = $contract_detail_list[0]->kitchen_need_name;

        // 台所(備考)
        $kitchen_exclusive_type_name = $contract_detail_list[0]->kitchen_exclusive_type_name;
        
        // コンロ
        $cooking_stove_need_name = $contract_detail_list[0]->cooking_stove_need_name;

        // コンロ(備考)
        $cooking_exclusive_type_name = $contract_detail_list[0]->cooking_exclusive_type_name;

        // 浴室
        $bath_need_name = $contract_detail_list[0]->bath_need_name;

        // 浴室(備考)
        $bath_exclusive_type_name = $contract_detail_list[0]->bath_exclusive_type_name;

        // トイレ
        $toilet_need_name = $contract_detail_list[0]->toilet_need_name;

        // トイレ(備考)
        $toilet_exclusive_type_name = $contract_detail_list[0]->toilet_exclusive_type_name;

        // 給湯器
        $water_heater_need_name = $contract_detail_list[0]->water_heater_need_name;

        // 給湯器(備考)
        $water_heater_exclusive_type_name = $contract_detail_list[0]->water_heater_exclusive_type_name;

        // エアコン
        $air_conditioner_need_name = $contract_detail_list[0]->air_conditioner_need_name;

        // エアコン(台数)
        $air_conditioner_exclusive_type_name = $contract_detail_list[0]->air_conditioner_exclusive_type_name;

        // エレベーター
        $elevator_need_name = $contract_detail_list[0]->elevator_need_name;

        // エレベーター(台数)
        $elevator_exclusive_type_name = $contract_detail_list[0]->elevator_exclusive_type_name;

        // 契約始期
        $contract_start_date = $common->format_date_jp($contract_detail_list[0]->contract_start_date);

        // 契約終期
        $contract_end_date = $common->format_date_jp($contract_detail_list[0]->contract_end_date);

        // 契約更新期間
        $contract_update_span = $contract_detail_list[0]->contract_update_span;

        // 契約更新に必要な事項
        $contract_update_item = $contract_detail_list[0]->contract_update_item;

        // 用途制限
        $limit_use_name = $contract_detail_list[0]->limit_use_name;

        // 利用制限
        $limit_type = $contract_detail_list[0]->limit_type;

        // 敷金清算に関する事項
        $security_settle_detail_need_name = $contract_detail_list[0]->security_settle_detail_need_name;

        // 礼金清算に関する事項
        $key_money_settle_detail_need_name = $contract_detail_list[0]->key_money_settle_detail_need_name;

        // 礼金清算に関する事項
        $key_money_settle_detail_need_name = $contract_detail_list[0]->key_money_settle_detail_need_name;     

        // 解約予告
        $announce_cancel_date = $contract_detail_list[0]->announce_cancel_date;     
        
        // 即時解約
        $soon_cancel_date = $contract_detail_list[0]->soon_cancel_date; 

        // 日割家賃の計算
        $daily_calculation_need_name = $contract_detail_list[0]->daily_calculation_need_name; 

        // 契約の解除
        $cancel_contract_document = $contract_detail_list[0]->cancel_contract_document; 

        // 契約の消滅
        $remove_contract_document = $contract_detail_list[0]->remove_contract_document;
        
        // 違約金
        $penalty_fee = $contract_detail_list[0]->penalty_fee;

        // 支払遅延損害金
        $penalty_fee_late_document = $contract_detail_list[0]->penalty_fee_late_document;

        // 損害賠償
        $claim_fee_document = $contract_detail_list[0]->claim_fee_document;
        
        // 修繕に関する事項
        $fix_document = $contract_detail_list[0]->fix_document;

        // 明渡及び原状回復
        $recovery_document = $contract_detail_list[0]->recovery_document;

        // 契約者名
        $contract_name = $contract_detail_list[0]->contract_name;

        // 契約者フリガナ
        $contract_ruby = $contract_detail_list[0]->contract_ruby;

        // 契約者電話番号
        $contract_tel = $contract_detail_list[0]->contract_tel;

        // 契約者生年月日
        $contract_date = $common->format_date_jp($contract_detail_list[0]->contract_date);
        
        // 特約事項
        $special_contract_detail_name = $contract_detail_list[0]->special_contract_detail_name;

        // 銀行名
        $bank_name = $contract_detail_list[0]->bank_name;

        // 支店名
        $bank_branch_name = $contract_detail_list[0]->bank_branch_name;
        
        // 口座種別
        $bank_type_name = $contract_detail_list[0]->bank_type_name;

        // 口座番号
        $bank_number = $contract_detail_list[0]->bank_number;

        // 口座名義人
        $bank_account_name = $contract_detail_list[0]->bank_account_name;

        // 家賃支払日
        $rent_fee_payment_date = $contract_detail_list[0]->rent_fee_payment_date;
        
        // メールボックス
        $mail_box_number = $contract_detail_list[0]->mail_box_number;
        
        // 連帯保証人有無
        $guarantor_need_name = $contract_detail_list[0]->guarantor_need_name;
        
        // 極度額
        $guarantor_max_payment = $contract_detail_list[0]->guarantor_max_payment;
        
        /**
         * Excelの設定
         */
        // Excelを開く
        $spreadsheet = IOFactory::load(public_path() . '/excel/important_explanation_contract_lutan.xlsx');

        // シートをアクティブにする
        $sheet = $spreadsheet->getActiveSheet();

        /**
         * セルに値挿入
         */
        // 名称
        $sheet->setCellValue('B146', $company_licenses);

        // 代表者
        $sheet->setCellValue('B147', $company_license_representative);

        // 所在地
        $sheet->setCellValue('B148', $company_license_address);

        // 免許番号
        $sheet->setCellValue('B149', $company_license_number);

        // 免許年月日
        $sheet->setCellValue('B150', $company_license_span);

        // 取扱店名称
        $sheet->setCellValue('B151', $company_nick_name);

        // 取扱店所在地
        $sheet->setCellValue('B152', $company_nick_address);

        // 担当者
        $sheet->setCellValue('B153', $manager_name);

        // 宅地建物取引士
        $sheet->setCellValue('B154', $user_license_name);
        
        // 登録番号
        $sheet->setCellValue('B155', $user_license_number);

        // 電話番号
        $sheet->setCellValue('B156', $company_license_tel);

        // ファックス番号
        $sheet->setCellValue('B157', $company_license_fax);

        // 管理の委託先(共有:名称)
        $sheet->setCellValue('C13', $m_share_name);

        // 管理の委託先(共有:住所)
        $sheet->setCellValue('C14', $m_share_address);

        // 管理の委託先(共有:tel)
        $sheet->setCellValue('C15', $m_share_tel);

        // 管理の委託先(専有:名称)
        $sheet->setCellValue('C16', $m_own_name);

        // 管理の委託先(専有:住所)
        $sheet->setCellValue('C17', $m_own_address);

        // 管理の委託先(専有:tel)
        $sheet->setCellValue('C18', $m_own_tel);

        // 法務局名
        $sheet->setCellValue('B162', $legal_place_name);

        // 法務局住所
        $sheet->setCellValue('B163', $legal_place_address);

        // 保証協会名称
        $sheet->setCellValue('B167', $guaranty_association_name);

        // 保証協会住所
        $sheet->setCellValue('B168', $guaranty_association_address);

        // 保証協会所属地方名称
        $sheet->setCellValue('B172', $guaranty_association_region_name);

        // 保証協会所属地方住所
        $sheet->setCellValue('B173', $guaranty_association_region_address);

        // 不動産名
        $sheet->setCellValue('C2', $real_estate_name);

        // 号室
        $sheet->setCellValue('C3', $room_name);

        // 契約面積
        $sheet->setCellValue('C4', $room_size);

        // 住所
        $sheet->setCellValue('C5', $real_estate_address);
        
        // 構造
        $sheet->setCellValue('C6', $real_estate_structure_name);

        // 階数
        $sheet->setCellValue('C7', $real_estate_floor);

        // 間取タイプ
        $sheet->setCellValue('C8', $room_layout_type);

        // 築年数
        $sheet->setCellValue('C9', $real_estate_age);

        // 取引形態
        $sheet->setCellValue('C10', $trade_type_name);

        // 貸主
        $sheet->setCellValue('C11', $owner_name);

        // 貸主住所
        $sheet->setCellValue('C12', $owner_address);

        // 石綿使用調査記録
        $sheet->setCellValue('C19', $report_asbestos_need_name);

        // 耐震診断
        $sheet->setCellValue('C20', $report_earthquake_need_name);

        // 造成宅地防災区域内
        $sheet->setCellValue('C21', $land_disaster_prevention_area_name);

        // 津波災害警戒区域
        $sheet->setCellValue('C22', $tsunami_disaster_alert_area_name);

        // 土砂災害区域
        $sheet->setCellValue('C23', $sediment_disaster_area_name);

        // 所有権
        $sheet->setCellValue('C25', $regi_name);

        // 所有権に係る権利に関する事項
        $sheet->setCellValue('C26', $regi_right_need_name);

        // ハザードマップ有無
        $sheet->setCellValue('C27', $hazard_map_need_name);

        // 洪水
        $sheet->setCellValue('C28', $warning_flood_need_name);

        // 高潮
        $sheet->setCellValue('C29', $warning_storm_surge_need_name);

        // 雨水・出水
        $sheet->setCellValue('C30', $warning_rain_water_need_name);

        // 抵当権設定
        $sheet->setCellValue('C31', $regi_mortgage_need_name);

        // 所有者が違う場合
        $sheet->setCellValue('C32', $regi_difference_owner);

        // 未完成物件の時
        $sheet->setCellValue('C33', $completion_date);

        // 敷金・保証金
        $sheet->setCellValue('C34', $security_fee);

        // 礼金・解約引き
        $sheet->setCellValue('C35', $key_fee);

        // 家賃
        $sheet->setCellValue('C36', $rent_fee);

        // 共益費
        $sheet->setCellValue('C37', $service_fee);

        // 水道代
        $sheet->setCellValue('C38', $water_fee);

        // その他
        $sheet->setCellValue('C39', $ohter_fee);

        // 駐輪代
        $sheet->setCellValue('C40', $bicycle_fee);

        // 住宅保険期間
        $sheet->setCellValue('C41', $fire_insurance_span);

        // 住宅保険料
        $sheet->setCellValue('C42', $fire_insurance_fee);
        
        // 初回保証料
        $sheet->setCellValue('C43', $guarantee_fee);

        // 保証会社更新期間
        $sheet->setCellValue('C44', $guarantee_update_span_name);

        // 保証会社更新料
        $sheet->setCellValue('C45', $guarantee_update_fee);

        // 安心サポート
        $sheet->setCellValue('C46', $support_fee);

        // 防虫抗菌代
        $sheet->setCellValue('C47', $disinfect_fee);

        // その他費用①
        $sheet->setCellValue('B48', $other_name1);

        // その他費用①
        $sheet->setCellValue('C48', $other_fee1);

        // その他費用②
        $sheet->setCellValue('B49', $other_name2);

        // その他費用②
        $sheet->setCellValue('C49', $other_fee2);

        // 仲介手数料
        $sheet->setCellValue('C50', $broker_fee);

        // 仲介手数料(駐車場)
        $sheet->setCellValue('C51', $car_broker_fee);
        
        // 駐車場代
        $sheet->setCellValue('C52', $car_broker_fee);

        // 駐車場代保証金
        $sheet->setCellValue('C53', $car_deposit_fee);

        // 預金
        $sheet->setCellValue('C54', $today_account_fee);

        // 決済予定日
        $sheet->setCellValue('C55', $payment_date);

        // 支払金、預金の保全措置
        $sheet->setCellValue('C56', $keep_account_fee_need_name);
        
        // 金銭の賃借の斡旋
        $sheet->setCellValue('C57', $introduction_fee_need_name);

        // 飲用水
        $sheet->setCellValue('C58', $water_name);

        // 飲用水（備考）
        $sheet->setCellValue('E58', $water_type_name);

        // 電気
        $sheet->setCellValue('C59', $electricity);

        // 電気（備考）
        $sheet->setCellValue('E59', $electricity_type_name);

        // ガス
        $sheet->setCellValue('C60', $gas_name);

        // ガス（備考）
        $sheet->setCellValue('E60', $gas_type_name);

        // 排水
        $sheet->setCellValue('C61', $waste_water_name);

        // 排水（備考）
        $sheet->setCellValue('E61', $waste_water_type_name);

        // 台所
        $sheet->setCellValue('C62', $kitchen_need_name);

        // 台所（備考）
        $sheet->setCellValue('E62', $kitchen_exclusive_type_name);

        // コンロ
        $sheet->setCellValue('C63', $cooking_stove_need_name);

        // コンロ（備考）
        $sheet->setCellValue('E63', $cooking_exclusive_type_name);

        // コンロ
        $sheet->setCellValue('C64', $bath_need_name);

        // コンロ（備考）
        $sheet->setCellValue('E64', $bath_exclusive_type_name);

        // トイレ
        $sheet->setCellValue('C65', $toilet_need_name);

        // トイレ（備考）
        $sheet->setCellValue('E65', $toilet_exclusive_type_name);

        // 給湯器
        $sheet->setCellValue('C66', $water_heater_need_name);

        // 給湯器（備考）
        $sheet->setCellValue('E66', $water_heater_exclusive_type_name);

        // エアコン
        $sheet->setCellValue('C67', $air_conditioner_need_name);

        // エアコン（台数）
        $sheet->setCellValue('E67', $air_conditioner_exclusive_type_name);

        // エレベーター
        $sheet->setCellValue('C68', $elevator_need_name);

        // エレベーター（台数）
        $sheet->setCellValue('E68', $elevator_exclusive_type_name);

        // 契約始期
        $sheet->setCellValue('C69', $contract_start_date);

        // 契約終期
        $sheet->setCellValue('C70', $contract_end_date);

        // 契約終期
        $sheet->setCellValue('C71', $contract_update_span);

        // 更新に必要な事項
        $sheet->setCellValue('C72', $contract_update_item);

        // 用途制限
        $sheet->setCellValue('C73', $limit_use_name);

        // 利用制限
        $sheet->setCellValue('C74', $limit_type);

        // 敷金精算に関する事項
        $sheet->setCellValue('C75', $security_settle_detail_need_name);

        // 礼金精算に関する事項
        $sheet->setCellValue('C76', $key_money_settle_detail_need_name);

        // 解約予告
        $sheet->setCellValue('C77', $announce_cancel_date);

        // 即時予告
        $sheet->setCellValue('C78', $soon_cancel_date);

        // 即時予告
        $sheet->setCellValue('C79', $daily_calculation_need_name);

        // 契約の解除
        $sheet->setCellValue('C80', $cancel_contract_document);

        // 契約の消滅
        $sheet->setCellValue('C81', $remove_contract_document);

        // 契約の消滅
        $sheet->setCellValue('C82', $penalty_fee);
        
        // 支払遅延損害金
        $sheet->setCellValue('C83', $penalty_fee_late_document);

        // 違約金
        $sheet->setCellValue('C83', $penalty_fee_late_document);

        // 損害賠償
        $sheet->setCellValue('C84', $claim_fee_document);

        // 修繕に関する事項
        $sheet->setCellValue('C85', $fix_document);

        // 明渡及び原状回復
        $sheet->setCellValue('C86', $recovery_document);
        
        // 契約者
        $sheet->setCellValue('C88', $contract_name);

        // 契約者フリガナ
        $sheet->setCellValue('C89', $contract_ruby);

        // 契約者tel
        $sheet->setCellValue('C90', $contract_tel);

        // 契約者生年月日
        $sheet->setCellValue('C91', $contract_date);
        
        // 特約事項
        $sheet->setCellValue('A113', $special_contract_detail_name);

        // 銀行名
        $sheet->setCellValue('C104', $bank_name);

        // 支店名
        $sheet->setCellValue('C105', $bank_branch_name);

        // 種別
        $sheet->setCellValue('C106', $bank_type_name);

        // 口座番号
        $sheet->setCellValue('C107', $bank_number);

        // 名義人
        $sheet->setCellValue('C108', $bank_account_name);

        // 家賃支払日
        $sheet->setCellValue('C109', $rent_fee_payment_date);
        
        // メールボックス
        $sheet->setCellValue('B102', $mail_box_number);

        // 連帯保証人の有無
        $sheet->setCellValue('C92', $guarantor_need_name);

        // 極度額
        $sheet->setCellValue('C93', $guarantor_max_payment);

        /**
         * 同居人
         */
        // A97から2人目の同居人の為、デフォルト値
        $housemate_cell = 97;

        for($i = 0; $i < $cnt; $i++){

            /**
             * 値取得
             */
            // 同居人名
            $contract_housemate_name = $housemate_detail_list[$i]->contract_housemate_name;
            Log::debug('$contract_housemate_name:'.$contract_housemate_name);
            
            // 生年月日
            $contract_housemate_birthday = $common->format_date_jp($housemate_detail_list[$i]->contract_housemate_birthday);
            Log::debug('$contract_housemate_birthday:'.$contract_housemate_birthday);

            // 1列目は0のため、スキップ
            if($i !== 0){

                $housemate_cell = 97 + $i;
                Log::debug('$i:'.$housemate_cell);

            }

            /**
             * Excelに値設定
             */
            // 同居人名
            $sheet->setCellValue('A' .$housemate_cell, $contract_housemate_name);

            // 生年月日
            $sheet->setCellValue('B' .$housemate_cell, $contract_housemate_birthday);

        }

        
        /**
         * 背景色設定
         */
        // $sheet->getStyle('A27')
        //     ->getFill()
        //     ->setFillType('solid')
        //     ->getStartColor()
        //     ->setARGB('FFFF0000');

        /**
         * Excelに書き込み
         */
        $writer = new Xlsx($spreadsheet);

        $writer->save(public_path() . '/excel/output.xlsx');

        /**
         * ファイル名作成
         */
        // 現在時刻の取得
        $now = date('Y/m/d');
        $replace_now = str_replace('/', '_', $now);

        // ファイル名の作成
        $file_name = $replace_now ."_" .$real_estate_name ."_" .$room_name .".xlsx";

        //一時ファイルを保存する(public/excel)第1引数/excel/output.xlsx、第2引数はファイル名、第3引数はオプション
        return response()->download(public_path() . '/excel/output.xlsx', $file_name,
        ['content-type' => 'application/vnd.ms-excel'])
        // 一時ファイルのdelete
        ->deleteFileAfterSend(true);
    }

    /**
     * 契約詳細取得
     *
     * @param [type] $request(fromデータ)
     * @param [type] $contract_detail_id(契約詳細id)
     * @return $ret(契約詳細取得)
     */
    private function getContractDetailList($request){
        Log::debug('log_start:' .__FUNCTION__);

        // 値設定
        $contract_detail_id = $request->input('contract_detail_id');
        Log::debug('contract_detail_id:' .$contract_detail_id);

        // sql
        $str = "select "
        ."contract_details.contract_detail_id as contract_detail_id, "
        ."contract_details.create_user_id as create_user_id, "
        ."create_users.create_user_name as create_user_name, "
        ."contract_details.contract_detail_progress_id as contract_detail_progress_id, "
        ."contract_detail_progress.contract_detail_progress_name as contract_detail_progress_name, "
        ."application_id as application_id, "
        ."contract_details.company_license_id as company_license_id, "
        ."company_licenses.company_license_name as company_licenses, "
        ."company_licenses.company_license_representative as company_license_representative, "
        ."company_licenses.company_license_address as company_license_address, "
        ."company_licenses.company_license_tel as company_license_tel, "
        ."company_licenses.company_license_fax as company_license_fax, "
        ."company_licenses.company_license_number as company_license_number, "
        ."company_licenses.company_license_span as company_license_span, "
        ."company_licenses.company_nick_name as company_nick_name, "
        ."company_licenses.company_nick_address as company_nick_address, "
        ."company_licenses.legal_place_id as legal_place_id, "
        ."legal_places.legal_place_name as legal_place_name, "
        ."legal_places.legal_place_address as legal_place_address, "
        ."company_licenses.guaranty_association_id as guaranty_association_id, "
        ."guaranty_associations.guaranty_association_name as guaranty_association_name, "
        ."guaranty_associations.guaranty_association_address as guaranty_association_address, "
        ."company_licenses.guaranty_association_region_id as guaranty_association_region_id, "
        ."guaranty_association_region.guaranty_association_name as guaranty_association_region_name, "
        ."guaranty_association_region.guaranty_association_address as guaranty_association_region_address, "
        ."manager_name as manager_name, "
        ."contract_details.user_license_id as user_license_id, "
        ."user_license_name as user_license_name, "
        ."user_license_number as user_license_number, "
        ."contract_details.trade_type_id as trade_type_id, "
        ."trade_types.trade_type_name as trade_type_name, "
        ."contract_name as contract_name, "
        ."contract_ruby as contract_ruby, "
        ."contract_date as contract_date, "
        ."contract_tel as contract_tel, "
        ."real_estate_name as real_estate_name, "
        ."real_estate_post_number as real_estate_post_number, "
        ."real_estate_address as real_estate_address, "
        ."room_name as room_name, "
        ."room_size as room_size, "
        ."contract_details.real_estate_structure_id as real_estate_structure_id, "
        ."real_estate_structures.real_estate_structure_name as real_estate_structure_name, "
        ."contract_details.real_estate_floor as real_estate_floor, "
        ."contract_details.room_layout_name as room_layout_count, "
        ."room_layouts.room_layout_name as room_layout_name, "
        ."real_estate_age as real_estate_age, "
        ."owner_name as owner_name, "
        ."owner_post_number as owner_post_number, "
        ."owner_address as owner_address, "
        ."bank_id as bank_id, "
        ."bank_name as bank_name, "
        ."bank_branch_name as bank_branch_name, "
        ."contract_details.bank_type_id as bank_type_id, "
        ."bank_types.bank_type_name as bank_type_name, "
        ."bank_number as bank_number, "
        ."bank_account_name as bank_account_name, "
        ."m_share_name as m_share_name, "
        ."m_share_post_number as m_share_post_number, "
        ."m_share_address as m_share_address, "
        ."m_share_tel as m_share_tel, "
        ."m_own_name as m_own_name, "
        ."m_own_post_number as m_own_post_number, "
        ."m_own_address as m_own_address, "
        ."m_own_tel as m_own_tel, "
        ."contract_details.report_asbestos as report_asbestos, "
        ."report_asbestos_needs.need_name as report_asbestos_need_name, "
        ."contract_details.report_earthquake as report_earthquake, "
        ."report_earthquake_needs.need_name as report_earthquake_need_name, "
        ."land_disaster_prevention_area as land_disaster_prevention_area, "
        ."land_disaster_prevention_area_types.inside_and_outside_area_name as land_disaster_prevention_area_name, "
        ."tsunami_disaster_alert_area as tsunami_disaster_alert_area, "
        ."tsunami_disaster_alert_area_types.inside_and_outside_area_name as tsunami_disaster_alert_area_name, "
        ."sediment_disaster_area as sediment_disaster_area, "
        ."sediment_disaster_area_types.inside_and_outside_area_name as sediment_disaster_area_name, "
        ."regi_name as regi_name, "
        ."regi_right as regi_right, "
        ."regi_right_needs.need_name as regi_right_need_name, "
        ."regi_mortgage as regi_mortgage, "
        ."regi_mortgage_needs.need_name as regi_mortgage_need_name, "
        ."regi_difference_owner as regi_difference_owner, "
        ."completion_date as completion_date, "
        ."hazard_map as hazard_map, "
        ."hazard_map_needs.need_name as hazard_map_need_name, "
        ."warning_flood as warning_flood, "
        ."warning_flood_needs.need_name as warning_flood_need_name, "
        ."warning_storm_surge as warning_storm_surge, "
        ."warning_storm_surge_needs.need_name as warning_storm_surge_need_name, "
        ."warning_rain_water as warning_rain_water, "
        ."warning_rain_water_needs.need_name as warning_rain_water_need_name, "
        ."security_fee as security_fee, "
        ."key_fee as key_fee, "
        ."rent_fee as rent_fee, "
        ."service_fee as service_fee, "
        ."water_fee as water_fee, "
        ."ohter_fee as ohter_fee, "
        ."bicycle_fee as bicycle_fee, "
        ."total_fee as total_fee, "
        ."car_fee as car_fee, "
        ."car_deposit_fee as car_deposit_fee, "
        ."fire_insurance_fee as fire_insurance_fee, "
        ."fire_insurance_span as fire_insurance_span, "
        ."guarantee_fee as guarantee_fee, "
        ."guarantee_update_span as guarantee_update_span, "
        ."guarantee_update_spans.guarantee_update_span_name as guarantee_update_span_name, "
        ."guarantee_update_fee as guarantee_update_fee, "
        ."support_fee as support_fee, "
        ."disinfect_fee as disinfect_fee, "
        ."other_name1 as other_name1, "
        ."other_fee1 as other_fee1, "
        ."other_name2 as other_name2, "
        ."other_fee2 as other_fee2, "
        ."broker_fee as broker_fee, "
        ."car_broker_fee as car_broker_fee, "
        ."today_account_fee_date as today_account_fee_date, "
        ."today_account_fee as today_account_fee, "
        ."payment_date as payment_date, "
        ."keep_account_fee as keep_account_fee, "
        ."keep_account_fee_needs.need_name as keep_account_fee_need_name, "
        ."introduction_fee as introduction_fee, "
        ."introduction_fee_needs.need_name as introduction_fee_need_name, "
        ."contract_details.water as water, "
        ."waters.water_name as water_name, "
        ."water_type_name as water_type_name, "
        ."electricity as electricity, "
        ."electricity_type_name as electricity_type_name, "
        ."contract_details.gas as gas, "
        ."gas.gas_name as gas_name, "
        ."gas_type_name as gas_type_name, "
        ."contract_details.waste_water as waste_water, "
        ."waste_water.waste_water_name as waste_water_name, "
        ."contract_details.waste_water_name as waste_water_type_name, "
        ."contract_details.kitchen as kitchen, "
        ."kitchen_needs.need_name as kitchen_need_name, "
        ."kitchen_exclusive_type_id as kitchen_exclusive_type_id, "
        ."kitchen_exclusive_types.exclusive_type_name as kitchen_exclusive_type_name, "
        ."cooking_stove as cooking_stove, "
        ."cooking_stove_needs.need_name as cooking_stove_need_name, "
        ."cooking_exclusive_type_id as cooking_exclusive_type_id, "
        ."cooking_exclusive_types.exclusive_type_name as cooking_exclusive_type_name, "
        ."bath as bath, "
        ."bath_needs.need_name as bath_need_name, "
        ."bath_exclusive_type_id as bath_exclusive_type_id, "
        ."bath_exclusive_types.exclusive_type_name as bath_exclusive_type_name, "
        ."toilet as toilet, "
        ."toilet_needs.need_name as toilet_need_name, "
        ."toilet_exclusive_type_id as toilet_exclusive_type_id, "
        ."toilet_exclusive_types.exclusive_type_name as toilet_exclusive_type_name, "
        ."water_heater as water_heater, "
        ."water_heater_needs.need_name as water_heater_need_name, "
        ."water_heater_exclusive_type_id as water_heater_exclusive_type_id, "
        ."water_heater_exclusive_types.exclusive_type_name as water_heater_exclusive_type_name, "
        ."air_conditioner as air_conditioner, "
        ."air_conditioner_needs.need_name as air_conditioner_need_name, "
        ."air_conditioner_exclusive_type_name as air_conditioner_exclusive_type_name, "
        ."elevator as elevator, "
        ."elevator_needs.need_name as elevator_need_name, "
        ."elevator_type_name as elevator_exclusive_type_name, "
        ."contract_start_date as contract_start_date, "
        ."contract_end_date as contract_end_date, "
        ."contract_update_span as contract_update_span, "
        ."contract_update_item as contract_update_item, "
        ."daily_calculation as daily_calculation, "
        ."daily_calculation_needs.need_name as daily_calculation_need_name, "
        ."security_settle_detail as security_settle_detail, "
        ."security_settle_detail_needs.need_name as security_settle_detail_need_name, "
        ."key_money_settle_detail as key_money_settle_detail, "
        ."key_money_settle_detail_needs.need_name as key_money_settle_detail_need_name, "
        ."limit_use as limit_use, "
        ."limit_uses.limit_use_name as limit_use_name, "
        ."limit_type as limit_type, "
        ."announce_cancel_date as announce_cancel_date, "
        ."soon_cancel_date as soon_cancel_date, "
        ."cancel_fee_count as cancel_fee_count, "
        ."cancel_contract_document as cancel_contract_document, "
        ."remove_contract_document as remove_contract_document, "
        ."penalty_fee as penalty_fee, "
        ."penalty_fee_late_document as penalty_fee_late_document, "
        ."claim_fee_document as claim_fee_document, "
        ."fix_document as fix_document, "
        ."recovery_document as recovery_document, "
        ."rent_fee_payment_date as rent_fee_payment_date, "
        ."mail_box_number as mail_box_number, "
        ."special_contract_details.special_contract_detail_id as special_contract_detail_id, "
        ."special_contract_details.special_contract_detail_name as special_contract_detail_name, "
        ."contract_details.guarantor_need_id as guarantor_need_id, "
        ."guarantor_needs.need_name as guarantor_need_name, "
        ."contract_details.guarantor_max_payment as guarantor_max_payment, "
        ."contract_details.entry_user_id as entry_user_id, "
        ."contract_details.entry_date as entry_date, "
        ."contract_details.update_user_id as update_user_id, "
        ."contract_details.update_date as update_date "
        ."from "
        ."contract_details "
        ."left join contract_detail_progress on "
        ."contract_detail_progress.contract_detail_progress_id = contract_details.contract_detail_id "
        ."left join company_licenses on "
        ."company_licenses.company_license_id = contract_details.company_license_id "
        ."left join legal_places on "
        ."legal_places.legal_place_id = company_licenses.legal_place_id "
        ."left join guaranty_associations on "
        ."guaranty_associations.guaranty_association_id = company_licenses.guaranty_association_id "
        ."left join guaranty_associations as guaranty_association_region on "
        ."guaranty_association_region.guaranty_association_id = company_licenses.guaranty_association_region_id "
        ."left join special_contract_details on "
        ."special_contract_details.contract_detail_id = contract_details.contract_detail_id "
        ."left join create_users on "
        ."create_users.create_user_id = contract_details.create_user_id "
        ."left join trade_types on "
        ."trade_types.trade_type_id = contract_details.trade_type_id "
        ."left join real_estate_structures on "
        ."real_estate_structures.real_estate_structure_id = contract_details.real_estate_structure_id "
        ."left join room_layouts on "
        ."contract_details.room_layout_id = room_layouts.room_layout_id "
        ."left join bank_types on "
        ."bank_types.bank_type_id = contract_details.bank_type_id "
        ."left join needs as report_asbestos_needs on "
        ."report_asbestos_needs.need_id = contract_details.report_asbestos "
        ."left join needs as report_earthquake_needs on "
        ."report_earthquake_needs.need_id = contract_details.report_earthquake "
        ."left join needs as regi_right_needs on "
        ."regi_right_needs.need_id = contract_details.regi_right "
        ."left join needs as regi_mortgage_needs on "
        ."regi_mortgage_needs.need_id = contract_details.regi_mortgage "
        ."left join needs as hazard_map_needs on "
        ."hazard_map_needs.need_id = contract_details.hazard_map "
        ."left join needs as warning_flood_needs on "
        ."warning_flood_needs.need_id = contract_details.warning_flood "
        ."left join needs as warning_rain_water_needs on "
        ."warning_rain_water_needs.need_id = contract_details.warning_rain_water "
        ."left join needs as warning_storm_surge_needs on "
        ."warning_storm_surge_needs.need_id = contract_details.warning_storm_surge "
        ."left join needs as keep_account_fee_needs on "
        ."keep_account_fee_needs.need_id = contract_details.keep_account_fee "
        ."left join needs as introduction_fee_needs on "
        ."introduction_fee_needs.need_id = contract_details.introduction_fee "
        ."left join waters on "
        ."waters.water_id = contract_details.water "
        ."left join gas on "
        ."gas.gas_id = contract_details.gas "
        ."left join waste_water on "
        ."waste_water.waste_water_id = contract_details.waste_water "
        ."left join needs as kitchen_needs on "
        ."kitchen_needs.need_id = contract_details.kitchen "
        ."left join exclusive_types as kitchen_exclusive_types on "
        ."kitchen_exclusive_types.exclusive_type_id = contract_details.kitchen_exclusive_type_id "
        ."left join needs as cooking_stove_needs on "
        ."cooking_stove_needs.need_id = contract_details.cooking_stove "
        ."left join exclusive_types as cooking_exclusive_types on "
        ."cooking_exclusive_types.exclusive_type_id = contract_details.cooking_exclusive_type_id "
        ."left join needs as bath_needs on "
        ."bath_needs.need_id = contract_details.bath "
        ."left join exclusive_types as bath_exclusive_types on "
        ."bath_exclusive_types.exclusive_type_id = contract_details.bath_exclusive_type_id "
        ."left join needs as toilet_needs on "
        ."toilet_needs.need_id = contract_details.toilet "
        ."left join exclusive_types as toilet_exclusive_types on "
        ."toilet_exclusive_types.exclusive_type_id = contract_details.toilet_exclusive_type_id "
        ."left join needs as water_heater_needs on "
        ."water_heater_needs.need_id = contract_details.water_heater "
        ."left join exclusive_types as water_heater_exclusive_types on "
        ."water_heater_exclusive_types.exclusive_type_id = contract_details.water_heater_exclusive_type_id "
        ."left join needs as air_conditioner_needs on "
        ."air_conditioner_needs.need_id = contract_details.air_conditioner "
        ."left join needs as elevator_needs on "
        ."elevator_needs.need_id = contract_details.elevator "
        ."left join needs as security_settle_detail_needs on "
        ."security_settle_detail_needs.need_id = contract_details.security_settle_detail "
        ."left join needs as key_money_settle_detail_needs on "
        ."key_money_settle_detail_needs.need_id = contract_details.key_money_settle_detail "
        ."left join inside_and_outside_area as land_disaster_prevention_area_types on "
        ."land_disaster_prevention_area_types.inside_and_outside_area_id = contract_details.land_disaster_prevention_area "
        ."left join inside_and_outside_area as tsunami_disaster_alert_area_types on "
        ."tsunami_disaster_alert_area_types.inside_and_outside_area_id = contract_details.tsunami_disaster_alert_area "
        ."left join inside_and_outside_area as sediment_disaster_area_types on "
        ."sediment_disaster_area_types.inside_and_outside_area_id = contract_details.sediment_disaster_area "
        ."left join limit_uses as limit_uses on "
        ."limit_uses.limit_use_id = contract_details.limit_use "
        ."left join guarantee_update_spans as guarantee_update_spans on "
        ."guarantee_update_spans.guarantee_update_span_id = contract_details.guarantee_update_span "
        ."left join needs as daily_calculation_needs on "
        ."daily_calculation_needs.need_id = contract_details.daily_calculation "
        ."left join needs as guarantor_needs on "
        ."guarantor_needs.need_id = contract_details.guarantor_need_id "
        ."where "
        ."contract_details.contract_detail_id = '$contract_detail_id;' ";

        Log::debug('getContractDetailList_sql:' .$str);
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * 同居人一覧取得
     *
     * @param [type] $request
     * @return $ret(同居人取得)
     */
    private function getHousemateDetailList($request){
        Log::debug('log_start:'.__FUNCTION__);

        // 値設定
        $contract_detail_id = $request->input('contract_detail_id');
        Log::debug('contract_detail_id:' .$contract_detail_id);

        $str = "select * from contract_housemates "
        ."where contract_detail_id = $contract_detail_id";

        Log::debug('getHousemateDetailList_sql:' .$str);
        
        $ret = DB::select($str);

        Log::debug('log_end:'.__FUNCTION__);
        return $ret;
    }

    /**
     * ExcelDummy
     * 帳票ダウンロード時にローディング画面実施の為のajax
     *
     * @param Request $request
     * @return void
     */
    public function excelEntryDummy(Request $request){
        Log::debug('log_start:'.__FUNCTION__);
        
        $response = [];

        Log::debug('log_end:' .__FUNCTION__);
        return response()->json($response);
    }
}