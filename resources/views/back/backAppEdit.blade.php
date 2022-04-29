<!DOCTYPE html>
<html lang="ja">

	<head>
        <title>申込管理/KASEGU</title>

		<!-- head -->
		@component('component.back_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('back/css/back_app_edit.css') }}">  
		
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
                                    <!-- 判定したい変数に0 1 2意外が格納されていた時の処理 -->
                            @endswitch

                        </div>

                    </div>
                </div>

                <!-- 入力項目 -->
                <div class="container mt-4">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">

                            <form id="editForm" class="needs-validation" novalidate>
                    
                                <!-- カード -->
                                <div class="card border border-0">

                                    <!-- タブ -->
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 mt-2">
                                            <!-- ナビゲーションの設定 -->
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    <a class="nav-link active" id="nav-user-tab" data-bs-toggle="tab" href="#nav-user" role="tab" aria-controls="nav-user" aria-selected="true">不動産業者</a>
                                                    <a class="nav-link" id="nav-trade-tab" data-bs-toggle="tab" href="#nav-trade" role="tab" aria-controls="nav-trade" aria-selected="false">募集要項</a>
                                                    <a class="nav-link" id="nav-contract-tab" data-bs-toggle="tab" href="#nav-contract" role="tab" aria-controls="nav-contract" aria-selected="false">契約者</a>
                                                    <a class="nav-link" id="nav-housemate-tab" data-bs-toggle="tab" href="#nav-housemate" role="tab" aria-controls="nav-housemate" aria-selected="false">同居人</a>
                                                    <a class="nav-link" id="nav-emergency-tab" data-bs-toggle="tab" href="#nav-emergency" role="tab" aria-controls="nav-emergency" aria-selected="false">緊急連絡先</a>
                                                    <a class="nav-link" id="nav-guarantor-tab" data-bs-toggle="tab" href="#nav-guarantor" role="tab" aria-controls="nav-guarantor" aria-selected="false">連帯保証人</a>
                                                    <a class="nav-link" id="nav-document-tab" data-bs-toggle="tab" href="#nav-document" role="tab" aria-controls="nav-document" aria-selected="false">付属書類</a>
                                                </div>
                                            </nav>
                                            <!-- ナビゲーションの設定 -->
                                        </div>
                                    </div>
                                    <!-- タブ -->

                                    <!-- タブ内のコンテンツ -->
                                    <div class="row row-cols-3">
                                        <div class="col-12 col-md-12 col-lg-12 mb-3">
                                            <!-- 内容 -->
                                            <div class="tab-content" id="nav-tabContent">
                                            
                                                <!-- 業者 -->
                                                <div class="tab-pane fade show active" id="nav-user" role="tabpanel" aria-labelledby="nav-user-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 契約進捗 -->
                                                        <div class="col-6 col-md-8 col-lg-2 mt-3">
                                                            <label class="label_any mb-2"></label>契約進捗
                                                            
                                                            <select class="form-select" name="contract_progress_id" id="contract_progress_id">
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($contract_progress as $progress)
                                                                    <option value="{{$progress->contract_progress_id}}" @if($app_list->contract_progress_id == $progress->contract_progress_id) selected @endif>{{ $progress->contract_progress_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 仲介業者名 -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>仲介業者
                                                            <input type="text" class="form-control" name="broker_company_name" id="broker_company_name" value="{{ $app_list->broker_company_name }}" placeholder="例：株式会社〇〇〇〇" required>
                                                            <!-- バリデーション -->
                                                            <div class="user-tab invalid-feedback" id ="broker_company_name_error">
                                                                仲介業者名は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>
                                                        
                                                        <!-- 担当者 -->
                                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>担当者
                                                            <input type="text" class="form-control" name="broker_name" id="broker_name" value="{{ $app_list->broker_name }}" placeholder="例：大阪　太郎" required>
                                                            <!-- バリデーション -->
                                                            <div class="user-tab invalid-feedback" id =broker_name_error">
                                                                担当者は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 仲介業者Tel -->
                                                        <div class="col-6 col-md-8 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>Tel
                                                            <input type="text" class="form-control" name="broker_tel" id="broker_tel" value="{{ $app_list->broker_tel }}" placeholder="例：06-1234-5678" required>
                                                            <!-- バリデーション -->
                                                            <div class="user-tab invalid-feedback" id ="broker_tel_error">
                                                                仲介業者Telは必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 仲介業者mail -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>E-mail
                                                            <input type="mail" class="form-control" name="broker_mail" id="broker_mail" value="{{ $app_list->broker_mail }}" placeholder="例：xxxx@xxxx.com" required>
                                                            <!-- バリデーション -->
                                                            <div class="user-tab invalid-feedback" id ="broker_mail_error">
                                                                E-mailは必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>
                                                    </div>
                                                </div>
                                                <!-- 業者 -->

                                                <!-- 募集要項 -->
                                                <div class="tab-pane fade" id="nav-trade" role="tabpanel" aria-labelledby="nav-trade-tab">
                                                    <div class="row row-cols-2">

                                                        <!-- 個人又は法人 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>個人又は法人
                                                            <select class="form-select" name="private_or_company_id" id="private_or_company_id" required>
                                                                <option></option>
                                                                @foreach($private_or_companies as $private_or_company)
                                                                    <option value="{{ $private_or_company->private_or_company_id }}" @if($app_list->private_or_company_id == $private_or_company->private_or_company_id) selected @endif>{{ $private_or_company->private_or_company_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="trade-tab invalid-feedback" id="private_or_company_id_error">
                                                                個人又は法人は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 個人又は法人 -->

                                                        <div class="w-100"></div>

                                                        <!-- 申込区分 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>申込区分
                                                            <select class="form-select" name="application_type_id" id="application_type_id" required>
                                                                <option></option>
                                                                @foreach($app_types as $types)
                                                                    <option value="{{ $types->application_type_id }}" @if($app_list->application_type_id == $types->application_type_id) selected @endif>{{ $types->application_type_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="trade-tab invalid-feedback" id="application_type_error">
                                                                申込区分は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 物件用途 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>物件用途
                                                            <select class="form-select" name="application_use_id" id="application_use_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($app_uses as $uses)
                                                                    <option value="{{ $uses->application_use_id }}" @if($app_list->application_use_id == $uses->application_use_id) selected @endif>{{ $uses->application_use_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="trade-tab invalid-feedback" id="application_use_error">
                                                                物件用途は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 入居予定日 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>入居予定日
                                                            <input type="text" class="form-control" name="contract_start_date" id="contract_start_date" value="{{ $app_list->contract_start_date }}" placeholder="例：xxxx年xx月xx日" required>
                                                            <div class="trade-tab invalid-feedback" id ="contract_start_date_error"></div>
                                                        </div>

                                                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 物件名 -->
                                                        <div class="col-12 col-md-12 col-lg-9 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>物件名
                                                            <input type="text" class="form-control" name="real_estate_name" id="real_estate_name" value="{{ $app_list->real_estate_name }}" placeholder="例：大阪マンション" required>
                                                            <div class="trade-tab invalid-feedback" id ="real_estate_name_error">
                                                                物件名は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 物件カナ -->
                                                        <div class="col-12 col-md-12 col-lg-9 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>物件名カナ
                                                            <input type="text" class="form-control" name="real_estate_ruby" id="real_estate_ruby" value="{{ $app_list->real_estate_ruby }}" placeholder="例：オオサカマンション" required>
                                                            <div class="trade-tab invalid-feedback" id ="real_estate_ruby_error">
                                                                物件名カナは必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>
                                                    
                                                        <!-- 号室 -->
                                                        <div class="col-5 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>号室
                                                            <input type="text" class="form-control" name="room_name" value="{{ $app_list->room_name }}" id="room_name" placeholder="例：101" required>
                                                            <div class="trade-tab invalid-feedback" id ="room_name_error">
                                                                号室は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 郵便番号 -->
                                                        <div class="col-7 col-md-4 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="post_number" id="post_number" value="{{ $app_list->post_number }}" placeholder="例：5450021" required>
                                                                <button id="real_estate_agent-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="trade-tab invalid-feedback" id="post_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- 住所 -->
                                                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>住所
                                                            <input type="text" class="form-control" name="address" id="address" value="{{ $app_list->address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy" required>
                                                            <div class="trade-tab invalid-feedback" id ="room_name_error">
                                                                住所は必須です。
                                                            </div>       
                                                        </div>

                                                        <!-- ペット飼育数 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>ペット飼育数
                                                            <input type="number" class="form-control" name="pet_bleed" id="pet_bleed" value="{{ $app_list->pet_bleed }}" placeholder="例：2" style="text-align:right" required> 
                                                            <div class="trade-tab invalid-feedback" id ="pet_bleed_error">
                                                                ペット飼育数は必須です。
                                                            </div>  
                                                        </div>

                                                        <!-- ペット種類 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>ペット種類
                                                            <input type="text" class="form-control" name="pet_kind" id="pet_kind" value="{{ $app_list->pet_kind }}" placeholder="例：飼育有：チワワ/飼育無：なし" required> 
                                                            <div class="trade-tab invalid-feedback" id ="pet_kind_error">
                                                                ペット種類は必須です。
                                                            </div>  
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 駐車台数 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>駐車台数
                                                            <input type="number" class="form-control" name="bicycle_number" id="bicycle_number" value="{{ $app_list->bicycle_number }}" placeholder="例：1" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="bicycle_number_error">
                                                                駐車台数は必須です。
                                                            </div>   
                                                        </div>

                                                        <!-- 駐輪台数 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>駐輪台数
                                                            <input type="number" class="form-control" name="car_number_number" id="car_number_number" value="{{ $app_list->car_number }}" placeholder="例：1" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="car_number_error">
                                                                駐輪台数は必須です。
                                                            </div>  
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 保証金 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>保証金
                                                            <input type="number" class="form-control" name="deposit_fee" id="deposit_fee" value="{{ $app_list->deposit_fee }}" placeholder="例：1000000" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="deposit_fee_error">
                                                                保証金は必須です。
                                                            </div>  
                                                        </div>

                                                        <!-- 解約引き -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>解約引き
                                                            <input type="number" class="form-control" name="refund_fee" id="refund_fee" value="{{ $app_list->refund_fee }}" placeholder="例：1000000" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="refund_fee_error">
                                                                解約引きは必須です。
                                                            </div>  
                                                        </div>

                                                        <!-- 敷金 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>敷金
                                                            <input type="number" class="form-control" name="security_fee" id="security_fee" value="{{ $app_list->security_fee }}" placeholder="例：1000000" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="security_fee_error">
                                                                敷金は必須です。
                                                            </div> 
                                                        </div>

                                                        <!-- 礼金 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>礼金
                                                            <input type="number" class="form-control" name="key_fee" id="key_fee" value="{{ $app_list->key_fee }}" placeholder="例：1000000" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="key_fee_error">
                                                                礼金は必須です。
                                                            </div> 
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 賃料 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>賃料
                                                            <input type="number" class="form-control fee_text" name="rent_fee" id="rent_fee" value="{{ $app_list->rent_fee }}" placeholder="例：1000000" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="rent_fee_error">
                                                                賃料は必須です。
                                                            </div> 
                                                        </div>

                                                        <!-- 共益費 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>共益費
                                                            <input type="number" class="form-control fee_text" name="service_fee" id="service_fee" value="{{ $app_list->service_fee }}" placeholder="例：10000" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="service_fee_error">
                                                                共益費は必須です。
                                                            </div> 
                                                        </div>

                                                        <!-- 水道代 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>水道代
                                                            <input type="number" class="form-control fee_text" name="water_fee" id="water_fee" value="{{ $app_list->water_fee }}" placeholder="例：2000" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="water_fee_error">
                                                                水道代は必須です。
                                                            </div> 
                                                        </div>

                                                        <!-- その他 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>その他
                                                            <input type="number" class="form-control fee_text" name="ohter_fee" id="ohter_fee" value="{{ $app_list->ohter_fee }}" placeholder="例：1000" style="text-align:right" required>
                                                            <div class="trade-tab invalid-feedback" id ="ohter_fee_error">
                                                                その他は必須です。
                                                            </div> 
                                                        </div>

                                                        <!-- 総賃料 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label for="">月額賃料</label>
                                                            <input type="number" disabled="disabled" class="form-control" name="total_fee" id="total_fee" value="{{ $app_list->total_fee }}" placeholder="例：200" style="text-align:right" required>
                                                        </div>
                                        
                                                    </div>
                                                </div>
                                                <!-- 条件 -->   

                                                <!-- 契約者 -->
                                                <div class="tab-pane fade" id="nav-contract" role="tabpanel" aria-labelledby="nav-contract-tab">
                                                    <div class="row row-cols-2">
                                                        <!-- 契約者名 -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>契約者
                                                            <input type="text" class="form-control" name="entry_contract_name" id="entry_contract_name" value="{{ $app_list->entry_contract_name }}" placeholder="例：大阪　太郎" required required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_name_error">
                                                                契約者は必須です。
                                                            </div>  
                                                        </div>

                                                        <!-- 契約者カナ -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>契約者カナ
                                                            <input type="text" class="form-control" name="entry_contract_ruby" id="entry_contract_ruby" value="{{ $app_list->entry_contract_ruby }}" placeholder="例：オオサカ　タロウ" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_ruby_error">
                                                                契約者カナは必須です。
                                                            </div>  
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 郵便番号 -->
                                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="entry_contract_post_number" id="entry_contract_post_number" value="{{ $app_list->entry_contract_post_number }}" placeholder="例：5450021" required>
                                                                <button id="contract-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="contract-tab invalid-feedback" id="entry_contract_post_number_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- 住所 -->
                                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>住所
                                                            <input type="text" class="form-control" name="entry_contract_address" id="entry_contract_address" value="{{ $app_list->entry_contract_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_address_error">
                                                                住所は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 性別 -->
                                                        <div class="col-6 col-md-5 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>性別
                                                            <select class="form-select" name="entry_contract_sex_id" id="entry_contract_sex_id" required>
                                                                <option></option> 
                                                                @foreach($app_sexes as $sexes)
                                                                    <option value="{{ $sexes->sex_id }}" @if($app_list->entry_contract_sex_id == $sexes->sex_id) selected @endif>{{ $sexes->sex_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <!-- エラーメッセージ -->
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_sex_id_error">
                                                                性別は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 生年月日 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>生年月日
                                                            <input type="text" class="form-control" name="entry_contract_birthday" id="entry_contract_birthday" value="{{ $app_list->entry_contract_birthday }}" placeholder="例：xxxx年xx月xx日" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_birthday_error">
                                                                生年月日は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 年齢 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>年齢
                                                            <input type="number" class="form-control" name="entry_contract_age" id="entry_contract_age" value="{{ $app_list->entry_contract_age }}" placeholder="例：30" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_age_error">
                                                                年齢は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 電話番号 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>電話番号
                                                            <input type="text" class="form-control" name="entry_contract_home_tel" id="entry_contract_home_tel" value="{{ $app_list->entry_contract_home_tel }}" placeholder="例：080-xxxx-xxxx" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_home_tel_error">
                                                                電話番号は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 電話番号2 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>電話番号2
                                                            <input type="text" class="form-control" name="entry_contract_mobile_tel" id="entry_contract_mobile_tel" value="{{ $app_list->entry_contract_mobile_tel }}" placeholder="例：080-xxxx-xxxx">
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_mobile_tel_error">
                                                            </div>
                                                        </div>

                                                        <!-- 勤務先名 -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>勤務先名
                                                            <input type="text" class="form-control" name="entry_contract_business_name" id="entry_contract_business_name" value="{{ $app_list->entry_contract_business_name }}" placeholder="例：株式会社〇〇〇〇" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_business_name_error">
                                                                勤務先名は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 勤務先フリガナ -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>勤務先名カナ
                                                            <input type="text" class="form-control" name="entry_contract_business_ruby" id="entry_contract_business_ruby" value="{{ $app_list->entry_contract_business_ruby }}" placeholder="例：カブシキカイシャ〇〇〇〇" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_business_ruby_error">
                                                                勤務先カナは必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 勤務先郵便番号 -->
                                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="entry_contract_business_post_number" id="entry_contract_business_post_number" value="{{ $app_list->entry_contract_business_post_number }}" placeholder="例：5450021" required>
                                                                <button id="contract_business-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="contract-tab invalid-feedback" id="entry_contract_business_post_number_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- 勤務先郵便番号 -->

                                                        <!-- 勤務先住所 -->
                                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>所在地
                                                            <input type="text" class="form-control" name="entry_contract_business_address" id="entry_contract_business_address" value="{{ $app_list->entry_contract_business_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_business_address_error">
                                                                所在地は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 勤務先電話番号 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>勤務先Tel
                                                            <input type="text" class="form-control" name="entry_contract_business_tel" id="entry_contract_business_tel" value="{{ $app_list->entry_contract_business_tel }}" placeholder="例：06-xxxx-xxxx" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_business_tel_error">
                                                                勤務先Telは必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 業種 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>業種
                                                            <input type="text" class="form-control" name="entry_contract_business_type" id="entry_contract_business_type" value="{{ $app_list->entry_contract_business_type }}" placeholder="例：建築業" required>
                                                            <div class="contract-tab invalid-feedback" id="entry_contract_business_type_error">
                                                                業種が必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 職種 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>職種
                                                            <input type="text" class="form-control" name="entry_contract_business_line" id="entry_contract_business_line" value="{{ $app_list->entry_contract_business_line }}" placeholder="例：営業部" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_business_line_error">
                                                                職種は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 雇用形態 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>雇用形態
                                                            <input type="text" class="form-control" name="entry_contract_business_status" id="entry_contract_business_status" value="{{ $app_list->entry_contract_business_status }}" placeholder="例：課長" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_business_status_error">
                                                                雇用形態は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 勤続年数 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>勤続年数
                                                            <input type="number" class="form-control" name="entry_contract_business_year" id="entry_contract_business_year" value="{{ $app_list->entry_contract_business_year }}" style="text-align:right" placeholder="例：10" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_business_year_error">
                                                                勤続年数は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 年収 -->
                                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>年収
                                                            <input type="number" class="form-control" name="entry_contract_income" id="entry_contract_income" value="{{ $app_list->entry_contract_income }}" style="text-align:right" placeholder="例：3000000" required>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_income_error">
                                                                年収は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 健康保険 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>健康保険
                                                            <select class="form-select" name="entry_contract_insurance_type_id" id="entry_contract_insurance_type_id" required>
                                                                <option></option>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                @foreach($app_insurances as $insurances)
                                                                    <option value="{{ $insurances->insurance_id }}" @if($app_list->entry_contract_insurance_type_id == $insurances->insurance_id) selected @endif>{{ $insurances->insurance_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="contract-tab invalid-feedback" id ="entry_contract_insurance_type_id_error">
                                                                健康保険は必須です。
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- 契約者 -->

                                                <!-- 同居人 -->
                                                <div class="tab-pane fade" id="nav-housemate" role="tabpanel" aria-labelledby="nav-housemate-tab">
                                                    <div class="row row-cols-2">

                                                        <!-- 新規登録の際、説明を非表示 -->
                                                        @if( $app_list->application_id == null )
                                                            <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                                <div class="mt-3">
                                                                    <i class="fas fa-exclamation-circle icon_blue me-1"></i><span class="text_red">初回登録時、同居人を追加することが出来ません。登録後、【編集】から同居人を追加してください。</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <!-- 新規登録の際、説明を非表示 -->

                                                        <!-- 同居人 -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>同居人
                                                            <input type="text" class="form-control" name="housemate_name" id="housemate_name" value="" placeholder="例：大阪　花子" disabled>
                                                            <div class="housemate-tab invalid-feedback" id ="housemate_name_error"></div>
                                                        </div>

                                                        <!-- 同居人カナ -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>同居人カナ
                                                            <input type="text" class="form-control" name="housemate_ruby" id="housemate_ruby" value="" placeholder="例：オオサカ　ハナコ" disabled>
                                                            <div class="housemate-tab invalid-feedback" id ="housemate_ruby_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 性別 -->
                                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>性別
                                                            <select class="form-select" name="housemate_sex_id" id="housemate_sex_id" disabled>
                                                                <option></option>
                                                                @foreach($app_sexes as $sexes)
                                                                    <option value="{{ $sexes->sex_id }}">{{ $sexes->sex_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="housemate-tab invalid-feedback" id ="housemate_sex_id_error"></div>
                                                        </div>

                                                        <!-- 続柄 -->
                                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>続柄
                                                                <select class="form-select" name="housemate_link_id" id="housemate_link_id" disabled>
                                                                <option></option>
                                                                @foreach($app_links as $links)
                                                                    <option value="{{ $links->link_id }}">{{ $links->link_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <!-- エラーメッセージ -->
                                                            <div class="housemate-tab invalid-feedback" id ="housemate_link_id_error"></div>
                                                        </div>

                                                        <!-- 生年月日 -->
                                                        <div class="col-12 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>生年月日
                                                            <input type="text" class="form-control" name="housemate_birthday" id="housemate_birthday" value="" placeholder="例：xxxx年xx月xx日" disabled>
                                                            <div class="housemate-tab invalid-feedback" id ="housemate_birthday_error"></div>
                                                        </div>

                                                        <!-- 年齢 -->
                                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>年齢
                                                            <input type="number" class="form-control" name="housemate_age" id="housemate_age" value="" style="text-align:right" placeholder="例：30" disabled>
                                                            <div class="housemate-tab invalid-feedback" id ="housemate_age_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 電話番号 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>電話番号
                                                            <input type="text" class="form-control" name="housemate_home_tel" id="housemate_home_tel" value="" placeholder="例：080-xxxx-xxxx" disabled>
                                                            <div class="housemate-tab invalid-feedback" id ="housemate_home_tel_error"></div>
                                                        </div>

                                                        <!-- 電話番号2 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>電話番号2
                                                            <input type="text" class="form-control" name="housemate_mobile_tel" id="housemate_mobile_tel" value="" placeholder="例：080-xxxx-xxxx" disabled>
                                                            <div class="housemate-tab invalid-feedback" id ="housemate_mobile_tel_error"></div>
                                                        </div>
                                                        
                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>
                                                        
                                                        <!-- 一覧タイトル -->
                                                        <div class="col-12 col-md-12 col-lg-12 mt-4 mb-2">
                                                            <div class="row">

                                                                <!-- 説明 -->
                                                                <div class="col-12 col-md-12 col-lg-6 mt-4">
                                                                    <i class="fas fa-plus icon_blue me-2"></i><span class="text_red">【編集】一覧をダブルクリックの後、詳細を上部テキストボックスに反映し、再度入力してください。</span>
                                                                </div>
                                                                
                                                                <!-- 同居人追加(新規=表示無/編集=表示有) -->
                                                                <div class="col-12 col-md-12 col-lg-6 mt-4">
                                                                    <span id="housemate_add" class="float-end text-primary" style="cursor: hand; cursor:pointer;">@if( $app_list->application_id !== '' ) 同居人追加 @endif</span> 
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <!-- 一覧タイトル -->
                                                        
                                                        <!-- 一覧 -->
                                                        <div class="col-12 col-md-12 col-lg-12">
                                                            <div class="card">
                                                                <!-- カードボディ -->
                                                                <div class="card-body">
                                                                    <!-- スクロール -->
                                                                    <div class="overflow-auto" style="height:12rem;">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover table-condensed">
                                                                                <!-- テーブルヘッド -->
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="col" id="create_user_id" style="display:none">id</th>
                                                                                        <th scope="col" id="create_user_name">同居人名</th>
                                                                                        <th scope="col" id="create_user_name">同居人カナ</th>
                                                                                        <th scope="col" id="create_user_name">性別</th>
                                                                                        <th scope="col" id="post_number">続柄</th>
                                                                                        <th scope="col" id="address">生年月日</th>
                                                                                        <th scope="col" id="create_user_fax">年齢</th>
                                                                                        <th scope="col" id="create_user_mail">自宅Tel</th>
                                                                                        <th scope="col" id="create_user_tel">携帯Tel</th>
                                                                                        <th scope="col" id="create_user_tel"></th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <!-- テーブルボディ -->
                                                                                <tbody>
                                                                                    
                                                                                    @foreach($houseMate_list as $houseMates)
                                                                                        <tr>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class" style="display:none"></td>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class">{{ $houseMates->housemate_name }}</td>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class">{{ $houseMates->housemate_ruby }}</td>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class">{{ $houseMates->housemate_sex_name }}</td>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class">{{ $houseMates->link_name }}</td>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class">{{ $houseMates->housemate_birthday }}</td>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class">{{ $houseMates->housemate_age }}</td>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class">{{ $houseMates->housemate_home_tel }}</td>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class">{{ $houseMates->housemate_mobile_tel }}</td>
                                                                                            <td id="{{ $houseMates->housemate_id }}" class="click_class"><span id="{{ $houseMates->housemate_id }}" class="hm_btn_delete text_red" style="cursor: hand; cursor:pointer;">削除</span></td>
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
                                                        </div>
                                                        <!-- 一覧 -->

                                                    </div>
                                                </div>
                                                <!-- 同居人 -->

                                                <!-- 緊急連絡先 -->
                                                <div class="tab-pane fade" id="nav-emergency" role="tabpanel" aria-labelledby="nav-emergency-tab">
                                                    <div class="row row-cols-2">

                                                        <!-- 緊急連絡先 -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>緊急連絡先
                                                            <input type="text" class="form-control" name="emergency_name" id="emergency_name" value="{{ $app_list->emergency_name }}" placeholder="例：大阪　一郎" required>
                                                            <div class="emergency-tab invalid-feedback" id ="emergency_contacts_name_error">
                                                                緊急連絡先は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 緊急連絡先カナ -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>緊急連絡先カナ
                                                            <input type="text" class="form-control" name="emergency_ruby" id="emergency_ruby" value="{{ $app_list->emergency_ruby }}" placeholder="例：オオサカ　イチロウ" required>
                                                            <div class="emergency-tab invalid-feedback" id ="emergency_contacts_ruby_error">
                                                                緊急連絡先カナは必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 性別 -->
                                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>性別
                                                            <select class="form-select" name="emergency_sex_id" id="emergency_sex_id" required>
                                                                <option></option>
                                                                @foreach($app_sexes as $sexes)
                                                                    <option value="{{ $sexes->sex_id }}" @if($app_list->emergency_sex_id == $sexes->sex_id) selected @endif>{{ $sexes->sex_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="emergency-tab invalid-feedback" id ="emergency_sex_id_error">
                                                                性別は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 続柄 -->
                                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>続柄
                                                            <select class="form-select" name="emergency_link_id" id="emergency_link_id" required>
                                                                <option></option>
                                                                @foreach($app_links as $links)
                                                                    <option value="{{ $links->link_id }}" @if($app_list->emergency_link_id == $links->link_id) selected @endif>{{ $links->link_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="emergency-tab invalid-feedback" id ="emergency_link_id_error">
                                                                続柄は必須です。
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 生年月日 -->
                                                        <div class="col-12 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>生年月日
                                                            <input type="text" class="form-control" name="emergency_birthday" id="emergency_birthday" value="{{ $app_list->emergency_birthday }}" placeholder="例：xxxx年xx月xx日" required>
                                                            <div class="emergency-tab invalid-feedback" id ="emergency_birthday_error">
                                                                生年月日は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 年齢 -->
                                                        <div class="col-12 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>年齢
                                                            <input type="number" class="form-control" name="emergency_age" id="emergency_age" value="{{ $app_list->emergency_age }}" style="text-align:right" placeholder="例：30" required>
                                                            <div class="emergency-tab invalid-feedback" id ="emergency_age_error">
                                                                年齢は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>
                                                        
                                                        <!-- 郵便番号 -->
                                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="emergency_post_number" id="emergency_post_number" value="{{ $app_list->emergency_post_number }}" placeholder="例：5450001" required>
                                                                <button id="emergency-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="emergency-tab invalid-feedback" id="emergency_post_number_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- 住所 -->
                                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>住所
                                                            <input type="text" class="form-control" name="emergency_address" id="emergency_address" value="{{ $app_list->emergency_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy" required>
                                                            <div class="emergency-tab invalid-feedback" id ="emergency_address_error">
                                                                住所は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 電話番号 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>電話番号
                                                            <input type="text" class="form-control" name="emergency_home_tel" id="emergency_home_tel" value="{{ $app_list->emergency_home_tel }}" placeholder="例：080-xxxx-xxxx" required>
                                                            <div class="emergency-tab invalid-feedback" id ="emergency_home_tel_error">
                                                                電話番号は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 電話番号2 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>電話番号2
                                                            <input type="text" class="form-control" name="emergency_mobile_tel" id="emergency_mobile_tel" value="{{ $app_list->emergency_mobile_tel }}" placeholder="例：080-xxxx-xxxx">
                                                            <div class="emergency-tab invalid-feedback" id ="emergency_mobile_tel_error">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- 緊急連絡先 --> 

                                                <!-- 連帯保証人 -->
                                                <div class="tab-pane fade" id="nav-guarantor" role="tabpanel" aria-labelledby="nav-guarantor-tab">
                                                    <div class="row row-cols-2">

                                                        <!-- 連帯保証人の有無 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>連帯保証人の有無
                                                            <select class="form-select" name="guarantor_flag" id="guarantor_flag" required>
                                                                <option></option>
                                                                @foreach($needs as $need_list)
                                                                    <option value="{{ $need_list->need_id }}" @if($app_list->guarantor_flag == $need_list->need_id) selected @endif>{{ $need_list->need_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="guarantor-tab invalid-feedback" id="guarantor_flag_error">
                                                                連帯保証人の有無は必須です。
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-12 col-lg-3 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input guarantor_disable_flag" type="checkbox" value="" id="cb_emergency">
                                                                <label class="form-check-label" for="flexCheckDefault">
                                                                    緊急連絡先と同じ
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 連帯保証人名 -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>連帯保証人
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_name" id="guarantor_name" value="{{ $app_list->guarantor_name }}" placeholder="例：大阪　二郎">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_name_error">
                                                            </div>
                                                        </div>

                                                        <!-- 連帯保証人カナ -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>連帯保証人カナ
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_ruby" id="guarantor_ruby" value="{{ $app_list->guarantor_ruby }}" placeholder="例：オオサカ　ジロウ">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_ruby_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 性別 -->
                                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>性別
                                                            <select class="form-select guarantor_disable_flag" name="guarantor_sex_id" id="guarantor_sex_id">
                                                                <option></option>
                                                                @foreach($app_sexes as $sexes)
                                                                    <option value="{{ $sexes->sex_id }}" @if($app_list->guarantor_sex_id == $sexes->sex_id) selected @endif>{{ $sexes->sex_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_sex_id_error"></div>
                                                        </div>

                                                        <!-- 続柄 -->
                                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>続柄
                                                            <select class="form-select guarantor_disable_flag" name="guarantor_link_id" id="guarantor_link_id" value="">
                                                                <option></option>
                                                                @foreach($app_links as $links)
                                                                    <option value="{{ $links->link_id }}" @if($app_list->guarantor_link_id == $links->link_id) selected @endif>{{ $links->link_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_link_id_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 生年月日 -->
                                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>生年月日
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_birthday" id="guarantor_birthday" value="{{ $app_list->guarantor_birthday }}" placeholder="例：xxxx年xx月xx日">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_birthday_error"></div>
                                                        </div>

                                                        <!-- 年齢 -->
                                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>年齢
                                                            <input type="number" class="form-control guarantor_disable_flag" name="guarantor_age" id="guarantor_age" value="{{ $app_list->guarantor_age }}" style="text-align:right" placeholder="例：40">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_age_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 郵便番号 -->
                                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                            <input type="number" class="form-control guarantor_disable_flag" name="guarantor_post_number" id="guarantor_post_number" value="{{ $app_list->guarantor_post_number }}" placeholder="例：5450021">
                                                            <button id="guarantors-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="guarantor-tab invalid-feedback" id="guarantor_post_number_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- 住所 -->
                                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>住所
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_address" id="guarantor_address" value="{{ $app_list->guarantor_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_address_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 電話番号 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>電話番号
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_home_tel" id="guarantor_home_tel" value="{{ $app_list->guarantor_home_tel }}" placeholder="例：080-xxxx-xxxx">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_home_tel_error"></div>
                                                        </div>

                                                        <!-- 電話番号2 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>電話番号2
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_mobile_tel" id="guarantor_mobile_tel" value="{{ $app_list->guarantor_mobile_tel }}" placeholder="例：080-xxxx-xxxx">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_mobile_tel_error"></div>
                                                        </div>

                                                        <!-- 勤務先名 -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>勤務先名
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_business_name" id="guarantor_business_name" value="{{ $app_list->guarantor_business_name }}" placeholder="例：株式会社〇〇〇〇">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_business_name_error"></div>
                                                        </div>

                                                        <!-- 勤務先フリガナ -->
                                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>勤務先カナ
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_business_ruby" id="guarantor_business_ruby" value="{{ $app_list->guarantor_business_ruby }}" placeholder="例：カブシキカイシャ〇〇〇〇">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_business_ruby_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 郵便番号 -->
                                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                            <input type="number" class="form-control guarantor_disable_flag" name="guarantor_business_post_number" id="guarantor_business_post_number" value="{{ $app_list->guarantor_business_post_number }}" placeholder="例：5450021">
                                                            <button id="guarantor_business-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="guarantor-tab invalid-feedback" id="guarantor_business_post_number_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 勤務先住所 -->
                                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>所在地
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_business_address" id="guarantor_business_address" value="{{ $app_list->guarantor_business_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_business_address_error"></div>
                                                        </div>

                                                        <!-- 勤務先電話番号 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>勤務先Tel
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_business_tel" id="guarantor_business_tel" value="{{ $app_list->guarantor_business_tel }}" placeholder="例：06-xxxx-xxxx">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_business_tel_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 業種 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>業種
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_business_type" id="guarantor_business_type" value="{{ $app_list->guarantor_business_type }}" placeholder="例：サービス業">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_business_type_error"></div>
                                                        </div>

                                                        <!-- 職種 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>職種
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_business_line" id="guarantor_business_line" value="{{ $app_list->guarantor_business_line }}" placeholder="例：事務">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_business_line_error"></div>
                                                        </div>

                                                        <!-- 雇用形態 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>雇用形態
                                                            <input type="text" class="form-control guarantor_disable_flag" name="guarantor_business_status" id="guarantor_business_status" value="{{ $app_list->guarantor_business_status }}" placeholder="例：正社員">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_business_status_error"></div>
                                                        </div>

                                                        <!-- 勤続年数 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>勤続年数
                                                            <input type="number" class="form-control guarantor_disable_flag" name="guarantor_business_years" id="guarantor_business_years" value="{{ $app_list->guarantor_business_years }}" style="text-align:right" placeholder="例：50">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_business_years_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 年収 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>年収
                                                            <input type="number" class="form-control guarantor_disable_flag" name="guarantor_income" id="guarantor_income" value="{{ $app_list->guarantor_income }}" style="text-align:right" placeholder="例：50000000">
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_income_error"></div>
                                                        </div>

                                                        <!-- 健康保険 -->
                                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>健康保険
                                                            <select class="form-select guarantor_disable_flag" name="guarantor_insurance_type_id" id="guarantor_insurance_type_id">
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option value=""></option>
                                                                @foreach($app_insurances as $insurances)
                                                                    <option value="{{ $insurances->insurance_id }}" @if($app_list->guarantor_insurance_type_id == $insurances->insurance_id) selected @endif>{{ $insurances->insurance_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="guarantor-tab invalid-feedback" id ="guarantor_insurance_type_id_error"></div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- 連帯保証人 -->

                                                <!-- 画像 -->
                                                <div class="tab-pane fade" id="nav-document" role="tabpanel" aria-labelledby="nav-document-tab">
                                                    <div class="row row-cols-3">

                                                        <!-- 添付書類 -->
                                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                                            <label class="mb-2">アップロード</label>
                                                            <input class="form-control" type="file" id="img_file">
                                                            <!-- エラーメッセージ -->
                                                            <div class="invalid-feedback" id ="img_file_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 種別 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="mb-2">ファイル種別</label>
                                                            <select class="form-select" name="img_type" id="img_type">
                                                                <option selected></option>
                                                                @foreach($img_type as $types)
                                                                    <option value="{{ $types->img_type_id }}">{{ $types->img_type_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback" id ="img_type_error"></div>
                                                        </div>

                                                        <!-- 補足 -->
                                                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                            <label for="">備考</label>
                                                            <textarea class="form-control" name="img_text" id="img_text" rows="2" placeholder="例：自由に入力"></textarea>
                                                            <div class="invalid-feedback" id ="img_text_error"></div>
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 画像ファイル -->
                                                        @if(count($img_list) > 0)
                                                            <div class="col-12 col-md-12 col-lg-12 mt-4">

                                                                <!-- タイトル -->
                                                                <i class="fas fa-file icon_blue me-2"></i>付属書類
                                                                <hr class="hr_album">

                                                                <div class="row">
                                                                    
                                                                    @foreach($img_list as $imgs)
                                                                        <div class="col-12 col-md-12 col-lg-4 mt-3 mb-2">
                                                                            <div class="card" style="min-height:25rem;">
                                                                                
                                                                                <img src="storage/{{ $imgs->img_path }}" class="card-img-top">
                                                                                
                                                                                <!-- カードボディ -->
                                                                                <div class="card-body">
                                                                                    <ul class="list-group list-group-flush">
                                                                                        <li class="list-group-item">種別:{{ $imgs->img_type_name }}</li>
                                                                                        <li class="list-group-item">備考:{{ $imgs->img_memo }}</li>
                                                                                    </ul>
                                                                                </div>
                                                                                <!-- カードボディ -->

                                                                                <!-- 削除ボタン -->
                                                                                <div class="card-footer">
                                                                                    <span id="{{ $imgs->img_id }}" class="btn_img_delete text_red float-end" style="cursor: hand; cursor:pointer;">削除</span>
                                                                                </div>
                                                                                <!-- 削除ボタン -->

                                                                            </div>
                                                                        </div>
                                                                    @endforeach                        

                                                                </div>
                                                            </div>
                                                        @endif
                                                        <!-- 画像ファイル -->

                                                    </div>
                                                </div>
                                                <!-- 画像 -->

                                            </div>
                                            <!-- 内容 -->
                                        </div>
                                    </div>
                                    <!-- タブ内のコンテンツ -->

                                    <!-- 境界線 -->
                                    <hr>
                                    <!-- 境界線 -->

                                    <!-- ボタン -->
                                    <div class="row row-cols-2 mb-5">

                                        <!-- 削除 -->
                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                            <div class="btn-group" role="group">
                                                <button type="button" id="btn_delete" class="btn btn-outline-danger btn-default">削除</button>
                                                <button type="button" id="btn_app" class="btn btn-outline-primary btn-default">契約に進む</button>
                                                <button type="button" id="btn_url_again" class="btn btn-outline-primary btn-default" data-bs-toggle="modal" data-bs-target="#urlModal">URL発行</button>
                                            </div>
                                        </div>
                                        <!-- 削除 -->

                                        <!-- 登録、帳票 -->
                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                            <div class="btn-group float-xl-end" role="group">

                                                <!-- 帳票登録 -->
                                                <button type="button" class="btn btn-outline-primary btn-default" data-bs-toggle="modal" data-bs-target="#appModal">帳票作成</button>

                                                <!-- 一時登録 -->
                                                <button id="btn_temporarily" class="btn btn-outline-primary btn-default">一時登録</button>

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

                <!-- URL発行 -->
                <div class="modal fade" id="urlModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <!-- ヘッダー -->
                            <div class="modal-header">
                                <div class="modal-title info_title" id="exampleModalLabel">
                                    <i class="fas fa-paper-plane icon_blue me-2"></i>URL発行
                                </div>

                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- ボディ -->
                            <div class="modal-body">
                                <form id="modalForm" class="needs-validation" novalidate>

                                    <div class="col-12 col-md-6 col-lg-12">
                                        <div class="row">

                                            <div class="col-12 col-md-6 col-lg-12">
                                                <label class="col-form-label">宛名</label>
                                                <input type="text" class="form-control was-validated" id="application_name" placeholder="例：株式会社〇〇〇〇" required>
                                                <div class="invalid-feedback" id ="application_name_error">
                                                    宛名は必須です。
                                                </div>
                                            </div>  

                                            <!-- E-mail -->
                                            <div class="col-12 col-md-6 col-lg-12 mt-2">
                                                <label class="col-form-label">E-mail</label>
                                                <input type="email" class="form-control" id="application_mail" placeholder="例：××××@gmail.com" required>
                                                <div class="invalid-feedback" id ="application_mail_error">
                                                    E-mailは必須です。
                                                </div>
                                            </div>

                                            <!-- 件名 -->
                                            <div class="col-12 col-md-6 col-lg-12 mt-2">
                                                <label class="col-form-label">件名</label>
                                                <input type="text" class="form-control" id="subject_text" placeholder="例：【〇〇〇〇マンション】お申込情報の件" required>
                                                <div class="invalid-feedback" id ="subject_text_error">
                                                    件名は必須です。
                                                </div>
                                            </div>

                                            <!-- 本文 -->
                                            <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                <label for="">本文</label>
                                                <textarea class="form-control" name="url_text" id="url_text" rows="10" placeholder="例：自由に入力"></textarea>
                                                <div class="invalid-feedback" id ="url_text_error"></div>
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                <button id="btn_copy" type="button" class="btn btn-outline-primary float-end"><i class="far fa-copy"></i></button>
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
                <!-- URL発行 -->

            </main>
            <!-- page-content" -->

            <!-- 新規url発行 -->
            @component('component.back_url')
            @endcomponent
            <!-- 新規url発行 -->

		</div>
		<!-- page-wrapper -->

		@component('component.back_js')
		@endcomponent

		<!-- 自作js -->
		<script src="{{ asset('back/js/back_app_edit.js') }}"></script>

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