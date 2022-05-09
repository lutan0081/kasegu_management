/**
 * ふわっと文字表示
 */
// blurTriggerにblurというクラス名を付ける定義
function BlurTextAnimeControl() {
    $('.blurTrigger').each(function(){ //blurTriggerというクラス名が
        var elemPos = $(this).offset().top-50;//要素より、50px上の
        var scroll = $(window).scrollTop();
        var windowHeight = $(window).height();
        if (scroll >= elemPos - windowHeight){
        $(this).addClass('blur');// 画面内に入ったらblurというクラス名を追記
        }else{
        $(this).removeClass('blur');// 画面外に出たらblurというクラス名を外す
        }
    });
}

// 画面をスクロールをしたら動かしたい場合の記述
$(window).scroll(function () {
    BlurTextAnimeControl();/* アニメーション用の関数を呼ぶ*/
});// ここまで画面をスクロールをしたら動かしたい場合の記述

/**
 * topの文字出現のコントロール
 * 時間の調整
 */
$(function(){
    setInterval(function(){
        $('.catch').addClass('blurTrigger');
        $('.catch').removeClass("catch");
        BlurTextAnimeControl();/* アニメーション用の関数を呼ぶ*/
    },1000);// ここまで画面が読み込まれたらすぐに動かしたい場合の記述
});

/**
 * TOP下画面のborderアニメーション
 */
$(window).on('scroll',function(){
    $(".JS_ScrollAnimationItem").each(function(){
        var position = $(this).offset().top;
        var scroll = $(window).scrollTop();
        var windowHeight = $(window).height();
        if (scroll > position - windowHeight){
        $(this).addClass('isActive');
        }
    });
});

/**
 * hrのアニメーション
 */
$(window).on('scroll',function(){
    $(".boderTrigger").each(function(){
        var position = $(this).offset().top;
        var scroll = $(window).scrollTop();
        var windowHeight = $(window).height();
        if (scroll > position - windowHeight){
            $("hr").css('width', '100%');
        }
    });
});
