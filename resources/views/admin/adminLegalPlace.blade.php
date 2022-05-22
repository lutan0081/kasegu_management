<!DOCTYPE html>
<html lang="ja">

	<head>
		<title>法務局一覧/ADMIN</title>

		<!-- head -->
		@component('component.admin_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('admin/css/admin_legal_place.css') }}">  
		
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
                
                <!-- 上部検索枠 -->
                <div class="container">

                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12 mt-2 mb-3">
                        
                            <!-- タイトル -->
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12 mb-2">
                                    <div class="info_title mt-3">
                                        <i class="far fa-gem icon_blue me-2"></i>法務局一覧
                                    </div>
                                    <!-- 境界線 -->
                                    <hr>
                                    <!-- 境界線 -->
                                </div>
                            </div>
                            <!-- タイトル -->

                            <!-- 上部検索 -->
                            <div class="row">
                                <form action="backLegalPlaceInit" method="post">
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

                <!-- 一覧 -->
                <div class="container mb-5">

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
                                                        <th scope="col" id="legal_place_name">名称</th>
                                                        <th scope="col" id="legal_place_post_number">郵便番号</th>
                                                        <th scope="col" id="legal_place_address">所在地</th>
                                                        <th scope="col" id="legal_place_tel">TEL</th>
                                                        <th scope="col" id="legal_place_fax">FAX</th>
                                                    </tr>
                                                </thead>
                                                <!-- テーブルヘッド -->

                                                <!-- テーブルボディ -->
                                                <tbody>
                                                    @foreach($res as $legal_place)
                                                        <tr>
                                                            <td id="{{ $legal_place->legal_place_id }}" class="click_class" style="display:none"></td>
                                                            <td id="{{ $legal_place->legal_place_id }}" class="click_class"><input id="{{ $legal_place->legal_place_id }}" type="radio" class="align-middle" name="flexRadioDisabled"></td>
                                                            <td id="{{ $legal_place->legal_place_id }}" class="click_class">{{ $legal_place->legal_place_name }}</td>
                                                            <td id="{{ $legal_place->legal_place_id }}" class="click_class">{{ $legal_place->legal_place_post_number }}</td>
                                                            <td id="{{ $legal_place->legal_place_id }}" class="click_class">{{ $legal_place->legal_place_address }}</td>
                                                            <td id="{{ $legal_place->legal_place_id }}" class="click_class">{{ $legal_place->legal_place_tel }}</td>
                                                            <td id="{{ $legal_place->legal_place_id }}" class="click_class">{{ $legal_place->legal_place_fax }}</td>
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
                                            <button type="button" onclick="location.href='adminConfigLegalPlaceNewInit'" class="btn btn-outline-primary float-end btn-default">新規登録</button>
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
		<script src="{{ asset('admin/js/admin_legal_place.js') }}"></script>
	</body>
	
</html>