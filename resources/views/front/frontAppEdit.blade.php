<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>申込詳細/KASEGU</title>

        <!-- css -->
        @component('component.front_head')
        @endcomponent

        <!-- siteUse -->
        <link rel="stylesheet" href="{{ asset('front/css/front_app_edit.css') }}">
    
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
        <!-- ヘッダー -->
        @component('component.front_headar')
        @endcomponent

		<!-- ローディング画面の表示 -->
		<div id="overlay">
			<div class="cv-spinner">
				<span class="spinner"></span>
			</div>
		</div>

        <!-- 説明 -->
        <div class="container mt-3">
            <div class="row">

                <!-- タイトル -->
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="info_title mt-3">
                        <i class="fas fa-id-card icon_blue me-2"></i>申込詳細
                    </div>
                    <hr>
                </div>

                <!-- プログレスバー -->
                <div class="col-12 col-md-12 col-lg-12 mt-4">

                    @switch($app_list->contract_progress_id)
                        @case(1)
                            <ul class="progressbar">
                                <li class="active">入居申込</li>
                                <li>入居審査中</li>
                                <li>契約手続中</li>
                            </ul>
                            @break
                        @case(2)
                            <ul class="progressbar">
                                <li class="complete">入居申込</li>
                                <li class="active">入居審査中</li>
                                <li>契約手続中</li>
                            </ul>
                            @break
                        @case(3)
                            <ul class="progressbar">
                                <li class="complete">入居申込</li>
                                <li class="complete">入居審査中</li>
                                <li class="active">契約手続中</li>
                            </ul>
                            @break
                        @default
                            <ul class="progressbar">
                                <li class="active">入居申込</li>
                                <li>入居審査中</li>
                                <li>契約手続中</li>
                            </ul>
                            <!-- 判定したい変数に0 1 2意外が格納されていた時の処理 -->
                    @endswitch

                </div>

            </div>
        </div>
        <!-- 説明 -->

        <!-- 入力項目 -->
        <div class="container mt-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <form id="editForm" class="needs-validation" novalidate>
            
                        <!-- カード -->
                        <div class="card border border-0">

                            @include('component.form_app')
                            
                            <!-- ボタン -->
                            <div class="row row-cols-2 mb-5">

                                <!-- 登録、帳票 -->
                                <div class="col-12 col-md-12 col-lg-12 mt-3">
                                    <div class="btn-group float-xl-end" role="group">

                                        <!-- 帳票登録 -->
                                        <button type="button" id="btn_make_report" class="btn btn-outline-primary btn-default" data-bs-toggle="modal" data-bs-target="#appModal">帳票作成</button>

                                        <!-- 登録 -->
                                        <button id="btn_edit" class="btn btn-outline-primary btn-default">登録</button>

                                    </div>
                                </div>
                                <!-- 登録、帳票 -->

                            </div>     
                            <!-- ボタン -->

                            <!-- 不動産id -->
                            <input type="hidden" name="application_id" id="application_id" value="{{ $app_list->application_id }}">
                            
                            <!-- 同居人id -->
                            <input type="hidden" name="housemate_id" id="housemate_id" value="">
                            
                            <!-- 同居人追加フラグ(追加=true 追加無=false) -->
                            <input type="hidden" name="housemate_add_flag" id="housemate_add_flag" value="false">
                            
                            <!-- ユーザid -->
                            <input type="hidden" name="session_id" id="session_id" value="{{ $session_id }}">

                        </div>
                        <!-- カード -->
                    </form>
                </div>
            </div>
        </div>
        <!-- 入力項目 -->

        <!-- 帳票モーダル -->
        <div class="modal fade" id="appModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body">
                        <!-- タイトル -->
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="info_title mt-3">
                                    <i class="fas fa-id-card icon_blue me-2"></i>帳票作成
                                </div>

                                <!-- 境界線 -->
                                <hr>
                                <!-- 境界線 -->
                            </div>

                            <!-- 保証会社名 -->
                            <div class="col-10 col-md-10 col-lg-10 mt-3">
                                <label class="mb-2" for="textBox"></label>保証会社名
                                
                                <select class="form-select " name="guarantee_company_id" id="guarantee_company_id">
                                    <!-- タグ内値を追加、値追加後同一の場合選択する -->
                                    <option></option>
                                    @foreach($guarantee_companies as $guarantee_company)
                                        <option value="{{ $guarantee_company->guarantee_company_id }}">{{ $guarantee_company->guarantee_company_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id ="guarantee_company_id_error">
                                </div>
                            </div>
                            <!-- 保証会社名 -->

                            <!-- 連帯保証人の有無 -->
                            <div class="col-4 col-md-4 col-lg-4 mt-3">
                                <label class="mb-2" for="textBox"></label>連帯保証人の有無

                                <select class="form-select" name="guarantor_need_id" id="guarantor_need_id">
                                    <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                    <option></option>
                                    @foreach($needs as $need)
                                        <option value="{{ $need->need_id }}">{{ $need->need_name }}</option>
                                    @endforeach
                    
                                </select>
                                <div class="invalid-feedback" id ="guarantor_need_id_error">
                                </div>
                            </div>
                            <!-- 連帯保証人の有無 -->

                        </div>
                        <!-- タイトル -->
                    </div>

                    <!-- ボタン -->
                    <div class="modal-footer mt-3">
                        <button type="button" id="btn_modal_close" class="btn btn-secondary btn-default" data-bs-dismiss="modal">閉じる</button>
                        <button type="button" id="btn_modal_report" class="btn btn-outline-primary btn-default">作成</button>
                    </div>
                    <!-- ボタン -->

                </div>
            </div>
        </div>
        <!-- 帳票モーダル -->

        <!-- フッダー-->
        @component('component.front_footer')
        @endcomponent

        <!-- js -->
        @component('component.front_js')
        @endcomponent

        <!-- 自作js -->
		<script src="{{ asset('front/js/front_app_edit.js') }}"></script>

        <!-- bootstrap-datepickerのjavascriptコード -->
        <script>

            // 契約者生年月日
            $('#entry_contract_birthday').datepicker({
                language:'ja'
            });

            /**
             * 入居予定日
             */
            $('#contract_start_date').datepicker({
                language:'ja'
            });

            /**
             * 同居人
             */
            $('#housemate_birthday').datepicker({
                language:'ja'
            });

            /**
             * 緊急連絡先
             */
            $('#emergency_birthday').datepicker({
                language:'ja'
            });

            /**
             * 連帯保証人
             */
            $('#guarantor_birthday').datepicker({
                language:'ja'
            });

        </script>

    </body>
</html>