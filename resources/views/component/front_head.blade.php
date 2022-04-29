<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<link rel="icon" href="./icon/kasegu_icon_64.ico">
<!--==============レイアウトを制御する独自のCSSを読み込み===============-->
<!-- トークン -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- レスポンシブ -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- boot.5.1.1 -->
<link rel="stylesheet" href="{{ asset('bootstrap-5.1.1/dist/css/bootstrap.min.css') }}">

<!-- bootstrap-datepicker-1.9.0-dist -->
<link rel="stylesheet" href="{{ asset('bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker.min.css') }}">

<!-- icon -->
<script src="https://kit.fontawesome.com/33bf6d5577.js" crossorigin="anonymous"></script>

<!-- ヘッダー -->
<link rel="stylesheet" href="{{ asset('front/css/front_header.css') }}">

<!-- フッター -->
<link rel="stylesheet" href="{{ asset('front/css/front_footer.css') }}">

<!-- Common(scroll bar等) -->
<link rel="stylesheet" href="{{ asset('common/css/common.css') }}">

<!-- fadeUpContinue(連続でフェードイン　親要素に"delayScroll指定"、子要素にbox) -->
<link rel="stylesheet" href="{{ asset('fadeUpContinue/css/fadeUpContinue.css') }}">

<!-- fadeAnimematon -->
<link rel="stylesheet" href="{{ asset('fadeAnimematon/css/fadeAnimematon.css') }}">

<!-- ajaxLoding.css -->
<link rel="stylesheet" href="{{ asset('ajaxLoding/css/ajaxLoding.css') }}">