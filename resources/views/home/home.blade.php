<!DOCTYPE html>
<html lang="ja">

  <head>
    <title>Home/KASEGU</title>
    <!-- css -->
    @component('component.head')
    @endcomponent
    <!-- home -->
    <link rel="stylesheet" href="{{ asset('home/css/home.css') }}">
    <!-- loding -->
    <link rel="stylesheet" href="{{ asset('lode/css/lode.css') }}">
    <!-- btnRight(右下のボタンの設定) -->
    <link rel="stylesheet" href="{{ asset('menuBtn/css/menuBtn.css') }}">

    <!-- 直接style指定 -->
    <style>

      /* 一覧の写真サイズ */
      .card-img, .card-img-top {
        height: 15rem;
      }

      /* ボタン:デフォルト値 */
      .btn-default{
        width: 7rem;
      }

    </style>
  </head>

  <body>

    <!-- splash -->
    <div id="splash">
      <div id="splash_text"></div>
      <div class="loader_cover loader_cover-up"></div><!--上に上がるエリア-->
      <div class="loader_cover loader_cover-down"></div><!--下に下がるエリア-->
    </div>
    <!-- splash -->

    <!-- メインはこの部分から -->
    <div id="container">

      <!-- ローディング画面の表示 -->
      <div id="overlay">
          <div class="cv-spinner">
              <span class="spinner"></span>
          </div>
      </div>
      

      <!--ヘッダー-->
      @component('component.header')
      @endcomponent
      
      <!-- 動画 -->
      <div class="video-container">
        <div class="video-wrap overlay">
          <video src="./home/img/top_video.mp4" type="video/mp4" playsinline loop autoplay muted>
          </video>
        </div>		
      </div>
      <!-- 動画 -->

      <!-- TOP動画の文字 -->
      <h1>
        <span class="catch">
          IT’S A PIECE OF CAKE.<br>
          WE CAN MAKE WHAT YOU WANT...<br>
          - KASEGU -<br>
          <span class="top_font_jp">「 これがほしかった...に答えます。 」</span>
        </span>
      </h1>
      <!-- TOP動画の文字 -->

      <!-- コンテンツ -->
      <!-- INFOMATION -->
      <div class="container mt-5">
        <div class="row row-cols-3">

          <!-- 新着情報一覧 -->
          <div class="col-6 col-md-6 col-lg-6 mt-4 mb-2 box fadeLeftTrigger">
            <span class="customer_list_jp">新着情報<br></span>
            <span class="customer_list_en">INFOMATION...</span>
          </div>
          <!-- 新着情報一覧 -->

          <!-- READ MORE -->
          <div class="col-6 col-md-6 col-lg-6 mt-4 mb-2 box fadeRightTrigger">
            <span class="customer_list_right float-end"><a href="infoInit">VIEW →</a></span>
          </div>
          <!-- READ MORE -->

        </div>
      </div>

      <!-- 新着情報コンテンツ -->
      <div class="container mt-3">
        <div class="row">
          <div class="col-12 col-md-12 col-lg-12 mb-3">
            @foreach($list_info as $index => $info)
            <div class="row py-4 day info_border-bottom box zoomInTrigger">
                
                <!-- レイアウトずれの為、lg-2-2-7に設定 -->
                <div class="col-5 col-md-3 col-lg-2">
                {{ Common::format_date_hy($info->create_date) }}
                </div>

                <div class="col-4 col-md-3 col-lg-2 label">
                お知らせ
                </div>

                <div class="col-12 col-md-12 col-lg-7 text">
                {{ $info->update_contents }}
                </div>

            </div>         
            @endforeach
          </div>
        </div>
      </div>

      <!-- 顧客情報がない場合 -->
      @if(count($app_list) == 0)
        <!-- 顧客情報表題 -->
        <div class="container-fluid mt-5 bg-img">

            <div class="row bg_filter">
                <div class="col-12 col-md-12 col-lg-12">
                  
                  <div class="row">
                    <div class="col-10 col-md-10 col-lg-7 my-5 mx-auto bg_contents bg-white box zoomInTrigger">
                      
                      <div class="row">
                        <!-- REGISTER -->
                        <div class="col-12 col-md-12 col-lg-12">
                          <span class="register_list_en">NEW REGISTER</span>
                        </div>

                        <!-- 新規登録 -->
                        <div class="col-12 col-md-12 col-lg-12">
                          <span class="register_list_jp">新規登録</span> 
                        </div>

                        <!-- 内容 -->
                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                          <span class="register_list_contents">申込URL発行 / 申込管理 / 帳票作成（契約書、重説）</span>
                        </div>

                        <!-- 登録ボタン -->
                        <div class="col-12 col-md-12 col-lg-12 mt-5">
                          <button id="btn_new" onclick="location.href='newInit'" type="button" class="btn btn-outline-primary btn-default">REGISTER</button>
                        </div>
                      
                      </div>
                    </div>
                  </div>

              </div>
            </div>
          
        </div> 
      @endif
      <!-- 顧客情報がない場合 -->

      <!-- 顧客情報がある場合 -->
      @if(count($app_list) !== 0)
        <!-- 顧客情報表題 -->
        <div class="container mb-5">
          <div class="row">
            <!-- 顧客情報 -->
            <div class="col-6 col-md-6 col-lg-6 mt-5 box fadeLeftTrigger">
              <span class="customer_list_jp">顧客情報<br></span>
              <span class="customer_list_en">CUSTOMER...</span>
            </div>
            <!-- 顧客情報 -->
            <!-- New-->
            <div class="col-6 col-md-6 col-lg-6 mt-5 mb-2 box fadeRightTrigger">
              <span class="customer_list_right float-end"><a href="newInit">New →</a></span>
            </div>
            <!-- New -->
          </div>
        </div>
        <!-- 顧客情報表題 -->
        
        <!-- 顧客情報コンテンツ -->
        <div class="container">
          <div class="row cols-3 mb-4">

            @foreach($app_list as $index => $app)
              <div class="col-12 col-md-12 col-lg-4 mb-5 box zoomInTrigger">
                <div class="card">
                  <!-- 顔写真 -->
                  @if(($app->img_path) == '')
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#0082e2"/><text x="42%" y="50%" fill="#eceeef" dy=".3em">No Image</text></svg>
                  @else
                    <img src="../storage/{{ $app->img_path }}" class="card-img-top">
                  @endif
                  
                  <div class="card-body">
                    @switch($app->contract_progress)
                        @case(1)
                          <span class="card-link progress_blue_label float-end">入居申込</span>
                          @break
                        @case(2)
                          <span class="card-link float-end progress_blue_label">入居審査</span>
                          @break
                        @case(3)
                          <span class="card-link float-end progress_blue_label">重要事項説明書</span>
                          @break
                        @case(4)
                          <span class="card-link float-end progress_blue_label">契約締結</span>
                          @break
                        @case(5)
                          <span class="card-link float-end progress_blue_label">引渡完了</span>
                          @break
                        @case(6)
                          <span class="card-link float-end progress_pink_label">キャンセル</span>
                          @break
                        @default
                            <!-- 判定したい変数に0 1 2意外が格納されていた時の処理 -->
                    @endswitch
                  </div>

                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">契約者名：{{$app->contract_name}}</li>
                    <li class="list-group-item">物件名：{{$app->real_estate_name}}</li>
                    <li class="list-group-item">号室：{{$app->room_name}}</li>
                    <li class="list-group-item">入居予定日：{{$app->contract_start_date}}</li>
                  </ul>

                  <div class="card-body">
                    <a href="" id="btn_delete_{{ $app->contract_id }}" class="card-link contents_f_link_line btn_delete">削除</a>
                    <a href="editInit?application_form_id={{ $app->application_form_id }}&application_Flag=false" id="btn_eidt_{{ $app->contract_id }}" class="card-link contents_f_link_line float-end btn_edit">編集</a>
                  
                    <!-- id -->
                    <!-- 不動産Id -->
                    <input type="hidden" name="application-form_{{ $app->application_form_id }}" id="application-form_{{ $app->application_form_id }}" value="{{ $app->application_form_id }}">
                    <!-- 賃借人Id -->
                    <input type="hidden" name="contract-id_{{ $app->contract_id }}" id="contract_id_{{ $app->contract_id }}" value="{{ $app->contract_id }}">
                    <!-- id -->
                  </div>

                </div>
              </div>
            @endforeach

          </div>
        </div>
      @endif
      <!-- 顧客情報がある場合 -->

      <!-- 右下のボタン -->
      <div class="fabs">
        <a href="contactInit" target="_blank" class="fab blue" tooltip="お問合せ"><i class="fas fa-comments"></i></a>
        <a href="newInit" target="_blank" class="fab green" tooltip="新規登録"><i class="fas fa-edit"></i></a>
        <a href="" target="_blank" class="fab pink" tooltip="申込URL" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-paper-plane"></i></a>
        <a target="_blank" class="fab blue" tooltip="機能"><i class="fas fa-wrench"></i></a>
      </div>
      <!-- 右下のボタン -->

        <!-- フッダー-->
        @component('component.front_footer')
        @endcomponent

      <!-- モーダル -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">

            <!-- ヘッダー -->
            <div class="modal-header">
              <h6 class="modal-title" id="exampleModalLabel"><i class="fas fa-paper-plane me-2"></i>申込URL発行</h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- ボディ -->
            <div class="modal-body">
              <form id="updateForm" class="needs-validation" novalidate>

                <!-- 説明 -->
                <div>
                  <p>
                    入力して頂いたメールアドレスに新規申込URLが届きます。<br>
                    万一届かない場合は、最初からやり直してください。             
                  </p>
                  <p class="modal_text_required">
                    ※必須箇所に入力頂き登録してください。<br>
                    　任意箇所は後からでも編集可能です。          
                  </p>
                </div>  
                <!-- 説明 -->

                <!-- 入力項目 -->
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">依頼者</label>
                  <input type="text" class="form-control" id="application_name" required>
                  <div class="invalid-feedback" id ="title_error">
                    依頼者は、必須です。
                  </div>
                </div>

                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">E-mail</label>
                  <input type="email" class="form-control" id="application_mail" required>
                  <div class="invalid-feedback" id ="title_error">
                    E-mailは、必須です。
                  </div>
                </div>
                <!-- 入力項目 -->

              </form>
            </div>
            <!-- ボディ -->

            <!-- フッター -->
            <div class="modal-footer">
              <div class="col">
                <!-- 戻る -->
                <button type="button" id="btn_modal_back" class="btn btn-outline-primary" data-bs-dismiss="modal">戻る</button>
                <!-- 編集 -->
                <button type="button" id="btn_modal_edit" class="btn btn-outline-primary float-end">登録</button>
              </div>
              <!-- id設定 -->
              <input type="hidden" class="form-control" id="update_id">
            </div>
            <!-- フッター -->
            
          </div>
        </div>
      </div>
      <!-- モーダル -->
    </div>
    <!-- メインはこの部分から -->

    <!-- js -->
    @component('component.js')
    @endcomponent

    <!-- プログレスバー -->
    <script src="https://rawgit.com/kimmobrunfeldt/progressbar.js/master/dist/progressbar.min.js"></script>
    <!-- loding -->
    <script src="{{ asset('lode/js/lode.js') }}"></script>
    <!-- home -->
    <script src="{{ asset('home/js/home.js') }}"></script>
  </body>

</html>