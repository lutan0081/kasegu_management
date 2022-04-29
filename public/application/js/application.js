/**
 * 申込URL発行
 */
$(function() {

    // ローディング画面開始の処理
    $(document).ajaxSend(function() {
        $("#overlay").fadeIn(300);　
    });

    /**
     * 送信ボタンの処理
     */
    $(".btn").on('click', function(e) {
        console.log("送信ボタンがクリックされています.");

        // 記載必須（別URLに誘導される）
        e.preventDefault();

        /**
         * formに空白有の場合、赤文字で表示
         */
        let forms = $('.needs-validation');
        console.log(forms[0].length);

        //  validationの初期値
        let v_check = true;

        for (let i = 0; i < forms[0].length; i++) {
            // forms[0]=form.[i]=中の項目;
            let form = forms[0][i];
            console.log('from:'+ form);

            // タグ名を取得 input or button
            let tag = $(form).prop("tagName");
            console.log('tag:'+ tag);

            let f_id = $(form).prop("id");
            console.log('id:'+ f_id);

            // form内のbuttonはスルー
            if (tag == 'BUTTON') {
                continue;
            }

            // formの値を確認->クラス付与（Message表示）
            let val = $(form).val();
            console.log(val);
            if (val === '') {
                $(forms).addClass("was-validated");
                v_check = false;
            }
        }

        /**
         * チェック=falseの処理
         */
        console.log(v_check);
        if (v_check === false) {
            return false;
        }

        /**
         * inputタグからの値取得
         */
        // 依頼者
        let application_name = $("#application_name").val();
        // mail
        let application_mail = $("#application_mail").val();

        // 一度登録ボタンを押した後、ボタンを押せなくする
        $('#btn_send').prop('disabled',true);

        /**
         * ajax
         */
        // 送信用データ設定
        let sendData = {
                "application_name": application_name,
                "application_mail": application_mail
        };

        /**
         * ajax接続
         */
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
            type: 'post',
            url: 'applicationEntry',
            dataType: 'json',
            data: sendData,
        
        // 接続処理
        }).done(function(data) {

            console.log("ajax接続されています。");
            console.log("data_status:" + data.status);

            // ajax接続前に付与された.was-validatedクラスを削除、テキストの値を空にする
            $('#from').removeClass('was-validated');
            $('.invalid-feedback').text('');

            /**
             * formの全要素をerror_Messageを非表示に変更
             * form数をループ処理
             */
            // form要素数取得
            let forms = $('.needs-validation');
            // form要素数ループ
            for (let i = 0; i < forms[0].length; i++) {
                let form = forms[0][i];
                $(form).addClass("is-valid");
                $(form).removeClass('is-invalid');
            }

            /**
             * falseの場合の処理
             */
            if(data.status == false){
                console.log("errorkeys:" + data.errkeys);

                // 送信ボタンを利用可能にする
                $('#btn_send').prop('disabled',false);

                // ローディング画面終了の処理
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);
                
                /**
                 * formの全要素をerror_Messageを表示に変更
                 * error数だけループ処理
                 */
                for (let i = 0; i < data.errkeys.length; i++) {
                    //　bladeの各divにclass指定
                    let id_key = "#" + data.errkeys[i];
                    $(id_key).addClass('is-invalid');

                    // エラーメッセージの追加、表示
                    let msg_key = "#" + data.errkeys[i] + "_error"
                    // error_messageテキスト追加
                    $(msg_key).text(data.messages[i]);
                    $(msg_key).show();
                }
                return false;
            }

            /**
             * trueの場合の処理
             */
            if(data.status == true){ 
                console.log(data.status);
                // alertの設定
                var options = {
                    title: "申込URLを発行しました。",
                    text: "依頼者のご登録後、Home画面より顧客情報を確認することが出来ます。",
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
                        location.href = "homeInit"
                    }
                });

            };
        // ajax接続失敗の時の処理
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
