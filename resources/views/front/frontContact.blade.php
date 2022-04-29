<!DOCTYPE html>
<html lang="ja">

    <head>
        <title>アカウント作成/KASEGU</title>

        <!-- css -->
        @component('component.front_head')
        @endcomponent

        <!-- front_contact -->
        <link rel="stylesheet" href="{{ asset('front/css/front_contact.css') }}">

        <!-- front_register -->
        <link rel="stylesheet" href="{{ asset('front/css/front_register.css') }}">

        <!-- 直接style指定 -->
        <style>
            /* ボタンデフォルト値 */
            .btn-default{
                width: 8rem;
            }
        </style>

    </head>

    <body>

        <!-- ロード画面画面の表示 -->
        <div id="overlay">
            <div class="cv-spinner">
                <span class="spinner"></span>
            </div>
        </div>

        <!-- ヘッダー -->
        @component('component.front_headar')
        @endcomponent

        <!-- 表題 -->
        <div class="container-fluid mb-5">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 top_box">
                    <div class="row">
                        <div class="top_img_filter">
                            <div class="col-8 col-md-8 col-lg-6 mx-auto">
                            
                                <div class="top_contents">
                                    <p class="title_en">Message</p>
                                    <p class="title_jp">メッセージ<br></p>
                                
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 表題 -->

        <!-- コンテンツ -->
        <div class="container mb-5">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 my-4">

                    <!-- カードコンテンツ -->
                    <div class="card box zoomInTrigger">

                        <div class="col-12 col-md-10 col-lg-8 mt-5 mt-4 px-3">
                                プログラム・データの不備などございましたらお問い合わせフォームからお願いします。<br><br>

                            <span class="dangerous_text">
                                ※恐れ入りますが、個人情報のお問合せなどの個別の回答は、行っておりませんのでご了承ください。<br>
                                個人情報に関するお問合わせは、仲介業者にご確認下さい。<br><br>
                            </span>

                                ※送信後に受付完了の自動返信メールが送信されます。<br>
                                フリーアドレスや迷惑メール排除の設定をしている場合、メールが届かない場合があります。<br>
                                その際はお手数をおかけしますがメールアドレスを変更して再送してください。<br>
                        </div>  

                        <form id="form" class="needs-validation p-3" novalidate>

                            <!-- 名前 -->
                            <div class="col-12 col-md-10 col-lg-7 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>名前
                                <input type="text" class="form-control" name="name_reqest" id="name" required="">
                                <!-- エラーメッセージ -->
                                <div class="invalid-feedback" id ="name_error">
                                    名前は必須です。
                                </div>
                            </div>

                            <!-- mail -->
                            <div class="col-12 col-md-10 col-lg-7 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>E-mail
                                <input type="mail" class="form-control" name="mail_reqest" id="mail" required>
                                <!-- エラーメッセージ -->
                                <div class="invalid-feedback" id ="mail_error">
                                    E-mailは必須です
                                </div>
                            </div>

                            <!-- タイトル -->
                            <div class="col-12 col-md-12 col-lg-7 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>タイトル
                                <input type="text" class="form-control" name="title_reqest" id="title" required>
                                <div class="invalid-feedback" id ="title_error">
                                    タイトルは必須です。
                                </div>
                            </div>

                            <!-- メッセージ -->
                            <div class="col-12 col-md-12 col-lg-12 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>メッセージ
                                <textarea class="form-control" name="message" id="message" rows="13" required></textarea>
                                <!-- エラーメッセージ -->
                                <div class="invalid-feedback" id ="message_error">
                                    メッセージは必須です。
                                </div>
                            </div>	

                            <div class="col-12 col-md-12 col-lg-12 text-center my-4 pt-2">
                                <button id="btn_edit" class="btn btn-outline-primary btn-default">送信</button>
                            </div>
                            
                            <!-- トップリンク用のURL -->
                            <input type="hidden" id="top_url" value="{{ url('/') }}" />

                        </form>
                    </div>
                    <!-- カードコンテンツ -->
                </div>
            </div>
        </div>
        <!-- コンテンツ -->

        <!-- free_register-->
        @component('component.front_register')
        @endcomponent

        <!-- フッダー-->
        @component('component.front_footer')
        @endcomponent

        <!-- js -->
        @component('component.front_js')
        @endcomponent
        
        <!-- info.js -->
        <script src="{{ asset('front/js/front_contact.js') }}"></script>
    </body>

</html>