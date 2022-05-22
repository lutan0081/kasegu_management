<!DOCTYPE html>
<html lang="ja">

	<head>
        <title>アカウント情報/ADMIN</title>

		<!-- head -->
		@component('component.admin_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('admin/css/admin_config_user.css') }}">  
		
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
		@component('component.admin_sidebar')
		@endcomponent
		<!-- sidebar-wrapper  -->
		
		<!-- page-content" -->
		<main class="page-content">

            <!-- 入力項目 -->
            <div class="container mt-3">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">

                        <form id="editForm" class="needs-validation" novalidate>
                
                            <div class="info_title mt-3">
                                <i class="far fa-gem icon_blue me-2"></i>アカウント情報
                            </div>

                            <!-- 境界線 -->
                            <hr>

                            <!-- カード -->
                            <div class="card border border-0">

                                @include('component.form_user')

                                <!-- ボタン -->
                                <div class="row row-cols-2 mb-5">
                                    <!-- 登録、帳票 -->
                                    <div class="col-12 col-md-12 col-lg-12 mt-3">
                                        <button id="btn_edit" class="btn btn-outline-primary float-end btn-default">登録</button>
                                    </div>
                                </div>     
                                <!-- ボタン -->

                                <!-- アカウントid -->
                                <input type="hidden" name="create_user_id" id="create_user_id" value="{{ $user_list->create_user_id }}">
                                
                            </div>
                            <!-- カード -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- コンテンツ -->

		</main>
		<!-- page-content" -->

		</div>
		<!-- page-wrapper -->

		@component('component.admin_js')
		@endcomponent

		<!-- 自作js -->
		<script src="{{ asset('admin/js/admin_config_user.js') }}"></script>
	</body>
	
</html>