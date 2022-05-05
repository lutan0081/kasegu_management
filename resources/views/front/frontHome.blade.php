<!DOCTYPE html>
<html lang="ja">

    <head>
        <title>ホーム/KASEGU</title>
        <meta name="description" content="「KASEGU-カセグ-」は、不動産賃貸入居申込書のWeb受付システムです。賃貸居住用物件の入居申込とそれに伴う受付業務をWeb上で完結することができます。シームレスな申込情報のやりとりによって、仲介会社さま・管理会社さま双方の業務の効率化が図れます。" />
        <meta name="keywords"  content="IT申込,IT契約,IT重説,不動産IT,賃貸,契約書,重説" />

        <!-- css -->
        @component('component.front_head')
        @endcomponent

        <!-- front_home -->
        <link rel="stylesheet" href="{{ asset('front/css/front_home.css') }}">

        <!-- front_register -->
        <link rel="stylesheet" href="{{ asset('front/css/front_register.css') }}">

        <!-- lode -->
        <link rel="stylesheet" href="{{ asset('lode/css/lode.css') }}">

        <!-- 直接style指定 -->
    </head>

    <body>
        <!-- ローディング画面 -->
        <div id="splash">
            <div id="splash_text"></div>
            <div class="loader_cover loader_cover-up"></div><!--上に上がるエリア-->
            <div class="loader_cover loader_cover-down"></div><!--下に下がるエリア-->
        </div>
        <!-- splash -->

        <!-- メインはこの部分から -->
        <div id="container">

            <!-- ロード画面画面の表示 -->
            <div id="overlay">
                <div class="cv-spinner">
                    <span class="spinner"></span>
                </div>
            </div>

            <!-- ヘッダー -->
            @component('component.front_headar')
            @endcomponent

            <!-- 動画 -->
            <div class="video-container movie">
                <div class="video-wrap overlay">
                    <video src="./front/video/front_top.mp4" type="video/mp4" playsinline loop autoplay muted>
                    </video>
                </div>		
            </div>
            <!-- 動画 -->

            <div class="scrolldown2"><span>Scroll</span></div>

            <!-- TOP動画の文字 -->
            <h1>
                <div class="catch">
                    <span class="top_font_en">Real Estate Work Innovation<br></span>
                    
                    <div class="top_jp_box">
                        <span class="top_font_jp">不動産業務を<br class="sp" />「もっとスムーズに」<br class="sp" />「もっとスピーディーに」<br></span>
                    </div>

                    <div class="top_btn_box">
                        <a href="frontUserInit" class="btn btn-border"><i class="far fa-gem me-2"></i>無料登録</a>
                        <a href="loginInit" class="btn btn-border"><i class="fas fa-sign-in-alt me-2"></i>ログイン</a><br>
                    </div>

                    <span class="top_font_en_bottom">Find your best way that will help change your task<br></span>
                </div>
            </h1>
            <!-- TOP動画の文字 -->

            <!-- INFOMATION -->
            <div class="container-fluid container_fluid_w_80 mt-5">
                <div class="row row-cols-3">

                    <!-- 新着情報一覧 -->
                    <div class="col-6 col-md-6 col-lg-6 mt-5 box fadeLeftTrigger">
                        <span class="new_info_jp">新着情報<br></span>
                        <span class="new_info_en">INFOMATION...</span>
                    </div>
                    <!-- 新着情報一覧 -->

                    <!-- VIEW ALL -->
                    <div class="col-6 col-md-6 col-lg-6 mt-5 box fadeRightTrigger">
                        <span class="view_all_text float-end"><a href="frontInfoInit">VIEW</a></span>
                    </div>
                    <!-- VIEW ALL -->

                </div>
            </div>
            <!-- INFOMATION -->

            <!-- 新着情報内容 -->
            <div class="container-fluid container_fluid_w_80 mt-3">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 mb-4">
                        @foreach($list_info as $index => $info)
                            <div class="row py-4 day info_border-bottom box zoomInTrigger">
                                <!-- レイアウトずれの為、lg-2-2-7に設定 -->
                                <div class="col-12 col-md-4 col-lg-2 ps-lg-5 date">
                                    {{ Common::format_date_hy($info->entry_date) }}
                                </div>

                                <div class="col-5 col-md-3 col-lg-1 label">
                                    お知らせ
                                </div>

                                <div class="col-12 col-md-12 col-lg-7 ps-lg-4 text">
                                    {{ $info->information_contents }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ABOUT KASEGU -->
            <div class="container-fluid container_fluid_w_80 mt-4">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 mb-lg-5">
                        
                        <div class="row">

                            <!-- 左説明 -->
                            <div class="col-12 col-md-12 col-lg-6">
                                
                                <!-- about_kasegu -->
                                <div class="col-12 col-md-12 col-lg-12 mt-5 box fadeLeftTrigger">
                                    <span class="new_info_jp">KASEGUとは...<br></span>
                                    <span class="new_info_en">ABOUT KASEGU...</span>
                                </div>
                                <!-- about_kasegu -->
                            
                                <!-- 業務が変わる -->
                                <div class="col-12 col-md-12 col-lg-12 about_change_task box fadeLeftTrigger">
                                    <span>日々の業務を「改革」</span>
                                </div>
                                <!-- 業務が変わる -->

                                <!-- about_kasegu(説明) -->
                                <div class="col-12 col-md-12 col-lg-12 mt-5 box fadeLeftTrigger">
                                    <span class="lh-lg">
                                        日々の業務に「改革」をコンセプトに提供するアプリケーションです。<br>
                                        不動産業務の効率化「申込管理」「契約管理」「契約書作成」「重説作成」を無料で提供します。<br>
                                        機能追加のご提案などがある場合、メッセージでリクエストしてください。<br>
                                    </span>
                                </div>
                                <!-- about_kasegu(説明) -->
                                
                                <!-- メッセージボタン -->
                                <div class="col-12 col-md-12 col-lg-12 btn_box mt-3 mb-3 box fadeLeftTrigger">
                                    <a href="#" class="btn bgleft"><span>Message</span></a>
                                </div>
                                <!-- メッセージボタン -->

                            </div>
                            <!-- 左説明 -->

                            <!-- 右画像 -->
                            <div class="col-12 col-md-12 col-lg-6 mt-5 box fadeRightTrigger mb_switch">
                                <div class="img_bg mb_switch">
                                    <!-- 画像フィルタ -->
                                    <div class="img_filter mb_switch">
                                        <div class="img_text">
                                            <span class="img_text_en">Change your daily work<br></span>
                                            <span class="img_text_jp">- 日々の業務を「変える」-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 右画像 -->

                        </div>
                    </div>
                </div>
                
            </div>
            <!-- ABOUT KASEGU -->
            
            <!-- 画像並び -->
            <div class="container-fluid mt-5 pb-5 pt-5 pt-md-0">
                <div class="row delayScroll">

                    <div class="col-12 col-md-12 col-lg-4 px-0 box">
                        <div class="bg_dark">
                        
                            <div class="zoomIn">
                                <a href="#">
                                    <span class="mask">
                                        <img src="./front/img/contract.jpg" class="img_size">
                                    </span>
                                    <div class="img_text">
                                        <span class="img_text_en">APPLICATION<br></span>
                                        <span class="img_text_jp">- 申込管理 -</span>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-4 px-0 box">
                        <div class="bg_dark">
                        
                            <div class="zoomIn">
                                <a href="#">
                                    <span class="mask">
                                        <img src="./front/img/read_book.jpg" class="img_size">
                                    </span>
                                    <div class="img_text">
                                        <span class="img_text_en">CONTRACT<br></span>
                                        <span class="img_text_jp">- 契約書 -</span>
                                    </div>
                                </a>
                            </div>
                            
                        </div>
                    </div>
                    

                    <div class="col-12 col-md-12 col-lg-4 px-0 box">
                        <div class="bg_dark">
                        
                            <div class="zoomIn">
                                <a href="#">
                                    <span class="mask">
                                        <img src="./front/img/writing.jpg" class="img_size">
                                    </span>
                                    <div class="img_text">
                                        <span class="img_text_en">DISCLOSURE STATEMENT<br></span>
                                        <span class="img_text_jp">- 重要事項説明書 -</span>
                                    </div>
                                </a>
                            </div>
                            
                        </div>
                    </div>

                </div>
            </div>
            <!-- 画像並び -->

            <!-- ユーザ登録後、ログイン画面に遷移の為のパス設定 -->
            <input type="hidden" id="top_url" value="{{ url('/') }}" />
            
            <!-- free_register-->
            @component('component.front_register')
            @endcomponent

            <!-- フッダー-->
            @component('component.front_footer')
            @endcomponent

        </div>
        <!-- メインはこの部分から -->

        <!-- js -->
        @component('component.front_js')
        @endcomponent

        <!-- プログレスバー -->
        <script src="https://rawgit.com/kimmobrunfeldt/progressbar.js/master/dist/progressbar.min.js"></script>
        
        <!-- 初回ローディング -->
        <script src="{{ asset('lode/js/lode.js') }}"></script>
        
        <!-- front_home -->
        <script src="{{ asset('front/js/front_home.js') }}"></script>
    </body>

</html>