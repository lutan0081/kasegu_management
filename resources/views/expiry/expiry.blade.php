<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ご指定のページが見つかりません/KASEGU</title>
        <!-- css -->
        @component('component.head')
        @endcomponent

        <!-- edit.css -->
        <link rel="stylesheet" href="{{ asset('expiry/css/expiry.css') }}">

        <style>
            .container{
                font-family: 'Nunito', sans-serif;
                width: 90%;
                box-shadow: 0 30px 60px 0 rgb(0 0 0 / 30%);
            }
        /* mobile画面になった時
        　 画面いっぱいの大きさにする */
        @media (max-width: 768px) {
            .container{
                width: 80%;
            }
        }
        @media (max-width: 768px) {
            h2{
                font-size: 1rem;
                padding: 0;
            }
        }
        </style>

    </head>
    <body>
        
        <!-- boxセンター寄り -->
        <div class="box_center">
            <!-- 親要素 -->
            <div class="container">
                <!-- 行 -->
                <div class="row row-cols-1">
                    <!-- 表題 -->
                    <div class="col-12 col-md-12 col-lg-12 p-5">

                        <div class="col-12 col-md-12 col-lg-12 mt-5">
                            <div class="title box fadeLeftTrigger">
                                <h2>ご指定のページが見つかりません。</h2>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 col-lg-12 mt-4">
                            <!-- 内容 -->
                            <div class="text_contents box fadeUpTrigger">
                                申し訳ございません。<br>
                                お客様のお探しのページが見つかりません。有効期限外か削除された可能性があります。
                                <hr>
                                <span class="danger_info">※注意事項<br></span>
                                お手数ですが、下部ボタンからログイン画面に移動し初めからやり直してください。
                            </div>
                        </div>
                    
                        <!-- ログインボタン -->
                        <div class="col-12 col-md-12 col-lg-12 my-5 text-center box fadeRightTrigger">
                            <a href="{{ url('/') }}" class="btn btn-outline-primary">ログイン画面へ</a>
                        </div>

                    </div>
                <!-- 行 -->
                </div>
            <!-- 親要素 -->
            </div>
        <!-- boxセンター寄り -->
        </div> 

        <!-- js -->
        @component('component.js')
        @endcomponent

        <!-- expiry -->
        <!-- <script src="{{ asset('expiry/js/expiry.js') }}"></script> -->
    </body>

</html>