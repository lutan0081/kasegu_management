<!DOCTYPE html>
<html lang="ja">

	<head>
        <title>契約詳細/ADMIN</title>

		<!-- head -->
		@component('component.admin_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('admin/css/admin_contract_detail_edit.css') }}">  
		
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

                <div class="container mt-3">
                    <div class="row">

                        <!-- タイトル -->
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="info_title mt-3">
                                <i class="fas fa-key icon_blue me-2"></i>契約詳細@if( $clone_flag == 'true' )（複製登録）@endif
                            </div>
                            <hr>
                        </div>

                        <!-- プログレスバー -->
                        <div class="col-12 col-md-12 col-lg-12 mt-4">

                            @switch($contract_list->contract_detail_progress_id)
                                @case(1)
                                    <ul class="progressbar">
                                        <li class="active">契約手続中</li>
                                        <li>契約書発行</li>
                                        <li>返却書類確認中</li>
                                        <li>引渡完了</li>
                                    </ul>
                                    @break
                                @case(2)
                                    <ul class="progressbar">
                                        <li class="complete">契約手続中</li>
                                        <li class="active">契約書発行</li>
                                        <li>返却書類確認中</li>
                                        <li>引渡完了</li>
                                    </ul>
                                    @break
                                @case(3)
                                    <ul class="progressbar">
                                        <li class="complete">契約手続中</li>
                                        <li class="complete">契約書発行</li>
                                        <li class="active">返却書類確認中</li>
                                        <li>引渡完了</li>
                                    </ul>
                                    @break
                                @case(4)
                                    <ul class="progressbar">
                                        <li class="complete">契約手続中</li>
                                        <li class="complete">契約書発行</li>
                                        <li class="complete">返却書類確認中</li>
                                        <li class="active">引渡完了</li>
                                    </ul>
                                @break

                                @default
                                    <!-- 判定したい変数に0 1 2意外が格納されていた時の処理 -->
                            @endswitch

                        </div>
                        <!-- プログレスバー -->

                    </div>
                </div>

                <!-- 入力項目 -->
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">

                            <form id="editForm" class="needs-validation" novalidate>
                    
                                <!-- カード -->
                                <div class="card border border-0">

                                    @include('component.form_contract')

                                    <!-- ボタン -->
                                    <div class="row row-cols-2 mb-5">

                                        <!-- 削除 -->
                                        <div class="col-6 col-md-6 col-lg-6 mt-3">
                                            <button id="btn_delete" class="btn btn-outline-danger btn-default">削除</button>
                                        </div>
                                        
                                        <!-- 登録、帳票 -->
                                        <div class="col-6 col-md-6 col-lg-6 mt-3">
                                            <div class="btn-group float-end" role="group">
                                                <!-- 契約詳細id='':帳票ボタン非表示 -->
                                                @if( $contract_list->contract_detail_id !== '')
                                                    <button id="btn_contract" class="btn btn-outline-primary btn-default" @if($contract_list->admin_user_flag == 0) disabled @endif>帳票作成</button>
                                                @endif
                                                <button id="btn_temporarily" class="btn btn-outline-primary btn-default" @if($contract_list->admin_user_flag == 0) disabled @endif>一時登録</button>
                                                <button id="btn_edit" class="btn btn-outline-primary btn-default" @if($contract_list->admin_user_flag == 0) disabled @endif>登録</button>
                                            </div>
                                        </div>

                                    </div>     
                                    <!-- ボタン -->

                                    <!-- id -->
                                    <input type="hidden" name="contract_detail_id" id="contract_detail_id" value="{{ $contract_list->contract_detail_id }}">
                            
                                    <input type="hidden" name="application_id" id="application_id" value="{{ $contract_list->application_id }}">

                                    <input type="hidden" name="special_contract_detail_id" id="special_contract_detail_id" value="{{ $contract_list->special_contract_detail_id }}">

                                </div>
                                <!-- カード -->
                            </form>
                        </div>
                    </div>
                </div>
                <!-- 入力項目 -->

                <!-- モーダル(銀行一覧) -->
                <div class="modal fade" id="bankModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content px-3">
                            
                            <!-- モーダルコンテンツ -->
                            <div class="modal-body">

                                <!-- タイトル -->
                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12">
                                        <div class="info_title mt-3">
                                            <i class="far fa-gem icon_blue me-2"></i>家賃振込先一覧
                                        </div>
                                        <!-- 境界線 -->
                                        <hr>
                                        <!-- 境界線 -->
                                    </div>
                                </div>
                                <!-- タイトル -->

                                <!-- 検索欄 -->
                                <div class="row">
                                    <form action="backBankInit" method="post">
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
                                                        <input id="bank_search" class="btn btn-default btn-outline-primary float-end" value="検索">
                                                    </div>
                                                    <!-- 検索ボタン -->

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- 検索欄 -->
                            
                                <!-- 一覧 -->
                                <div class="col-12 col-md-12 col-lg-12 mt-4">
                                    <!-- カード -->
                                    <div class="card">
                                        <!-- カードボディ -->
                                        <div class="card-body">
                                            <!-- スクロール -->
                                            <div class="overflow-auto" style="height:25rem;">
                                                <div class="table-responsive">
                                                    <table id="bank_table" class="table table-hover table-condensed table-striped">
                                                        
                                                        <!-- 見出し -->
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" id="bank_name">銀行名</th>
                                                                <th scope="col" id="bank_branch_name">支店名</th>
                                                                <th scope="col" id="bank_type_name">種別</th>
                                                                <th scope="col" id="bank_number">口座番号</th>
                                                                <th scope="col" id="bank_account_name">名義人</th>
                                                            </tr>
                                                        </thead>
                                                        <!-- 見出し -->

                                                        <!-- テーブルボディ -->
                                                        <tbody></tbody>
                                                        <!-- テーブルボディ -->

                                                    </table>
                                                </div>
                                            </div>
                                            <!-- スクロール -->
                                        </div>
                                        <!-- カードボディ -->
                                    </div>
                                    <!-- カード -->

                                    <!-- ボタン -->
                                    <div class="col-sm-12 my-3 pt-2">
                                        <div class="card border border-0">
                                            <!-- row -->
                                            <div class="row">
                                                <!-- 選択・閉じる -->
                                                <div class="col-12">
                                                    <button type="button" class="btn btn-outline-primary float-end btn-default" data-bs-dismiss="modal">閉じる</button>
                                                </div>
                                            </div>
                                            <!-- row -->
                                        </div>
                                    </div>
                                    <!-- ボタン -->

                                </div>
                                <!-- 一覧 -->
                            </div>
                            <!-- モーダルコンテンツ -->

                        </div>
                    </div>
                </div>
                <!-- モーダル(銀行一覧) -->

                <!-- モーダル(同居人) -->
                <div class="modal fade" id="housemateModal" tabindex="-1" aria-labelledby="staticBackdropLabel" data-bs-backdrop="static" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered ">
                        <div class="modal-content">

                            <!-- モーダルボディ -->
                            <div class="modal-body">

                                <!-- タイトル -->
                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12">
                                        <div class="info_title mt-3">
                                            <i class="fas fa-key icon_blue me-2"></i>同居人追加
                                        </div>
                                        <!-- 境界線 -->
                                        <hr>
                                        <!-- 境界線 -->
                                    </div>
                                </div>
                                <!-- タイトル -->

                                <!-- コンテンツ -->
                                <div class="col-12 col-md-12 col-lg-12">
                                    <label for="">同居人名</label>
                                    <input type="text" class="form-control" name="modal_housemate_name" id="modal_housemate_name" value="">
                                    <div class="other-tab invalid-feedback" id ="modal_housemate_name_error">
                                    </div>
                                </div>

                                <!-- 生年月日 -->
                                <div class="col-12 col-md-12 col-lg-6 mt-3 mb-3">
                                    <label for="">生年月日</label>
                                    <input type="text" class="form-control" name="modal_housemate_date" id="modal_housemate_date" value="">
                                    <div class="other-tab invalid-feedback" id ="modal_housemate_date_error">
                                    </div>
                                </div>
                                <!-- コンテンツ -->

                            </div>
                            <!-- モーダルボディ -->

                            <div class="modal-footer">
                                <!-- id -->
                                <input type="hidden" name="contract_housemate_id" id="contract_housemate_id" value="">
                                <button type="button" class="btn btn-default btn-secondary" data-bs-dismiss="modal">閉じる</button>
                                <button type="button" id="btn_houseMate_edit" class="btn btn-default btn-outline-primary">登録</button>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- モーダル(同居人) -->

            </main>
            <!-- page-content" -->

		</div>
		<!-- page-wrapper -->

		@component('component.admin_js')
		@endcomponent

		<!-- 自作js -->
		<script src="{{ asset('admin/js/admin_contract_detail_edit.js') }}"></script>

        <!-- bootstrap-datepickerのjavascriptコード -->
        <script>
            
            // 契約始期
            $('#contract_start_date').datepicker({
                language:'ja'
            });

            // 契約終期
            $('#contract_end_date').datepicker({
                language:'ja'
            });

            // 築年月日
            $('#real_estate_age').datepicker({
                language:'ja'
            });

            // 契約者生年月日
            $('#contract_date').datepicker({
                language:'ja'
            });

            // 同居人生年月日
            $('#modal_housemate_date').datepicker({
                language:'ja'
            });

            // 決済予定日
            $('#payment_date').datepicker({
                language:'ja'
            });

            // 預金日付
            $('#today_account_fee_date').datepicker({
                language:'ja'
            });

        </script>
	</body>
	
</html>