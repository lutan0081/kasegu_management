<!DOCTYPE html>
<html lang="ja">

	<head>
		<title>契約一覧/KASEGU</title>

		<!-- head -->
		@component('component.back_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('back/css/back_contract_detail.css') }}">  
		
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
                                        <i class="fas fa-key icon_blue me-2"></i>契約一覧
                                    </div>
                                    <!-- 境界線 -->
                                    <hr>
                                    <!-- 境界線 -->
                                </div>
                            </div>
                            <!-- タイトル -->
                        
                            <!-- 上部検索 -->
                            <div class="row">
                                <form action="backContractInit" method="post" autocomplete="off">
                                    {{ csrf_field() }}
                                    <div class="col-sm-12">
                                        <div class="card border border-0">
                                            <div class="row align-items-end">

                                                <!-- フリーワード -->
                                                <div class="col-12 col-md-8 col-lg-4 mt-1">
                                                    <label for="">フリーワード</label>
                                                    <input type="text" class="form-control" name="free_word" id="free_word" value="">
                                                </div>
                                        
                                                <!-- 進捗状況 -->
                                                <div class="col-12 col-md-8 col-lg-2 mt-1">
                                                    <label class="mb-2" for="textBox"></label>進捗状況
                                                    
                                                    <select class="form-select" name="contract_progress_id" id="contract_progress_id">
                                                        <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                        <option></option>
                                                        @foreach($contract_detail_progress as $progress)
                                                            <option value="{{$progress->contract_detail_progress_id}}">{{ $progress->contract_detail_progress_name }}</option>
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
                <div class="container mt-3 mb-5">
                    
                    <div class="row">
                            
                        <!-- テーブルcard -->
                        <div class="col-12 col-md-12 col-lg-12">

                            <div class="card">
                        
                                <!-- カードボディ -->
                                <div class="card-body">
                                    <!-- スクロール -->
                                    <div class="overflow-auto" style="height:35rem;">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-condensed table-striped">
                                                <!-- テーブルヘッド -->
                                                <thead>
                                                    <tr>
                                                        <th scope="col" id="create_user_id" style="display:none">id</th>
                                                        <th>選択</th>
                                                        <th scope="col" id="create_user_name">管理番号</th>
                                                        <th scope="col" id="create_user_name">物件名</th>
                                                        <th scope="col" id="create_user_name">号室</th>
                                                        <th scope="col" id="create_user_name">契約者</th>
                                                        <th scope="col" id="post_number">TEL</th>
                                                        <th scope="col" id="address">賃料</th>
                                                        <th scope="col" id="create_user_fax">共益費</th>
                                                        <th scope="col" id="create_user_mail">水道代</th>
                                                        <th scope="col" id="create_user_tel">その他</th>
                                                        <th scope="col" id="password">総賃料</th>
                                                        <th scope="col" id="password">契約始期</th>
                                                        <th scope="col" id="password">進捗状況</th>
                                                    </tr>
                                                </thead>

                                                <!-- テーブルボディ -->
                                                <tbody>
                                                    @foreach($res as $contracts)
                                                        <tr @if($contracts->contract_detail_progress_id == 5) class="table table-danger" @endif>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class" style="display:none">{{ $contracts->contract_detail_id }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class"><input id="{{ $contracts->contract_detail_id }}" type="radio" class="align-middle" name="flexRadioDisabled"></td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->admin_number }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->real_estate_name }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->room_name }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->contract_name }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->contract_tel }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->rent_fee }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->service_fee }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->water_fee }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->ohter_fee }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->ohter_fee }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->contract_start_date }}</td>
                                                            <td id="{{ $contracts->contract_detail_id }}" class="click_class">{{ $contracts->contract_detail_progress_name }}</td>
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
                        <div class="col-sm-12 mt-4 pt-1">
                            <div class="card border border-0">
                            <!-- row -->
                            <div class="row">

                                <div class="col-6">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary float-start btn-default">CSV出力</button>
                                    </div>
                                </div>
                                
                                <!-- 新規、編集 -->
                                <div class="col-6">
                                    <div class="btn-group float-end" role="group">
                                        <button type="button" id="btn_clone" class="btn btn-outline-primary float-end btn-default">複製登録</button>
                                        <button type="button" onclick="location.href='backContractNewInit'" class="btn btn-outline-primary float-end btn-default">新規登録</button>
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
		<script src="{{ asset('back/js/back_contract_detail.js') }}"></script>
	</body>
	
</html>