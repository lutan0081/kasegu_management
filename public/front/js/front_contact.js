$(function() {

    /**
     * 登録ボタン(ajax)
     */
    $("#btn_edit").on('click', function(e) {
        
        console.log("btn_editクリックされています");

        // ローディングの処理
        $("#overlay").fadeIn(300);
        
        e.preventDefault();

        // バリデーション
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
            
            // form内のbuttonはスルー
            if (tag == 'BUTTON') {
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
        console.log('v_check:' + v_check);
        if (v_check === false) {

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

            return false;
        }

        // ボタン使用不可
        $('#btn_edit').attr('disabled',true);

        // 値取得
        // 氏名
        let name = $("#name").val();
        // メール
        let mail = $("#mail").val();
        // タイトル
        let title = $("#title").val();
        // メッセージ
        let message = $("#message").val();

        // 送信用データ設定
        let sendData = {
            "name": name,
            "mail": mail,
            "title": title,
            "message": message,
        };
        console.log("sendData:"+ sendData);

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        // ===========================
        // ajax直列
        // ===========================
        f1().then(f2).then(function(){

            // ローディング画面終了の処理
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);

            console.log('処理が完了しました。');

            // alertの設定
            var options = {
                title: "お問合せありがとうございます。",
                text: "引き続き、KASEGUをご愛顧の程よろしくお願い申し上げます。",
                icon: "success",
                buttons: {
                ok: true
                }
            };
            
            swal(options)
                .then(function(val) {
                if (val) {
                    location.href = $('#top_url').val(); 
                    // location.href = 'loginInit';
                };
            });

            return false;
        });

        // ユーザ登録の処理
        function f1() {

            let def = new $.Deferred();
            $.ajax({
                
                    type:'GET',
                    url:'frontContactEntry',
                    dataType:'json',
                    data: sendData,

                }).done((data) => {
                    
                    console.log("1個目のajax実行後の処理");
                    console.log(data.status);

                    // ajax送信前に付与された.was-validatedクラスを削除、テキストの値を空にする
                    $('#form').removeClass('was-validated');
                    $('.invalid-feedback').text('');

                    // formの全要素をerror_Messageを非表示に変更
                    let forms = $('.needs-validation');
                    for (let i = 0; i < forms[0].length; i++) {
                        let form = forms[0][i];
                        $(form).addClass("is-valid");
                        $(form).removeClass('is-invalid');
                    }

                    // falseの場合の処理
                    if(data.status == false){

                        console.log('falseの処理')
                        console.log("errorkeys:" + data.errkeys);

                        // ローディング画面終了の処理
                        setTimeout(function(){
                            $("#overlay").fadeOut(300);
                        },500);

                        // ボタン使用可にする
                        $('#btn_edit').prop('disabled',false);

                        for (let i = 0; i < data.errkeys.length; i++) {

                            //　bladeの各divにclass指定
                            let id_key = "#" + data.errkeys[i];
                            $(id_key).addClass('is-invalid');
            
                            // 表示箇所のMessageのkey取得
                            let msg_key = "#" + data.errkeys[i] + "_error"
                            // error_messageテキスト追加
                            $(msg_key).text(data.messages[i]);
                            $(msg_key).show();
                        }
                    }

                    // trueの場合の処理
                    if(data.status == true){

                        console.log('trueの処理')
                        
                        // 次のajax接続(f2の引数)
                        def.resolve();
                    }
                    
                // 失敗の処理
                }).fail((data) => {
                    
                    // プログラム強制終了
                    reject(data);
            });
            return def.promise();
        }

        // メール送信の処理
        function f2() {
            
            let def = new $.Deferred();
            $.ajax({
                    type:'GET',
                    url:'frontContactMailEntry',
                    dataType:'json',
                    data: sendData,

                }).done((data) => {

                    console.log("2個目のajax実行後の処理");
                    console.log(data.status);

                    // falseの処理
                    if(data.status == false){

                        // ローディング画面終了の処理
                        setTimeout(function(){
                            $("#overlay").fadeOut(300);
                        },500);

                        // ボタン使用可にする
                        $('#btn_edit').prop('disabled',false);
                    }

                    // trueの処理
                    if(data.status == true){
                        // 次へ行ってOK
                        def.resolve();
                    }

                }).fail((data) => {
                    // プログラム強制終了
                    reject(data);
            });
            return def.promise();
        }
        return false;
    });
});

