$(function() {

    /**
     * 専任取引士(コンボボックス変更の処理)
     */
    $("#full_time_user_license_id").change(function(e) {

        console.log('コンボボックス変更の処理');

        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);

        // id
        let full_time_user_license_id = $("#full_time_user_license_id").val();
        console.log(full_time_user_license_id);

        // 送信データ設定
        var sendData = new FormData();
        
        sendData.append('full_time_user_license_id', full_time_user_license_id);
        
        // ajaxヘッダー
        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            
        });

        $.ajax({

            type: 'post',
            url: 'backUserLicenseChange',
            dataType: 'json',
            data: sendData,
            cache:false,
            processData : false,
            contentType : false,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // ログ(登録番号)
            console.log("user_license_number:" + data.user_license_list[0]['user_license_number']);

            $('#full_time_user_license_number').val(data.user_license_list[0]['user_license_number']);

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
     * 法務局(コンボボックス変更の処理)
     */
    $("#legal_place_id").change(function(e) {

        console.log('コンボボックス変更の処理');

        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);

        // id
        let legal_place_id = $("#legal_place_id").val();
        console.log(legal_place_id);

        // 送信データ設定
        var sendData = new FormData();
        
        sendData.append('legal_place_id', legal_place_id);
        
        // ajaxヘッダー
        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            
        });

        $.ajax({

            type: 'post',
            url: 'backLegalPlaceChange',
            dataType: 'json',
            data: sendData,
            cache:false,
            processData : false,
            contentType : false,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // 郵便番号
            console.log("legal_place_post_number:" + data.legal_place_list[0]['legal_place_post_number']);
            $('#legal_place_post_number').val(data.legal_place_list[0]['legal_place_post_number']);

            // 住所
            console.log("legal_place_address:" + data.legal_place_list[0]['legal_place_address']);
            $('#legal_place_address').val(data.legal_place_list[0]['legal_place_address']);

            // tel
            console.log("legal_place_tel:" + data.legal_place_list[0]['legal_place_tel']);
            $('#legal_place_tel').val(data.legal_place_list[0]['legal_place_tel']);

            // fax
            console.log("legal_place_fax:" + data.legal_place_list[0]['legal_place_fax']);
            $('#legal_place_fax').val(data.legal_place_list[0]['legal_place_fax']);

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
     * 不動産保証協会(コンボボックス変更の処理)
     */
    $("#guaranty_association_id").change(function(e) {

        console.log('コンボボックス変更の処理');

        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);

        // id
        let guaranty_association_id = $("#guaranty_association_id").val();
        console.log(guaranty_association_id);

        // 送信データ設定
        var sendData = new FormData();
        
        sendData.append('guaranty_association_id', guaranty_association_id);
        
        // ajaxヘッダー
        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            
        });

        $.ajax({

            type: 'post',
            url: 'backGuarantyAssociationChange',
            dataType: 'json',
            data: sendData,
            cache:false,
            processData : false,
            contentType : false,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // 郵便番号
            console.log("guaranty_association_post_number:" + data.guaranty_association_list[0]['guaranty_association_post_number']);
            $('#guaranty_association_post_number').val(data.guaranty_association_list[0]['guaranty_association_post_number']);

            // 住所
            console.log("guaranty_association_address:" + data.guaranty_association_list[0]['guaranty_association_address']);
            $('#guaranty_association_address').val(data.guaranty_association_list[0]['guaranty_association_address']);

            // tel
            console.log("guaranty_association_tel:" + data.guaranty_association_list[0]['guaranty_association_tel']);
            $('#guaranty_association_tel').val(data.guaranty_association_list[0]['guaranty_association_tel']);

            // fax
            console.log("guaranty_association_fax:" + data.guaranty_association_list[0]['guaranty_association_fax']);
            $('#guaranty_association_fax').val(data.guaranty_association_list[0]['guaranty_association_fax']);

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
     * 登録
     */
    $("#btn_edit").on('click', function(e) {

        console.log("編集ボタンの処理");

        e.preventDefault();

        $('#nav-user-tab').removeClass('bg_tab_error');
        $('#nav-company_license-tab').removeClass('bg_tab_error');
        $('#nav-legal_places-tab').removeClass('bg_tab_error');
        $('#nav-guaranty_societies-tab').removeClass('bg_tab_error');

        // ローディング画面
        $("#overlay").fadeIn(300);

        // バリデーション
        // formの値数を取得
        let forms = $('.needs-validation');
        console.log('forms.length:' + forms[0].length);

        // validationフラグ初期値
        let v_check = true;

        // formの項目数ループ処理
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

            // formの値を取得->クラス付与
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
         * 送信データ
         */
        // アカウントid
        let create_user_id = $("#create_user_id").val();

        // 名前
        let create_user_name = $("#create_user_name").val();

        // mail
        let create_user_mail = $("#create_user_mail").val();

        // 郵便番号
        let create_user_post_number = $("#create_user_post_number").val();

        // 住所
        let create_user_address = $("#create_user_address").val();

        // tel
        let create_user_tel = $("#create_user_tel").val();

        // fax
        let create_user_fax = $("#create_user_fax").val();

        // パスワード
        let password = $("#password").val();

        /**
         * 免許
         */
        // 商号名
        let company_license_name = $("#company_license_name").val();

        // 代表者
        let company_license_representative = $("#company_license_representative").val();

        // 所在地
        let company_license_address = $("#company_license_address").val();

        // Tel
        let company_license_tel = $("#company_license_tel").val();

        // Fax
        let company_license_fax = $("#company_license_fax").val();

        // 登録番号
        let company_license_number = $("#company_license_number").val();

        // 登録年月日
        let company_license_span = $("#company_license_span").val();

        // 専任取引士
        let full_time_user_license_id = $("#full_time_user_license_id").val();
        
        // 登録番号
        let full_time_user_license_number = $("#full_time_user_license_number").val();
        
        // 取扱店
        let company_nick_name = $("#company_nick_name").val();

        // 所在地
        let company_nick_address = $("#company_nick_address").val();

        /**
         * 宅建取引士
         */
        // 法務局
        let legal_place_id = $("#legal_place_id").val();

        // 郵便番号
        let legal_place_post_number = $("#legal_place_post_number").val();

        // 所在地
        let legal_place_address = $("#legal_place_address").val();

        // Tel
        let legal_place_tel = $("#legal_place_tel").val();
        
        // Fax
        let legal_place_fax = $("#legal_place_fax").val();

        /**
         * 不動産保証協会
         */
        let guaranty_association_id = $("#guaranty_association_id").val();
        
        // 郵便番号
        let guaranty_association_post_number = $("#guaranty_association_post_number").val();

        // 所在地
        let guaranty_association_address = $("#guaranty_association_address").val();
        
        // tel
        let guaranty_association_tel = $("#guaranty_association_tel").val();

        // fax
        let guaranty_association_fax = $("#guaranty_association_fax").val();

        // 送信データインスタンス化
        var sendData = new FormData();

        /**
         * アカウント情報
         */
        sendData.append('create_user_name', create_user_name);
        sendData.append('create_user_mail', create_user_mail);
        sendData.append('create_user_post_number', create_user_post_number);
        sendData.append('create_user_address', create_user_address);
        sendData.append('create_user_tel', create_user_tel);
        sendData.append('create_user_fax', create_user_fax);
        sendData.append('password', password);

        // 免許
        sendData.append('company_license_name', company_license_name);
        sendData.append('company_license_representative', company_license_representative);
        sendData.append('company_license_address', company_license_address);
        sendData.append('company_license_tel', company_license_tel);
        sendData.append('company_license_fax', company_license_fax);
        sendData.append('company_license_number', company_license_number);
        sendData.append('company_license_span', company_license_span);
        sendData.append('full_time_user_license_id', full_time_user_license_id);
        sendData.append('full_time_user_license_number', full_time_user_license_number);
        sendData.append('company_nick_name', company_nick_name);
        sendData.append('company_nick_address', company_nick_address);

        // 法務局
        sendData.append('legal_place_id', legal_place_id);
        sendData.append('legal_place_post_number', legal_place_post_number);
        sendData.append('legal_place_address', legal_place_address);
        sendData.append('legal_place_tel', legal_place_tel);
        sendData.append('legal_place_fax', legal_place_fax);

        // 不動産保証協会
        sendData.append('guaranty_association_id', guaranty_association_id);
        sendData.append('guaranty_association_post_number', guaranty_association_post_number);
        sendData.append('guaranty_association_address', guaranty_association_address);
        sendData.append('guaranty_association_tel', guaranty_association_tel);
        sendData.append('guaranty_association_fax', guaranty_association_fax);

        // id
        sendData.append('create_user_id', create_user_id);
    
        // ajaxヘッダー
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({

            type: 'post',
            url: 'backUserEditEntry',
            dataType: 'json',
            data: sendData,
            cache:false,
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

                        location.reload();

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

                // アラートボタン設定
                var options = {
                    title: '入力箇所をご確認ください。',
                    text: '※誤入力箇所を赤文字で表示しています。',
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

                            // ローディング画面停止
                            setTimeout(function(){
                                $("#overlay").fadeOut(300);
                            },500);
                        };
                    };

                    return false;
                });
            }

            // ローディング画面停止
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
     * エラー時のタブ背景色の設定
     */
    function tabError(error_message_id){

        // error_message_idがある場合の処理
        if(error_message_id !== undefined){

            // error_message_id内にあるclassのtab名を取得
            let tab_class = error_message_id.split(' ')[0];
            console.log('tab_class:' + tab_class);
            
            // アカウント
            if(tab_class == 'user-tab'){
                                
                console.log('ユーザタブの処理');

                $('#nav-user-tab').addClass('bg_tab_error');
                
            }

            // 免許概要
            if(tab_class == 'company_licences-tab'){
                                
                console.log('ユーザタブの処理');

                $('#nav-company_licences-tab').addClass('bg_tab_error');
                
            } 

        }
    }

    /**
     * 住所検索
     */
    $(".btn_zip").on('click', function(e) {
        console.log("btn_zipクリックされています");
        
        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);

        let post_number = $('#create_user_post_number').val();

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

                let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                console.log(address);
                $('#create_user_address').val(address);

            }

        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

        });
        
    });

});