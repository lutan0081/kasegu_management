// 登録ボタン(ajax)
$(function() {

    // ローディング画面開始の処理
    $(document).ajaxSend(function() {
        $("#overlay").fadeIn(300);
    });

    $("#btn_edit").on('click', function(e) {
        console.log("btn_editクリックされています");
        
        e.preventDefault();

        /**
         * バリデーション
         * 不動産業者(全項目)、賃借人(氏名、フリガナ)入力必須
         */
        // バリデーションの為にformの中の値数を確認
        let forms_all = $('.needs-validation');
        console.log(forms_all[0].length);

        // validationフラグの初期値
        let v_check = true;

        // formの項目数ループ処理
        for (let i = 0; i < forms_all[0].length; i++) {
            /**
             * タグ名、Id名取得
             */
            // forms[0]=form.[i]=中の項目;
            let form_01 = forms_all[0][i];
            console.log('from:'+ form_01);

            // タグ名を取得 input or button
            let tag = $(form_01).prop("tagName");
            console.log('tag:'+ tag);

            // 各項目のid取得
            let f_id = $(form_01).prop("id");
            console.log('id:'+ f_id);
            
            // form内のbuttonはスルー
            if (tag == 'BUTTON') {
                continue;
            }

            /**
             * f_id = 各フォームのid
             * 必須項目に空白があった場合、was-validatedクラスを代入し赤文字でErrorメッセージを表示する
             * f_id = 任意(スルーしてもいい項目の為)の項目の為、continueでスルーする
             */
            // ===不動産業者(任意の項目)===
            // 進捗状況
            if (f_id == 'contract_progress') {
                continue;
            }
            // 入居開始日
            if (f_id == 'contract_start_date') {
                continue;
            }
            // ペット飼育数
            if (f_id == 'pet_bleeding_name') {
                continue;
            }
            // ペット飼育種類
            if (f_id == 'pet_kind_name') {
                continue;
            }
            // 保証金
            if (f_id == 'deposit_money') {
                continue;
            }
            // 解約引き
            if (f_id == 'deposit_refund') {
                continue;
            }
            // 敷金
            if (f_id == 'security_deposit') {
                continue;
            }
            // 礼金
            if (f_id == 'key_money') {
                continue;
            }
            // 家賃
            if (f_id == 'rent_fee') {
                continue;
            }
            // 共益費
            if (f_id == 'service_fee') {
                continue;
            }
            // 水道代
            if (f_id == 'water_fee') {
                continue;
            }
            // その他
            if (f_id == 'ohter_fee') {
                continue;
            }
            // 合計額
            if (f_id == 'total_rent_fee') {
                continue;
            }
            
            // ===賃借人(任意の項目)===
            // 郵便番号
            if (f_id == 'contract_post_number') {
                continue;
            }
            // 住所
            if (f_id == 'contract_address') {
                continue;
            }
            // 性別
            if (f_id == 'contract_sex_id') {
                continue;
            }
            // 年齢
            if (f_id == 'contract_birthday') {
                continue;
            }
            // 生年月日
            if (f_id == 'contract_age') {
                continue;
            }
            // 自宅電話番号
            if (f_id == 'contract_home_tel') {
                continue;
            }
            // 携帯Tel
            if (f_id == 'contract_mobile_tel') {
                continue;
            }
            // 勤務先名
            if (f_id == 'contract_work_place_name') {
                continue;
            }
            // 勤務先カナ
            if (f_id == 'contract_work_place_ruby') {
                continue;
            }
            // 勤務先郵便番号
            if (f_id == 'contract_work_place_post_number') {
                continue;
            }
            // 勤務先住所
            if (f_id == 'contract_work_place_address') {
                continue;
            }
            // 勤務先電話番号
            if (f_id == 'contract_work_place_tel') {
                continue;
            }
            // 業種
            if (f_id == 'contract_work_place_Industry') {
                continue;
            }
            // 職種
            if (f_id == 'contract_work_place_occupation') {
                continue;
            }
            // 雇用形態
            if (f_id == 'contract_employment_status') {
                continue;
            }
            // 勤続年数
            if (f_id == 'contract_work_place_years') {
                continue;
            }
            // 年収
            if (f_id == 'contract_annual_income') {
                continue;
            }
            // 健康保険
            if (f_id == 'contracts_insurance_type_id') {
                continue;
            }

            // ===同居人(任意の項目)===
            // 同居人
            if (f_id == 'housemate_name') {
                continue;
            }
            // 同居人カナ
            if (f_id == 'housemate_ruby') {
                continue;
            }
            // 性別
            if (f_id == 'housemates_sex_id') {
                continue;
            }
            // 続柄
            if (f_id == 'housemate_relationship_id') {
                continue;
            }
            // 生年月日
            if (f_id == 'housemate_birthday') {
                continue;
            }
            // 年齢
            if (f_id == 'housemate_age') {
                continue;
            }
            // 郵便番号
            if (f_id == 'housemate_post_number') {
                continue;
            }
            // 住所
            if (f_id == 'housemate_address') {
                continue;
            }
            // 自宅電話番号
            if (f_id == 'housemate_home_tel') {
                continue;
            }
            // 携帯電話番号
            if (f_id == 'housemate_mobile_tel') {
                continue;
            }

            // ===緊急連絡先(任意の項目)===
            // 緊急連絡先
            if (f_id == 'emergency_contacts_name') {
                continue;
            }
            // 緊急連絡先カナ
            if (f_id == 'emergency_contacts_ruby') {
                continue;
            }
            // 性別
            if (f_id == 'emergency_contacts_sex_id') {
                continue;
            }
            // 続柄
            if (f_id == 'emergency_contacts_relationships_id') {
                continue;
            }
            // 生年月日
            if (f_id == 'emergency_contacts_birthday') {
                continue;
            }
            // 年齢
            if (f_id == 'emergency_contract_age') {
                continue;
            }
            // 郵便番号
            if (f_id == 'emergency_contacts_post_number') {
                continue;
            }
            // 住所
            if (f_id == 'emergency_contacts_post_address') {
                continue;
            }
            // 自宅電話番号
            if (f_id == 'emergency_contacts_home_tel') {
                continue;
            }
            // 携帯電話番号
            if (f_id == 'emergency_contacts_mobile_tel') {
                continue;
            }

            // ===連帯保証人(任意の項目)===
            // 連帯保証人名
            if (f_id == 'guarantor_name') {
                continue;
            }
            // 連帯保証人カナ
            if (f_id == 'guarantor_ruby') {
                continue;
            }
            // 性別
            if (f_id == 'guarantor_sex_id') {
                continue;
            }
            // 続柄
            if (f_id == 'guarantors_relationship_id') {
                continue;
            }
            // 生年月日
            if (f_id == 'guarantor_birthday') {
                continue;
            }
            // 年齢
            if (f_id == 'guarantor_age') {
                continue;
            }
            // 郵便番号
            if (f_id == 'guarantor_post_number') {
                continue;
            }
            // 住所
            if (f_id == 'guarantor_address') {
                continue;
            }
            // 自宅電話番号
            if (f_id == 'guarantor_home_tel') {
                continue;
            }
            // 携帯電話番号
            if (f_id == 'guarantor_mobile_tel') {
                continue;
            }
            // 勤務先名
            if (f_id == 'guarantor_work_place_name') {
                continue;
            }
            // 勤務先フリガナ
            if (f_id == 'guarantor_work_place_ruby') {
                continue;
            }
            // 郵便番号
            if (f_id == 'guarantor_work_place_post_number') {
                continue;
            }
            // 勤務先住所
            if (f_id == 'guarantor_work_place_address') {
                continue;
            }
            // 勤務先電話番号
            if (f_id == 'guarantor_work_place_tel') {
                continue;
            }
            // 業種
            if (f_id == 'guarantor_work_place_Industry') {
                continue;
            }
            // 職種
            if (f_id == 'guarantor_work_place_occupation') {
                continue;
            }
            // 雇用形態
            if (f_id == 'guarantor_status') {
                continue;
            }
            // 勤続年数
            if (f_id == 'guarantor_work_place_years') {
                continue;
            }
            // 年収
            if (f_id == 'guarantor_annual_income') {
                continue;
            }
            // 健康保険
            if (f_id == 'guarantor_insurance_type_id') {
                continue;
            }

            // ===添付ファイル(任意の項目)===
            // 添付書類
            if (f_id == 'file_img') {
                continue;
            }
            // 種別
            if (f_id == 'file_img_type') {
                continue;
            }
            // 補足
            if (f_id == 'file_img_type_textarea') {
                continue;
            }

            /**
             * その他
             */
            // 不動産業者Id
            if (f_id == 'application_form_id') {
                continue;
            }
            // 契約者
            if (f_id == 'contract_id') {
                continue;
            }
            // 同居人
            if (f_id == 'housemate_id') {
                continue;
            }
            // 緊急連絡先Id
            if (f_id == 'emergency_contact_id') {
                continue;
            }
            // 保証人Id
            if (f_id == 'guarantor_contracts_id') {
                continue;
            }
            // img_id
            if (f_id == 'img_id') {
                continue;
            }
            // application_flag
            if (f_id == 'application_Flag') {
                continue;
            }
            // session_id
            if (f_id == 'session_id') {
                continue;
            }
            // url_send_flag
            if (f_id == 'url_send_flag') {
                continue;
            }

            // formの値を取得->クラス付与
            let val = $(form_01).val();
            console.log('value:'+ val);
            if (val === '') {
                // blade側のformタグにwas-validatedを追加
                $(forms_all).addClass("was-validated");
                v_check = false;
            }
        }

        /**
         * チェック=falseの場合プログラム終了
         */
        console.log(v_check);
        if (v_check === false) {
            return false;
        }

        /**
         * formデータ送信
         */
        var forms = $('#editForm').serializeArray();
        console.log(forms)

        /**
         * その他
         */
        // URLからの判定 url空の場合:true　urlからでない場合:false
        let application_Flag = $("#application_Flag").val();
        // URLからの場合のsession_id
        let session_id = $("#session_id").val();

        /**
         * 不動産業者
         */
        // 進捗状況
        let contract_progress = $("#contract_progress").val();
        console.log('contract_progress:' + contract_progress);
        // 不動産業者id
        let application_form_id = $("#application_form_id").val();
        // 仲介業者名
        let broker_name = $("#broker_name").val();
        // 仲介業者Tel
        let broker_tel = $("#broker_tel").val();
        // 仲介業者mail
        let broker_mail = $("#broker_mail").val();
        // 担当者
        let manager_name = $("#manager_name").val();
        // url_send_flag
        let url_send_flag = $("#url_send_flag").val();
        // 申込区分id
        let application_type_id = $("#application_type_id").val();
        // 物件用途
        let application_use_id = $("#application_use_id").val();
        // 入居予定
        let contract_start_date = $("#contract_start_date").val();
        // 物件名
        let real_estate_name = $("#real_estate_name").val();
        // 物件カナ
        let real_estate_ruby = $("#real_estate_ruby").val();
        // 号室
        let room_name = $("#room_name").val();
        // 郵便番号
        let post_number = $("#post_number").val();
        // 住所
        let address = $("#address").val();
        // ペット飼育数
        let pet_bleeding_name = $("#pet_bleeding_name").val();
        // 飼育頭数
        let pet_kind_name = $("#pet_kind_name").val();
        // 駐車台数
        let car_parking_number = $("#car_parking_number").val();
        // 駐輪台数
        let bicycle_parking_number = $("#bicycle_parking_number").val();
        // 保証金
        let deposit_money = $("#deposit_money").val();
        // 解約引き
        let deposit_refund = $("#deposit_refund").val();
        // 敷金
        let security_deposit = $("#security_deposit").val();
        // 礼金
        let key_money = $("#key_money").val();
        // 家賃
        let rent_fee = $("#rent_fee").val();
        // 共益費
        let service_fee = $("#service_fee").val();
        // 水道代
        let water_fee = $("#water_fee").val();
        // その他
        let ohter_fee = $("#ohter_fee").val();
        // 総賃料
        let total_rent_fee = $("#total_rent_fee").val();

        /**
         * 賃貸人
         */
        // 契約者Id
        let contract_id = $("#contract_id").val();
        // 契約者名
        let contract_name = $("#contract_name").val();
        // 契約者カナ
        let contract_ruby = $("#contract_ruby").val();
        // 郵便番号
        let contract_post_number = $("#contract_post_number").val();
        // 住所
        let contract_address = $("#contract_address").val();
        // 性別
        let contract_sex_id = $("#contract_sex_id").val();
        // 生年月日
        let contract_birthday = $("#contract_birthday").val();
        // 年齢
        let contract_age = $("#contract_age").val();
        // 自宅電話番号
        let contract_home_tel = $("#contract_home_tel").val();
        // 携帯電話番号
        let contract_mobile_tel = $("#contract_mobile_tel").val();
        // 勤務先名
        let contract_work_place_name = $("#contract_work_place_name").val();
        // 勤務先カナ
        let contract_work_place_ruby = $("#contract_work_place_ruby").val();
        // 勤務先郵便番号
        let contract_work_place_post_number = $("#contract_work_place_post_number").val();
        // 勤務先住所
        let contract_work_place_address = $("#contract_work_place_address").val();
        // 勤務先電話番号
        let contract_work_place_tel = $("#contract_work_place_tel").val();
        // 業種
        let contract_work_place_Industry = $("#contract_work_place_Industry").val();
        // 職種
        let contract_work_place_occupation = $("#contract_work_place_occupation").val();
        // 雇用形態
        let contract_employment_status = $("#contract_employment_status").val();
        // 勤続年数
        let contract_work_place_years = $("#contract_work_place_years").val();
        // 年収
        let contract_annual_income = $("#contract_annual_income").val();
        // 健康保険
        let contracts_insurance_type_id = $("#contracts_insurance_type_id").val();

        /**
         * 同居人
         */
        // 同居人Id
        let housemate_id = $("#housemate_id").val();
        // 同居人名
        let housemate_name = $("#housemate_name").val();
        // 同居人カナ
        let housemate_ruby = $("#housemate_ruby").val();
        // 性別
        let housemates_sex_id = $("#housemates_sex_id").val();
        // 続柄
        let housemate_relationship_id = $("#housemate_relationship_id").val()
        // 生年月日
        let housemate_birthday = $("#housemate_birthday").val()
        // 年齢
        let housemate_age = $("#housemate_age").val()
        // 郵便番号
        let housemate_post_number = $("#housemate_post_number").val()
        // 住所
        let housemate_address = $("#housemate_address").val()
        // 自宅電話番号
        let housemate_home_tel = $("#housemate_home_tel").val()
        // 携帯電話
        let housemate_mobile_tel = $("#housemate_mobile_tel").val()

        /**
         * 緊急連絡先
         */
        // Id
        let emergency_contact_id = $("#emergency_contact_id").val();
        // 緊急連絡先名
        let emergency_contacts_name = $("#emergency_contacts_name").val();
        // 緊急連絡先カナ
        let emergency_contacts_ruby = $("#emergency_contacts_ruby").val();
        // 性別
        let emergency_contacts_sex_id = $("#emergency_contacts_sex_id").val();
        // 続柄
        let emergency_contacts_relationships_id = $("#emergency_contacts_relationships_id").val();
        // 生年月日
        let emergency_contacts_birthday = $("#emergency_contacts_birthday").val();
        // 年齢
        let emergency_contract_age = $("#emergency_contract_age").val();
        // 郵便番号
        let emergency_contacts_post_number = $("#emergency_contacts_post_number").val();
        // 住所
        let emergency_contacts_post_address = $("#emergency_contacts_post_address").val();
        // 自宅電話番号
        let emergency_contacts_home_tel = $("#emergency_contacts_home_tel").val();
        // 携帯電話番号
        let emergency_contacts_mobile_tel = $("#emergency_contacts_mobile_tel").val();

        /**
         * 連帯保証人
         */
        // Id
        let guarantor_contracts_id = $("#guarantor_contracts_id").val();
        // 連帯保証人名
        let guarantor_name = $("#guarantor_name").val();
        // 連帯保証人カナ
        let guarantor_ruby = $("#guarantor_ruby").val();
        // 郵便番号
        let guarantor_post_number = $("#guarantor_post_number").val();
        // 住所
        let guarantor_address = $("#guarantor_address").val();
        // 性別
        let guarantor_sex_id = $("#guarantor_sex_id").val();
        // 生年月日
        let guarantor_birthday = $("#guarantor_birthday").val();
        // 年齢
        let guarantor_age = $("#guarantor_age").val();
        // 続柄
        let guarantors_relationship_id = $("#guarantors_relationship_id").val();
        // 自宅電話番号
        let guarantor_home_tel = $("#guarantor_home_tel").val();
        // 携帯電話番号
        let guarantor_mobile_tel = $("#guarantor_mobile_tel").val();
        // 勤務先名
        let guarantor_work_place_name = $("#guarantor_work_place_name").val();
        // 勤務先フリガナ
        let guarantor_work_place_ruby = $("#guarantor_work_place_ruby").val();
        // 勤務先郵便番号
        let guarantor_work_place_post_number = $("#guarantor_work_place_post_number").val();
        // 勤務先住所
        let guarantor_work_place_address = $("#guarantor_work_place_address").val();
        // 勤務先電話番号
        let guarantor_work_place_tel = $("#guarantor_work_place_tel").val();
        // 業種
        let guarantor_work_place_Industry = $("#guarantor_work_place_Industry").val();
        // 職種
        let guarantor_work_place_occupation = $("#guarantor_work_place_occupation").val();
        // 雇用形態
        let guarantor_status = $("#guarantor_status").val();
        // 勤続年数
        let guarantor_work_place_years = $("#guarantor_work_place_years").val();
        // 年収
        let guarantor_annual_income = $("#guarantor_annual_income").val();
        // 健康保険
        let guarantor_insurance_type_id = $("#guarantor_insurance_type_id").val();

        /**
         * 画像アップロード
         */
        // 画像id
        let file_img_id = $("#img_id").val();
        // 画パスを取得
        let file_img = $('#file_img').prop('files')[0];
        console.log("file_img" + file_img);

        // 種別
        let file_img_type = $("#file_img_type").val();
        // 備考
        let file_img_type_textarea = $("#file_img_type_textarea").val();

        var sendData = new FormData();
        /**
         * その他
         */
        // URLからのフラグ urlからの場合:true/通常からの場合:null
        sendData.append('application_Flag', application_Flag);
        // session_id
        sendData.append('session_id', session_id);
        // url_send_flag
        sendData.append('url_send_flag', url_send_flag);
        
        /**
         * 画像
         */
        sendData.append('file_img_id', file_img_id);
        sendData.append('file_img', file_img);
        sendData.append('file_img_type', file_img_type);
        sendData.append('file_img_type_textarea', file_img_type_textarea);
        /**
         * 賃借人
         */
        sendData.append('contract_id', contract_id);
        sendData.append('contract_name', contract_name);
        sendData.append('contract_ruby', contract_ruby);
        sendData.append('contract_post_number', contract_post_number);
        sendData.append('contract_address', contract_address);
        sendData.append('contract_sex_id', contract_sex_id);
        sendData.append('contract_birthday', contract_birthday);
        sendData.append('contract_age', contract_age);
        sendData.append('contract_home_tel', contract_home_tel);
        sendData.append('contract_mobile_tel', contract_mobile_tel);
        sendData.append('contract_work_place_name', contract_work_place_name);
        sendData.append('contract_work_place_ruby', contract_work_place_ruby);
        sendData.append('contract_work_place_post_number', contract_work_place_post_number);
        sendData.append('contract_work_place_address', contract_work_place_address);
        sendData.append('contract_work_place_tel', contract_work_place_tel);
        sendData.append('contract_work_place_Industry', contract_work_place_Industry);
        sendData.append('contract_work_place_occupation', contract_work_place_occupation);
        sendData.append('contract_employment_status', contract_employment_status);
        sendData.append('contract_work_place_years', contract_work_place_years);
        sendData.append('contract_annual_income', contract_annual_income);
        sendData.append('contracts_insurance_type_id', contracts_insurance_type_id);
        /**
         * 不動産業者
         */
        sendData.append('contract_progress', contract_progress);
        sendData.append('application_form_id', application_form_id);
        sendData.append('broker_name', broker_name);
        sendData.append('broker_tel', broker_tel);
        sendData.append('broker_mail', broker_mail);
        sendData.append('manager_name', manager_name);
        sendData.append('application_type_id', application_type_id);
        sendData.append('application_use_id', application_use_id);
        sendData.append('contract_start_date', contract_start_date);
        sendData.append('real_estate_name', real_estate_name);
        sendData.append('real_estate_ruby', real_estate_ruby);
        sendData.append('room_name', room_name);
        sendData.append('post_number', post_number);
        sendData.append('address', address);
        sendData.append('pet_bleeding_name', pet_bleeding_name);
        sendData.append('pet_kind_name', pet_kind_name);
        sendData.append('bicycle_parking_number', bicycle_parking_number);
        sendData.append('car_parking_number', car_parking_number);
        sendData.append('deposit_money', deposit_money);
        sendData.append('deposit_refund', deposit_refund);
        sendData.append('security_deposit', security_deposit);
        sendData.append('key_money', key_money);
        sendData.append('rent_fee', rent_fee);
        sendData.append('service_fee', service_fee);
        sendData.append('water_fee', water_fee);
        sendData.append('ohter_fee', ohter_fee);
        sendData.append('total_rent_fee', total_rent_fee);
        /**
         * 同居人
         */
        sendData.append('housemate_id', housemate_id);
        sendData.append('housemate_name', housemate_name);
        sendData.append('housemate_ruby', housemate_ruby);
        sendData.append('housemates_sex_id', housemates_sex_id);
        sendData.append('housemate_relationship_id', housemate_relationship_id);
        sendData.append('housemate_age', housemate_age);
        sendData.append('housemate_birthday', housemate_birthday);
        sendData.append('housemate_post_number', housemate_post_number);
        sendData.append('housemate_address', housemate_address);
        sendData.append('housemate_home_tel', housemate_home_tel);
        sendData.append('housemate_mobile_tel', housemate_mobile_tel);
        /**
         * 緊急連絡先
         */
        sendData.append('emergency_contact_id', emergency_contact_id);
        sendData.append('emergency_contacts_name', emergency_contacts_name);
        sendData.append('emergency_contacts_ruby', emergency_contacts_ruby);
        sendData.append('emergency_contacts_sex_id', emergency_contacts_sex_id);
        sendData.append('emergency_contacts_relationships_id', emergency_contacts_relationships_id);
        sendData.append('emergency_contacts_birthday', emergency_contacts_birthday);
        sendData.append('emergency_contract_age', emergency_contract_age);
        sendData.append('emergency_contacts_post_number', emergency_contacts_post_number);
        sendData.append('emergency_contacts_post_address', emergency_contacts_post_address);
        sendData.append('emergency_contacts_home_tel', emergency_contacts_home_tel);
        sendData.append('emergency_contacts_mobile_tel', emergency_contacts_mobile_tel);
        /**
        * 連帯保証人
        */
        sendData.append('guarantor_contracts_id', guarantor_contracts_id);
        sendData.append('guarantor_name', guarantor_name);
        sendData.append('guarantor_ruby', guarantor_ruby);
        sendData.append('guarantor_post_number', guarantor_post_number);
        sendData.append('guarantor_address', guarantor_address);
        sendData.append('guarantor_sex_id', guarantor_sex_id);
        sendData.append('guarantor_birthday', guarantor_birthday);
        sendData.append('guarantor_age', guarantor_age);
        sendData.append('guarantors_relationship_id', guarantors_relationship_id);
        sendData.append('guarantor_home_tel', guarantor_home_tel);
        sendData.append('guarantor_mobile_tel', guarantor_mobile_tel);
        sendData.append('guarantor_work_place_name', guarantor_work_place_name);
        sendData.append('guarantor_work_place_ruby', guarantor_work_place_ruby);
        sendData.append('guarantor_work_place_post_number', guarantor_work_place_post_number);
        sendData.append('guarantor_work_place_address', guarantor_work_place_address);
        sendData.append('guarantor_work_place_tel', guarantor_work_place_tel);
        sendData.append('guarantor_work_place_Industry', guarantor_work_place_Industry);
        sendData.append('guarantor_work_place_occupation', guarantor_work_place_occupation);
        sendData.append('guarantor_status', guarantor_status);
        sendData.append('guarantor_work_place_years', guarantor_work_place_years);
        sendData.append('guarantor_annual_income', guarantor_annual_income);
        sendData.append('guarantor_insurance_type_id', guarantor_insurance_type_id);

        /**
         * ボタン使用不可にする
         */
        $('#btn_edit').prop('disabled',true);
        $('#btn_download').prop('disabled',true);
        $('#btn_url').prop('disabled',true);
        $('#btn_delete').prop('disabled',true);

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
            type: 'post',
            url: 'editEntry',
            dataType: 'json',
            data: sendData,
            /**
             * 画像送信設定
             */
            //ajaxのキャッシュの削除
            cache:false,
            /**
             * dataに指定したオブジェクトをクエリ文字列に変換するかどうかを設定します。
             * 初期値はtrue、自動的に "application/x-www-form-urlencoded" 形式に変換します。
             */
            processData : false,
            contentType : false,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // Errorがない場合=true->画面upload
            if(data.status == true){

                console.log("status:" + data.status);

                // alertの設定
                var options = {
                    title: "登録が完了しました。",
                    icon: "success",
                    buttons: {
                    ok: true
                    }
                };
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    if (val) {
                        // Okボタン処理
                        // URLからの場合、登録完了に画面遷移
                        if(application_Flag == "true"){
                            // 契約id
                            contract_id = data.contract_id;
                            console.log("contract_id:" + data.contract_id);

                            // ユーザid(create_user_id)
                            create_user_id = data.create_user_id;
                            console.log("create_user_id:" + data.create_user_id);
                            
                            // 現在の日付
                            deadline = data.deadline;
                            console.log("deadline:" + data.deadline);
                            
                            // 不動産業者id(application_form_id)
                            application_form_id = data.application_form_id;
                            console.log("application_form_id:" + data.application_form_id);
                            
                            // 認証用URL発行フラグ(url_send_flag)
                            url_send_flag = data.url_send_flag;
                            console.log("url_send_flag:" + data.url_send_flag);

                            // 編集用URLを発行後->登録した時、url_send_flagを1にする
                            // 0 = 編集用URLを発行する
                            if(data.url_send_flag == 0){
                                // 登録完了画面に遷移(パラメーター:contract_id(契約id)、create_user_id(ユーザid)、deadline(期限、契約者メールアドレス))
                                location.href = 'applicationCompleteInit?contract_id=' + contract_id + '&create_user_id=' + create_user_id + '&broker_mail=' + broker_mail　+ '&broker_name=' + broker_name + '&application_form_id=' + application_form_id;
                            }

                            // 1 = 編集用URLを発行しない(画面更新)
                            if(data.url_send_flag == 1){
                                // 画面更新
                                location.reload();
                            }

                        // 通常の登録画面からの場合、Home画面に遷移
                        }else{
                            location.href = 'homeInit';
                        }
                    };
                });
                return false;
            };

            // Errorがある場合=false->メッセージ表示
            if(data.status == false){
                console.log("status:" + data.status);
                console.log("messages:" + data.messages);
                console.log("errorkeys:" + data.errkeys);

                /**
                 * ボタンを使用可能にする
                 */
                $('#btn_edit').prop('disabled',false);
                $('#btn_download').prop('disabled',false);
                $('#btn_url').prop('disabled',false);
                $('#btn_delete').prop('disabled',false);

                /**
                 * formの全要素をerror_Messageを表示に変更
                 * error数だけループ処理
                 */
                    for (let i = 0; i < data.errkeys.length; i++) {
                        //　bladeの各divにclass指定
                    let id_key = "#" + data.errkeys[i];
                    $(id_key).addClass('is-invalid');
                    console.log(id_key);

                    // 表示箇所のMessageのkey取得
                    let msg_key = "#" + data.errkeys[i] + "_error"
                    // error_messageテキスト追加
                    $(msg_key).text(data.messages[i]);
                    $(msg_key).show();
                    console.log(msg_key);
                    };
                return false;
            }
        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });

        // ローディング画面終了の処理
        setTimeout(function(){
            $("#overlay").fadeOut(300);
        },500);
    });
});

// 住所検索
$(function() {

    /**
     * ローディング画面開始の処理
     */
     $(document).ajaxSend(function() {
        $("#overlay").fadeIn(300);　
    });

    $(".btn_zip").on('click', function(e) {
        console.log("btn_zipクリックされています");
        
        e.preventDefault();

        /**
         * 住所検索ボタンのid取得
         */
        var post_number_id = $(this).attr('id');
        console.log(post_number_id);
        
        // 郵便番号初期値:空白
        let post_number = '';

        // 不動産業者
        if(post_number_id == 'real_estate_agent-btn-zip'){
            post_number = $('#post_number').val();
            console.log('不動産の検索ボタンだよ');
        }
        // 賃借人
        if(post_number_id == 'contracts-btn-zip'){
            post_number = $('#contract_post_number').val();
            console.log('賃借人の検索ボタンだよ');
        }
        // 賃借人(郵便番号)
        if(post_number_id == 'contract_work_place-btn-zip'){
            post_number = $('#contract_work_place_post_number').val();
            console.log('賃借人の勤務先の郵便番号の検索ボタンだよ');
        }
        // 同居人
        if(post_number_id == 'housemates-btn-zip'){
            post_number = $('#housemate_post_number').val();
            console.log('同居人の検索ボタンだよ');
        }
        // 緊急連絡先
        if(post_number_id == 'emergency_contacts-btn-zip'){
            post_number = $('#emergency_contacts_post_number').val();
            console.log('緊急連絡先の検索ボタンだよ');
        }
        // 連帯保証人
        if(post_number_id == 'guarantors-btn-zip'){
            post_number = $('#guarantor_post_number').val();
            console.log('連帯保証人の検索ボタンだよ');
        }

        // 郵便番号が空白の場合のプログラム終了
        if(post_number==""){
            return false;
        }

        // url指定
        let zipUrl = "https://zipcloud.ibsnet.co.jp/api/search?zipcode="+ post_number;

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
            type: 'get',
            url: zipUrl,
            // 自身のサイトの場合json,他のサイトの場合jsonp
            dataType: 'jsonp'
           
        // 接続が出来た場合の処理
        }).done(function(data) {
            console.log(data);
            
            /**
             * ローディング画面終了の処理
             */
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

            //取得結果がNGの場合、メッセージを追加する
            if(data.results == null){
                console.log(data.message);
            }

            // 取得結果がOKの場合、ifで入力データをもとに分岐し住所を設定する
            if(data.results !== null){
                // 不動産業者
                if(post_number_id == 'real_estate_agent-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#address').val(address);
                    console.log('不動産の検索ボタンだよ');
                }
                // 賃貸借契約
                if(post_number_id == 'contracts-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#contract_address').val(address);
                    console.log('賃貸借契約の検索ボタンだよ');
                }
                // 勤務先(賃借人)
                if(post_number_id == 'contract_work_place-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#contract_work_place_address').val(address);
                    console.log('賃貸借契約郵便番号の検索ボタンだよ');
                }
                // 同居人
                if(post_number_id == 'housemates-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#housemate_address').val(address);
                    console.log('同居人の検索ボタンだよ');
                }
                // 緊急連絡先
                if(post_number_id == 'emergency_contacts-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#emergency_contacts_post_address').val(address);
                    console.log('緊急連絡先の検索ボタンだよ');
                }
                // 連帯保証人
                if(post_number_id == 'guarantors-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#guarantor_address').val(address);
                    console.log('連帯保証人の検索ボタンだよ');
                }
            }
        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
        
    });
});

// 画像編集(ajdx)
$(function() {

    /**
     * ローディング画面開始の処理
     */
     $(document).ajaxSend(function() {
        $("#overlay").fadeIn(300);　
    });

    $(".btn_img_edit").on('click', function(e) {
        console.log("btn_img_editを通過しています");

        let img_id = $(this).attr('id').split('_')[1];
        console.log(img_id);
        
        e.preventDefault();

        //送信データの設定
        let sendData = {
            "img_id": img_id,
        };
        console.log('sendData:' + sendData);

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
            type: 'post',
            url: 'imgEditInit',
            dataType: 'json',
            data: sendData,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // 種別
            $('#file_img_type').val(data.list_img[0].img_type);
            // テキストエリア
            $('#file_img_type_textarea').val(data.list_img[0].img_memo);
            
        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });

        // ローディング画面終了の処理
        setTimeout(function(){
            $("#overlay").fadeOut(300);
        },500);
    });
});

/**
 * 添付処理の画像をダブルクリック->画像詳細データに値設定->取得
 */
$(function() {
    $("#card-img-top").on('dblclick', function(e) {
        console.log("card-img-top通過しています");

        var img_id = $(this).attr('id');
        console.log(img_id);
    });
});

/**
 * 総額賃料の足算
 */
$(function() {
    $('.money_text').keyup(function() {
        console.log("keyを押されました");

        //家賃
        let rent_fee = $('#rent_fee').val();
        //共益費
        let service_fee = $('#service_fee').val();
        //水道代
        let water_fee = $('#water_fee').val();
        //その他
        let ohter_fee = $('#ohter_fee').val();
        // 合計
        let total_rent_fee = Number(rent_fee) + Number(service_fee) + Number(water_fee) + Number(ohter_fee);
        console.log(total_rent_fee);
        $("#total_rent_fee").val(total_rent_fee);
    });
});

/**
 * 削除ボタンの処理(全体)
 */
$(function() {

    // ローディング画面開始の処理
    $(document).ajaxSend(function() {
        $("#overlay").fadeIn(300);　
    });

    $("#btn_delete").on('click', function(e) {
        console.log("btn_deleteボタンがクリックされています.");

        e.preventDefault();

        /**
         * id取得
         */
        let contract_id = $("#contract_id").val();
        console.log("contract_id:" + contract_id);

        /**
         * アラートの表示
         */
        var options = {
        title: "削除しますか？",
        icon: "warning",
        buttons: {
        cancel: "Cancel",
        ok: true
        }
        };

        swal(options)
        // then() メソッドを使えばクリックした時の値が取れます
        .then(function(val) {
            // OKボタンが押された時の処理
            if (val == null) {
            console.log("NG");
            return false;

            // NGボタンが押された時の処理 
            } else {
            console.log("OKボタンをクリックしました");
            
            // 送信用データ設定
            let sendData = {
                "contract_id": contract_id,
            };
            console.log(sendData);

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $.ajax({
                type: 'post',
                url: 'deleteEntry',
                dataType: 'json',
                data: sendData,
            
            // 接続処理
            }).done(function(data) {

                /**
                 * ローディング画面終了の処理
                 */
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);

                console.log("ajax通信後のstatus" + data.status);

                // controller側でtrueの処理
                if(data.status == true){
                // alertの設定
                var options = {
                    title: "削除が完了しました。",
                    icon: "success",
                    buttons: {
                        ok: true
                    }
                    };
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    if (val) {
                        // Okボタン処理
                        location.href = 'homeInit'
                    }
                });
                };
            
                // ajax接続失敗の時の処理
                }).fail(function(jqXHR, textStatus, errorThrown) {

                    setTimeout(function(){
                        $("#overlay").fadeOut(300);
                    },500);

                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            }//swalのthen
        });
    });
});

/**
 * 削除ボタンの処理(個別)
 */
$(function() {

    // ローディング画面開始の処理
    $(document).ajaxSend(function() {
        $("#overlay").fadeIn(300);　
    });

    $(".btn_delete_detail").on('click', function(e) {

        console.log("btn_delete_detailボタンがクリックされています.");

        e.preventDefault();

        /**
         * id取得
         */
        var img_id = $(this).attr('id').split('_')[1];
        console.log("img_id:" + img_id);

        /**
         * アラートの表示
         */
        var options = {
            title: "削除しますか？",
            icon: "warning",
            buttons: {
                cancel: "Cancel",
                ok: true
            }
        };

        swal(options)
        // then() メソッドを使えばクリックした時の値が取れます
        .then(function(val) {

            // NGボタンが押された時の処理
            if (val == null) {

                console.log("NG");
                return false;

            // OKボタンが押された時の処理
            } else {
                console.log("OKボタンをクリックしました");
            
                // 送信用データ設定
                let sendData = {
                    "img_id": img_id,
                };

                console.log(sendData);

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                $.ajax({
                    type: 'post',
                    url: 'deleteEntryDetail',
                    dataType: 'json',
                    data: sendData,
                
                // 接続処理
                }).done(function(data) {

                    /**
                     * ローディング画面終了の処理
                     */
                    setTimeout(function(){
                        $("#overlay").fadeOut(300);
                    },500);

                    console.log("ajax通信後のstatus:" + data.status);

                    // controller側でtrueの処理
                    if(data.status == true){
                        
                    // alertの設定
                    var options = {
                        title: "削除が完了しました。",
                        icon: "success",
                        buttons: {
                            ok: true
                        }
                    };

                    // then() OKを押した時の処理
                    swal(options)
                        .then(function(val) {
                        if (val) {
                            // Okボタン処理
                            location.href = 'homeInit'
                        }
                    });
                };
                
                // ajax接続失敗の時の処理
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            }//swalのthen
        });
    });
});

$(function() {
    $(".dropdown-toggle").on('click', function(e) {
        // ドロップダウンメニュー
        //.dropdown-menuを一旦隠す
        $('.menu').find('.dropdown-menu').hide();
        //.menuをhoverした場合
        $('.menu').hover(function(){
        //.dropdown-menuをslideDown    
        $(".dropdown-menu:not(:animated)", this).slideDown();
        //hoverが外れた場合  
            }, function(){
        //.dropdown-menuをslideUp
        $(".dropdown-menu",this).slideUp();
        });
    });
});

