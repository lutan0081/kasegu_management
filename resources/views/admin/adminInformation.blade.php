<!DOCTYPE html>
<html lang="ja">

	<head>
		<title>新着情報一覧/ADMIN</title>

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
                                        <i class="fas fa-bell icon_blue me-2"></i>新着情報一覧
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
                                                        <th scope="col" id="legal_place_name">タイトル</th>
                                                        <th scope="col" id="legal_place_name">種別</th>
                                                        <th scope="col" id="legal_place_post_number">内容</th>
                                                        <th scope="col" id="legal_place_address">登録日</th>
                                                    </tr>
                                                </thead>
                                                <!-- テーブルヘッド -->

                                                <!-- テーブルボディ -->
                                                <tbody>
                                                    @foreach($res as $information_list)
                                                        <tr>
                                                            <td id="id_{{ $information_list->information_id }}" class="click_class" style="display:none"></td>
                                                            <td id="btn_{{ $information_list->information_id }}" class="click_class"><input id="{{ $information_list->information_id }}" type="radio" class="align-middle" name="flexRadioDisabled"></td>
                                                            <td id="title_{{ $information_list->information_id }}" class="click_class">{{ $information_list->information_title }}</td>
                                                            <td id="title_{{ $information_list->information_id }}" class="click_class">{{ $information_list->information_type_name }}</td>
                                                            <td id="contents_{{ $information_list->information_id }}" class="click_class">{{ $information_list->information_contents }}</td>
                                                            <td id="update_{{ $information_list->information_id }}" class="click_class">{{ $information_list->update_date }}</td>
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
                                            <button type="button" class="btn btn-outline-primary float-end btn-default" data-bs-toggle="modal" data-bs-target="#urlModal">新規登録</button>
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

                <!-- 新着情報編集画面 -->
                <div class="modal fade" id="urlModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <!-- ヘッダー -->
                            <div class="modal-header">
                                <div class="modal-title info_title" id="exampleModalLabel">
                                    <i class="fas fa-bell icon_blue me-2"></i>新着情報詳細
                                </div>

                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- ボディ -->
                            <div class="modal-body">
                                <form id="modalForm" class="needs-validation" novalidate>

                                    <div class="col-12 col-md-6 col-lg-12 mb-3">
                                        <div class="row">

                                            <div class="col-12 col-md-6 col-lg-12">
                                                <label class="col-form-label">タイトル</label>
                                                <input type="text" class="form-control was-validated" id="application_name" placeholder="例：株式会社〇〇〇〇" required>
                                                <div class="invalid-feedback" id ="application_name_error">
                                                    タイトルは必須です。
                                                </div>
                                            </div>
                                            
                                            <!-- 種別 -->
                                            <div class="col-12 col-md-12 col-lg-4 mt-3">
                                                <label class="mb-2">種別</label>
                                                <select class="form-select" name="img_type" id="img_type" required>
                                                    <option selected></option>
                                                    @foreach($information_type_list as $information_types)
                                                        <option value="{{ $information_types->information_type_id }}">{{ $information_types->information_type_name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id ="img_type_error">
                                                    種別は必須です。
                                                </div>
                                            </div>

                                            <!-- 内容 -->
                                            <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                <label for="">内容</label>
                                                <textarea class="form-control" name="url_text" id="url_text" rows="10" placeholder="例：自由に入力"></textarea>
                                                <div class="invalid-feedback" id ="url_text_error">
                                                    内容は必須です。
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
                <!-- 新着情報編集画面 -->

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