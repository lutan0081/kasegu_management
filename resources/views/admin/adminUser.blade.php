<!DOCTYPE html>
<html lang="ja">

	<head>
		<title>アカウント一覧/ADMIN</title>

		<!-- head -->
		@component('component.admin_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('admin/css/admin_information.css') }}">  
		
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
                        <div class="col-12 col-md-12 col-lg-12 mt-2">
                        
                            <!-- タイトル -->
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12 mb-2">
                                    <div class="info_title mt-3">
                                        <i class="fas fa-user icon_blue me-2"></i>アカウント一覧
                                    </div>

                                    <!-- 境界線 -->
                                    <hr>
                                    <!-- 境界線 -->

                                </div>
                            </div>
                            <!-- タイトル -->

                            <!-- 上部検索 -->
                            <div class="row">
                                <form action="adminUserInit" method="post">
                                    {{ csrf_field() }}
                                    <div class="col-sm-12">
                                        <div class="card border border-0">
                                            <div class="row align-items-end">

                                                <!-- フリーワード -->
                                                <div class="col-6 col-md-4 col-lg-4">
                                                    <label for="">フリーワード</label>
                                                    <input type="text" class="form-control" name="free_word" id="free_word" value="">
                                                </div>

                                                <!-- 検索ボタン -->
                                                <div class="col-5 col-md-4 col-lg-8">
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
                <!-- 申込管理 -->

                <!-- 一覧 -->
                <div class="container mt-3 mb-3">
                    
                    <div class="row">
                            
                        <!-- テーブルcard -->
                        <div class="col-12 col-md-12 col-lg-12">

                            <div class="card">
                        
                                <!-- カードボディ -->
                                <div class="card-body">
                                    <!-- スクロール -->
                                    <div class="overflow-auto" style="height:37rem;">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-condensed table-striped">
                                                
                                                <!-- テーブルヘッド -->
                                                <thead>
                                                    <tr>
                                                        <th scope="col" id="create_user_id" style="display:none">id</th>
                                                        <th>選択</th>
                                                        <th scope="col" id="legal_place_name">アカウント名</th>
                                                        <th scope="col" id="legal_place_name">E-mail</th>
                                                        <th scope="col" id="legal_place_post_number">郵便番号</th>
                                                        <th scope="col" id="legal_place_address">住所</th>
                                                        <th scope="col" id="legal_place_address">TEL</th>
                                                        <th scope="col" id="legal_place_address">FAX</th>
                                                        <th scope="col" id="legal_place_address">パスワード</th>
                                                    </tr>
                                                </thead>
                                                <!-- テーブルヘッド -->

                                                <!-- テーブルボディ -->
                                                <tbody>
                                                    @foreach($res as $user_list)
                                                        <tr>
                                                            <td id="id_{{ $user_list->create_user_id }}" class="click_class" style="display:none"></td>
                                                            <td id="btn_{{ $user_list->create_user_id }}" class="click_class"><input id="{{ $user_list->create_user_id }}" type="radio" class="align-middle" name="flexRadioDisabled"></td>
                                                            <td id="name_{{ $user_list->create_user_id }}" class="click_class">{{ $user_list->create_user_name }}</td>
                                                            <td id="mail_{{ $user_list->create_user_id }}" class="click_class">{{ $user_list->create_user_mail }}</td>
                                                            <td id="post_{{ $user_list->create_user_id }}" class="click_class">{{ $user_list->create_user_post_number }}</td>
                                                            <td id="address_{{ $user_list->create_user_id }}" class="click_class">{{ $user_list->create_user_address }}</td>
                                                            <td id="tel_{{ $user_list->create_user_id }}" class="click_class">{{ $user_list->create_user_tel }}</td>
                                                            <td id="fax_{{ $user_list->create_user_id }}" class="click_class">{{ $user_list->create_user_fax }}</td>
                                                            <td id="password_{{ $user_list->password }}" class="click_class">{{ $user_list->password }}</td>
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
                        <div class="col-sm-12 mt-2 pt-2">
                            <div class="card border border-0">

                                <!-- row -->
                                <div class="row">
                                    <!-- 新規、編集 -->
                                    <div class="col-12">
                                        <div class="btn-group float-end" role="group">
                                            <button type="button" class="btn btn-outline-primary float-end btn-default" data-bs-toggle="modal" data-bs-target="#informaitonModal">新規登録</button>
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

		@component('component.admin_js')
		@endcomponent
        
		<!-- 自作js -->
		<script src="{{ asset('admin/js/admin_information.js') }}"></script>
	</body>
	
</html>