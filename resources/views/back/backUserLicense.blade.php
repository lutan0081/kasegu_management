<!DOCTYPE html>
<html lang="ja">

	<head>
		<title>宅地建物取引士一覧/KASEGU</title>

		<!-- head -->
		@component('component.back_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('back/css/back_user_license.css') }}">  
		
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
            @component('component.back_sidebar')
            @endcomponent
            <!-- sidebar-wrapper  -->
            
            <!-- page-content" -->
            <main class="page-content">

                <!-- 申込管理 -->
                <div class="container">

                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12 mt-2 mb-3">

                            <!-- タイトル -->
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12 mb-2">
                                    <div class="info_title mt-3">
                                        <i class="far fa-gem icon_blue me-2"></i>宅地建物取引士一覧
                                    </div>
                                    <!-- 境界線 -->
                                    <hr>
                                </div>
                            </div>
                            <!-- タイトル -->
                            
                            <div class="row">
                                <form action="backUserLicenseInit" method="post">
                                    {{ csrf_field() }}
                                    <div class="col-sm-12">
                                        <div class="card border border-0">
                                            <div class="row align-items-end">

                                                <!-- フリーワード -->
                                                <div class="col-7 col-md-8 col-lg-4">
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

                        </div>
                    </div>
                </div>
                <!-- 申込管理 -->

                <!-- 一覧 -->
                <div class="container mt-3 mb-5">
                    
                    <div class="row">
                            
                        <!-- テーブルcard -->
                        <div class="col-12 col-md-12 col-lg-12">

                            <div class="card">
                        
                                <!-- カードボディ -->
                                <div class="card-body">
                                    <!-- スクロール -->
                                    <div class="overflow-auto" style="height:40rem;">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-condensed table-striped">
                                                <!-- テーブルヘッド -->
                                                <thead>
                                                    <tr>
                                                        <th scope="col" id="create_user_id" style="display:none">id</th>
                                                        <th>選択</th>
                                                        <th scope="col" id="legal_place_name">名前</th>
                                                        <th scope="col" id="legal_place_post_number">フリガナ</th>
                                                        <th scope="col" id="legal_place_address">登録番号</th>
                                                        <th scope="col" id="legal_place_address">更新日</th>
                                                    </tr>
                                                </thead>

                                                <!-- テーブルボディ -->
                                                <tbody>
                                                    @foreach($res as $user_license)
                                                        <tr>
                                                            <td id="{{ $user_license->user_license_id }}" class="click_class" style="display:none"></td>
                                                            <td id="{{ $user_license->user_license_id }}" class="click_class"><input id="{{ $user_license->user_license_id }}" type="radio" class="align-middle" name="flexRadioDisabled"></td>
                                                            <td id="{{ $user_license->user_license_id }}" class="click_class">{{ $user_license->user_license_name }}</td>
                                                            <td id="{{ $user_license->user_license_id }}" class="click_class">{{ $user_license->user_license_ruby }}</td>
                                                            <td id="{{ $user_license->user_license_id }}" class="click_class">{{ $user_license->user_license_number }}</td>
                                                            <td id="{{ $user_license->user_license_id }}" class="click_class">{{ $user_license->user_license_number }}</td>
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
                        <div class="col-sm-12 mt-3">
                            <div class="card border border-0">
                                <!-- row -->
                                <div class="row">
                                    <!-- 新規、編集 -->
                                    <div class="col-12">
                                        <div class="btn-group float-end" role="group">
                                            <button type="button" onclick="location.href='backUserLicenseNewInit'" class="btn btn-outline-primary float-end btn-default">新規登録</button>
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
        
        <!-- 新規url発行 -->
		@component('component.back_url')
		@endcomponent
        <!-- 新規url発行 -->

		@component('component.back_js')
		@endcomponent

		<!-- 自作js -->
		<script src="{{ asset('back/js/back_user_license.js') }}"></script>
	</body>
	
</html>