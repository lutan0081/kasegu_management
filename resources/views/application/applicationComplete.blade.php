<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>顧客登録/KASEGU</title>
        <!-- css -->
        @component('component.head')
        @endcomponent

        <!-- applicationComplete.css -->
        <link rel="stylesheet" href="{{ asset('application/css/applicationComplete.css') }}">

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
                font-size: 1.0rem;
                padding: 0;
            }
        }
        @media (max-width: 768px) {
            .text_contents {
                text-align: center;
                font-size: 1rem;
            }
        }
        </style>

    </head>
    <body>
        
        <!--ヘッダー-->
        @component('component.header')
        @endcomponent

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
                                <h2>新規申込が完了しました。</h2>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 col-lg-12 mt-4">
                            <!-- 内容 -->
                            <div class="text_contents box fadeUpTrigger">
                                編集登録用のURLをメールでお送りしました。<br>
                                編集登録の際は、そちらから編集してください。<br>
                            </div>
                        </div>
                
                        <!-- ログインボタン -->
                        <div class="col-12 col-md-12 col-lg-12 my-5 text-center box fadeRightTrigger">
                            <hr>
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

        <!-- mailComplete -->
        <script src="{{ asset('mail/js/mailComplete.js') }}"></script>
    </body>

</html>