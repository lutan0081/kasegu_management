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

        <!-- Googleフォント -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ms+Madi&display=swap" rel="stylesheet">

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
                    
                    <!-- トップの日本語ボックス -->
                    <div class="top_jp_box">
                        <span class="top_font_jp">不動産業務を<br class="sp" />「もっとスムーズに」<br class="sp" />「もっとスピーディーに」<br></span>
                    </div>

                    <!-- トップのボタンボックス -->
                    <div class="top_btn_box">
                        <a href="frontUserInit" class="btn btn-border"><i class="far fa-gem me-2"></i>無料登録</a>
                        <a href="loginInit" class="btn btn-border"><i class="fas fa-sign-in-alt me-2"></i>ログイン</a><br>
                    </div>

                    <span class="top_font_en_bottom">Find your best way that will help change your task<br></span>

                </div>
            </h1>

            <!-- 効率の悪い業務をしてませんか？ -->
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 top_bottom_title_box mt-xl-5">
                        <p class="Text"><span class="Text-Span JS_ScrollAnimationItem">効率の悪い仕事をしてませんか？</span></p>
                    </div>
                    
                </div>
            </div>
            <!-- 効率の悪い業務をしてませんか？ -->

            <!-- 申込管理 -->
            <div class="container mt-xl-5 pt-xl-5 mb-xl-5">
                <div class="row">

                    <!-- 左ボックス -->
                    <div class="col-12 col-md-12 col-lg-6 box fadeLeftTrigger">
                        <div class="bg_dark">
                            
                            <div class="zoomIn">
                                <a href="#">
                                    <span class="mask">
                                        <img src="./front/img/read_book.jpg" class="img_size">
                                    </span>
                                    <div class="img_text">
                                        <span class="img_text_en">Application Management<br></span>
                                        <span class="img_text_jp">申込管理</span>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                    
                    <!-- 右ボックス -->
                    <div class="col-12 col-md-12 col-lg-6 app_right_box box fadeLeftTrigger">

                        <span class="app_right_text">
                            「申込管理」は、賃貸居物件の入居申込とそれに伴う業務をWeb上で完結できるサービスです。<br><br>
                        </span>
                        
                        <div>
                            <span class="fw-bold">■機能紹介<br><br></span>
                            01.Web申込管理<br><br>
                            02.申込詳細URL発行<br><br>
                            03.進捗状況管理<br><br>
                            04.帳票作成<br>

                        </div>

                        <!-- メッセージボタン -->
                        <div class="col-12 col-md-12 col-lg-12 btn_box mt-5 box fadeLeftTrigger">
                            <a href="#" class="btn bgleft float-xl-end">
                                <span>詳細はこちら<i class="fas fa-download ms-1"></i></span>
                            </a>
                        </div>
                        <!-- メッセージボタン -->

                    </div>
                    <!-- 右ボックス -->

                </div>
            </div>
            <!-- 申込管理 -->

            <!-- 契約管理 -->
            <div class="container mt-5 pt-5">
                <div class="row">

                    <!-- 右ボックス -->
                    <div class="col-12 col-md-12 col-lg-6 box fadeRightTrigger order-xl-2">
                        <div class="bg_dark">
                            
                            <div class="zoomIn">
                                <a href="#">
                                    <span class="mask">
                                        <img src="./front/img/writing.jpg" class="img_size">
                                    </span>
                                    <div class="img_text">
                                        <span class="img_text_en">Contract Management<br></span>
                                        <span class="img_text_jp">契約管理</span>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                    <!-- 右ボックス -->

                    <!-- 左ボックス -->
                    <div class="col-12 col-md-12 col-lg-6 contract_left_box box fadeRightTrigger order-xl-1">

                        <span class="contract_left_text">
                            「契約管理」は、賃貸物件の契約管理とそれに伴う業務をWeb上で完結できるサービスです。<br><br>
                        </span>
                        
                        <div>
                            <span class="fw-bold">■機能紹介<br><br></span>
                            01.Web契約管理<br><br>
                            02.契約書・重要事項説明書などの帳票作成<br><br>
                            03.進捗状況管理<br><br>
                            04.取引台帳作成・管理<br>

                        </div>

                        <!-- メッセージボタン -->
                        <div class="col-12 col-md-12 col-lg-12 btn_box mt-5 box fadeRightTrigger float-end">
                            <a href="#" class="btn bgleft float-xl-end">
                                <span>詳細はこちら<i class="fas fa-download ms-1"></i></span>
                            </a>
                        </div>
                        <!-- メッセージボタン -->

                    </div>
                    <!-- 左ボックス -->

                </div>
            </div>
            <!-- 契約管理 --> 

            <!-- 資料請求 -->
            <div class="container-fluid mt-5 pt-3">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 document_box">

                        <div class="row">
                            <div class="document_img_filter">
                                <div class="col-12 col-md-12 col-lg-12 mx-auto">
                                
                                    <div class="document_contents">
                                        <span class="f_document_jp">効率的に仕事をしたくないですか？<br></span>
                                        <a href="" class="btn btn-document box zoomInTrigger"><i class="far fa-gem me-2"></i>今すぐ資料請求</a><br>
                                        <span class="f_document_jp_bottom">今までの「非効率」を「効率的」に<br></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                            
                    </div>
                </div>
            </div>
            <!-- 資料請求 -->

            <hr class="boderTrigger"> 

            <!-- INFOMATION -->
            <div class="container mt-3">
                <div class="row row-cols-3">

                    <!-- 新着情報一覧 -->
                    <div class="col-6 col-md-6 col-lg-6 mt-5 box fadeLeftTrigger">
                        <span class="new_info_jp">新着情報<br></span>
                        <span class="new_info_en">INFOMATION...</span>
                    </div>
                    <!-- 新着情報一覧 -->

                    <!-- VIEW ALL -->
                    <div class="col-6 col-md-6 col-lg-6 mt-5 box fadeRightTrigger">
                        <span class="view_all_text float-end"><a href="frontInfoInit">READ MORE</a></span>
                    </div>
                    <!-- VIEW ALL -->

                </div>
            </div>
            <!-- INFOMATION -->

            <!-- 新着情報内容 -->
            <div class="container mt-5">
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