<!DOCTYPE html>
<html lang="ja">

	<head>
		<title>申込管理/ADMIN</title>

		<!-- head -->
		@component('component.admin_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('admin/css/admin_app.css') }}">  
		
        <style>

            /* ボタンデフォルト値 */
            .btn-default{
                width: 6rem;
            }

            /* 一覧の左右に余白が出来るため、0に設定 */
            .card-body {
                padding: 0rem;
            }

		</style>
	</head>

	<body>
		<!-- page-wrapper -->
		<div class="page-wrapper chiller-theme toggled">

            <!-- ローディング画面の表示 -->
            <div id="overlay">
                <div class="cv-spinner">
                    <span class="spinner"></span>
                </div>
            </div>
        
            <!-- sidebar-wrapper  -->
            @component('component.admin_sidebar')
            @endcomponent
            <!-- sidebar-wrapper  -->
            
            <!-- page-content" -->
            <main class="page-content">

                <div class="container">

                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12 mt-2 mb-3">
                            
                            <!-- タイトル -->
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="info_title mt-3">
                                        <i class="fas fa-id-card icon_blue me-2"></i>申込一覧
                                    </div>
                                    <!-- 境界線 -->
                                    <hr>
                                    <!-- 境界線 -->
                                </div>
                            </div>
                            <!-- タイトル -->
                        
                            <!-- 上部検索 -->
                            <div class="row">
                                <form action="backAppInit" method="post">
                                    {{ csrf_field() }}
                                    <div class="col-sm-12">
                                        <div class="card border border-0">
                                            <div class="row align-items-end">

                                                <!-- フリーワード -->
                                                <div class="col-12 col-md-8 col-lg-4 mt-1">
                                                    <label for="">フリーワード</label>
                                                    <input type="text" class="form-control" name="free_word" id="free_word" value="">
                                                </div>
                                        
                                                <!-- 申込進捗 -->
                                                <div class="col-12 col-md-8 col-lg-2 mt-1">
                                                    <label class="label_any mb-2" for="textBox"></label>申込進捗
                                                    
                                                    <select class="form-select" name="contract_progress_id" id="contract_progress_id">
                                                        <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                        <option></option>
                                                        @foreach($contract_progress as $progress)
                                                            <option value="{{$progress->contract_progress_id}}">{{ $progress->contract_progress_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- 契約進捗 -->

                                                <!-- 改行 -->
                                                <div class="w-100"></div>

                                                <!-- 日付始期 -->
                                                <div class="col-6 col-md-6 col-lg-2 mt-1">
                                                    <label for="">日付始期</label>
                                                    <input type="text" class="form-control" id="start_date" name="start_date">
                                                </div>

                                                <!-- 日付終期 -->
                                                <div class="col-6 col-md-6 col-lg-2 mt-1">
                                                    <label for="">日付終期</label>
                                                    <input type="text" class="form-control" id="end_date" name="end_date">
                                                </div>

                                                <!-- 全て表示 -->
                                                <div class="col-12 col-md-8 col-lg-4 mt-1">
                                                    
                                                    <!-- 全て表示 -->
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" name="radio" value="0">
                                                        <label for="inlineRadio2" class="form-check-label">全表示</label>
                                                    </div>

                                                    <!-- キャンセル -->
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input" name="radio" value="1">
                                                        <label for="inlineRadio1" class="form-check-label">キャンセル</label>
                                                    </div>
                    
                                                </div>

                                                <!-- 検索ボタン -->
                                                <div class="col-12 col-md-4 col-lg-4">
                                                    <input type="submit" class="btn btn-default btn-outline-primary float-end" value="検索">
                                                </div>
                                                <!-- 検索ボタン -->
                                                
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- 上部検索 -->

                        </div>
                    </div>
                </div>

                <!-- 一覧 -->
                <div class="container mb-5">
                    
                    <div class="row">
                            
                        <!-- テーブルcard -->
                        <div class="col-12 col-md-12 col-lg-12">

                            <div class="card">
                        
                                <!-- カードボディ -->
                                <div class="card-body">
                                    <!-- スクロール -->
                                    <div class="overflow-auto" style="height:34rem;">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-condensed table-striped">
                                                <!-- テーブルヘッド -->
                                                <thead>
                                                    <tr>
                                                        <th scope="col" id="create_user_id" style="display:none">id</th>
                                                        <th>選択</th>
                                                        <th scope="col" id="create_user_name">アカウント</th>
                                                        <th scope="col" id="broker_coompany_name">仲介業者</th>
                                                        <th scope="col" id="create_user_name">Tel</th>
                                                        <th scope="col" id="create_user_name">E-mail</th>
                                                        <th scope="col" id="post_number">物件名</th>
                                                        <th scope="col" id="address">号室</th>
                                                        <th scope="col" id="create_user_fax">契約者</th>
                                                        <th scope="col" id="create_user_mail">携帯Tel</th>
                                                        <th scope="col" id="create_user_tel">入居予定日</th>
                                                        <th scope="col" id="password">進捗状況</th>
                                                    </tr>
                                                </thead>

                                                <!-- テーブルボディ -->
                                                <tbody>
                                                    @foreach($res as $app)
                                                        <tr @if($app->contract_progress_id == 4) class="table table-danger" @endif>
                                                            <td id="{{ $app->application_id }}" class="click_class" style="display:none">{{ $app->application_id }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class"><input id="{{ $app->application_id }}" type="radio" class="align-middle" name="flexRadioDisabled"></td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->create_user_name }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->broker_company_name }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->broker_tel }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->broker_mail }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->real_estate_name }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->room_name }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->entry_contract_name }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->entry_contract_mobile_tel }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->contract_start_date }}</td>
                                                            <td id="{{ $app->application_id }}" class="click_class">{{ $app->contract_progress_name }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <!-- テーブルボディ -->
                                            </table>
                                        </div>
                                    </div>
                                    <!-- スクロール -->
                                <!-- カードボディ -->
                                </div>

                            </div>

                            <!-- ぺージネーション -->   
                            <div id="links" style="display:none;" class="mt-3">
                                {{ $res->links() }}
                            </div>

                        </div>
                        <!-- テーブルcard -->

                        <!-- ボタン -->
                        <div class="col-12 col-md-6 col-lg-12 mt-3">
                            <div class="card border border-0">
                            <!-- row -->
                            <div class="row">

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary float-start btn-default">CSV出力</button>
                                        <button type="button" class="btn btn-outline-primary float-start btn-default" data-bs-toggle="modal" data-bs-target="#urlModal">URL発行</button>
                                    </div>
                                </div>
                                <!-- 新規、編集 -->
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="btn-group float-xl-end" role="group">
                                        <button type="button" onclick="location.href='adminAppNewInit'" class="btn btn-outline-primary float-end btn-default">新規登録</button>
                                        <button type="button" id="btn_edit" class="btn btn-outline-primary float-end btn-default">編集</button>
                                    </div>
                                </div>
                            </div>
                            <!-- row -->
                            </div>
                        </div>
                        <!-- ボタン -->

                    </div>
                </div>
                <!-- 一覧 --> 

            </main>
            <!-- page-content" -->

		</div>
		<!-- page-wrapper -->
        
        <!-- URL発行 -->
        <div class="modal fade" id="urlModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- ヘッダー -->
                    <div class="modal-header">
                        <div class="modal-title info_title" id="exampleModalLabel">
                            <i class="fas fa-paper-plane icon_blue me-2"></i>申込URL発行
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- ボディ -->
                    <div class="modal-body">
                        <form id="modalForm" class="needs-validation" novalidate>

                            <div class="col-12 col-md-6 col-lg-12 mt-3">
                                <div class="row">

                                    <div class="col-12 col-md-12 col-lg-12 mt-2">
                                        入力したメールアドレスに申込URLが届きます。<br>
                                        <span class="text_red">※メールが届かない場合、最初からやり直してください。</span>
                                    </div>  

                                    <div class="col-12 col-md-6 col-lg-12 mt-3">
                                        <label class="col-form-label">業者名</label>
                                        <input type="text" class="form-control" id="application_name" placeholder="例：株式会社〇〇〇〇" required>
                                        <div class="invalid-feedback" id ="application_name_error">
                                            名前は必須です。
                                        </div>
                                    </div>  

                                    <!-- address -->
                                    <div class="col-12 col-md-6 col-lg-12 mt-3">
                                        <label class="col-form-label">E-mail</label>
                                        <input type="email" class="form-control" id="application_mail" placeholder="例：××××@gmail.com" required>
                                        <div class="invalid-feedback" id ="application_mail_error">
                                            E-mailは必須です。
                                        </div>
                                    </div>

                                    <!-- 物件名 -->
                                    <div class="col-12 col-md-6 col-lg-12 mt-3">
                                        <label class="col-form-label">物件名</label>
                                        <input type="email" class="form-control" id="real_estate_name" placeholder="例：〇〇〇〇マンション" required>
                                        <div class="invalid-feedback" id ="real_estate_name_error">
                                            物件名は必須です。
                                        </div>
                                    </div>

                                    <!-- 号室 -->
                                    <div class="col-12 col-md-6 col-lg-6 my-3">
                                        <label class="col-form-label">号室</label>
                                        <input type="email" class="form-control" id="room_name" placeholder="例：101" required>
                                        <div class="invalid-feedback" id ="room_name_error">
                                            号室は必須です。
                                        </div>
                                    </div>

                                </div>  
                            </div>

                        </form>
                    </div>
                    <!-- ボディ -->

                    <!-- フッター -->
                    <div class="modal-footer">

                        <div class="col my-3">

                            <!-- 戻る -->
                            <button type="button" id="btn_modal_url_back" class="btn btn-outline-primary btn-default" data-bs-dismiss="modal">戻る</button>

                            <!-- 送信 -->
                            <button type="button" id="btn_modal_url_send" class="btn btn-outline-primary btn-default float-end">送信</button>
                            
                        </div>

                    </div>
                    <!-- フッター -->
                    
                </div>
            </div>
        </div>
        <!-- URL発行 -->

		@component('component.back_js')
		@endcomponent
        
        <!-- bootstrap-datepickerのjavascriptコード -->
        <script>
            
            $('#start_date').datepicker({
                language:'ja'
            });

            $('#end_date').datepicker({
                language:'ja'
            });

        </script>

		<!-- 自作js -->
		<script src="{{ asset('admin/js/admin_app.js') }}"></script>
	</body>
	
</html>