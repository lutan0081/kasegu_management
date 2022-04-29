$(function() {
    
    /**
     * 登録
     */
    $("#btn_edit").on('click', function(e) {

        console.log("登録ボタンがクリックされています");

        // ローディングの処理
        $("#overlay").fadeIn(300);

        e.preventDefault();

        let forms = $('.needs-validation');
        console.log(forms[0].length);

        //  validationの初期値
        let v_check = true;

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

        }

        // チェック=falseの処理
        console.log(v_check);
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
        // Mail
        let mail = $("#mail").val();
        // 氏名
        let name = $("#name").val();
        // 郵便番号
        let post = $("#post").val();
        // 住所
        let address = $("#address").val();
        // tel
        let tel = $("#tel").val();
        // fax
        let fax = $("#fax").val();
        // パスワード
        let password = $("#password").val();
        // パスワード確認
        let password_conf = $("#password_conf").val();
        // チェックボックス/チェック:true チェック無し:false
        let agree =$("#agree").prop("checked") 

        // 送信用データ設定
        let sendData = {

            "name": name,
            "mail": mail,
            "post": post,
            "address": address,
            "tel": tel,
            "fax": fax,
            "password": password,
            "password_conf": password_conf,
            "agree": agree

        };
        console.log("sendData:"+ sendData);

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
                title: "仮登録が完了しました。",
                text: " ご登録のメールアドレスに本登録用URLを送信しました。URLをクリックし本登録を完了してください。",
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

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });

            let def = new $.Deferred();
            $.ajax({
                
                    type:'GET',
                    url:'frontUserEdit',
                    dataType:'json',
                    data: sendData,

                }).done((data) => {
                    
                    console.log("1個目のajax実行後の処理");
                    console.log(data.status);
                    console.log(data.m_create_user_id);

                    // ajax送信前に付与された.was-validatedクラスを削除、テキストの値を空にする
                    $('#userForm').removeClass('was-validated');
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
                        def.resolve(data.m_create_user_id);
                    }
                    
                // 失敗の処理
                }).fail((data) => {
                    
                    // プログラム強制終了
                    reject(data);
            });
            return def.promise();
        }

        // メール送信の処理
        function f2(create_user_id) {

            console.log('create_user_id:' + create_user_id)

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });

            let def = new $.Deferred();
            $.ajax({
                    type:'GET',
                    url:'frontUserMail?create_user_id=' + create_user_id,
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

    /**
     * ローディング画面の表示
     */
    $(".btn_zip").on('click', function(e) {
        console.log("btn_zipクリックされています");

        $("#overlay").fadeIn(300);
        
        e.preventDefault();

        /**
         * 住所検索ボタンのid取得
         */
        var post_number_id = $(this).attr('id');
        console.log(post_number_id);
        
        // 郵便番号初期値:空白
        let post_number = '';

        post_number = $('#post').val();
        console.log('住所検索ボタンだよ');

        // 郵便番号が空白の場合のプログラム終了
        if(post_number==""){

            // ローディング画面終了の処理
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
                // ユーザ登録ボタン
                let address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
                console.log(address);
                $('#address').val(address);
                console.log('ajax通信後の処理');
            }
        // ajax接続が出来なかった場合の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
        
    });

    /**
     * パスワード再入力の処理
     * パスワード != パスワード再入力、error_messageの表示
     * パスワード = パスワード再入力、error_messageの非表示
     */
    $("#password_conf").keyup(function(e) {
        // パスワード = パスワード再入力で無い場合、error表示
        let password = $("#password").val();
        let password_conf = $("#password_conf").val();

        if(!(password == password_conf)){
            $('#password_conf').addClass('error_text');
            $('#password_conf_error').text('パスワードが異なります。').show();
        }else{
            $('#password_conf_error').text('').show();
        }
    });

    $("#password").keyup(function(e) {
        // パスワード = パスワード再入力で無い場合、error表示
        let password = $("#password").val();
        let password_conf = $("#password_conf").val();

        if(!(password == password_conf)){
            $('#password_conf').addClass('error_text');
            $('#password_conf_error').text('パスワードが異なります。').show();
        }else{
            $('#password_conf_error').text('').show();
        }
    });

});

