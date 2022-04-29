$(function(){

    /**
     * 住所検索
     */
    $(".btn_zip").on('click', function(e) {
        console.log("btn_zipクリックされています");
        
        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);

        let post_number = $('#guaranty_association_post_number').val();

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
                $('#guaranty_association_address').val(address);

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

            /**
             * f_id = 各値のid
             * 必須項目に空白があった場合、was-validatedクラスを代入し赤文字でErrorメッセージを表示
             * f_id = 任意(スルーしてもいい項目の為)の項目の為、continueで通過
             */
            // tel
            if (f_id == 'guaranty_association_tel') {
                continue;
            }

            // fax
            if (f_id == 'guaranty_association_fax') {
                continue;
            }

            // id
            if (f_id == 'guaranty_association_id') {
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

        // 保証協会id
        let guaranty_association_id = $("#guaranty_association_id").val();

        // 名称
        let guaranty_association_name = $("#guaranty_association_name").val();

        // 郵便番号
        let guaranty_association_post_number = $("#guaranty_association_post_number").val();

        // 住所
        let guaranty_association_address = $("#guaranty_association_address").val();

        // tel
        let guaranty_association_tel = $("#guaranty_association_tel").val();
        
        // fax
        let guaranty_association_fax = $("#guaranty_association_fax").val();
        
        // 送信データインスタンス化
        var sendData = new FormData();
        
        sendData.append('guaranty_association_id', guaranty_association_id);
        sendData.append('guaranty_association_name', guaranty_association_name);
        sendData.append('guaranty_association_post_number', guaranty_association_post_number);
        sendData.append('guaranty_association_address', guaranty_association_address);
        sendData.append('guaranty_association_tel', guaranty_association_tel);
        sendData.append('guaranty_association_fax', guaranty_association_fax);
        
        // ajaxヘッダー
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $.ajax({
            type: 'post',
            url: 'backGuarantyAssociationEditEntry',
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

                        location.href = 'backGuarantyAssociationInit';
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
                        ok: 'OK'
                    }
                };
                
                // then() OKを押した時の処理
                swal(options)
                    .then(function(val) {
                    /**
                     * ダイアログ外をクリックされた場合、nullを返す為
                     * ok,nullの場合の処理を記載
                     */
                    if (val == 'ok' || val == null) {

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
     * 削除
     */
    $("#btn_delete").on('click', function(e) {

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
        let guaranty_association_id = $("#guaranty_association_id").val();
        console.log(guaranty_association_id);
        
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

                    "guaranty_association_id": guaranty_association_id,
                };

                console.log(sendData);

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                $.ajax({

                    type: 'post',
                    url: 'backGuarantyAssociationDeleteEntry',
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

                            location.href="backGuarantyAssociationInit"
                            
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

});
