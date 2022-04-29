$(function(){

    /**
     * イニシャライズ
     */
    $(window).on('load', function(){

        modalClear();

    });

    /**
     * 初期化
     */
    function modalClear() {

        // デフォルト値
        $('#special_contract_default_id').prop('checked', false);

        // 内容
        $('#special_contract_name').val('');

        // id
        $('#special_contract_id').val('');
    }

    /**
     * 登録(新規登録)
     */
    $("#btn_modal_edit").on('click', function(e) {

        console.log("編集ボタンの処理");

        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);

        // バリデーション
        let forms = $('.needs-validation');

        // validationフラグ初期値
        let v_check = true;

        // formの項目数ループ処理
        for (let i = 0; i < forms[0].length; i++) {

            // タグ名、Id名取得
            let form = forms[0][i];

            // タグ名を取得 input or button
            let tag = $(form).prop("tagName");
            
            // form内のbuttonは通過
            if (tag == 'BUTTON'){
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

        // 特約id
        let special_contract_id = $("#special_contract_id").val();
        console.log('special_contract_id' + special_contract_id);

        // 特約内容
        let special_contract_name = $("#special_contract_name").val();
        console.log('special_contract_name' + special_contract_name);

        let special_contract_default_id = 0;

        // デフォルト値(check=1/check無=0)
        if ($("#special_contract_default_id").prop("checked") == true){

            console.log('checkされています');
            special_contract_default_id = $("#special_contract_default_id").val();

        }
        
        // 送信データの設定
        var sendData = new FormData();
        
        sendData.append('special_contract_id', special_contract_id);
        sendData.append('special_contract_name', special_contract_name);
        sendData.append('special_contract_default_id', special_contract_default_id);
        
        // ajaxヘッダー
        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        
        });

        /**
         * ajax接続
         */
        $.ajax({
            
            type: 'post',
            url: 'backSpecialContractEntry',
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
                        ok: true
                    }
                };
                
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    if (val) {

                        location.href = 'backSpecialContractInit';
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
                            // error_messageテキスト追加
                            $(msg_key).text(data.messages[i]);
                            $(msg_key).show();
                            console.log(msg_key);
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
     * 新規表示
     */
    $("#btn_new").on('click', function(e) {

        // 初期化
        modalClear()

        // モーダル表示
        $('#exampleModal').modal('show');

    });

    /**
     * 編集(ラジオボタンの処理)
     */
    $("#btn_edit").on('click', function(e) {

        console.log("編集ボタンの処理");

        modalClear();

        // ローディング画面
        $("#overlay").fadeIn(300);

        e.preventDefault();

        /**
         * ラジオボタンにチェックがない場合、プログラム終了
         * ラジオボタンに値がない場合 = 0
         * ラジオボタンに値がある場合 = 1
         */
        // チェックがない場合終了
        if ($('input[name=flexRadioDisabled]:checked').length <= 0) {

            console.log("チェックがない場合の処理");

            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
            
            exit;
        }

        // id取得
        var special_contract_id = $('input[name=flexRadioDisabled]:checked').attr('id');
        console.log(special_contract_id);

        // 送信データインスタンス化
        var sendData = new FormData();
        
        sendData.append('special_contract_id', special_contract_id);
        
        // ajaxヘッダー
        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        
        });

        $.ajax({
            
            type: 'post',
            url: 'backSpecialContractEdit',
            dataType: 'json',
            data: sendData,
            cache:false,
            processData : false,
            contentType : false,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // ローディング画面停止
            setTimeout(function(){
                $("#overlay").fadeOut(100);
            },300);

            // 初期化
            modalClear();

            console.log("ajax通信後の処理");

            // 特約id
            let special_contract_id = data.special_contract_info['special_contract_id'];
            console.log("special_contract_id:" + special_contract_id);

            // 特約内容
            let special_contract_name = data.special_contract_info['special_contract_name'];
            console.log("special_contract_name:" + special_contract_name);

            // デフォルト値
            let special_contract_default_id = data.special_contract_info['special_contract_default_id'];
            console.log("special_contract_default_id:" + special_contract_default_id);

            // コンテンツ挿入
            // 特約id
            $('#special_contract_id').val(special_contract_id);
            
            // 特約内容
            $('#special_contract_name').val(special_contract_name);

            // 1=check/0=check無
            if(special_contract_default_id == 1){

                $('#special_contract_default_id').prop('checked', true);

            }else{

                $('#special_contract_default_id').prop('checked', false);
            }

            // モーダル表示
            $('#exampleModal').modal('show');

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
     * 編集表示(ダブルクリックの処理)
     */
    $(".click_class").on('dblclick', function(e) {

        console.log("ダブルクリックの処理.");

        modalClear();

        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);
        
        var special_contract_id = $(this).attr("id");
        console.log(special_contract_id);

        // 送信データインスタンス化
        var sendData = new FormData();
        
        sendData.append('special_contract_id', special_contract_id);
        
        // ajaxヘッダー
        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        
        });

        $.ajax({
            
            type: 'post',
            url: 'backSpecialContractEdit',
            dataType: 'json',
            data: sendData,
            cache:false,
            processData : false,
            contentType : false,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // ローディング画面停止
            setTimeout(function(){
                $("#overlay").fadeOut(100);
            },300);

            // 初期化
            modalClear();

            console.log("ajax通信後の処理");

            // 特約id
            let special_contract_id = data.special_contract_info['special_contract_id'];
            console.log("special_contract_id:" + special_contract_id);

            // 特約内容
            let special_contract_name = data.special_contract_info['special_contract_name'];
            console.log("special_contract_name:" + special_contract_name);

            // デフォルト値
            let special_contract_default_id = data.special_contract_info['special_contract_default_id'];
            console.log("special_contract_default_id:" + special_contract_default_id);

            // コンテンツ挿入
            // 特約id
            $('#special_contract_id').val(special_contract_id);
            
            // 特約内容
            $('#special_contract_name').val(special_contract_name);

            // 1=check/0=check無
            if(special_contract_default_id == 1){

                $('#special_contract_default_id').prop('checked', true);

            }else{

                $('#special_contract_default_id').prop('checked', false);
            }

            // モーダル表示
            $('#exampleModal').modal('show');

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
    $("#btn_modal_delete").on('click', function(e) {

        console.log('画像削除の処理');

        e.preventDefault();

        // 特約id
        let special_contract_id = $("#special_contract_id").val();
        console.log('special_contract_id' + special_contract_id);

        // alertの設定
        var options = {
            title: "削除しますか？",
            text: "※一度削除したデータは復元出来ません。",
            icon: 'warning',
            buttons: {
                cancel: "Cancel", // キャンセルボタン
                OK: true
            }
        };
        
        // then() OKを押した時の処理
        swal(options)
            .then(function(val) {

            if(val == null){

                console.log('キャンセルの処理');

                return false;
            }

            if (val == "OK") {

                console.log('OKの処理');

                // 送信データインスタンス化
                var sendData = new FormData();

                sendData.append('special_contract_id', special_contract_id);

                console.log(sendData);

                // ajaxヘッダー
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                $.ajax({

                    type: 'post',
                    url: 'backSpecialDeleteEntry',
                    dataType: 'json',
                    data: sendData,
                    //ajaxのキャッシュの削除
                    cache:false,
                    /**
                     * dataに指定したオブジェクトをクエリ文字列に変換するかどうかを設定します。
                     * 初期値はtrue、自動的に "application/x-www-form-urlencoded" 形式に変換します。
                     */
                    processData : false,
                    contentType : false,
                
                // 接続処理
                }).done(function(data) {

                    console.log('status:' + data.status)

                    if(data.status == true){

                        var options = {
                            title: "削除が完了しました。",
                            icon: 'success',
                            buttons: {
                                ok: true
                            }
                        };
    
                        // then() OKを押した時の処理
                        swal(options)
                            .then(function(val) {
                            if (val) {
                                // 画面更新
                                window.location.reload();
                            }
                        });
                    }

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
     * ページネーションセンター
     */
    $(".pagination").addClass("justify-content-center");
    $("#links").show();

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

            // 募集要項
            if(tab_class == 'trade-tab'){
                                
                console.log('募集要項の処理');

                $('#nav-trade-tab').addClass('bg_tab_error');
                
            }

            // 契約者
            if(tab_class == 'contract-tab'){
                                
                console.log('契約者の処理');

                $('#nav-contract-tab').addClass('bg_tab_error');
                
            }

            // 同居人
            if(tab_class == 'housemate-tab'){
                                
                console.log('契約者の処理');

                $('#nav-housemate-tab').addClass('bg_tab_error');
                
            }

            // 緊急連絡先
            if(tab_class == 'emergency-tab'){
                                
                console.log('契約者の処理');

                $('#nav-emergency-tab').addClass('bg_tab_error');
                
            }

            // 連帯保証人
            if(tab_class == 'guarantor-tab'){
                                
                console.log('契約者の処理');

                $('#nav-guarantor-tab').addClass('bg_tab_error');
                
            }

            // 画像
            if(tab_class == 'document-tab'){
                                
                console.log('契約者の処理');

                $('#nav-document-tab').addClass('bg_tab_error');
                
            }
        }
    }
    
    // ★重要 ソート設定
    $('#sort_table').sortable({"items": "tr.sortable-tr"});

    // 並び替えボタンの処理
    $("#btn_sort_edit").on('click', function(e) {

        console.log('並び替えボタンの処理');

        // 格納する配列
        e.preventDefault();

        let ids = [];

        // テーブルのtr分ループ
        $("#sort_table tbody tr").each(function () {

            let tr_id = $(this).attr("id");

            // console.log(tr);
            ids.push(tr_id.split("_")[1]);

        });

        // idの順番の確認
        console.log(ids);

        // ajax送信

        // 送信データインスタンス化
        var sendData = {
            
            'ids': ids
        };
        
        // ajaxヘッダー
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({
            type: 'post',
            url: 'backSpecialSortEntry',
            dataType: 'json',
            data: sendData,

        // 接続が出来た場合の処理
        }).done(function(data) {

            // trueの処理->申込一覧に遷移
            if(data.status == true){

                console.log("status:" + data.status);

                // alertの設定
                var options = {
                    title: "並び替えが完了しました。",
                    icon: "success",
                    buttons: {
                        ok: true
                    }
                };
                
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    if (val) {

                        location.href = 'backSpecialContractInit';
                    };
                });

                // ローディング画面終了の処理
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);
                
                return false;
            };

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
    
});
