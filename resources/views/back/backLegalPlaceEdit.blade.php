<!DOCTYPE html>
<html lang="ja">

	<head>
        <title>法務局詳細/KASEGU</title>

		<!-- head -->
		@component('component.back_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('back/css/back_legal_place_edit.css') }}">  
		
        <style>

            /* ボタンデフォルト値 */
            .btn-default{
                width: 6rem;
            }

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
            @component('component.back_sidebar')
            @endcomponent
            <!-- sidebar-wrapper  -->
            
            <!-- page-content" -->
            <main class="page-content">

                <!-- 入力項目 -->
                <div class="container mt-3">
                    <div class="row">
                        
                        <form id="editForm" class="needs-validation" novalidate>

                            <div class="info_title mt-3">
                                <i class="far fa-gem icon_blue me-2"></i>法務局詳細
                            </div>

                            <!-- 境界線 -->
                            <hr>

                            <!-- 名前 -->
                            <div class="col-12 col-md-10 col-lg-6 mt-4">
                                <label class="label_required mb-2" for="textBox"></label>名称
                                <input type="text" class="form-control" name="legal_place_name" id="legal_place_name" value="{{ $legal_place_list->legal_place_name }}" required="" placeholder="例：東京法務局">
                                <!-- エラーメッセージ -->
                                <div class="invalid-feedback" id ="name_error">
                                    名称は必須です。
                                </div>
                            </div>

                            <!-- 改行 -->
                            <div class="w-100"></div>

                            <!-- 郵便番号、検索ボタン -->
                            <div class="col-7 col-md-7 col-lg-2 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>郵便番号
                                <div class="input-group">
                                    <input type="text" id="legal_place_post_number" class="form-control" name="legal_place_post_number" value="{{ $legal_place_list->legal_place_post_number }}" required placeholder="例：1028225">
                                    <button class="btn btn-outline-primary btn_zip" type="button" id="legal_place-btn-zip" >検索</button>
                                    <div class="invalid-feedback" id="legal_place_post_number">
                                        郵便番号は必須です。
                                    </div>
                                </div>
                            </div>
                            <!-- 郵便番号、検索ボタン -->

                            <!-- 改行 -->
                            <div class="w-100"></div>

                            <!-- 所在地 -->
                            <div class="col-12 col-md-12 col-lg-10 mt-3">
                                <label class="label_required mb-2" for="textBox"></label>所在地
                                <input type="text" class="form-control" name="legal_place_address" id="legal_place_address" value="{{ $legal_place_list->legal_place_address }}" required required placeholder="例：東京都千代田区九段南1丁目1−15">
                                <div class="invalid-feedback" id ="legal_place_address_error">
                                    所在地は必須です。
                                </div>
                            </div>

                            <!-- Tel -->
                            <div class="col-12 col-md-6 col-lg-5 mt-3">
                                <label class="label_any mb-2" for="textBox"></label>Tel
                                <input type="text" class="form-control" name="legal_place_tel" id="legal_place_tel" value="{{ $legal_place_list->legal_place_tel }}" placeholder="例：0352131234">
                                <div class="invalid-feedback" id ="legal_place_tel_error">
                                    Telは必須です。
                                </div>
                            </div>

                            <!-- Fax -->
                            <div class="col-12 col-md-6 col-lg-5 mt-3 pb-3">
                                <label class="label_any mb-2" for="textBox"></label>Fax
                                <input type="text" class="form-control" name="legal_place_fax" id="legal_place_fax" value="{{ $legal_place_list->legal_place_fax }}" placeholder="例：0352131234">
                                <div class="invalid-feedback" id ="legal_place_fax_error">
                                </div>
                            </div>

                            <!-- 境界線 -->
                            <hr>

                            <!-- ボタン -->
                            <div class="row row-cols-2 mb-5">

                                <!-- 削除 -->
                                <div class="col-6 col-md-6 col-lg-6 mt-3">
                                    <button id="btn_delete" class="btn btn-outline-danger btn-default">削除</button>
                                </div>
                                
                                <!-- 登録 -->
                                <div class="col-6 col-md-6 col-lg-6 mt-3">
                                    <button id="btn_edit" class="btn btn-outline-primary float-end btn-default">登録</button>
                                </div>
                            </div>     
                            <!-- ボタン -->

                            <!-- 法務局id -->
                            <input type="hidden" name="legal_place_id" id="legal_place_id" value="{{ $legal_place_list->legal_place_id }}">

                        </form>

                    </div>
                </div>
                <!-- コンテンツ -->

            </main>
            <!-- page-content" -->

		</div>
		<!-- page-wrapper -->

		@component('component.back_js')
		@endcomponent

		<!-- 自作js -->
		<script src="{{ asset('back/js/back_legal_place_edit.js') }}"></script>
	</body>
	
</html>