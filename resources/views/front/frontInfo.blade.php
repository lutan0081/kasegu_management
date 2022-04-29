<!DOCTYPE html>
<html lang="ja">

    <head>
        <title>新着情報/KASEGU</title>
        <!-- css -->
        @component('component.front_head')
        @endcomponent

        <!-- front_info -->
        <link rel="stylesheet" href="{{ asset('front/css/front_info.css') }}">

        <!-- front_register -->
        <link rel="stylesheet" href="{{ asset('front/css/front_register.css') }}">

        <!-- 直接style指定 -->
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
                                    <p class="title_en">Infomation</p>
                                    <p class="title_jp">お知らせ<br></p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 表題 -->

        <!-- コンテンツ -->
        <div class="container-fluid container_fluid_w_80 pt-4 mb-5">
            <div class="row">

                @foreach($res as $info)
                    <div class="col-12 col-md-6 col-lg-6 mb-4 box flipLeftTopTrigger">
                        <div class="card card_bg_contents border-0">
                            <div class="row g-0 py-3">
                                
                                <!-- コンテンツ内ロゴ -->
                                <div class="col-12 col-md-12 col-lg-4 d-flex align-items-center px-3">
                                    <img src="./img/kasegu_logo.png" class="card_img ms-2" alt="logo">
                                </div>
                                
                                <!-- コンテンツ -->
                                <div class="col-12 col-md-12 col-lg-8 d-flex align-items-center px-3">
                                    <div class="card-body">
                                        <p class="card_date">{{ Common::format_date_hy($info->create_date) }}</sp>
                                        <p class="card-title card_head">{{ $info->update_title }}</p>
                                        <p class="card-text card_contents">{{ $info->update_contents }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- ぺージネーション -->   
                <div id="links" style="display:none;" class="mt-3">
                    {{ $res->links() }}
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

        <!-- プログレスバー -->
        <script src="https://rawgit.com/kimmobrunfeldt/progressbar.js/master/dist/progressbar.min.js"></script>
        
        <!-- 初回ローディング -->
        <script src="{{ asset('lode/js/lode.js') }}"></script>
        
        <!-- info.js -->
        <script src="{{ asset('front/js/front_info.js') }}"></script>
    </body>

</html>