<!DOCTYPE html>
<html lang="ja">

    <head>
        <title>アカウント作成/KASEGU</title>

        <!-- css -->
        @component('component.front_head')
        @endcomponent

        <!-- front_info -->
        <link rel="stylesheet" href="{{ asset('front/css/front_user.css') }}">

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
                                    <p class="title_en">Account</p>
                                    <p class="title_jp">アカウント作成<br></p>
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
                            ご登録後、ご入力して頂いたメールアドレスに本登録用URLが届きます。<br>
                            メール本文中の本登録URLをクリックし、24時間以内に本登録を完了させてください。<br><br>
                            仮登録後10分経過しても仮登録完了のメールが届かない場合、最初からお手続きください。<br>
                        </div>  

                        <form id="form" class="needs-validation p-3" novalidate>

                            <!-- 名前 -->
                            <div class="col-12 col-md-10 col-lg-8 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>名前
                                <input type="text" class="form-control" name="name_reqest" id="name" required>
                                <!-- エラーメッセージ -->
                                <div class="invalid-feedback" id ="name_error">
                                    名前は必須です。
                                </div>
                            </div>

                            <!-- mail -->
                            <div class="col-12 col-md-10 col-lg-8 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>E-mail
                                <input type="mail" class="form-control" name="mail_reqest" id="mail" required>
                                <!-- エラーメッセージ -->
                                <div class="invalid-feedback" id ="mail_error">
                                    E-mailは必須です
                                </div>
                            </div>

                            <!-- 郵便番号、検索ボタン -->
                            <div class="col-7 col-md-7 col-lg-2 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>郵便番号
                                <div class="input-group">
                                    <input type="text" id="post" class="form-control" name="post_reqest" aria-label="Recipient's username" aria-describedby="button-addon2" required>
                                    <button class="btn btn-outline-primary btn_zip" type="button" id="user-btn-zip" >検索</button>
                                    <div class="invalid-feedback" id="post_error">
                                        郵便番号は必須です。
                                    </div>
                                </div>
                            </div>
                            <!-- 郵便番号、検索ボタン -->

                            <!-- 改行 -->
                            <div class="w-100"></div>

                            <!-- 住所 -->
                            <div class="col-12 col-md-12 col-lg-10 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>住所
                                <input type="text" class="form-control" name="address_reqest" id="address" required>
                                <div class="invalid-feedback" id ="address_error">
                                    住所は必須です。
                                </div>
                            </div>

                            <!-- Tel -->
                            <div class="col-12 col-md-6 col-lg-6 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>Tel
                                <input type="text" class="form-control" name="tel_reqest" id="tel" required>
                                <div class="invalid-feedback" id ="tel_error">
                                    Telは必須です。
                                </div>
                            </div>

                            <!-- Fax -->
                            <div class="col-12 col-md-6 col-lg-6 mt-3">
                                <label class="label_any mb-2" for="textBox"></label>Fax
                                <input type="text" class="form-control" name="fax_reqest" id="fax">
                                <div class="invalid-feedback" id ="fax_error">
                                </div>
                            </div>

                            <!-- パスワード -->
                            <div class="col-12 col-md-6 col-lg-6 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>パスワード
                                <input type="password" class="form-control" name="password_reqest" id="password" required autocomplete="off">
                                <div class="invalid-feedback" id ="password_error">
                                    パスワードは必須です。
                                </div>
                            </div>

                            <!-- 改行 -->
                            <div class="w-100"></div>

                            <!-- パスワード確認 -->
                            <div class="col-12 col-md-6 col-lg-6 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>パスワード再入力
                                <input type="password" class="form-control" name="password_conf_reqest" id="password_conf" required autocomplete="off">
                                <div class="invalid-feedback" id ="password_conf_error">
                                    パスワードが異なります。
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-12 col-lg-12 text-center mt-4 mb-3">
                                <div class="invalid-feedback" id ="agree_error">
                                    個人情報保護方針に同意して下さい。
                                </div>
                                <label class="mt-3" for="c_agree">
                                <input id="agree" type="checkbox" required> 個人情報保護方針に同意する</label>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 text-center">
                                <label class="mb-4" for="c_agree"><a class="f_link_line_cb" href="frontPrivacyInit">個人情報保護方針規約</a></label>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 text-center mb-3">
                                <button id="btn_edit" class="btn btn-outline-primary btn-default">登録</button>
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

        <!-- フッダー-->
        @component('component.front_footer')
        @endcomponent

        <!-- js -->
        @component('component.front_js')
        @endcomponent
        
        <!-- info.js -->
        <script src="{{ asset('front/js/front_user.js') }}"></script>
    </body>

</html>