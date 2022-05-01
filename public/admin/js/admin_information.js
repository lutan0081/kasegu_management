$(function(){

    // ページネーションセンター
    $(".pagination").addClass("justify-content-center");
    $("#links").show();

    /**
     * 編集(ダブルクリックの処理:ajax)
     */
    $(".click_class").on('dblclick', function(e) {

        console.log("ダブルクリックの処理.");

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
    $("#information_contents").on('dblclick', function(e) {

        console.log("編集ボタンの処理");

        // ローディング画面
        $("#overlay").fadeIn(300);

        e.preventDefault();
    });
});