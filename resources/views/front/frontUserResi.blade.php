<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>お知らせ/KASEGU</title>

        <!-- css -->
        @component('component.front_head')
        @endcomponent

        <!-- mailComplete -->
        <link rel="stylesheet" href="{{ asset('front/css/front_user_resi.css') }}">

        <style>

            .container{
                width: 90%;
                box-shadow: 0 30px 60px 0 rgb(0 0 0 / 30%);
            }

            .btn_default {
                width: 8rem;
            }

            /* スマホ */
            @media (max-width: 768px) {
                .container{
                    width: 80%;
                }

                h2{
                    font-size: 1rem;
                    padding: 0;
                }

                .text_contents {
                    text-align: left;
                    font-size: 10px;
                }

                .btn_default {
                    width: 6rem;
                    font-size: 0.5rem;
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
                                <h2>本登録が完了しました。</h2>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 col-lg-12 mt-4">
                            <!-- 内容 -->
                            <div class="text_contents box fadeUpTrigger">
                                ユーザ登録いただき、ありがとうございます。<br>
                                引き続き、KASEGUをご愛顧の程よろしくお願い申し上げます。<br>
                            </div>
                        </div>
                
                        <!-- ログインボタン -->
                        <div class="col-12 col-md-12 col-lg-12 my-5 text-center box fadeRightTrigger">
                            <hr>
                            <a href="{{ url('/') }}" class="btn btn-outline-primary btn_default">トップ画面</a>
                        </div>

                    </div>
                <!-- 行 -->
                </div>
            <!-- 親要素 -->
            </div>
        <!-- boxセンター寄り -->
        </div> 

        <!-- js -->
        @component('component.front_js')
        @endcomponent

    </body>

</html>