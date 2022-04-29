$(function() {
    /**
     * 商号(コンボボックス変更の処理)
     */
    $("#company_license_id").change(function(e) {

        console.log('コンボボックス変更の処理');

        e.preventDefault();
        
        // ローディング画面
        $("#overlay").fadeIn(300);

        // id
        let company_licence_id = $("#company_licence_id").val();
        console.log(company_licence_id);

        // 送信データ設定
        var sendData = new FormData();
        
        sendData.append('company_licence_id', company_licence_id);
        
        // ajaxヘッダー
        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            
        });

        $.ajax({

            type: 'post',
            url: 'backChangeCompanyLicense',
            dataType: 'json',
            data: sendData,
            cache:false,
            processData : false,
            contentType : false,

        // 接続が出来た場合の処理
        }).done(function(data) {

            
            // 代表者
            console.log("company_license_representative:" + data.company_license_list['company_license_representative']);
            $('#company_license_representative').val(data.company_license_list['company_license_representative']);

            // 所在地
            console.log("company_license_address:" + data.company_license_list['company_license_address']);
            $('#company_license_address').val(data.company_license_list['company_license_address']);         
            
            // tel
            console.log("company_license_tel:" + data.company_license_list['company_license_tel']);
            $('#company_license_tel').val(data.company_license_list['company_license_tel']);  

            // fax
            console.log("company_license_fax:" + data.company_license_list['company_license_fax']);
            $('#company_license_fax').val(data.company_license_list['company_license_fax']); 

            // 免許番号
            console.log("company_license_number:" + data.company_license_list['company_license_number']);
            $('#company_license_number').val(data.company_license_list['company_license_number']);

            // 免許番号
            console.log("company_license_span:" + data.company_license_list['company_license_span']);
            $('#company_license_span').val(data.company_license_list['company_license_span']);

            // 取扱店
            console.log("company_nick_name:" + data.company_license_list['company_nick_name']);
            $('#company_nick_name').val(data.company_license_list['company_nick_name']);

            // 所在地
            console.log("company_nick_address:" + data.company_license_list['company_nick_address']);
            $('#company_nick_address').val(data.company_license_list['company_nick_address']);

            // 法務局
            console.log("guaranty_association_name:" + data.company_license_list['guaranty_association_name']);
            $('#guaranty_association_name').val(data.company_license_list['guaranty_association_name']);

            // 保証協会
            console.log("legal_place_name:" + data.company_license_list['legal_place_name']);
            $('#legal_place_name').val(data.company_license_list['legal_place_name']);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
        });
    });

    /**
     * 宅地建物取引士(コンボボックス変更の処理)
     */
    $("#user_license_id").change(function(e) {

        console.log('コンボボックス変更の処理');

        e.preventDefault();
        
        // ローディング画面
        $("#overlay").fadeIn(300);

        // id
        let user_license_id = $("#user_license_id").val();
        console.log(user_license_id);

        // 送信データ設定
        var sendData = new FormData();
        
        sendData.append('user_license_id', user_license_id);
        
        // ajaxヘッダー
        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            
        });

        $.ajax({

            type: 'post',
            url: 'backChangeUserLicense',
            dataType: 'json',
            data: sendData,
            cache:false,
            processData : false,
            contentType : false,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // 登録番号
            console.log("user_license_number:" + data.user_license_list['user_license_number']);
            $('#user_license_number').val(data.user_license_list['user_license_number']);         

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
        });
    });

    /**
     * 住所検索
     */
    $(".btn_zip").on('click', function(e) {

        console.log("btn_zipクリックされています");

        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);

        // 住所検索ボタンのid取得
        var post_number_id = $(this).attr('id');
        console.log(post_number_id);

        // 郵便番号初期値
        let post_number = '';

        // 不動産
        if(post_number_id == 'real_estate-btn-zip'){
            post_number = $('#real_estate_post_number').val();
            console.log('不動産の郵便番号');
        }

        // 家主
        if(post_number_id == 'owner-btn-zip'){
            post_number = $('#owner_post_number').val();
            console.log('家主の郵便番号');
        }

        // 管理の委託先(共有)
        if(post_number_id == 'm_share-btn-zip'){
            post_number = $('#m_share_post_number').val();
            console.log('管理の委託先（共有）の郵便番号');
        }

        // 管理の委託先(専有)
        if(post_number_id == 'm_own-btn-zip'){
            post_number = $('#m_own_post_number').val();
            console.log('管理の委託先（専有）の郵便番号');
        }

        // 緊急連絡先
        if(post_number_id == 'emergency-btn-zip'){
            post_number = $('#emergency_post_number').val();
            console.log('緊急連絡先の検索ボタンだよ');
        }

        // 連帯保証人
        if(post_number_id == 'guarantors-btn-zip'){
            post_number = $('#guarantor_post_number').val();
            console.log('連帯保証人の検索ボタンだよ');
        }

        // 連帯保証人(勤務先)
        if(post_number_id == 'guarantor_business-btn-zip'){
            post_number = $('#guarantor_business_post_number').val();
            console.log('連帯保証人勤務先の検索ボタンだよ');
        }

        // 郵便番号が空白の場合のプログラム終了
        if(post_number==""){

            // ローディング画面停止
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

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
            
            // ローディング画面終了の処理
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
                if(post_number_id == 'real_estate-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#real_estate_address').val(address);
                    console.log('不動産の郵便番号検索ボタン');
                }

                // 家主
                if(post_number_id == 'owner-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#owner_address').val(address);
                    console.log('家主の検索ボタン');
                }

                // 管理委託先(共有)
                if(post_number_id == 'm_share-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#m_share_address').val(address);
                    console.log('管理委託先(共有)検索ボタン');
                }

                // 管理委託先(専有)
                if(post_number_id == 'm_own-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#m_own_address').val(address);
                    console.log('管理委託先(専有)検索ボタン');
                }

                // 緊急連絡先
                if(post_number_id == 'emergency-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#emergency_address').val(address);
                    console.log('緊急連絡先の検索ボタン');
                }

                // 連帯保証人
                if(post_number_id == 'guarantors-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#guarantor_address').val(address);
                    console.log('連帯保証人の検索ボタン');
                }

                // 連帯保証人(勤務先)
                if(post_number_id == 'guarantor_business-btn-zip'){
                    let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                    console.log(address);
                    $('#guarantor_business_address').val(address);
                    console.log('連帯保証人の検索ボタン');
                }

            }
        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

        });

    });

    /**
     * 登録
     */
    $("#btn_edit").on('click', function(e) {

        console.log("btn_editクリックされています");

        e.preventDefault();

        // タブの色初期化
        $('#nav-real_estate-tab').removeClass('bg_tab_error');
        $('#nav-contract_detail-tab').removeClass('bg_tab_error');
        $('#nav-company-tab').removeClass('bg_tab_error');
        $('#nav-law-tab').removeClass('bg_tab_error');
        $('#nav-registry-tab').removeClass('bg_tab_error');
        $('#nav-fee_detail-tab').removeClass('bg_tab_error');
        $('#nav-real_estate_facilities-tab').removeClass('bg_tab_error');
        $('#nav-contract_span-tab').removeClass('bg_tab_error');
        $('#nav-use_limitation-tab').removeClass('bg_tab_error');
        $('#nav-contract_cancel-tab').removeClass('bg_tab_error');
        $('#nav-indemnity_fee-tab').removeClass('bg_tab_error');
        $('#nav-bank-tab').removeClass('bg_tab_error');
        $('#nav-other-tab').removeClass('bg_tab_error');
        
        // ローディング画面
        $("#overlay").fadeIn(300);

        // バリデーション
        // formの値数を取得
        let forms = $('.needs-validation');
        console.log('forms.length:' + forms[0].length);

        // validationフラグ初期値
        let v_check = true;

        // // formの項目数ループ処理
        for (let i = 0; i < forms[0].length; i++) {

            // タグ名、Id名取得
            let form = forms[0][i];
            console.log('from:'+ form);

            // タグ名を取得 input or button
            let tag = $(form).prop("tagName");
            console.log('tag:'+ tag);

            // 各項目のid取得
            let f_id = $(form).prop("id");
            console.log('id:'+ f_id);
            
            // form内のbuttonは通過
            if (tag == 'BUTTON') {
                continue;
            }

            // 必須ではない場合、以降を処理せず次のレコードに行く
            let required = $(form).attr("required");

            console.log('required:' + required);

            if (required !== 'required') {
                continue;
            }

            // 必須で値が空白の場合の処理
            let val = $(form).val();

            console.log('value:'+ val);

            if (val === '') {

                // エラーメッセージのidを作成
                let f_id_error = f_id + '_error';

                let error_message_id = $('#' + f_id_error).attr('class');
                
                // タブを赤色に変更する(引数:エラーメッセージのid)
                tabError(error_message_id);

                // blade側のformタグにwas-validatedを追加
                $(forms).addClass("was-validated");
                v_check = false;

            }
        }

        // チェック=falseの場合プログラム終了
        console.log(v_check);
        if (v_check === false) {

            // ローディング画面停止
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

            return false;
        }

        /**
         * id
         */
        // 契約詳細id
        let contract_detail_id = $("#contract_detail_id").val();

        // 申込id
        let application_id = $("#application_id").val();

        // 特約事項id
        let special_contract_detail_id = $("#special_contract_detail_id").val(); 
        
        console.log('special_contract_detail_id:' + special_contract_detail_id);

        /**
         * 進捗状況
         */
        // 進捗状況
        let contract_detail_progress_id = $("#contract_detail_progress_id").val();
        console.log('contract_detail_progress_id:' + contract_detail_progress_id);

        // 管理番号
        let admin_number = $("#admin_number").val();

        /**
         * 物件概要
         */
        // 物件名
        let real_estate_name = $("#real_estate_name").val();

        // 号室
        let room_name = $("#room_name").val();

        // 契約面積
        let room_size = $("#room_size").val();

        // 郵便番号
        let real_estate_post_number = $("#real_estate_post_number").val();

        // 住所
        let real_estate_address = $("#real_estate_address").val();

        // 構造
        let real_estate_structure_id = $("#real_estate_structure_id").val();
        console.log('real_estate_structure_id:' + real_estate_structure_id);

        // 地上階数
        let real_estate_floor = $("#real_estate_floor").val();

        // 築年月日
        let real_estate_age = $("#real_estate_age").val();

        // 間取数
        let room_layout_name = $("#room_layout_name").val();

        // 間取種別
        let room_layout_id = $("#room_layout_id").val();
        console.log('room_layout_id:' + room_layout_id);

        // 家主名
        let owner_name = $("#owner_name").val();

        // 郵便番号
        let owner_post_number = $("#owner_post_number").val();

        // 住所
        let owner_address = $("#owner_address").val();

        // 管理委託先(共有)
        let m_share_name = $("#m_share_name").val();

        // 郵便番号
        let m_share_post_number = $("#m_share_post_number").val();

        // 住所
        let m_share_address = $("#m_share_address").val();

        // Tel
        let m_share_tel = $("#m_share_tel").val();

        // 管理委託先(専有)
        let m_own_name = $("#m_own_name").val();

        // 郵便番号
        let m_own_post_number = $("#m_own_post_number").val();

        // 住所
        let m_own_address = $("#m_own_address").val();

        // Tel
        let m_own_tel = $("#m_own_tel").val();

        /**
         * 契約者・同居人
         */
        // 契約者名
        let contract_name = $("#contract_name").val();

        // 契約者カナ
        let contract_ruby = $("#contract_ruby").val();

        // 生年月日
        let contract_date = $("#contract_date").val();

        // Tel
        let contract_tel = $("#contract_tel").val();

        /**
         * 商号
         */
        // 免許id
        let company_license_id = $("#company_license_id").val();

        // 宅建取引士id
        let user_license_id = $("#user_license_id").val();

        // 宅建取引士名
        let user_license_name = $('#user_license_id option:selected').text();

        // 宅建取引士登録番号
        let user_license_number = $("#user_license_number").val();

        // 担当者
        let manager_name = $("#manager_name").val();

        /**
         * 法令
         */
        // 石綿調査記録の有無
        let report_asbestos = $("#report_asbestos").val();

        // 耐震診断
        let report_earthquake = $("#report_earthquake").val();

        // 造成宅地防災区域
        let land_disaster_prevention_area = $("#land_disaster_prevention_area").val();
        
        // 津波災害警戒区域
        let tsunami_disaster_alert_area = $("#tsunami_disaster_alert_area").val();

        // 土砂災害区域
        let sediment_disaster_area = $("#sediment_disaster_area").val();

        // ハザードマップ有無
        let hazard_map = $("#hazard_map").val();

        // 洪水
        let warning_flood = $("#warning_flood").val();

        // 高潮
        let warning_storm_surge = $("#warning_storm_surge").val();

        // 雨水・出水
        let warning_rain_water = $("#warning_rain_water").val();

        /**
         * 登記事項
         */
        // 所有権
        let regi_name = $("#regi_name").val();

        // 権利に関する事項
        let regi_right = $("#regi_right").val();

        // 抵当権・根抵当権
        let regi_mortgage_id = $("#regi_mortgage_id").val();

        // 所有者と貸主が違う場合
        let regi_difference_owner = $("#regi_difference_owner").val();

        // 未完成物件の時
        let completion_date = $("#completion_date").val();

        /**
         * 授受される金額
         */
        // 敷金
        let security_fee = $("#security_fee").val();

        // 礼金
        let key_fee = $("#key_fee").val();
        
        // 賃料
        let rent_fee = $("#rent_fee").val();

        // 共益費
        let service_fee = $("#service_fee").val();

        // 水道代
        let water_fee = $("#water_fee").val();

        // その他
        let ohter_fee = $("#ohter_fee").val();

        // 駐輪代
        let bicycle_fee = $("#bicycle_fee").val();

        // 月額合計
        let total_fee = $("#total_fee").val();

        // 駐車場保証金
        let car_deposit_fee = $("#car_deposit_fee").val();

        // 駐車場代
        let car_fee = $("#car_fee").val();

        // 住宅保険料
        let fire_insurance_fee = $("#fire_insurance_fee").val();

        // 住宅保険期間
        let fire_insurance_span = $("#fire_insurance_span").val();

        // 保証会社費用
        let guarantee_fee = $("#guarantee_fee").val();

        // 保証会社更新期間
        let guarantee_update_span = $("#guarantee_update_span").val();

        // 保証会社更新料
        let guarantee_update_fee = $("#guarantee_update_fee").val();

        // 安心サポート
        let support_fee = $("#support_fee").val();

        // 防虫抗菌代
        let disinfect_fee = $("#disinfect_fee").val();

        // その他項目①
        let other_name1 = $("#other_name1").val();

        // その他費用①
        let other_fee1 = $("#other_fee1").val();

        // その他項目②
        let other_name2 = $("#other_name2").val();

        // その他費用②
        let other_fee2 = $("#other_fee2").val();

        // 仲介手数料(駐車場)
        let car_broker_fee = $("#car_broker_fee").val();

        // 仲介手数料
        let broker_fee = $("#broker_fee").val();

        // 預り金の日付
        let today_account_fee_date = $("#today_account_fee_date").val();

        // 預り金
        let today_account_fee = $("#today_account_fee").val();

        // 決済予定日
        let payment_date = $("#payment_date").val();

        // 敷金の清算に関する事項
        let introduction_security_fee = $("#introduction_security_fee").val();

        // 礼金の清算に関する事項
        let introduction_key_fee = $("#introduction_key_fee").val();

        // 預金の保全措置
        let keep_account_fee = $("#keep_account_fee").val();

        // 金銭賃借の斡旋
        let introduction_fee = $("#introduction_fee").val();

        /**
         * 設備状況
         */
        // 飲用水
        let water = $("#water").val();

        // 飲用水種別
        let water_type_name = $("#water_type_name").val();

        // ガス
        let gas = $("#gas").val();

        // ガス種別
        let gas_type_name = $("#gas_type_name").val();

        // 電気
        let electricity = $("#electricity").val();

        // 電気種別
        let electricity_type_name = $("#electricity_type_name").val();

        // 排水
        let waste_water = $("#waste_water").val();

        // 排水種別
        let waste_water_name = $("#waste_water_name").val();

        // 台所
        let kitchen = $("#kitchen").val();

        // 台所種別
        let kitchen_exclusive_type_id = $("#kitchen_exclusive_type_id").val();

        // コンロ
        let cooking_stove = $("#cooking_stove").val();

        // コンロ種別
        let cooking_stove_exclusive_type_id = $("#cooking_stove_exclusive_type_id").val();

        // 浴室
        let bath = $("#bath").val();

        // 浴室種別
        let bath_exclusive_type_id = $("#bath_exclusive_type_id").val();

        // トイレ
        let toilet = $("#toilet").val();

        // トイレ種別
        let toilet_exclusive_type_id = $("#toilet_exclusive_type_id").val();

        // 給湯器
        let water_heater = $("#water_heater").val();

        // 給湯器種別
        let water_heater_exclusive_type_id = $("#water_heater_exclusive_type_id").val();

        // 冷暖房設備
        let air_conditioner = $("#air_conditioner").val();

        // 冷暖房設備台数
        let air_conditioner_exclusive_type_name = $("#air_conditioner_exclusive_type_name").val();

        // エレベーター
        let elevator = $("#elevator").val();

        // エレベーター台数
        let elevator_exclusive_type_name = $("#elevator_exclusive_type_name").val();

        /**
         * 契約期間
         */
        let contract_start_date = $("#contract_start_date").val();

        let contract_end_date = $("#contract_end_date").val();

        let contract_update_span = $("#contract_update_span").val();

        let contract_update_item = $("#contract_update_item").val();

        let daily_calculation = $("#daily_calculation").val();
        

        /**
         * 用途の制限
         */
        let limit_use_id = $("#limit_use_id").val();

        let limit_type_name = $("#limit_type_name").val();

        /**
         * 契約の解約及び解除
         */
        let announce_cancel_date = $("#announce_cancel_date").val();

        let soon_cancel_date = $("#soon_cancel_date").val();

        let cancel_fee_count = $("#cancel_fee_count").val();

        let cancel_contract_document = $("#cancel_contract_document").val();

        let remove_contract_document = $("#remove_contract_document").val();

        /**
         * 損害賠償・違約金・免責
         */
        let penalty_fee = $("#penalty_fee").val();

        let penalty_fee_late_document = $("#penalty_fee_late_document").val();

        let claim_fee_document = $("#claim_fee_document").val();

        let fix_document = $("#fix_document").val();

        let recovery_document = $("#recovery_document").val();

        /**
         * 家賃振込先
         */
        let bank_id = $("#bank_id").val();

        let bank_name = $("#bank_name").val();

        let bank_branch_name = $("#bank_branch_name").val();

        let bank_type_id = $("#bank_type_id").val();

        let bank_number = $("#bank_number").val();

        let bank_account_name = $("#bank_account_name").val();

        let rent_fee_payment_date = $("#rent_fee_payment_date").val();

        /**
         * その他
         */
        let trade_type_id = $("#trade_type_id").val();

        let mail_box_number = $("#mail_box_number").val();

        let guarantor_need_id = $("#guarantor_need_id").val();
        console.log('guarantor_need_id' + guarantor_need_id);

        let guarantor_max_payment = $("#guarantor_max_payment").val();
        console.log('guarantor_max_payment:' + guarantor_max_payment);
        
        
        /**
         * 特約事項
         */
        let textarea_checked = $("#textarea_checked").val();

        // 送信データインスタンス化
        var sendData = new FormData();
        
        /**
         * 進捗状況
         */
        sendData.append('contract_detail_progress_id', contract_detail_progress_id);

        sendData.append('admin_number', admin_number);
        

        /**
         * 物件概要
         */
        sendData.append('real_estate_name', real_estate_name);
        sendData.append('room_name', room_name);
        sendData.append('room_size', room_size);
        sendData.append('real_estate_post_number', real_estate_post_number);
        sendData.append('real_estate_address', real_estate_address);
        sendData.append('real_estate_structure_id', real_estate_structure_id);
        sendData.append('real_estate_floor', real_estate_floor);
        sendData.append('real_estate_age', real_estate_age);
        sendData.append('room_layout_name', room_layout_name);
        sendData.append('room_layout_id', room_layout_id);
        sendData.append('owner_name', owner_name);
        sendData.append('owner_post_number', owner_post_number);
        sendData.append('owner_address', owner_address);
        sendData.append('m_share_name', m_share_name);
        sendData.append('m_share_post_number', m_share_post_number);
        sendData.append('m_share_address', m_share_address);
        sendData.append('m_share_tel', m_share_tel);
        sendData.append('m_own_name', m_own_name);
        sendData.append('m_own_post_number', m_own_post_number);
        sendData.append('m_own_address', m_own_address);
        sendData.append('m_own_tel', m_own_tel);

        /**
         * 契約者・同居人
         */
        sendData.append('contract_name', contract_name);
        sendData.append('contract_ruby', contract_ruby);
        sendData.append('contract_date', contract_date);
        sendData.append('contract_tel', contract_tel);

        /**
         * 商号
         */
        sendData.append('company_license_id', company_license_id);
        sendData.append('user_license_id', user_license_id);
        sendData.append('user_license_name', user_license_name);
        sendData.append('user_license_number', user_license_number);
        sendData.append('manager_name', manager_name);

        /**
         * 法令関係
         */
        sendData.append('report_asbestos', report_asbestos);
        sendData.append('report_earthquake', report_earthquake);
        sendData.append('land_disaster_prevention_area', land_disaster_prevention_area);
        sendData.append('tsunami_disaster_alert_area', tsunami_disaster_alert_area);
        sendData.append('sediment_disaster_area', sediment_disaster_area);
        sendData.append('hazard_map', hazard_map);
        sendData.append('warning_flood', warning_flood);
        sendData.append('warning_storm_surge', warning_storm_surge);
        sendData.append('warning_rain_water', warning_rain_water);

        /**
         * 登記事項
         */
        sendData.append('regi_name', regi_name);
        sendData.append('regi_right', regi_right);
        sendData.append('regi_mortgage_id', regi_mortgage_id);
        sendData.append('regi_difference_owner', regi_difference_owner);
        sendData.append('completion_date', completion_date);

        /**
         * 授受される金額
         */
        sendData.append('security_fee', security_fee);
        sendData.append('key_fee', key_fee);
        sendData.append('rent_fee', rent_fee);
        sendData.append('service_fee', service_fee);
        sendData.append('water_fee', water_fee);
        sendData.append('ohter_fee', ohter_fee);
        sendData.append('bicycle_fee', bicycle_fee);
        sendData.append('total_fee', total_fee);
        sendData.append('car_deposit_fee', car_deposit_fee);
        sendData.append('car_fee', car_fee);
        sendData.append('fire_insurance_fee', fire_insurance_fee);
        sendData.append('fire_insurance_span', fire_insurance_span);
        sendData.append('guarantee_fee', guarantee_fee);
        sendData.append('guarantee_update_span', guarantee_update_span);
        sendData.append('guarantee_update_fee', guarantee_update_fee);
        sendData.append('support_fee', support_fee);
        sendData.append('disinfect_fee', disinfect_fee);
        sendData.append('other_name1', other_name1);
        sendData.append('other_fee1', other_fee1);
        sendData.append('other_name2', other_name2);
        sendData.append('other_fee2', other_fee2);
        sendData.append('car_broker_fee', car_broker_fee);
        sendData.append('broker_fee', broker_fee);
        sendData.append('today_account_fee_date', today_account_fee_date);
        sendData.append('today_account_fee', today_account_fee);
        sendData.append('payment_date', payment_date);
        sendData.append('introduction_security_fee', introduction_security_fee);
        sendData.append('introduction_key_fee', introduction_key_fee);
        sendData.append('keep_account_fee', keep_account_fee);
        sendData.append('introduction_fee', introduction_fee);

        /**
         * 設備状況
         */
        sendData.append('water', water);
        sendData.append('water_type_name', water_type_name);
        sendData.append('gas', gas);
        sendData.append('gas_type_name', gas_type_name);
        sendData.append('electricity', electricity);
        sendData.append('electricity_type_name', electricity_type_name);
        sendData.append('waste_water', waste_water);
        sendData.append('waste_water_name', waste_water_name);
        sendData.append('kitchen', kitchen);
        sendData.append('kitchen_exclusive_type_id', kitchen_exclusive_type_id);
        sendData.append('cooking_stove', cooking_stove);
        sendData.append('cooking_stove_exclusive_type_id', cooking_stove_exclusive_type_id);
        sendData.append('bath', bath);
        sendData.append('bath_exclusive_type_id', bath_exclusive_type_id);
        sendData.append('toilet', toilet);
        sendData.append('toilet_exclusive_type_id', toilet_exclusive_type_id);
        sendData.append('water_heater', water_heater);
        sendData.append('water_heater_exclusive_type_id', water_heater_exclusive_type_id);
        sendData.append('air_conditioner', air_conditioner);
        sendData.append('air_conditioner_exclusive_type_name', air_conditioner_exclusive_type_name);
        sendData.append('elevator', elevator);
        sendData.append('elevator_exclusive_type_name', elevator_exclusive_type_name);
        
        /**
         * 契約期間
         */
        sendData.append('contract_start_date', contract_start_date);
        sendData.append('contract_end_date', contract_end_date);
        sendData.append('contract_update_span', contract_update_span);
        sendData.append('contract_update_item', contract_update_item);
        sendData.append('daily_calculation', daily_calculation);
        
        /**
         * 用途・利用の制限
         */
        sendData.append('limit_use_id', limit_use_id);
        sendData.append('limit_type_name', limit_type_name);

        /**
         * 契約解約及び解除
         */
        sendData.append('announce_cancel_date', announce_cancel_date);
        sendData.append('soon_cancel_date', soon_cancel_date);
        sendData.append('cancel_fee_count', cancel_fee_count);
        sendData.append('cancel_contract_document', cancel_contract_document);
        sendData.append('remove_contract_document', remove_contract_document);

        /**
         * 損害賠償及び違約金
         */
        sendData.append('penalty_fee', penalty_fee);
        sendData.append('penalty_fee_late_document', penalty_fee_late_document);
        sendData.append('claim_fee_document', claim_fee_document);
        sendData.append('fix_document', fix_document);
        sendData.append('recovery_document', recovery_document);
        
        /**
         * 家賃振込先
         */
        sendData.append('bank_id', bank_id);
        sendData.append('bank_name', bank_name);
        sendData.append('bank_branch_name', bank_branch_name);
        sendData.append('bank_type_id', bank_type_id);
        sendData.append('bank_number', bank_number);
        sendData.append('bank_account_name', bank_account_name);
        sendData.append('rent_fee_payment_date', rent_fee_payment_date);

        /**
         * その他
         */
        sendData.append('trade_type_id', trade_type_id);
        sendData.append('mail_box_number', mail_box_number);
        sendData.append('guarantor_need_id', guarantor_need_id);
        sendData.append('guarantor_max_payment', guarantor_max_payment);

        /**
         * id
         */
        // 契約詳細id
        sendData.append('contract_detail_id', contract_detail_id);
        // 申込id
        sendData.append('application_id', application_id);
        // 特約事項id
        sendData.append('special_contract_detail_id', special_contract_detail_id);
        
        /**
         * 特約事項
         */
        sendData.append('textarea_checked', textarea_checked);

        // ajaxヘッダー
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({

            type: 'post',
            url: 'backContractEditEntry',
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

            // trueの処理->申込一覧に遷移
            if(data.status == true){

                console.log("status:" + data.status);

                // alertの設定
                var options = {
                    title: "登録が完了しました。",
                    icon: "success",
                    buttons: {
                    OK: true
                    }
                };
                
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    if (val) {

                        location.href = 'backContractInit';

                    };
                });

                // ローディング画面終了の処理
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);
                
                return false;
            };

             // falseの処理->アラートでエラーメッセージを表示
            if(data.status == false){

                console.log("status:" + data.status);
                console.log("messages:" + data.messages);
                console.log("errorkeys:" + data.errkeys);

                // ローディング画面終了の処理
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);

                // アラートボタン設定
                var options = {
                    title: '入力箇所をご確認ください。',
                    text: '※赤表示の箇所を修正し、再登録をしてください。',
                    icon: 'error',
                    buttons: {
                        OK: 'OK'
                    }
                };
                
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    /**
                     * ダイアログ外をクリックされた場合、nullを返す為
                     * ok,nullの場合の処理を記載
                     */
                    if (val == 'OK' || val == null) {

                        console.log(val);

                        /**
                         * formの全要素をerror_Messageを表示に変更
                         * error数だけループ処理
                         */
                        for (let i = 0; i < data.errkeys.length; i++) {
                            
                            // bladeの各divにclass指定
                            let id_key = "#" + data.errkeys[i];
                            $(id_key).addClass('is-invalid');
                            console.log(id_key);

                            // 表示箇所のMessageのkey取得
                            let msg_key = "#" + data.errkeys[i] + "_error"
                            
                            let error_message_id = $(msg_key).attr('class');

                            tabError(error_message_id);

                            // error_messageテキスト追加
                            $(msg_key).text(data.messages[i]);
                            $(msg_key).show();
                            console.log(msg_key);

                        };

                        return false;
                    };
                });
            }
        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
        });
    });

    /**
     * 削除
     */
    $("#btn_delete").on('click', function(e) {

        console.log('全体削除の処理');

        e.preventDefault();

        // alertの設定
        var options = {
            title: "削除しますか？",
            text: "※一度削除したデータは復元出来ません。",
            icon: 'warning',
            buttons: {
                Cancel: "Cancel", // キャンセルボタン
                OK: true
            }
        };

        // 値取得
        let contract_detail_id = $("#contract_detail_id").val();
        console.log(contract_detail_id);
        
        // then() OKを押した時の処理
        swal(options)
            .then(function(val) {

            if(val == null){

                console.log('キャンセルの処理');

            }

            if (val == "OK") {

                console.log('OKの処理');

                // 送信用データ
                let sendData = {

                    "contract_detail_id": contract_detail_id,
                };

                console.log(sendData);

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                $.ajax({

                    type: 'post',
                    url: 'backContractDeleteEntry',
                    dataType: 'json',
                    data: sendData,
                
                // 接続処理
                }).done(function(data) {

                    console.log('status:' + data.status)

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

                            location.href = 'backContractInit';
                            
                        }
                    });

                // ajax接続失敗の時の処理
                }).fail(function(jqXHR, textStatus, errorThrown) {

                    setTimeout(function(){
                        $("#overlay").fadeOut(300);
                    },500);

                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            };
            // sweetalert
        });

    });

    /**
     * エラー時のタブ背景色の設定
     */
    function tabError(error_message_id){

        // error_message_idがある場合の処理
        if(error_message_id !== undefined){

            // error_message_id内にあるclassのtab名を取得
            let tab_class = error_message_id.split(' ')[0];
            console.log('tab_class:' + tab_class);

            // 進捗状況
            if(tab_class == 'contract_progress-tab'){
                                
                console.log('物件概要の処理');

                $('#nav-contract_progress-tab').addClass('bg_tab_error');
                
            }
            
            // 物件概要
            if(tab_class == 'real_estate-tab'){
                                
                console.log('物件概要の処理');

                $('#nav-real_estate-tab').addClass('bg_tab_error');
                
            }

            // 物件概要
            if(tab_class == 'contract_detail-tab'){
                                
                console.log('契約者の処理');

                $('#nav-contract_detail-tab').addClass('bg_tab_error');
                
            }

            // 商号
            if(tab_class == 'company-tab'){
                                
                console.log('商号の処理');

                $('#nav-company-tab').addClass('bg_tab_error');
                
            }

            // 法令関係
            if(tab_class == 'law-tab'){
                                
                console.log('法令関係の処理');

                $('#nav-law-tab').addClass('bg_tab_error');
                
            }
        
            // 登記事項
            if(tab_class == 'registry-tab'){
                                
                console.log('登記事項の処理');

                $('#nav-registry-tab').addClass('bg_tab_error');
                
            }

            // 授受される金額
            if(tab_class == 'fee_detail-tab'){
                                
                console.log('授受される金額の処理');

                $('#nav-fee_detail-tab').addClass('bg_tab_error');
                
            }

            // 整備・設備状況
            if(tab_class == 'real_estate_facilities-tab'){
                                
                console.log('整備・設備状況の処理');

                $('#nav-real_estate_facilities-tab').addClass('bg_tab_error');
                
            }

            // 契約期間
            if(tab_class == 'contract_span-tab'){
                                
                console.log('契約期間の処理');

                $('#nav-contract_span-tab').addClass('bg_tab_error');
                
            }
    
            // 用途・利用の制限
            if(tab_class == 'use_limitation-tab'){
                                
                console.log('用途・利用の制限の処理');

                $('#nav-use_limitation-tab').addClass('bg_tab_error');
                
            }

            // 契約の解約及び解除
            if(tab_class == 'contract_cancel-tab'){
                                
                console.log('契約の解約及び解除の処理');

                $('#nav-contract_cancel-tab').addClass('bg_tab_error');
                
            }

            // 損害賠償・違約金・免責
            if(tab_class == 'indemnity_fee-tab'){
                                
                console.log('損害賠償・違約金・免責の処理');

                $('#nav-indemnity_fee-tab').addClass('bg_tab_error');
                
            }

            // 家賃振込先
            if(tab_class == 'bank-tab'){
                                
                console.log('家賃振込先の処理');

                $('#nav-bank-tab').addClass('bg_tab_error');
                
            }

            // その他
            if(tab_class == 'other-tab'){
                                
                console.log('その他の処理');

                $('#nav-other-tab').addClass('bg_tab_error');
                
            }

        }
    }

    /**
     * モーダル画面表示(開く時のイベント)
     */
    var myModalEl = document.getElementById('bankModal')
    myModalEl.addEventListener('show.bs.modal', function (event) {
        
        /**
         * 初期化
         */
        $('#bank_table > tbody').empty();

        $('#free_word').val('');
        
        var sendData = new FormData();

        // ajaxヘッダー
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({

            type: 'post',
            url: 'backSearchBank',
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

            console.log(data.bank_list.data[0]);

            // 一覧作成(モーダル画面内)
            setList(data);

        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
        });
        
    })

    /**
     * 一覧作成(モーダル画面内)
     */
    function setList(data){
        for(let i = 0; i < data.bank_list.data.length; i++){

            let bank_info  = data.bank_list.data[i];


            // trタグ生成
            let tr = $('<tr></tr>');

            /**
             * 銀行名
             */
            // tdタグ生成
            let td_name = $('<td></td>');

            // tdタグ内のdiv作成
            let div_name = $('<div>', {
                id:"bankname_" + bank_info['bank_id'],
                class:"db_class",
                text: bank_info['bank_name'],
                style: "cursor:pointer"
            });

            // tdタグにdivを追加
            td_name.append(div_name);

            /**
             * 支店名
             */
            // tdタグ生成
            let td_branch_name = $('<td></td>');

            // tdタグ内のdiv作成
            let div_branch_name = $('<div>', {
                id:"bankBranchName_" + bank_info['bank_id'],
                class:"db_class",
                text: bank_info['bank_branch_name'],
                style: "cursor:pointer"
            });

            // tdタグにdivを追加
            td_branch_name.append(div_branch_name);

            /**
             * 種別
             */
            // tdタグ生成
            let td_type_name = $('<td></td>');

            // tdタグ内のdiv作成
            let div_type_name = $('<div>', {
                id:"bankTypeName_" + bank_info['bank_id'],
                class:"db_class",
                text: bank_info['bank_type_name'],
                style: "cursor:pointer"
            });

            // tdタグにdivを追加
            td_type_name.append(div_type_name);

            /**
             * 種別id
             */
            let div_type_id = $('<input>', {
                type:"hidden",
                id:"bankTypeId_" + bank_info['bank_id'],
                value:bank_info['bank_type_id'],
            });

            // tdタグにinputを隠し項目として追加
            // td_type_nameにidとnameの2個を設定
            td_type_name.append(div_type_id);

            /**
             * 口座番号
             */
            // tdタグ生成
            let td_number = $('<td></td>');

            // tdタグ内のdiv作成
            let div_number = $('<div>', {
                id:"bankNumber_" + bank_info['bank_id'],
                class:"db_class",
                text: bank_info['bank_number'],
                style: "cursor:pointer"
            });

            // tdタグにdivを追加
            td_number.append(div_number);

            /**
             * 口座名義
             */
            // tdタグ生成
            let td_account_name = $('<td></td>');

            // tdタグ内のdiv作成
            let div_account_name = $('<div>', {
                id:"bankAccountName_" + bank_info['bank_id'],
                class:"db_class",
                text: bank_info['bank_account_name'],
                style: "cursor:pointer"
            });

            // tdタグにdivを追加
            td_account_name.append(div_account_name);

            /**
             * tdの追加
             */
            // trタグにtd追加(div)
            tr.append(td_name);

            // trタグにtd追加(div)
            tr.append(td_branch_name);

            // trタグにtd追加(div)
            tr.append(td_type_name);

            // trタグにtd追加(div)
            tr.append(td_number);

            // trタグにtd追加(div)
            tr.append(td_account_name);

            // tbodyにtrタグを追加
            $("#bank_table tbody").append(tr);

        }
    }

    /**
     * モーダル内のclickイベントとダブルクリックイベント
     * イベントをスペースで区切り、ボタンのクリックとdivのダブルクリックを同一処理としている
     */
    $("body").on('click dblclick', '.db_class', function(e) {

        console.log("db click");

        // クリックしたidを取得
        let ids = $(this).attr("id");
        let id = ids.split("_")[1];

        console.log(id);

        // 一覧のテキスト取得
        // 銀行名
        let bank_name = $("#bankname_" + id).text();
        console.log(bank_name);

        // 支店名
        let bank_branch_name = $("#bankBranchName_" + id).text();
        console.log(bank_branch_name);

        // 種別id
        // プルダウンの為、valにidを入れる
        let bank_type_id = $("#bankTypeId_" + id).val();
        console.log(bank_type_id);

        // 口座番号
        let bank_number = $("#bankNumber_" + id).text();
        console.log(bank_number);

        // 名義人
        let bank_account_name = $("#bankAccountName_" + id).text();
        console.log(bank_account_name);


        // 親要素にテキスト設定
        // id
        $("#bank_id").val(id);

        // 銀行名
        $("#bank_name").val(bank_name);

        // 支店名
        $("#bank_branch_name").val(bank_branch_name);

        // 種別
        $("#bank_type_id").val(bank_type_id);

        // 口座番号
        $("#bank_number").val(bank_number);

        // 名義人
        $("#bank_account_name").val(bank_account_name);
        
        // モーダルを閉じる
        $('#bankModal').modal('hide');
    });

    /**
     * モーダル内の検索ボタンの処理
     */
    $("#bank_search").on('click', function(e) {

        console.log('検索ボタンの処理');

        // リスト初期化
        $('#bank_table > tbody').empty();

        e.preventDefault();

        /**
         * 値取得
         */
        let free_word = $("#free_word").val();
        console.log(free_word)

        /**
         * 送信データ
         */
        // 送信データインスタンス化
        var sendData = new FormData();

        sendData.append('free_word', free_word);

        /**
         * ajax
         */
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({

            type: 'post',
            url: 'backSearchBank',
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

            console.log(data.bank_list.data[0]);

            // 一覧作成(モーダル画面内)
            setList(data);

        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
        });
    });

    /**
     * 総額賃料の計算
     */
    $('.fee_text').keyup(function() {
        console.log("fee_textを押されました");

        // 家賃
        let rent_fee = $('#rent_fee').val();

        // 共益費
        let service_fee = $('#service_fee').val();
        
        // 水道代
        let water_fee = $('#water_fee').val();

        // その他
        let ohter_fee = $('#ohter_fee').val();

        // その他
        let bicycle_fee = $('#bicycle_fee').val();

        // 総賃料
        let total_fee = Number(rent_fee) + Number(service_fee) + Number(water_fee) + Number(ohter_fee) + Number(bicycle_fee);
        console.log(total_fee);

        $("#total_fee").val(total_fee);
    });

    /**
     * ページネーションセンター
     */
    $(".pagination").addClass("justify-content-center");
    $("#links").show();

    /**
     * 特約事項(右側に反映)
     */
    $("#btn_chcked").on('click', function(e) {

        console.log("btn_chckedの処理");

        e.preventDefault();
        
        // 反映する特約の初期値
        let special_contract_detail = '';

        // ループ
        $('input[type="checkbox"]:checked').each(function(i, elem) {
            
            // チェックボックスのidを取得
            let ids = $(this).attr('id');
            console.log(ids);

            // idに分割する
            let id = ids.split('_')[1];
            console.log(id);

            // テキストのidを作成し、値を取得
            let special_contract_detail_text = $('#special_contract_detail_' + id).text();

            // 前回の特約に文字列を連結
            // falseの場合、改行コード無
            // trueの場合、改行コード有
            if(special_contract_detail == ''){

                special_contract_detail += '・' + special_contract_detail_text;
                console.log(special_contract_detail);

            }else{

                special_contract_detail += '\n' + '・' + special_contract_detail_text;
                console.log(special_contract_detail);

            }

        });

        // 右側に値を挿入にする※Jqueryでやりたい
        $('#textarea_checked').val(special_contract_detail);

    })

    /**
     * 特約事項初期化
     */
    $("#btn_clear").on('click', function(e) {
        
        e.preventDefault();

        $('#textarea_checked').val('');
    });

    /**
     * 同居人(登録)
     */
    $("#btn_houseMate_edit").on('click', function(e) {

        e.preventDefault();

        console.log('同居人登録の処理');

        /**
         * 同居人登録
         */
        // 同居人名
        let modal_housemate_name = $("#modal_housemate_name").val();
        console.log(modal_housemate_name);

        // 生年月日
        let modal_housemate_date = $("#modal_housemate_date").val();
        console.log(modal_housemate_date);

        // 契約詳細id
        let contract_detail_id = $("#contract_detail_id").val();
        console.log(contract_detail_id);

        // 同居人id
        let contract_housemate_id = $("#contract_housemate_id").val();
        console.log(contract_housemate_id);

        /**
         * 送信データ
         */
        let sendData = {

            "modal_housemate_name": modal_housemate_name,

            "modal_housemate_date": modal_housemate_date,

            "contract_detail_id": contract_detail_id,

            "contract_housemate_id": contract_housemate_id,

        }
        
        // ajaxヘッダー
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({

            type: 'post',
            url: 'backContractHouseMateEditEntry',
            dataType: 'json',
            data: sendData,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // trueの処理->申込一覧に遷移
            if(data.status == true){

                console.log("status:" + data.status);

                // alertの設定
                var options = {
                    title: "登録が完了しました。",
                    icon: "success",
                    buttons: {
                    OK: true
                    }
                };
                
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    if (val) {

                        // location.href = 'backContractInit';
                        window.location.reload();

                    };
                });

                // ローディング画面終了の処理
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);
                
                return false;
            };

             // falseの処理->アラートでエラーメッセージを表示
            if(data.status == false){

                console.log("status:" + data.status);
                console.log("messages:" + data.messages);
                console.log("errorkeys:" + data.errkeys);

                // ローディング画面終了の処理
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);

                // アラートボタン設定
                var options = {
                    title: '入力箇所をご確認ください。',
                    text: '※赤表示の箇所を修正し、再登録をしてください。',
                    icon: 'error',
                    buttons: {
                        OK: 'OK'
                    }
                };
                
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {

                    /**
                     * ダイアログ外をクリックされた場合、nullを返す為
                     * ok,nullの場合の処理を記載
                     */
                    if (val == 'OK' || val == null) {

                        console.log(val);

                        /**
                         * formの全要素をerror_Messageを表示に変更
                         * error数だけループ処理
                         */
                        for (let i = 0; i < data.errkeys.length; i++) {
                            
                            // bladeの各divにclass指定
                            let id_key = "#" + data.errkeys[i];
                            $(id_key).addClass('is-invalid');
                            console.log(id_key);

                            // 表示箇所のMessageのkey取得
                            let msg_key = "#" + data.errkeys[i] + "_error"
                            
                            let error_message_id = $(msg_key).attr('class');

                            tabError(error_message_id);

                            // error_messageテキスト追加
                            $(msg_key).text(data.messages[i]);
                            $(msg_key).show();
                            console.log(msg_key);

                        };

                        return false;
                    };
                });
            }
        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
        });

    });

    /**
     * 同居人初期化
     */
    function clearModalVal(){

        console.log("初期化関数の実行.");

        // 同居人名
        $("input[name='modal_housemate_name']").val('');
            
        // 誕生日
        $("input[name='modal_housemate_date']").val('');

        // id
        $("input[name='contract_housemate_id']").val('');

    }

    /**
     * 同居人(新規表示)
     */
    $("#housemate_add").on('click', function(e) {

        console.log("同居人追加の処理.");

        clearModalVal();
    });

    /**
     * 同居人(編集表示:ダブルクリック)
     */
    $(".click_class").on('dblclick', function(e) {

        console.log("編集ダブルクリックの処理.");

        /**
         * 初期化
         */
        clearModalVal();

        // ローディング画面
        $("#overlay").fadeIn(300);
        
        // tdのidを配列に分解
        var contract_housemate_id = $(this).attr("id").split('_')[2];
        console.log(contract_housemate_id);

        setTimeout(function(){
            $("#overlay").fadeOut(300);
        },500);

        /**
         * 送信データ
         */
        let sendData = {
            "contract_housemate_id": contract_housemate_id,
        }

        // ajaxヘッダー
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({

            type: 'post',
            url: 'backContractHouseMateEditInit',
            dataType: 'json',
            data: sendData,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // 取得データを変数に格納
            // id
            let set_contract_housemate_id = data.contract_housemates_info.contract_housemate_id
            console.log('set_contract_housemate_id:' + set_contract_housemate_id);
            
            // 同居人名
            let set_contract_housemate_name = data.contract_housemates_info.contract_housemate_name
            console.log('set_contract_housemate_name:' + set_contract_housemate_name);

            // 生年月日
            let set_contract_housemate_date = data.contract_housemates_info.contract_housemate_birthday
            console.log('contract_housemate_date:' + set_contract_housemate_date);

            // モーダルに値設定
            // 同居人名
            $("input[name='modal_housemate_name']").val(set_contract_housemate_name);
            
            // 誕生日
            $("input[name='modal_housemate_date']").val(set_contract_housemate_date);

            // id
            $("input[name='contract_housemate_id']").val(set_contract_housemate_id);
            
            // モーダル表示
            $('#housemateModal').modal('show');

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
        });
    });

    /**
     * 同居人(編集表示::編集ボタン)
     */
    $(".hm_btn_edit").on('click', function(e) {

        console.log("編集ボタンの処理.");

        /**
         * 初期化
         */
        clearModalVal();

        // ローディング画面
        $("#overlay").fadeIn(300);
        
        // tdのidを配列に分解
        var contract_housemate_id = $(this).attr("id").split('_')[2];
        console.log(contract_housemate_id);

        setTimeout(function(){
            $("#overlay").fadeOut(300);
        },500);

        /**
         * 送信データ
         */
        let sendData = {
            "contract_housemate_id": contract_housemate_id,
        }

        // ajaxヘッダー
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({

            type: 'post',
            url: 'backContractHouseMateEditInit',
            dataType: 'json',
            data: sendData,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // 取得データを変数に格納
            // id
            let set_contract_housemate_id = data.contract_housemates_info.contract_housemate_id
            console.log('set_contract_housemate_id:' + set_contract_housemate_id);
            
            // 同居人名
            let set_contract_housemate_name = data.contract_housemates_info.contract_housemate_name
            console.log('set_contract_housemate_name:' + set_contract_housemate_name);

            // 生年月日
            let set_contract_housemate_date = data.contract_housemates_info.contract_housemate_birthday
            console.log('contract_housemate_date:' + set_contract_housemate_date);

            // モーダルに値設定
            // 同居人名
            $("input[name='modal_housemate_name']").val(set_contract_housemate_name);
            
            // 誕生日
            $("input[name='modal_housemate_date']").val(set_contract_housemate_date);

            // id
            $("input[name='contract_housemate_id']").val(set_contract_housemate_id);
            
            // モーダル表示
            $('#housemateModal').modal('show');

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
        });

    });

    /**
     * 同居人(削除)
     */
    $(".hm_btn_delete").on('click', function(e) {

        console.log('削除の処理');

        e.preventDefault();

        // alertの設定
        var options = {
            title: "削除しますか？",
            text: "※一度削除したデータは復元出来ません。",
            icon: 'warning',
            buttons: {
                Cancel: "Cancel", // キャンセルボタン
                OK: true
            }
        };

        // 値取得
        // let contract_housemate_id = $("#contract_housemate_id").val();
        // console.log(contract_housemate_id);

        let ids = $(this).attr("id");
        let contract_housemate_id = ids.split("_")[2];
        console.log(contract_housemate_id);
        
        // then() OKを押した時の処理
        swal(options)
            .then(function(val) {

            if(val == null){

                console.log('キャンセルの処理');

                return false;
            }

            if (val == "OK") {

                console.log('OKの処理');

                // 送信用データ
                let sendData = {

                    "contract_housemate_id": contract_housemate_id,
                };

                console.log(sendData);

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                $.ajax({

                    type: 'post',
                    url: 'backContractHouseMateDeleteEntry',
                    dataType: 'json',
                    data: sendData,
                
                // 接続処理
                }).done(function(data) {

                    console.log('status:' + data.status)

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

                            window.location.reload();
                            
                        }
                    });

                // ajax接続失敗の時の処理
                }).fail(function(jqXHR, textStatus, errorThrown) {

                    setTimeout(function(){
                        $("#overlay").fadeOut(300);
                    },500);

                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            };
            // sweetalert
        });

    });

    /**
     * 一時登録
     */
    $("#btn_temporarily").on('click', function(e) {

        console.log("btn_temporarilyクリックされています");

        e.preventDefault();
        
        // ローディング画面
        $("#overlay").fadeIn(300);

        /**
         * 一時登録フラグ
         */
        let temporarily_flag = true;

        /**
         * id
         */
        // 契約詳細id
        let contract_detail_id = $("#contract_detail_id").val();

        // 申込id
        let application_id = $("#application_id").val();

        // 特約事項id
        let special_contract_detail_id = $("#special_contract_detail_id").val(); 
        
        console.log('special_contract_detail_id:' + special_contract_detail_id);

        /**
         * 進捗状況
         */
        // 進捗状況
        let contract_detail_progress_id = $("#contract_detail_progress_id").val();
        console.log('contract_detail_progress_id:' + contract_detail_progress_id);

        // 管理者番号
        let admin_number = $("#admin_number").val();
        
        /**
         * 物件概要
         */
        // 物件名
        let real_estate_name = $("#real_estate_name").val();

        // 号室
        let room_name = $("#room_name").val();

        // 契約面積
        let room_size = $("#room_size").val();

        // 郵便番号
        let real_estate_post_number = $("#real_estate_post_number").val();

        // 住所
        let real_estate_address = $("#real_estate_address").val();

        // 構造
        let real_estate_structure_id = $("#real_estate_structure_id").val();
        console.log('real_estate_structure_id:' + real_estate_structure_id);

        // 地上階数
        let real_estate_floor = $("#real_estate_floor").val();

        // 築年月日
        let real_estate_age = $("#real_estate_age").val();

        // 間取数
        let room_layout_name = $("#room_layout_name").val();

        // 間取種別
        let room_layout_id = $("#room_layout_id").val();
        console.log('room_layout_id:' + room_layout_id);

        // 家主名
        let owner_name = $("#owner_name").val();

        // 郵便番号
        let owner_post_number = $("#owner_post_number").val();

        // 住所
        let owner_address = $("#owner_address").val();

        // 管理委託先(共有)
        let m_share_name = $("#m_share_name").val();

        // 郵便番号
        let m_share_post_number = $("#m_share_post_number").val();

        // 住所
        let m_share_address = $("#m_share_address").val();

        // Tel
        let m_share_tel = $("#m_share_tel").val();

        // 管理委託先(専有)
        let m_own_name = $("#m_own_name").val();

        // 郵便番号
        let m_own_post_number = $("#m_own_post_number").val();

        // 住所
        let m_own_address = $("#m_own_address").val();

        // Tel
        let m_own_tel = $("#m_own_tel").val();

        /**
         * 契約者・同居人
         */
        // 契約者名
        let contract_name = $("#contract_name").val();

        // 契約者カナ
        let contract_ruby = $("#contract_ruby").val();

        // 生年月日
        let contract_date = $("#contract_date").val();

        // Tel
        let contract_tel = $("#contract_tel").val();

        // 同居人名
        let contract_housemate_name = $("#contract_housemate_name").val();

        // 同居人生年月日
        let contract_housemate_birthday = $("#contract_housemate_birthday").val();

        /**
         * 商号
         */
        // 免許id
        let company_license_id = $("#company_license_id").val();

        // 宅建取引士id
        let user_license_id = $("#user_license_id").val();

        // 宅建取引士名
        let user_license_name = $("#user_license_name option:selected").text();

        // 宅建取引士登録番号
        let user_license_number = $("#user_license_number").val();

        // 担当者
        let manager_name = $("#manager_name").val();

        /**
         * 法令
         */
        // 石綿調査記録の有無
        let report_asbestos = $("#report_asbestos").val();

        // 耐震診断
        let report_earthquake = $("#report_earthquake").val();

        // ハザードマップ有無
        let hazard_map = $("#hazard_map").val();

        // 洪水
        let warning_flood = $("#warning_flood").val();

        // 高潮
        let warning_storm_surge = $("#warning_storm_surge").val();

        // 雨水・出水
        let warning_rain_water = $("#warning_rain_water").val();

        /**
         * 登記事項
         */
        // 所有権
        let regi_name = $("#regi_name").val();

        // 権利に関する事項
        let regi_right = $("#regi_right").val();

        // 抵当権・根抵当権
        let regi_mortgage_id = $("#regi_mortgage_id").val();

        // 所有者と貸主が違う場合
        let regi_difference_owner = $("#regi_difference_owner").val();

        // 未完成物件の時
        let completion_date = $("#completion_date").val();

        /**
         * 授受される金額
         */
        // 敷金
        let security_fee = $("#security_fee").val();

        // 礼金
        let key_fee = $("#key_fee").val();
        
        // 賃料
        let rent_fee = $("#rent_fee").val();

        // 共益費
        let service_fee = $("#service_fee").val();

        // 水道代
        let water_fee = $("#water_fee").val();

        // その他
        let ohter_fee = $("#ohter_fee").val();

        // 駐輪代
        let bicycle_fee = $("#bicycle_fee").val();

        // 月額合計
        let total_fee = $("#total_fee").val();

        // 駐車場保証金
        let car_deposit_fee = $("#car_deposit_fee").val();

        // 駐車場代
        let car_fee = $("#car_fee").val();

        // 住宅保険料
        let fire_insurance_fee = $("#fire_insurance_fee").val();

        // 住宅保険期間
        let fire_insurance_span = $("#fire_insurance_span").val();

        // 保証会社費用
        let guarantee_fee = $("#guarantee_fee").val();

        // 保証会社更新期間
        let guarantee_update_span = $("#guarantee_update_span").val();

        // 保証会社更新料
        let guarantee_update_fee = $("#guarantee_update_fee").val();

        // 安心サポート
        let support_fee = $("#support_fee").val();

        // 防虫抗菌代
        let disinfect_fee = $("#disinfect_fee").val();

        // その他項目①
        let other_name1 = $("#other_name1").val();

        // その他費用①
        let other_fee1 = $("#other_fee1").val();

        // その他項目②
        let other_name2 = $("#other_name2").val();

        // その他費用②
        let other_fee2 = $("#other_fee2").val();

        // 仲介手数料(駐車場)
        let car_broker_fee = $("#car_broker_fee").val();

        // 仲介手数料
        let broker_fee = $("#broker_fee").val();

        // 預り金の日付
        let today_account_fee_date = $("#today_account_fee_date").val();

        // 預り金
        let today_account_fee = $("#today_account_fee").val();

        // 決済予定日
        let payment_date = $("#payment_date").val();

        // 敷金の清算に関する事項
        let introduction_security_fee = $("#introduction_security_fee").val();

        // 礼金の清算に関する事項
        let introduction_key_fee = $("#introduction_key_fee").val();

        // 預金の保全措置
        let keep_account_fee = $("#keep_account_fee").val();

        // 金銭賃借の斡旋
        let introduction_fee = $("#introduction_fee").val();

        /**
         * 設備状況
         */
        // 飲用水
        let water = $("#water").val();

        // 飲用水種別
        let water_type_name = $("#water_type_name").val();

        // ガス
        let gas = $("#gas").val();

        // ガス種別
        let gas_type_name = $("#gas_type_name").val();

        // 電気
        let electricity = $("#electricity").val();

        // 電気種別
        let electricity_type_name = $("#electricity_type_name").val();

        // 排水
        let waste_water = $("#waste_water").val();

        // 排水種別
        let waste_water_name = $("#waste_water_name").val();

        // 台所
        let kitchen = $("#kitchen").val();

        // 台所種別
        let kitchen_exclusive_type_id = $("#kitchen_exclusive_type_id").val();

        // コンロ
        let cooking_stove = $("#cooking_stove").val();

        // コンロ種別
        let cooking_stove_exclusive_type_id = $("#cooking_stove_exclusive_type_id").val();

        // 浴室
        let bath = $("#bath").val();

        // 浴室種別
        let bath_exclusive_type_id = $("#bath_exclusive_type_id").val();

        // トイレ
        let toilet = $("#toilet").val();

        // トイレ種別
        let toilet_exclusive_type_id = $("#toilet_exclusive_type_id").val();

        // 給湯器
        let water_heater = $("#water_heater").val();

        // 給湯器種別
        let water_heater_exclusive_type_id = $("#water_heater_exclusive_type_id").val();

        // 冷暖房設備
        let air_conditioner = $("#air_conditioner").val();

        // 冷暖房設備台数
        let air_conditioner_exclusive_type_name = $("#air_conditioner_exclusive_type_name").val();

        // エレベーター
        let elevator = $("#elevator").val();

        // エレベーター台数
        let elevator_exclusive_type_name = $("#elevator_exclusive_type_name").val();

        /**
         * 契約期間
         */
        let contract_start_date = $("#contract_start_date").val();

        let contract_end_date = $("#contract_end_date").val();

        let contract_update_span = $("#contract_update_span").val();

        let contract_update_item = $("#contract_update_item").val();

        /**
         * 用途の制限
         */
        let limit_use_id = $("#limit_use_id").val();

        let limit_type_name = $("#limit_type_name").val();

        /**
         * 契約の解約及び解除
         */
        let announce_cancel_date = $("#announce_cancel_date").val();

        let soon_cancel_date = $("#soon_cancel_date").val();

        let cancel_fee_count = $("#cancel_fee_count").val();

        let cancel_contract_document = $("#cancel_contract_document").val();

        let remove_contract_document = $("#remove_contract_document").val();

        /**
         * 損害賠償・違約金・免責
         */
        let penalty_fee = $("#penalty_fee").val();

        let penalty_fee_late_document = $("#penalty_fee_late_document").val();

        let claim_fee_document = $("#claim_fee_document").val();

        let fix_document = $("#fix_document").val();

        let recovery_document = $("#recovery_document").val();

        /**
         * 家賃振込先
         */
        let bank_id = $("#bank_id").val();

        let bank_name = $("#bank_name").val();

        let bank_branch_name = $("#bank_branch_name").val();

        let bank_type_id = $("#bank_type_id").val();

        let bank_number = $("#bank_number").val();

        let bank_account_name = $("#bank_account_name").val();

        let rent_fee_payment_date = $("#rent_fee_payment_date").val();

        /**
         * その他
         */
        let trade_type_id = $("#trade_type_id").val();

        let mail_box_number = $("#mail_box_number").val();

        /**
         * 特約事項
         */
        let textarea_checked = $("#textarea_checked").val();

        // 送信データインスタンス化
        var sendData = new FormData();
        
        /**
         * 一時登録フラグ
         */
        sendData.append('temporarily_flag', temporarily_flag);

        /**
         * 進捗状況
         */
        sendData.append('contract_detail_progress_id', contract_detail_progress_id);
        sendData.append('admin_number', admin_number);
        
        /**
         * 物件概要
         */
        sendData.append('real_estate_name', real_estate_name);
        sendData.append('room_name', room_name);
        sendData.append('room_size', room_size);
        sendData.append('real_estate_post_number', real_estate_post_number);
        sendData.append('real_estate_address', real_estate_address);
        sendData.append('real_estate_structure_id', real_estate_structure_id);
        sendData.append('real_estate_floor', real_estate_floor);
        sendData.append('real_estate_age', real_estate_age);
        sendData.append('room_layout_name', room_layout_name);
        sendData.append('room_layout_id', room_layout_id);
        sendData.append('owner_name', owner_name);
        sendData.append('owner_post_number', owner_post_number);
        sendData.append('owner_address', owner_address);
        sendData.append('m_share_name', m_share_name);
        sendData.append('m_share_post_number', m_share_post_number);
        sendData.append('m_share_address', m_share_address);
        sendData.append('m_share_tel', m_share_tel);
        sendData.append('m_own_name', m_own_name);
        sendData.append('m_own_post_number', m_own_post_number);
        sendData.append('m_own_address', m_own_address);
        sendData.append('m_own_tel', m_own_tel);

        /**
         * 契約者・同居人
         */
        sendData.append('contract_name', contract_name);
        sendData.append('contract_ruby', contract_ruby);
        sendData.append('contract_date', contract_date);
        sendData.append('contract_tel', contract_tel);
        sendData.append('contract_housemate_name', contract_housemate_name);
        sendData.append('contract_housemate_birthday', contract_housemate_birthday);

        /**
         * 商号
         */
        sendData.append('company_license_id', company_license_id);
        sendData.append('user_license_id', user_license_id);
        sendData.append('user_license_name', user_license_name);
        sendData.append('user_license_number', user_license_number);
        sendData.append('manager_name', manager_name);

        /**
         * 法令関係
         */
        sendData.append('report_asbestos', report_asbestos);
        sendData.append('report_earthquake', report_earthquake);
        sendData.append('hazard_map', hazard_map);
        sendData.append('warning_flood', warning_flood);
        sendData.append('warning_storm_surge', warning_storm_surge);
        sendData.append('warning_rain_water', warning_rain_water);

        /**
         * 登記事項
         */
        sendData.append('regi_name', regi_name);
        sendData.append('regi_right', regi_right);
        sendData.append('regi_mortgage_id', regi_mortgage_id);
        sendData.append('regi_difference_owner', regi_difference_owner);
        sendData.append('completion_date', completion_date);

        /**
         * 授受される金額
         */
        sendData.append('security_fee', security_fee);
        sendData.append('key_fee', key_fee);
        sendData.append('rent_fee', rent_fee);
        sendData.append('service_fee', service_fee);
        sendData.append('water_fee', water_fee);
        sendData.append('ohter_fee', ohter_fee);
        sendData.append('bicycle_fee', bicycle_fee);
        sendData.append('total_fee', total_fee);
        sendData.append('car_deposit_fee', car_deposit_fee);
        sendData.append('car_fee', car_fee);
        sendData.append('fire_insurance_fee', fire_insurance_fee);
        sendData.append('fire_insurance_span', fire_insurance_span);
        sendData.append('guarantee_fee', guarantee_fee);
        sendData.append('guarantee_update_span', guarantee_update_span);
        sendData.append('guarantee_update_fee', guarantee_update_fee);
        sendData.append('support_fee', support_fee);
        sendData.append('disinfect_fee', disinfect_fee);
        sendData.append('other_name1', other_name1);
        sendData.append('other_fee1', other_fee1);
        sendData.append('other_name2', other_name2);
        sendData.append('other_fee2', other_fee2);
        sendData.append('car_broker_fee', car_broker_fee);
        sendData.append('broker_fee', broker_fee);
        sendData.append('today_account_fee_date', today_account_fee_date);
        sendData.append('today_account_fee', today_account_fee);
        sendData.append('payment_date', payment_date);
        sendData.append('introduction_security_fee', introduction_security_fee);
        sendData.append('introduction_key_fee', introduction_key_fee);
        sendData.append('keep_account_fee', keep_account_fee);
        sendData.append('introduction_fee', introduction_fee);

        /**
         * 設備状況
         */
        sendData.append('water', water);
        sendData.append('water_type_name', water_type_name);
        sendData.append('gas', gas);
        sendData.append('gas_type_name', gas_type_name);
        sendData.append('electricity', electricity);
        sendData.append('electricity_type_name', electricity_type_name);
        sendData.append('waste_water', waste_water);
        sendData.append('waste_water_name', waste_water_name);
        sendData.append('kitchen', kitchen);
        sendData.append('kitchen_exclusive_type_id', kitchen_exclusive_type_id);
        sendData.append('cooking_stove', cooking_stove);
        sendData.append('cooking_stove_exclusive_type_id', cooking_stove_exclusive_type_id);
        sendData.append('bath', bath);
        sendData.append('bath_exclusive_type_id', bath_exclusive_type_id);
        sendData.append('toilet', toilet);
        sendData.append('toilet_exclusive_type_id', toilet_exclusive_type_id);
        sendData.append('water_heater', water_heater);
        sendData.append('water_heater_exclusive_type_id', water_heater_exclusive_type_id);
        sendData.append('air_conditioner', air_conditioner);
        sendData.append('air_conditioner_exclusive_type_name', air_conditioner_exclusive_type_name);
        sendData.append('elevator', elevator);
        sendData.append('elevator_exclusive_type_name', elevator_exclusive_type_name);
        
        /**
         * 契約期間
         */
        sendData.append('contract_start_date', contract_start_date);
        sendData.append('contract_end_date', contract_end_date);
        sendData.append('contract_update_span', contract_update_span);
        sendData.append('contract_update_item', contract_update_item);
        
        /**
         * 用途・利用の制限
         */
        sendData.append('limit_use_id', limit_use_id);
        sendData.append('limit_type_name', limit_type_name);

        /**
         * 契約解約及び解除
         */
        sendData.append('announce_cancel_date', announce_cancel_date);
        sendData.append('soon_cancel_date', soon_cancel_date);
        sendData.append('cancel_fee_count', cancel_fee_count);
        sendData.append('cancel_contract_document', cancel_contract_document);
        sendData.append('remove_contract_document', remove_contract_document);

        /**
         * 損害賠償及び違約金
         */
        sendData.append('penalty_fee', penalty_fee);
        sendData.append('penalty_fee_late_document', penalty_fee_late_document);
        sendData.append('claim_fee_document', claim_fee_document);
        sendData.append('fix_document', fix_document);
        sendData.append('recovery_document', recovery_document);
        
        /**
         * 家賃振込先
         */
        sendData.append('bank_id', bank_id);
        sendData.append('bank_name', bank_name);
        sendData.append('bank_branch_name', bank_branch_name);
        sendData.append('bank_type_id', bank_type_id);
        sendData.append('bank_number', bank_number);
        sendData.append('bank_account_name', bank_account_name);
        sendData.append('rent_fee_payment_date', rent_fee_payment_date);

        /**
         * その他
         */
        sendData.append('trade_type_id', trade_type_id);
        sendData.append('mail_box_number', mail_box_number);

        /**
         * id
         */
        // 契約詳細id
        sendData.append('contract_detail_id', contract_detail_id);
        // 申込id
        sendData.append('application_id', application_id);
        // 特約事項id
        sendData.append('special_contract_detail_id', special_contract_detail_id);
        
        /**
         * 特約事項
         */
        sendData.append('textarea_checked', textarea_checked);

        // ajaxヘッダー
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({

            type: 'post',
            url: 'backContractEditEntry',
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

            // trueの処理->申込一覧に遷移
            if(data.status == true){

                console.log("status:" + data.status);

                // alertの設定
                var options = {
                    title: "登録が完了しました。",
                    icon: "success",
                    buttons: {
                    OK: true
                    }
                };
                
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    if (val) {

                        location.href = 'backContractInit';

                    };
                });

                // ローディング画面終了の処理
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);
                
                return false;
            };

             // falseの処理->アラートでエラーメッセージを表示
            if(data.status == false){

                console.log("status:" + data.status);
                console.log("messages:" + data.messages);
                console.log("errorkeys:" + data.errkeys);

                // ローディング画面終了の処理
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);

                // アラートボタン設定
                var options = {
                    title: '入力箇所をご確認ください。',
                    text: '※赤表示の箇所を修正し、再登録をしてください。',
                    icon: 'error',
                    buttons: {
                        OK: 'OK'
                    }
                };
                
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    /**
                     * ダイアログ外をクリックされた場合、nullを返す為
                     * ok,nullの場合の処理を記載
                     */
                    if (val == 'OK' || val == null) {

                        console.log(val);

                        /**
                         * formの全要素をerror_Messageを表示に変更
                         * error数だけループ処理
                         */
                        for (let i = 0; i < data.errkeys.length; i++) {
                            
                            // bladeの各divにclass指定
                            let id_key = "#" + data.errkeys[i];
                            $(id_key).addClass('is-invalid');
                            console.log(id_key);

                            // 表示箇所のMessageのkey取得
                            let msg_key = "#" + data.errkeys[i] + "_error"
                            
                            let error_message_id = $(msg_key).attr('class');

                            tabError(error_message_id);

                            // error_messageテキスト追加
                            $(msg_key).text(data.messages[i]);
                            $(msg_key).show();
                            console.log(msg_key);

                        };

                        return false;
                    };
                });
            }
        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
        });
    });

    /**
     * 帳票作成
     */
    $("#btn_contract").on('click', function(e) {
        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);
        
        // --------------------
        // ★ボタン非活性
        // --------------------
        $("#btn_contract").prop("disabled", true);

        /**
         * 値取得
         */
        // 契約詳細id
        let contract_detail_id = $("#contract_detail_id").val();
        console.log(contract_detail_id);

        let sendData = {

        };
        console.log(sendData);

        // ajaxセットアップ
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({
            type: 'get',
            url: 'excelEntryDummy',
            dataType: 'json',
            data: sendData,

        // 接続が出来た場合の処理
        }).done(function(data) {
            console.log(data);

            // --------------------
            // ★エクセルダウンロード処理
            // --------------------
            window.open('backContractExcelEntry?contract_detail_id=' + contract_detail_id, '_self');

            // ★3秒間固定でスリープ(ここは要調整で)
            sleep(7000);

            // -------------------
            // ★ボタン活性
            // --------------------
            $("#btn_contract").prop("disabled", false);

            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

        });
    });

    // スリープ処理
    function sleep(waitMsec) {
        var startMsec = new Date();
        
        // 指定ミリ秒間だけループさせる（CPUは常にビジー状態）
        while (new Date() - startMsec < waitMsec);
    }

    /**
     * 連帯保証人の有無のプルダウン変化処理
     * 1=有の場合、極度額編集可
     * 2=無の場合、極度額編集不可
     */
    $("#guarantor_need_id").change(function(e) {

        let guarantor_need_id = $("#guarantor_need_id").val();
        console.log('guarantor_need_id' + guarantor_need_id);

        if(guarantor_need_id == 1){

            $('#guarantor_max_payment').attr('readonly',false);

        }else{

            $('#guarantor_max_payment').attr('readonly',true);
            $('#guarantor_max_payment').val('');

        }

    });
    
    /**
     * 共有と同じをチェックした時の処理
     */
    $('#cb_m_share').on('change', function(){

        // チェックされた場合の処理
        if(jQuery(this).prop('checked')){
            
            console.log('共有と同じにチェックされています');

            let m_share_name = $('#m_share_name').val();

            let m_share_post_number = $('#m_share_post_number').val();

            let m_share_address = $('#m_share_address').val();

            let m_share_tel = $('#m_share_tel').val();

            $('#m_own_name').val(m_share_name);

            $('#m_own_post_number').val(m_share_post_number);
            
            $('#m_own_address').val(m_share_address);

            $('#m_own_tel').val(m_share_tel);
        }

    });

    /**
     * 利用の制限(システムの契約書を使用)
     */
    $('#cb_limit_type_name').on('change', function(){
        // チェックされた場合の処理
        if(jQuery(this).prop('checked')){
            
            console.log('システムの契約書を使用にチェックされています');

            $('#limit_type_name').val('契約書案第7条参照');
            
        }
    });

    /**
     * 契約の解除(システムの契約書を使用)
     */
    $('#cb_cancel_contract_document').on('change', function(){
        // チェックされた場合の処理
        if(jQuery(this).prop('checked')){
            
            console.log('システムの契約書を使用にチェックされています');

            $('#cancel_contract_document').val('契約書案第9条参照');
            
        }
    });

    /**
     * 契約の消滅(システムの契約書を使用)
     */
    $('#cb_remove_contract_document').on('change', function(){
        // チェックされた場合の処理
        if(jQuery(this).prop('checked')){
            
            console.log('システムの契約書を使用にチェックされています');

            $('#remove_contract_document').val('契約書案第9条参照');
            
        }
    });

    /**
     * 支払遅延損害金(システムの契約書を使用)
     */
    $('#cb_penalty_fee_late_document').on('change', function(){
        // チェックされた場合の処理
        if(jQuery(this).prop('checked')){
            
            console.log('システムの契約書を使用にチェックされています');

            $('#penalty_fee_late_document').val('法廷利率に準ずる');
            
        }
    });

    /**
     * 支払遅延損害金(システムの契約書を使用)
     */
    $('#cb_claim_fee_document').on('change', function(){
        // チェックされた場合の処理
        if(jQuery(this).prop('checked')){
            
            console.log('システムの契約書を使用にチェックされています');

            $('#claim_fee_document').val('契約書案第1条3参照');
            
        }
    });

    /**
     * 入居中の修繕に関する事項(システムの契約書を使用)
     */
    $('#cb_fix_document').on('change', function(){
        // チェックされた場合の処理
        if(jQuery(this).prop('checked')){
            
            console.log('システムの契約書を使用にチェックされています');

            $('#fix_document').val('契約書案第7条5・6参照');
            
        }
    });

    /**
     * 明渡及び原状回復(システムの契約書を使用)
     */
    $('#cb_recovery_document').on('change', function(){
        // チェックされた場合の処理
        if(jQuery(this).prop('checked')){
            
            console.log('システムの契約書を使用にチェックされています');

            $('#recovery_document').val('契約書案第10条2・5参照');
            
        }
    });
});