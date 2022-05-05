<!DOCTYPE html>
<html lang="ja">

	<head>
		<title>特約事項一覧/KASEGU</title>

		<!-- head -->
		@component('component.back_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('back/css/back_special_contract.css') }}">  
		
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

                <div class="container">

                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12 mt-2 mb-3">
                            
                            <!-- タイトル -->
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">

                                    <div class="info_title mt-3">
                                        <i class="fas fa-id-card icon_blue me-2"></i>特約事項一覧
                                    </div>

                                    <!-- 境界線 -->
                                    <hr>

                                </div>
                            </div>
                        
                            <!-- 上部検索 -->
                            <div class="row">
                                <form action="backSpecialContractInit" method="post">
                                    {{ csrf_field() }}
                                    <div class="col-sm-12">
                                        <div class="card border border-0">
                                            <div class="row align-items-end">

                                                <!-- フリーワード -->
                                                <div class="col-12 col-md-4 col-lg-4 mt-1">
                                                    <label for="">フリーワード</label>
                                                    <input type="text" class="form-control" name="free_word" id="free_word" value="">
                                                </div>

                                                <!-- 検索ボタン -->
                                                <div class="col-12 col-md-4 col-lg-8 mt-1">
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
                                            <table id="sort_table" class="table table-hover table-condensed table-striped">
                                                
                                                <!-- テーブルヘッド -->
                                                <thead>
                                                    <tr>
                                                        <th scope="col" id="create_user_id" style="display:none">id</th>
                                                        <th>選択</th>
                                                        <th>デフォルト</th>
                                                        <th scope="col" id="create_user_name">特約事項</th>
                                                    </tr>
                                                </thead>
                                                <!-- テーブルヘッド -->

                                                <!-- テーブルボディ -->
                                                <tbody>
                                                    @foreach($res as $special_contract)
                                                        <tr class="sortable-tr" id="tr_{{ $special_contract->special_contract_id }}">
                                                            <td id="{{ $special_contract->special_contract_id }}" class="click_class" style="display:none"></td>
                                                            <td id="{{ $special_contract->special_contract_id }}" class="click_class"><input id="{{ $special_contract->special_contract_id }}" type="radio" class="align-middle" name="flexRadioDisabled"></td>
                                                            <td id="{{ $special_contract->special_contract_id }}" class="click_class"><input class="form-check-input" type="checkbox" value="{{ $special_contract->special_contract_id }}" @if($special_contract->special_contract_default_id == 1) checked="checked" @endif) disabled="disabled"></td>
                                                            <td id="{{ $special_contract->special_contract_id }}" class="click_class">{{ $special_contract->special_contract_name }}</td>
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
                                            <button id="btn_sort_edit" type="button" class="btn btn-default btn-outline-primary">並び替え</button>
                                            <button type="button" id="btn_new" class="btn btn-outline-primary float-end btn-default" data-bs-toggle="modal" data-bs-target="#exampleModal">新規登録</button>
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

                <!-- モーダル(編集画面) -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <div class="modal-title info_title" id="exampleModalLabel">
                                    <i class="fas fa-id-card icon_blue me-2"></i>特約事項詳細
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- 内容 -->
                            <div class="modal-body">
                                <form id="modalForm" class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 mt-2 mb-2">
                                            
                                            <!-- デフォルト値 -->
                                            <div class="form-check float-end">
                                                <input class="form-check-input" type="checkbox" id="special_contract_default_id" value="1">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    デフォルト設定
                                                </label>
                                            </div>

                                            <!-- 内容 -->
                                            <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                <label for="">内容</label>
                                                <textarea class="form-control" name="special_contract_name" id="special_contract_name" rows="10" required></textarea>
                                                <div class="invalid-feedback" id="special_contract_name_error">
                                                    内容は必須です。
                                                </div>
                                            </div>
                                            
                                            <!-- ボタン -->
                                            <div class="col-12 col-md-12 col-lg-12 mt-2">

                                                <!-- 削除 -->
                                                <button id="btn_modal_delete" type="button" class="btn btn-outline-danger float-start btn-default mt-3">削除</button>

                                                <!-- 閉じる・登録 -->
                                                <div class="btn-group float-end mt-3" role="group">
                                                    <button id="btn_modal_edit" type="button" class="btn btn-default btn-outline-primary">登録</button>
                                                    <button id="btn_modal_close" type="button" class="btn btn-default btn-outline-primary" data-bs-dismiss="modal">閉じる</button>
                                                </div>
                                                <!-- 閉じる・登録 -->

                                            </div> 
                                            <!-- ボタン -->
                                                
                                        </div>

                                        <input type="hidden" name="special_contract_id" id="special_contract_id" value="">
                                    </div>
                                </form>
                            </div>
                            <!-- 内容 -->

                        </div>
                    </div>
                </div>
                <!-- モーダル(編集画面) -->

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

		<!-- ドラッグ＆ドロップで並び替え -->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        
        <!-- 自作js -->
		<script src="{{ asset('back/js/back_special_contract.js') }}"></script>
	</body>
	
</html>