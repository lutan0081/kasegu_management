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

    }
    
    /**
     * ページネーションセンター
     */
    $(".pagination").addClass("justify-content-center");
    $("#links").show();

    /**
     *  編集(ダブルクリックの処理)
     */
    $(".click_class").on('dblclick', function(e) {

        console.log("ダブルクリックの処理.");

        // ローディング画面
        $("#overlay").fadeIn(300);
        
        // tdのidを配列に分解
        var id = $(this).attr("id");
        id = id.split('_')[1];
        console.log(id);

        setTimeout(function(){
            $("#overlay").fadeOut(300);
        },500);
    
        // idをパラメーターでControllerに渡す
        location.href = "adminUserEditInit?create_user_id=" + id;
        
    });

    /**
     * 編集(ラジオボタンの処理)
     */
    $("#btn_edit").on('click', function(e) {
        console.log("編集ボタンの処理");

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
        var id = $('input[name=flexRadioDisabled]:checked').attr('id');
        console.log(id);

        setTimeout(function(){
            $("#overlay").fadeOut(300);
        },500);

        // idをパラメーターでControllerに渡す
        location.href = "adminUserEditInit?create_user_id=" + id;
    });

});