$(function(){

    /**
     * 編集ダブルクリック
     */
    $(".click_class").on('dblclick', function(e) {
        console.log("ダブルクリックの処理.");

        // ローディング画面
        $("#overlay").fadeIn(300);

        // tdのidを配列に分解
        var id = $(this).attr("id");
        console.log(id);

        setTimeout(function(){
            $("#overlay").fadeOut(300);
        },500);

        // idをパラメーターでControllerに渡す
        location.href = "adminContractEditInit?contract_detail_id=" + id;
    });

    /**
     * 複製登録の処理
     */
    $("#btn_clone").on('click', function(e) {

        console.log("複製登録の処理");

        e.preventDefault();

        // ローディング画面
        $("#overlay").fadeIn(300);


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
        var id = $('input[name=flexRadioDisabled]:checked').attr('id');
        console.log(id);

        // 複製登録のフラグ
        let clone_flag = 'true';

        // idをパラメーターでControllerに渡す
        location.href = "backContractEditInit?contract_detail_id=" + id + '&clone_flag=' + clone_flag;

        setTimeout(function(){
            $("#overlay").fadeOut(300);
        },500);
    
    });
});