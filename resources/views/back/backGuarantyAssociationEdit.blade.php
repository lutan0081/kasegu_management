<!DOCTYPE html>
<html lang="ja">

	<head>
        <title>保証協会詳細/KASEGU</title>

		<!-- head -->
		@component('component.back_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('back/css/back_guaranty_association_edit.css') }}">  
		
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
                            <i class="far fa-gem icon_blue me-2"></i>保証協会詳細
                        </div>

                        <!-- 境界線 -->
                        <hr>

                        <!-- 名称 -->
                        <div class="col-12 col-md-10 col-lg-6 mt-4">
                            <label class="label_required mb-2" for="textBox"></label>名称
                            <input type="text" class="form-control" name="guaranty_association_name" id="guaranty_association_name" value="{{ $guaranty_association_list->guaranty_association_name }}" required="" placeholder="例：〇〇〇〇保証協会">
                            <!-- エラーメッセージ -->
                            <div class="invalid-feedback" id ="guaranty_association_name_error">
                                名称は必須です。
                            </div>
                        </div>
                        <!-- 名称 -->

                        <!-- 改行 -->
                        <div class="w-100"></div>

                        <!-- 郵便番号、検索ボタン -->
                        <div class="col-7 col-md-7 col-lg-2 mt-3">
                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                            <div class="input-group">
                                <input type="text" id="guaranty_association_post_number" class="form-control" name="guaranty_association_post_number" value="{{ $guaranty_association_list->guaranty_association_post_number }}" required placeholder="例：5450021">
                                <button class="btn btn-outline-primary btn_zip" type="button" id="guaranty_association_post_number-btn-zip" >検索</button>
                                <div class="invalid-feedback" id="guaranty_association_post_number_error">
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
                            <input type="text" class="form-control" name="guaranty_association_address" id="guaranty_association_address" value="{{ $guaranty_association_list->guaranty_association_address }}" required placeholder="例：大阪府大阪市梅田1丁目xx-yy">
                            <div class="invalid-feedback" id ="guaranty_association_address_error">
                                所在地は必須です。
                            </div>
                        </div>

                        <!-- Tel -->
                        <div class="col-12 col-md-6 col-lg-5 mt-3">
                            <label class="label_any mb-2" for="textBox"></label>Tel
                            <input type="text" class="form-control" name="guaranty_association_tel" id="guaranty_association_tel" value="{{ $guaranty_association_list->guaranty_association_tel }}" placeholder="例：06-xxxx-xxxx">
                            <div class="invalid-feedback" id ="guaranty_association_tel_error">
                                Telは必須です。
                            </div>
                        </div>

                        <!-- Fax -->
                        <div class="col-12 col-md-6 col-lg-5 mt-3 pb-3">
                            <label class="label_any mb-2" for="textBox"></label>Fax
                            <input type="text" class="form-control" name="guaranty_association_fax" id="guaranty_association_fax" value="{{ $guaranty_association_list->guaranty_association_fax }}" placeholder="例：06-xxxx-xxxx">
                            <div class="invalid-feedback" id ="guaranty_association_fax_error">
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
                        <input type="hidden" name="guaranty_association_id" id="guaranty_association_id" value="{{ $guaranty_association_list->guaranty_association_id }}">

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
		<script src="{{ asset('back/js/back_guaranty_association_edit.js') }}"></script>
	</body>
	
</html>