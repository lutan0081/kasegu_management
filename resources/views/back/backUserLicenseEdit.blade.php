<!DOCTYPE html>
<html lang="ja">

	<head>
        <title>宅地建物取引士詳細/KASEGU</title>

		<!-- head -->
		@component('component.back_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('back/css/back_user_license_edit.css') }}">  
		
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
                            <i class="far fa-gem icon_blue me-2"></i>宅地建物取引士詳細
                        </div>

                        <!-- 境界線 -->
                        <hr>

                        <!-- 宅地建物取引士名 -->
                        <div class="col-12 col-md-10 col-lg-6 mt-4">
                            <label class="label_required mb-2" for="textBox"></label>宅地建物取引士名
                            <input type="text" class="form-control" name="user_license_name" id="user_license_name" value="{{ $user_license_list->user_license_name }}" required>
                            <!-- エラーメッセージ -->
                            <div class="invalid-feedback" id ="user_license_name_error">
                                宅地建物取引士名は必須です。
                            </div>
                        </div>
                        <!-- 宅地建物取引士名 -->

                        <!-- 改行 -->
                        <div class="w-100"></div>

                        <!-- フリガナ -->
                        <div class="col-12 col-md-10 col-lg-6 mt-4">
                            <label class="label_required mb-2" for="textBox"></label>フリガナ
                            <input type="text" class="form-control" name="user_license_ruby" id="user_license_ruby" value="{{ $user_license_list->user_license_ruby }}" required>
                            <!-- エラーメッセージ -->
                            <div class="invalid-feedback" id ="user_license_ruby_error">
                                フリガナは必須です。
                            </div>
                        </div>
                        <!-- フリガナ -->

                        <!-- 改行 -->
                        <div class="w-100"></div>

                        <!-- Fax -->
                        <div class="col-12 col-md-6 col-lg-5 mt-3 pb-3">
                            <label class="label_required mb-2" for="textBox"></label>登録番号
                            <input type="text" class="form-control" name="user_license_number" id="user_license_number" value="{{ $user_license_list->user_license_number }}" required>
                            <div class="invalid-feedback" id ="user_license_number_error">
                                登録番号は必須です。
                            </div>
                        </div>

                        <!-- 境界線 -->
                        <hr>
                        <!-- 境界線 -->

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

                        <!-- 宅地建物取引士id -->
                        <input type="hidden" name="user_license_id" id="user_license_id" value="{{ $user_license_list->user_license_id }}">

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
		<script src="{{ asset('back/js/back_user_license_edit.js') }}"></script>
	</body>
	
</html>