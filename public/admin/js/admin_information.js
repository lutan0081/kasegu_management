$(function(){

    /**
     * ページネーションセンター
     */
    $(".pagination").addClass("justify-content-center");
    $("#links").show();

    /**
     * 編集(ダブルクリックの処理:ajax)
     */
    $(".click_class").on('dblclick', function(e) {

        console.log("ダブルクリックの処理");

        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);

        // tdのidを配列に分解
        var id = $(this).attr("id");

        var information_id = id.split('_')[1];
        console.log(information_id);

        // 送信データ
        let sendData = {

			"information_id": information_id,

        };

        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}

        });

        $.ajax({

            type: 'post',
            url: 'adminInformationEditInit',
            dataType: 'json',
            data: sendData,
        
        // 接続処理
        }).done(function(data) {

            console.log(data.information_list[0]['information_title']);

            /**
             * 値代入
             */
            // id
            $("#information_id").val(data.information_list[0]['information_id']);

            // タイトル名
            $("#information_title").val(data.information_list[0]['information_title']);

            // 種別
            $("#information_type").val(data.information_list[0]['information_type_id']);

            // 内容
            $("#information_contents").val(data.information_list[0]['information_contents']);

            // モーダル開く
            $('#informaitonModal').modal('show');

            // ローディング画面停止
			setTimeout(function(){
				$("#overlay").fadeOut(300);
			},500);

            return false;
    
        // ajax接続失敗の時の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown); 

        });

    });

    /**
     * 登録
     */
    $("#btn_modal_url_send").on('click', function(e) {

        console.log("登録の処理");

        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);

        // id
        var information_id = $('#information_id').val();
        
        var information_title = $('#information_title').val();

        var information_contents = $('#information_contents').val();

        var information_type_id = $('#information_type').val();

        // 送信データ
        let sendData = {

			"information_id": information_id,

            "information_title": information_title,

            "information_contents": information_contents,

            "information_type_id": information_type_id,

        };

        $.ajaxSetup({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}

        });

        $.ajax({

            type: 'post',
            url: 'adminInformationEditEntry',
            dataType: 'json',
            data: sendData,
        
        // 接続処理
        }).done(function(data) {

            console.log(data);
            
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

                        location.href = 'adminInformationInit';
                    };
                });

                // ローディング画面終了の処理
                setTimeout(function(){
                    $("#overlay").fadeOut(300);
                },500);
                
                return false;
    
        // ajax接続失敗の時の処理
        }).fail(function(jqXHR, textStatus, errorThrown) {

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

        });
    });

    /**
     * 削除
     */
    $("#btn_modal_delete").on('click', function(e) {

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
        let information_id = $("#information_id").val();
        console.log(information_id);
        
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

                    "information_id": information_id,
                };

                console.log(sendData);

                $.ajaxSetup({

                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                
                });

                $.ajax({

                    type: 'post',
                    url: 'adminDeleteEntry',
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

                            location.href="adminInformationInit"
                            
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