<!DOCTYPE html>
<html lang="ja">

	<head>
        <title>契約詳細/KASEGU</title>

		<!-- head -->
		@component('component.back_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('back/css/back_contract_detail_edit.css') }}">  
		
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
                                                    <a class="nav-link active" id="nav-contract_progress-tab" data-bs-toggle="tab" href="#nav-contract_progress" role="tab" aria-controls="nav-indemnity_fee" aria-selected="false">進捗状況</a>
                                                    <a class="nav-link" id="nav-real_estate-tab" data-bs-toggle="tab" href="#nav-real_estate" role="tab" aria-controls="nav-real_estate" aria-selected="true">物件概要</a>
                                                    <a class="nav-link" id="nav-contract_detail-tab" data-bs-toggle="tab" href="#nav-contract_detail" role="tab" aria-controls="nav-contract_detail" aria-selected="false">契約者・同居人</a>
                                                    <a class="nav-link" id="nav-company-tab" data-bs-toggle="tab" href="#nav-company" role="tab" aria-controls="nav-company" aria-selected="false">商号</a>
                                                    <a class="nav-link" id="nav-law-tab" data-bs-toggle="tab" href="#nav-law" role="tab" aria-controls="nav-law" aria-selected="false">法令関係</a>
                                                    <a class="nav-link" id="nav-registry-tab" data-bs-toggle="tab" href="#nav-registry" role="tab" aria-controls="nav-registry" aria-selected="false">登記事項</a>
                                                    <a class="nav-link" id="nav-fee_detail-tab" data-bs-toggle="tab" href="#nav-fee_detail" role="tab" aria-controls="nav-fee_detail" aria-selected="false">授受される金額</a>
                                                    <a class="nav-link" id="nav-real_estate_facilities-tab" data-bs-toggle="tab" href="#nav-real_estate_facilities" role="tab" aria-controls="nav-real_estate_facilities" aria-selected="false">設備状況</a>
                                                    <a class="nav-link" id="nav-contract_span-tab" data-bs-toggle="tab" href="#nav-contract_span" role="tab" aria-controls="nav-contract_span" aria-selected="false">契約期間</a>
                                                    <a class="nav-link" id="nav-use_limitation-tab" data-bs-toggle="tab" href="#nav-use_limitation" role="tab" aria-controls="nav-use_limitation" aria-selected="false">用途・利用の制限</a>
                                                    <a class="nav-link" id="nav-contract_cancel-tab" data-bs-toggle="tab" href="#nav-contract_cancel" role="tab" aria-controls="nav-contract_cancel" aria-selected="false">契約の解約及び解除</a>
                                                    <a class="nav-link" id="nav-indemnity_fee-tab" data-bs-toggle="tab" href="#nav-indemnity_fee" role="tab" aria-controls="nav-indemnity_fee" aria-selected="false">損害賠償・違約金・免責</a>
                                                    <a class="nav-link" id="nav-bank-tab" data-bs-toggle="tab" href="#nav-bank" role="tab" aria-controls="nav-bank" aria-selected="false">家賃振込先</a>
                                                    <a class="nav-link" id="nav-other-tab" data-bs-toggle="tab" href="#nav-other" role="tab" aria-controls="nav-other" aria-selected="false">その他</a>
                                                    <a class="nav-link" id="nav-special_contract-tab" data-bs-toggle="tab" href="#nav-special_contract" role="tab" aria-controls="nav-special_contract" aria-selected="false">特約事項</a>
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
                                            
                                                <!-- 進捗状況 -->
                                                <div class="tab-pane fade show active" id="nav-contract_progress" role="tabpanel" aria-labelledby="nav-contract_progress-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 管理番号 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>管理番号
                                                            <input type="text" class="form-control" name="admin_number" id="admin_number" value="{{ $contract_list->admin_number }}" placeholder="例：0001" style="text-align:right">
                                                            <!-- バリデーション -->
                                                            <div class="contract_progress-tab invalid-feedback" id ="admin_number_error">
                                                            </div>
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 契約進捗 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>契約進捗
                                                            
                                                            <select class="form-select " name="contract_detail_progress_id" id="contract_detail_progress_id">
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($contract_detail_progress_list as $contract_detail_progress)
                                                                    <option value="{{$contract_detail_progress->contract_detail_progress_id}}" @if($contract_list->contract_detail_progress_id == $contract_detail_progress->contract_detail_progress_id) selected @endif>{{$contract_detail_progress->contract_detail_progress_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="contract_progress-tab invalid-feedback" id ="real_estate_name_error">
                                                            </div>
                                                        </div>
                                                        <!-- 契約進捗 -->

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                    </div>

                                                </div>
                                                <!-- 進捗状況 -->

                                                <!-- 物件概要 -->
                                                <div class="tab-pane fade" id="nav-real_estate" role="tabpanel" aria-labelledby="nav-real_estate-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 物件名 -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>物件名
                                                            <input type="text" class="form-control" name="real_estate_name" id="real_estate_name" value="{{ $contract_list->real_estate_name }}" placeholder="例：〇〇〇〇マンション" required>
                                                            <!-- バリデーション -->
                                                            <div class="real_estate-tab invalid-feedback" id ="real_estate_name_error">
                                                                物件名は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 号室 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>号室
                                                            <input type="text" class="form-control" name="room_name" id="room_name" value="{{ $contract_list->room_name }}" placeholder="例：101" required>
                                                            <!-- バリデーション -->
                                                            <div class="real_estate-tab invalid-feedback" id ="room_name">
                                                                号室は必須です。
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- 契約面積 -->
                                                        <div class="col-12 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>契約面積
                                                            <!-- テキストボックスの右側に文字表示 -->
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="room_size" id="room_size" value="{{ $contract_list->room_size }}" style="text-align:right" placeholder="例：60.00" required>
                                                                <span class="d-flex align-items-end ms-1">㎡</span>                                                      <!-- バリデーション -->
                                                                <div class="real_estate-tab invalid-feedback" id ="room_size_error">
                                                                    契約面積は必須です。
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 郵便番号 -->
                                                        <div class="col-7 col-md-4 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="real_estate_post_number" id="real_estate_post_number" value="{{ $contract_list->real_estate_post_number }}" placeholder="例：1111111" required>
                                                                <button id="real_estate-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="real_estate-tab invalid-feedback" id="real_estate_post_number_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- 住所 -->
                                                        <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>住所
                                                            <input type="text" class="form-control" name="real_estate_address" id="real_estate_address" value="{{ $contract_list->real_estate_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy" required>
                                                            <div class="real_estate-tab invalid-feedback" id ="real_estate_address_error">
                                                                住所は必須です。
                                                            </div>       
                                                        </div>

                                                        <!-- 改行 -->
                                                        <div class="w-100"></div>

                                                        <!-- 構造 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>構造
                                                            <div class="input-group">
                                                                <select class="form-select " name="real_estate_structure_id" id="real_estate_structure_id" required>
                                                                    <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                    <option></option>
                                                                    @foreach($real_estate_structure_list as $real_estate_structure)
                                                                        <option value="{{$real_estate_structure->real_estate_structure_id}}" @if($contract_list->real_estate_structure_id == $real_estate_structure->real_estate_structure_id) selected @endif>{{$real_estate_structure->real_estate_structure_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="d-flex align-items-end ms-1">造</span>
                                                                <div class="real_estate-tab invalid-feedback" id ="real_estate_structure_id_error">
                                                                    構造は必須です。
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <!-- 構造 -->

                                                        <!-- 地上階数 -->
                                                        <div class="col-6 col-md-8 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>地上階数
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="real_estate_floor" id="real_estate_floor" value="{{ $contract_list->real_estate_floor }}" style="text-align:right" placeholder="例：2" required>
                                                                <span class="d-flex align-items-end ms-1">階建</span>
                                                                <div class="real_estate-tab invalid-feedback" id ="real_estate_floor_error">
                                                                    階建は必須です。
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- 地上階数 -->

                                                        <!-- 築年月日 -->
                                                        <div class="col-12 col-md-12 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>築年月日
                                                            <input type="text" class="form-control" name="real_estate_age" id="real_estate_age" value="{{ $contract_list->real_estate_age }}" placeholder="例：1989/08/01" required>
                                                            <div class="trade-tab invalid-feedback" id ="real_estate_age_error">
                                                                築年月日は必須です。
                                                            </div>       
                                                        </div>

                                                        <!-- 間取 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>間取
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="room_layout_name" id="room_layout_name" value="{{ $contract_list->room_layout_name }}" style="text-align:right" placeholder="例：1" required>
                                                                <select class="form-select " name="room_layout_id" id="room_layout_id" required>
                                                                    <option></option>
                                                                    @foreach($room_layout_list as $room_layout)
                                                                        <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                        <option value="{{$room_layout->room_layout_id}}" @if($contract_list->room_layout_id == $room_layout->room_layout_id) selected @endif>{{$room_layout->room_layout_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="real_estate-tab invalid-feedback" id ="room_layout_id_error">
                                                                    間取は必須です。
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 家主名 -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-2">
                                                            <label class="label_required mb-2" for="textBox"></label>家主名
                                                            <input type="text" class="form-control" name="owner_name" id="owner_name" value="{{ $contract_list->owner_name }}" placeholder="例：大阪　太郎" required>
                                                            <!-- バリデーション -->
                                                            <div class="real_estate-tab invalid-feedback" id ="owner_name_error">
                                                                家主名は必須です。
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 郵便番号 -->
                                                        <div class="col-7 col-md-4 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="owner_post_number" id="owner_post_number" value="{{ $contract_list->owner_post_number }}" placeholder="例：1111111" required>
                                                                <button id="owner-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="real_estate-tab invalid-feedback" id="owner_post_number_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- 住所 -->
                                                        <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>住所
                                                            <input type="text" class="form-control" name="owner_address" id="owner_address" value="{{ $contract_list->owner_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy" required>
                                                            <div class="real_estate-tab invalid-feedback" id ="owner_address_error">
                                                                住所は必須です。
                                                            </div>       
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 管理の委託先 -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-2">
                                                            <label class="label_required mb-2" for="textBox"></label>管理委託先(共有)
                                                            <input type="text" class="form-control" name="m_share_name" id="m_share_name" value="{{ $contract_list->m_share_name }}" placeholder="例：株式会社〇〇〇〇" required>
                                                            <!-- バリデーション -->
                                                            <div class="real_estate-tab invalid-feedback" id ="m_share_name_error">
                                                                管理委託先(共有)は必須です。
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 郵便番号 -->
                                                        <div class="col-7 col-md-4 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="m_share_post_number" id="m_share_post_number" value="{{ $contract_list->m_share_post_number }}" placeholder="例：1111111" required>
                                                                <button id="m_share-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="real_estate-tab invalid-feedback" id="m_share_post_number_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- 住所 -->
                                                        <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>住所
                                                            <input type="text" class="form-control" name="m_share_address" id="m_share_address" value="{{ $contract_list->m_share_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy" required>
                                                            <div class="real_estate-tab invalid-feedback" id ="m_share_address_error">
                                                                住所は必須です。
                                                            </div>       
                                                        </div>

                                                        <!-- tel -->
                                                        <div class="col-12 col-md-12 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>Tel
                                                            <input type="text" class="form-control" name="m_share_tel" id="m_share_tel" value="{{ $contract_list->m_share_tel }}" placeholder="例：06-1234-5678" required>
                                                            <div class="real_estate-tab invalid-feedback" id ="m_share_tel_error">
                                                                Telは必須です。
                                                            </div>       
                                                        </div>
                                                        
                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 管理の委託先(専有) -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-2">
                                                            <label class="label_required mb-2" for="textBox"></label>管理委託先(専有)
                                                            <input type="text" class="form-control" name="m_own_name" id="m_own_name" value="{{ $contract_list->m_own_name }}" placeholder="例：株式会社〇〇〇〇" required>
                                                            <!-- バリデーション -->
                                                            <div class="real_estate-tab invalid-feedback" id ="m_own_name_error">
                                                                管理の委託先(専有)は必須です。
                                                            </div>
                                                        </div>

                                                        <div class="col-5 col-md-12 col-lg-4 d-flex align-items-end">
                                                            <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="cb_m_share">
                                                            <label class="form-check-label" for="flexCheckIndeterminate">
                                                                共有と同じ
                                                            </label>
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 郵便番号 -->
                                                        <div class="col-7 col-md-4 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="m_own_post_number" id="m_own_post_number" value="{{ $contract_list->m_own_post_number }}" placeholder="例：1111111" required>
                                                                <button id="m_own-btn-zip" class="btn btn-outline-primary btn_zip"><i class="fas fa-search"></i></button>
                                                                <div class="real_estate-tab invalid-feedback" id="m_own_post_number_error">
                                                                    郵便番号は必須です。
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- 住所 -->
                                                        <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>住所
                                                            <input type="text" class="form-control" name="m_own_address" id="m_own_address" value="{{ $contract_list->m_own_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy" required>
                                                            <div class="real_estate-tab invalid-feedback" id ="m_own_address_error">
                                                                住所は必須です。
                                                            </div>       
                                                        </div>

                                                        <!-- tel -->
                                                        <div class="col-12 col-md-12 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>Tel
                                                            <input type="text" class="form-control" name="m_own_tel" id="m_own_tel" value="{{ $contract_list->m_own_tel }}" placeholder="例：06-1234-5678" required>
                                                            <div class="real_estate-tab invalid-feedback" id ="m_own_tel_error">
                                                                Telは必須です。
                                                            </div>       
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- 物件概要 -->

                                                <!-- 契約者・同居人 -->
                                                <div class="tab-pane fade" id="nav-contract_detail" role="tabpanel" aria-labelledby="nav-contract_detail-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 契約者 -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>契約者名
                                                            <input type="text" class="form-control" name="contract_name" id="contract_name" value="{{ $contract_list->contract_name }}" placeholder="例：大阪　太郎" required>
                                                            <!-- バリデーション -->
                                                            <div class="contract_detail-tab invalid-feedback" id ="contract_name_error">
                                                                契約者名は必須です。
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 契約者フリガナ -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>フリガナ
                                                            <input type="text" class="form-control" name="contract_ruby" id="contract_ruby" value="{{ $contract_list->contract_ruby }}" placeholder="例：オオサカ　タロウ" required>
                                                            <!-- バリデーション -->
                                                            <div class="contract_detail-tab invalid-feedback" id ="contract_ruby_error">
                                                                フリガナは必須です。
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 生年月日 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                        <label class="label_required mb-2" for="textBox"></label>生年月日
                                                            <input type="text" class="form-control" name="contract_date" id="contract_date" value="{{ $contract_list->contract_date }}" placeholder="例：1989/08/01" required>
                                                            <div class="contract_detail-tab invalid-feedback" id ="contract_date_error">
                                                                生年月日は必須です。
                                                            </div>       
                                                        </div>
                                                        
                                                        <!-- 契約者Tel -->
                                                        <div class="col-12 col-md-12 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>Tel
                                                            <input type="text" class="form-control" name="contract_tel" id="contract_tel" value="{{ $contract_list->contract_tel }}" placeholder="例：06-1234-5678" required>
                                                            <!-- バリデーション -->
                                                            <div class="contract_detail-tab invalid-feedback" id ="contract_tel_error">
                                                                Telは必須です。
                                                            </div>
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 一覧タイトル -->
                                                        <div class="col-12 col-md-12 col-lg-12 mt-3 mb-2">
                                                            <div class="row">

                                                                <!-- 説明 -->
                                                                <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                                    <i class="fas fa-exclamation-circle icon_blue me-1"></i><span class="text_red">初回登録時、同居人を追加することが出来ません。登録後、【編集】から同居人を追加してください。</span>
                                                                </div>
                                                                
                                                                <!-- 同居人追加(新規=表示無/編集=表示有) -->
                                                                @if( $contract_list->contract_detail_id !== '' )
                                                                    <div class="col-12 col-md-12 col-lg-6 mt-4">
                                                                        <span id="housemate_add" class="float-end text-primary" style="cursor: hand; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#housemateModal">同居人追加</span> 
                                                                    </div>
                                                                @endif
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
                                                                                        <th scope="col" id="th_contract_housemate_id" style="display:none">id</th>
                                                                                        <th scope="col" id="th_contract_housemate_name">同居人名</th>
                                                                                        <th scope="col" id="th_contract_housemate_birthday">生年月日</th>
                                                                                        <th scope="col" id="th_contract_housemate_edit"></th>
                                                                                        <th scope="col" id="th_contract_housemate_delete"></th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <!-- テーブルボディ -->
                                                                                <tbody>
                                                                                    <!-- 契約詳細id(複製の場合、clone_flag = 空白) -->
                                                                                    @if( $clone_flag !== 'true' )
                                                                                        @foreach($contract_housemate_list as $contract_housemate)
                                                                                            <tr>
                                                                                                <td id="housemate_id_{{ $contract_housemate->contract_housemate_id }}" class="click_class" style="display:none"></td>
                                                                                                <td id="housemate_name_{{ $contract_housemate->contract_housemate_id }}" class="click_class">{{ $contract_housemate->contract_housemate_name }}</td>
                                                                                                <td id="housemate_birthday_{{ $contract_housemate->contract_housemate_id }}" class="click_class">{{ $contract_housemate->contract_housemate_birthday }}</td>
                                                                                                <td id="housemate_edit_{{ $contract_housemate->contract_housemate_id }}" class="click_class hm_btn_edit"><span id="" class="text_blue" style="cursor: hand; cursor:pointer;">編集</span></td>
                                                                                                <td id="housemate_delete_{{ $contract_housemate->contract_housemate_id }}" class="click_class hm_btn_delete"><span id="" class="text_red" style="cursor: hand; cursor:pointer;">削除</span></td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @endif
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
                                                <!-- 契約者・同居人 -->

                                                <!-- 商号 -->
                                                <div class="tab-pane fade" id="nav-company" role="tabpanel" aria-labelledby="nav-company-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 商号 -->
                                                        <div class="col-6 col-md-8 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>商号
                                                            <select class="form-select " name="company_license_id" id="company_license_id" required>
                                                                <option></option>
                                                                @foreach($company_license_list as $company_license)
                                                                    <option value="{{$company_license->company_license_id}}" @if($contract_list->company_license_id == $company_license->company_license_id) selected @endif>{{$company_license->company_license_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="company-tab invalid-feedback" id ="company_license_id_error">
                                                                商号は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 商号 -->

                                                        <div class="w-100"></div> 

                                                        <!-- 代表者 -->
                                                        <div class="col-12 col-md-10 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>代表者
                                                            <input type="text" class="form-control" name="company_license_representative" id="company_license_representative" value="{{ $contract_list->company_license_representative }}" disabled="disabled">
                                                            <!-- エラーメッセージ -->
                                                            <div class="company-tab invalid-feedback" id ="company_license_representative_error">
                                                            </div>
                                                        </div>
                                                        <!-- 代表者 -->

                                                        <div class="w-100"></div>

                                                        <!-- 所在地 -->
                                                        <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>所在地
                                                            <input type="text" class="form-control" name="company_license_address" id="company_license_address" value="{{ $contract_list->company_license_address }}" disabled="disabled">
                                                            <div class="company-tab invalid-feedback" id ="company_lisence_address_error">
                                                                所在地は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 所在地 -->

                                                        <div class="w-100"></div>

                                                        <!-- Tel -->
                                                        <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>Tel
                                                            <input type="text" class="form-control" name="company_license_tel" id="company_license_tel" value="{{ $contract_list->company_license_tel }}" disabled="disabled">
                                                            <div class="company-tab invalid-feedback" id ="company_license_tel_error">
                                                                Telは必須です。
                                                            </div>
                                                        </div>

                                                        <!-- Fax -->
                                                        <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>Fax
                                                            <input type="text" class="form-control" name="company_license_fax" id="company_license_fax" value="{{ $contract_list->company_license_fax }}" disabled="disabled">
                                                            <div class="company-tab invalid-feedback" id ="company_license_fax_error">
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 免許番号 -->
                                                        <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>免許番号
                                                            <input type="text" class="form-control" name="company_license_number" id="company_license_number" value="{{ $contract_list->company_license_number }}" disabled="disabled">
                                                            <div class="company-tab invalid-feedback" id ="company_license_number_error">
                                                            </div>
                                                        </div>

                                                        <!-- 免許年月日 -->
                                                        <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>免許年月日
                                                            <input type="text" class="form-control" name="company_license_span" id="company_license_span" value="{{ $contract_list->company_license_span }}" disabled="disabled">
                                                            <div class="company-tab invalid-feedback" id ="company_license_span_error">
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-6 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 取扱店 -->
                                                        <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>取扱店
                                                            <input type="text" class="form-control" name="company_nick_name" id="company_nick_name" value="{{ $contract_list->company_nick_name }}" disabled="disabled">
                                                            <div class="company-tab invalid-feedback" id ="fax_error">
                                                            </div>
                                                        </div>
                                                        <!-- 取扱店 -->

                                                        <div class="w-100"></div>

                                                        <!-- 所在地 -->
                                                        <div class="col-12 col-md-12 col-lg-7 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>所在地
                                                            <input type="text" class="form-control" name="company_nick_address" id="company_nick_address" value="{{ $contract_list->company_nick_address }}" disabled="disabled">
                                                            <div class="company-tab invalid-feedback" id ="company_nick_address_error">
                                                            </div>
                                                        </div>
                                                        <!-- 所在地 -->

                                                        <div class="col-12 col-md-6 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 法務局 -->
                                                        <div class="col-12 col-md-10 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>法務局
                                                            <input type="text" class="form-control" name="guaranty_association_name" id="guaranty_association_name" value="{{ $contract_list->guaranty_association_name }}" disabled="disabled">
                                                            <!-- エラーメッセージ -->
                                                            <div class="company-tab invalid-feedback" id ="guaranty_association_name_error">
                                                            </div>
                                                        </div>
                                                        <!-- 法務局 -->

                                                        <!-- 保証協会 -->
                                                        <div class="col-12 col-md-10 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>保証協会
                                                            <input type="text" class="form-control" name="legal_place_name" id="legal_place_name" value="{{ $contract_list->legal_place_name }}" disabled="disabled">
                                                            <!-- エラーメッセージ -->
                                                            <div class="company-tab invalid-feedback" id ="legal_place_name_error">
                                                            </div>
                                                        </div>
                                                        <!-- 保証協会 -->

                                                        <!-- 保証協会所属地方 -->
                                                        <div class="col-12 col-md-10 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>保証協会所属地方
                                                            <input type="text" class="form-control" name="legal_place_name" id="legal_place_name" value="{{ $contract_list->legal_place_name }}" disabled="disabled">
                                                            <!-- エラーメッセージ -->
                                                            <div class="company-tab invalid-feedback" id ="legal_place_name_error">
                                                            </div>
                                                        </div>
                                                        <!-- 保証協会所属地方 -->

                                                        <div class="col-12 col-md-6 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 宅地建物取引士 -->
                                                        <div class="col-6 col-md-8 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>宅地建物取引士
                                                            
                                                            <select class="form-select" name="user_license_id" id="user_license_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($user_license_list as $user_license)
                                                                    <option value="{{$user_license->user_license_id}}" @if($contract_list->user_license_id == $user_license->user_license_id) selected @endif>{{$user_license->user_license_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="company-tab invalid-feedback" id ="user_license_id_error">
                                                                宅地建物取引士は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 宅地建物取引士 -->

                                                        <!-- 登録番号 -->
                                                        <div class="col-12 col-md-10 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>登録番号
                                                            <input type="text" class="form-control" name="user_license_number" id="user_license_number" value="{{ $contract_list->user_license_number }}" disabled="disabled">
                                                            <!-- エラーメッセージ -->
                                                            <div class="company-tab invalid-feedback" id ="user_license_number_error">
                                                                登録番号は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 登録番号 -->

                                                        <div class="w-100"></div>

                                                        <!-- 担当者 -->
                                                        <div class="col-12 col-md-10 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>担当者
                                                            <input type="text" class="form-control" name="manager_name" id="manager_name" value="{{ $contract_list->manager_name }}" placeholder="例：大阪　太郎" required>
                                                            <!-- エラーメッセージ -->
                                                            <div class="company-tab invalid-feedback" id ="manager_name_error">
                                                                担当者は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 担当者 -->

                                                    </div>

                                                </div>
                                                <!-- 商号 -->

                                                <!-- 法令 -->
                                                <div class="tab-pane fade" id="nav-law" role="tabpanel" aria-labelledby="nav-law-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 石綿使用調査記録 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>石綿使用調査記録
                                                            
                                                            <select class="form-select " name="report_asbestos" id="report_asbestos" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->report_asbestos == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="law-tab invalid-feedback" id ="report_asbestos_error">
                                                                石綿使用調査記録は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 石綿使用調査記録 -->

                                                        <!-- 耐震診断 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>耐震診断
                                                            
                                                            <select class="form-select " name="report_earthquake" id="report_earthquake" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->report_earthquake == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="law-tab invalid-feedback" id ="report_asbestos_error">
                                                                耐震診断は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 耐震診断 -->

                                                        <div class="w-100"></div>

                                                        <!-- 造成宅地防災区域 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>造成宅地防災区域
                                                            
                                                            <select class="form-select " name="land_disaster_prevention_area" id="land_disaster_prevention_area" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($inside_and_outside_area_list as $inside_and_outside_area)
                                                                    <option value="{{$inside_and_outside_area->inside_and_outside_area_id}}" @if($contract_list->land_disaster_prevention_area == $inside_and_outside_area->inside_and_outside_area_id) selected @endif>{{$inside_and_outside_area->inside_and_outside_area_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="law-tab invalid-feedback" id ="land_disaster_prevention_area_error">
                                                                造成宅地防災区域は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 造成宅地防災区域 -->

                                                        <!-- 津波災害警戒区域 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>津波災害警戒区域
                                                            
                                                            <select class="form-select " name="tsunami_disaster_alert_area" id="tsunami_disaster_alert_area" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($inside_and_outside_area_list as $inside_and_outside_area)
                                                                    <option value="{{$inside_and_outside_area->inside_and_outside_area_id}}" @if($contract_list->tsunami_disaster_alert_area == $inside_and_outside_area->inside_and_outside_area_id) selected @endif>{{$inside_and_outside_area->inside_and_outside_area_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="law-tab invalid-feedback" id ="tsunami_disaster_alert_area_error">
                                                                津波災害警戒区域は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 津波災害警戒区域 -->

                                                        <!-- 土砂災害区域 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>土砂災害区域
                                                            
                                                            <select class="form-select " name="sediment_disaster_area" id="sediment_disaster_area" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($inside_and_outside_area_list as $inside_and_outside_area)
                                                                    <option value="{{$inside_and_outside_area->inside_and_outside_area_id}}" @if($contract_list->sediment_disaster_area == $inside_and_outside_area->inside_and_outside_area_id) selected @endif>{{$inside_and_outside_area->inside_and_outside_area_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="law-tab invalid-feedback" id ="sediment_disaster_area_error">
                                                                土砂災害区域は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 土砂災害区域 -->

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- ハザードマップ有無 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>ハザードマップの有無
                                                            <select class="form-select " name="hazard_map" id="hazard_map" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->hazard_map == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <!-- バリデーション -->
                                                            <div class="law-tab invalid-feedback" id ="hazard_map_error">
                                                                ハザードマップ有無は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 洪水 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>洪水
                                                            <select class="form-select " name="warning_flood" id="warning_flood" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->warning_flood == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <!-- バリデーション -->
                                                            <div class="law-tab invalid-feedback" id ="warning_flood_error">
                                                                洪水は必須です。
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 高潮 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>高潮
                                                            <select class="form-select " name="warning_storm_surge" id="warning_storm_surge" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->warning_storm_surge == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <!-- バリデーション -->
                                                            <div class="law-tab invalid-feedback" id ="warning_storm_surge_error">
                                                                高潮は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 雨水・出水 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>雨水・出水
                                                            <select class="form-select " name="warning_rain_water" id="warning_rain_water" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->warning_rain_water == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <!-- バリデーション -->
                                                            <div class="user-tab invalid-feedback" id ="warning_rain_water_error">
                                                                雨水・出水は必須です。
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- 法令 -->

                                                <!-- 登記事項 -->
                                                <div class="tab-pane fade" id="nav-registry" role="tabpanel" aria-labelledby="nav-registry-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 所有権 -->
                                                        <div class="col-12 col-md-6 col-lg-5 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>所有権
                                                            <input type="text" class="form-control" name="regi_name" id="regi_name" value="{{ $contract_list->regi_name }}" placeholder="例：株式会社〇〇〇〇" required>
                                                            <div class="registry-tab invalid-feedback" id ="regi_name_error">
                                                                所有権は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 所有権 -->

                                                        <div class="w-100"></div>

                                                        <!-- 所有権に係る権利に関する事項 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>所有権に係る事項
                                                            
                                                            <select class="form-select " name="regi_right" id="regi_right" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->regi_right == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="registry-tab invalid-feedback" id ="regi_right_error">
                                                                所有権は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 所有権に係る権利に関する事項 -->
                                                    
                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 抵当権・根抵当権の設定 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>抵当権・根抵当権
                                                            
                                                            <select class="form-select " name="regi_mortgage_id" id="regi_mortgage_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($regi_mortgages_list as $regi_mortgages)
                                                                    <option value="{{$regi_mortgages->regi_mortgage_id}}" @if($contract_list->regi_mortgage == $regi_mortgages->regi_mortgage_id) selected @endif>{{$regi_mortgages->regi_mortgage_name}}</option>
                                                                @endforeach
                                                            </select>

                                                            <div class="registry-tab invalid-feedback" id ="regi_mortgage_id_error">
                                                                抵当権・根抵当権は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 抵当権・根抵当権の設定 -->
                                                        
                                                        <div class="w-100"></div>

                                                        <!-- 所有者と貸主が違う場合 -->
                                                        <div class="col-6 col-md-8 col-lg-8 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>所有者と貸主が違う場合
                                                            <textarea class="form-control" name="regi_difference_owner" id="regi_difference_owner" rows="2" placeholder="例：サブリースのため、登記簿謄本とは所有者が違う。">{{ $contract_list->regi_difference_owner }}</textarea>
                                                            <div class="registry-tab invalid-feedback" id ="regi_difference_owner_error">
                                                            </div> 
                                                        </div>
                                                        <!-- 所有者と貸主が違う場合 -->

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 未完成物件の時 -->
                                                        <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>完成予定日※未完成物件の時入力して下さい。
                                                            <input type="text" class="form-control" name="completion_date" id="completion_date" value="{{ $contract_list->completion_date }}" placeholder="例：1989/08/01">
                                                            <div class="registry-tab invalid-feedback" id ="completion_date_error"> 
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <!-- 登記事項 -->
                                                
                                                <!-- 授受される金額 -->
                                                <div class="tab-pane fade" id="nav-fee_detail" role="tabpanel" aria-labelledby="nav-fee_detail-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 敷金 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>敷金・保証金
                                                            <input type="number" class="form-control" name="security_fee" id="security_fee" value="{{ $contract_list->security_fee }}" placeholder="例：100000" style="text-align:right" required>
                                                            <div class="fee_detail-tab invalid-feedback" id ="security_fee_error">
                                                                敷金・保証金は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 敷金 -->

                                                        <!-- 礼金 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>礼金・解約引
                                                            <input type="number" class="form-control" name="key_fee" id="key_fee" value="{{ $contract_list->rent_fee }}" placeholder="例：100000" style="text-align:right" required>
                                                            <div class="fee_detail-tab invalid-feedback" id ="key_fee_error">
                                                                礼金・解約引は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 礼金 -->

                                                        <div class="w-100"></div>
            
                                                        <!-- 賃料 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>賃料
                                                            <input type="number" class="form-control fee_text" name="rent_fee" id="rent_fee" value="{{ $contract_list->rent_fee }}" placeholder="例：100000" style="text-align:right" required>
                                                            <div class="fee_detail-tab invalid-feedback" id ="rent_fee_error">
                                                                賃料は必須です。
                                                            </div> 
                                                        </div>

                                                        <!-- 共益費 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>共益費
                                                            <input type="number" class="form-control fee_text" name="service_fee" id="service_fee" value="{{ $contract_list->service_fee }}" placeholder="例：10000" style="text-align:right" required>
                                                            <div class="fee_detail-tab invalid-feedback" id ="service_fee_error">
                                                                共益費は必須です。
                                                            </div> 
                                                        </div>

                                                        <!-- 水道代 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>水道代
                                                            <input type="number" class="form-control fee_text" name="water_fee" id="water_fee" value="{{ $contract_list->water_fee }}" placeholder="例：2000" style="text-align:right" required>
                                                            <div class="fee_detail-tab invalid-feedback" id ="water_fee_error">
                                                                水道代は必須です。
                                                            </div> 
                                                        </div>

                                                        <!-- その他 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>その他
                                                            <input type="number" class="form-control fee_text" name="ohter_fee" id="ohter_fee" value="{{ $contract_list->ohter_fee }}" placeholder="例：2000" style="text-align:right" required>
                                                            <div class="fee_detail-tab invalid-feedback" id ="ohter_fee_error">
                                                                その他は必須です。
                                                            </div> 
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 駐輪代 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>駐輪代
                                                            <input type="number" class="form-control fee_text" name="bicycle_fee" id="bicycle_fee" value="{{ $contract_list->bicycle_fee }}" placeholder="例：200" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="bicycle_fee_error">
                                                            </div>
                                                        </div>

                                                        <!-- 総賃料 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-4">
                                                            <label for="">月額賃料</label>
                                                            <input type="number" disabled="disabled" class="form-control" name="total_fee" id="total_fee" value="{{ $contract_list->total_fee }}" placeholder="例：114200" style="text-align:right">
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 駐車場保証金 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>駐車場保証金
                                                            <input type="number" class="form-control fee_text" name="car_deposit_fee" id="car_deposit_fee" value="{{ Common::zeroToSpace($contract_list->car_deposit_fee) }}" placeholder="例：60000" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="car_deposit_fee_error">
                                                            </div> 
                                                        </div>

                                                        <!-- 駐車場代 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>駐車場代
                                                            <input type="number" class="form-control fee_text" name="car_fee" id="car_fee" value="{{ Common::zeroToSpace($contract_list->car_fee) }}" placeholder="例：20000" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="car_fee_error">
                                                            </div> 
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 住宅保険料 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>住宅保険料
                                                            <input type="number" class="form-control fee_text" name="fire_insurance_fee" id="fire_insurance_fee" value="{{ $contract_list->fire_insurance_fee }}" placeholder="例：15000" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="fire_insurance_fee_error">
                                                            </div> 
                                                        </div>

                                                        <!-- 住宅保険期間 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>住宅保険期間
                                                            <!-- テキストボックスの右側に文字表示 -->
                                                            <input type="text" class="form-control" name="fire_insurance_span" id="fire_insurance_span" value="{{ $contract_list->fire_insurance_span }}" placeholder="例：2" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="fire_insurance_span_error">
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 保証会社費用 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>保証会社費用
                                                            <input type="number" class="form-control fee_text" name="guarantee_fee" id="guarantee_fee" value="{{ $contract_list->guarantee_fee }}" placeholder="例：60000" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="guarantee_fee_error">
                                                            </div> 
                                                        </div>

                                                        <!-- 保証会社更新期間 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>保証会社更新期間
                                                            <select class="form-select " name="guarantee_update_span" id="guarantee_update_span">
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($guarantee_update_spans_list as $guarantee_update_span)
                                                                    <option value="{{$guarantee_update_span->guarantee_update_span_id}}" @if($contract_list->guarantee_update_span == $guarantee_update_span->guarantee_update_span_id) selected @endif>{{$guarantee_update_span->guarantee_update_span_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="fee_detail-tab invalid-feedback" id ="introduction_key_fee_error">
                                                            </div>
                                                        </div>
                                                        <!-- 保証会社更新期間 -->

                                                        <!-- 保証会社更新料 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>保証会社更新料
                                                            <input type="number" class="form-control fee_text" name="guarantee_update_fee" id="guarantee_update_fee" value="{{ $contract_list->guarantee_update_fee }}" placeholder="例：13000" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="guarantee_update_fee_error">
                                                            </div> 
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 安心サポート -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>安心サポート
                                                            <input type="number" class="form-control fee_text" name="support_fee" id="support_fee" value="{{ $contract_list->support_fee }}" style="text-align:right" placeholder="例：15000">
                                                            <div class="fee_detail-tab invalid-feedback" id ="support_fee_error">
                                                            </div> 
                                                        </div>

                                                        <!-- 防虫・抗菌 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>防虫・抗菌
                                                            <input type="number" class="form-control fee_text" name="disinfect_fee" id="disinfect_fee" value="{{ $contract_list->disinfect_fee }}" style="text-align:right" placeholder="例：18000">
                                                            <div class="fee_detail-tab invalid-feedback" id ="disinfect_fee_error">
                                                            </div> 
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- その他項目 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>その他項目①
                                                            <input type="text" class="form-control fee_text" name="other_name1" id="other_name1" value="{{ $contract_list->other_name1 }}" placeholder="例：ハウスクリーニング代">
                                                            <div class="fee_detail-tab invalid-feedback" id ="other_name1_error">
                                                            </div> 
                                                        </div>

                                                        <!-- その他費用 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>その他費用①
                                                            <input type="number" class="form-control fee_text" name="other_fee1" id="other_fee1" value="{{ $contract_list->other_fee1 }}" placeholder="例：33000" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="other_fee1_error">
                                                            </div> 
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- その他項目② -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>その他項目②
                                                            <input type="text" class="form-control fee_text" name="other_name2" id="other_name2" value="{{ $contract_list->other_name2 }}" placeholder="例：鍵交換代">
                                                            <div class="fee_detail-tab invalid-feedback" id ="other_name2_error">
                                                            </div> 
                                                        </div>

                                                        <!-- その他費用② -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>その他費用②
                                                            <input type="number" class="form-control fee_text" name="other_fee2" id="other_fee2" value="{{ $contract_list->other_fee2 }}" placeholder="例：22000" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="other_fee2_error">
                                                            </div> 
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 仲介手数料 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>仲介手数料
                                                            <input type="number" class="form-control fee_text" name="broker_fee" id="broker_fee" value="{{ $contract_list->broker_fee }}" placeholder="例：62810" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="broker_fee_error">
                                                            </div> 
                                                        </div>
                                                        
                                                        <!-- 仲介手数料(駐車場) -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>仲介手数料(駐車場)
                                                            <input type="number" class="form-control fee_text" name="car_broker_fee" id="car_broker_fee" value="{{ $contract_list->car_broker_fee }}" placeholder="例：22000" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="car_broker_fee_error">
                                                            </div> 
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 預り金の日付 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>預り金の日付
                                                            <input type="text" class="form-control" name="today_account_fee_date" id="today_account_fee_date" value="{{ $contract_list->today_account_fee_date }}" placeholder="例：2022/1/1">
                                                            <div class="fee_detail-tab invalid-feedback" id ="today_account_fee_date_error">
                                                            </div>       
                                                        </div>

                                                        <!-- 預り金 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>預り金
                                                            <input type="number" class="form-control" name="today_account_fee" id="today_account_fee" value="{{ $contract_list->today_account_fee }}" placeholder="例：50000" style="text-align:right">
                                                            <div class="fee_detail-tab invalid-feedback" id ="today_account_fee_error">
                                                            </div>       
                                                        </div>

                                                        <!-- 決算予定日 -->
                                                        <div class="col-12 col-md-12 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>決済予定日
                                                            <input type="text" class="form-control" name="payment_date" id="payment_date" value="{{ $contract_list->payment_date }}" placeholder="例：2022/1/1">
                                                            <div class="fee_detail-tab invalid-feedback" id ="payment_date_error">
                                                            </div>       
                                                        </div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 敷金の清算 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>敷金の清算に関する事項
                                                            <select class="form-select " name="introduction_security_fee" id="introduction_security_fee">
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->security_settle_detail == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="fee_detail-tab invalid-feedback" id ="introduction_security_fee_error"></div> 
                                                        </div>
                                                        <!-- 敷金の清算 -->

                                                        <!-- 礼金の清算 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>礼金の清算に関する事項
                                                            <select class="form-select " name="introduction_key_fee" id="introduction_key_fee">
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->key_money_settle_detail == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="fee_detail-tab invalid-feedback" id ="introduction_key_fee_error">
                                                            </div>
                                                        </div>
                                                        <!-- 礼金の清算 -->

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 預り金の保全措置 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>預り金の保全措置
                                                            
                                                            <select class="form-select " name="keep_account_fee" id="keep_account_fee">
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->keep_account_fee == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                                
                                                            </select>
                                                            <div class="fee_detail-tab invalid-feedback" id ="keep_account_fee_error">
                                                            </div> 
                                                        </div>
                                                        <!-- 預り金の保全措置 -->

                                                        <!-- 金銭賃借の斡旋 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>金銭賃借の斡旋
                                                            
                                                            <select class="form-select " name="" id="introduction_fee">
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->introduction_fee == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="fee_detail-tab invalid-feedback" id ="introduction_fee_error">
                                                            </div> 
                                                        </div>
                                                        <!-- 金銭賃借の斡旋 -->

                                                    </div>

                                                </div>
                                                <!-- 授受される金額 -->
                                                
                                                <!-- 整備・設備状況 -->
                                                <div class="tab-pane fade" id="nav-real_estate_facilities" role="tabpanel" aria-labelledby="nav-real_estate_facilities-tab">
                                                    
                                                    <div class="row row-cols-2">
                                                        
                                                        <!-- 飲用水 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>飲用水
                                                            
                                                            <select class="form-select" name="water" id="water" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($water_list as $water)
                                                                    <option value="{{$water->water_id}}" @if($contract_list->water == $water->water_id) selected @endif>{{$water->water_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="water_error">
                                                                飲用水は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 飲料水 -->

                                                        <!-- 備考 -->
                                                        <div class="col-12 col-md-6 col-lg-7 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>備考
                                                            <input type="text" class="form-control" name="water_type_name" id="water_type_name" value="{{ $contract_list->water_type_name }}" placeholder="例：毎月15日に管理会社が検針">
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="water_type_name_error">
                                                            </div>
                                                        </div>
                                                        <!-- 備考 -->

                                                        <div class="w-100"></div>

                                                        <!-- ガスの種類 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>ガスの種類
                                                            
                                                            <select class="form-select " name="gas" id="gas" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($gas_list as $gas)
                                                                    <option value="{{$gas->gas_id}}" @if($contract_list->gas == $gas->gas_id) selected @endif>{{$gas->gas_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="gas_error">
                                                                ガスの種類は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- ガスの種類 -->

                                                        <!-- 備考 -->
                                                        <div class="col-12 col-md-6 col-lg-7 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>備考
                                                            <input type="text" class="form-control" name="gas_type_name" id="gas_type_name" value="{{ $contract_list->gas_type_name }}" placeholder="例：プロパンガス会社名　06-1234-5678">
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="gas_type_name_error">
                                                            </div>
                                                        </div>
                                                        <!-- 備考 -->

                                                        <div class="w-100"></div>

                                                        <!-- 電力会社名 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>電力会社名
                                                            <input type="text" class="form-control" name="electricity" id="electricity" value="{{ $contract_list->electricity }}" placeholder="例：関西電力" required>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="electricity_error">
                                                                電力会社名は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 電力会社名 -->

                                                        <!-- 備考 -->
                                                        <div class="col-12 col-md-6 col-lg-7 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>備考
                                                            <input type="text" class="form-control" name="electricity_type_name" id="electricity_type_name" value="{{ $contract_list->electricity_type_name }}" placeholder="例：06-1234-5678">
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="electricity_type_name_error">
                                                            </div>
                                                        </div>
                                                        <!-- 備考 -->

                                                        <div class="w-100"></div>

                                                        <!-- 排水 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>排水
                                                            
                                                            <select class="form-select " name="waste_water" id="waste_water" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($waste_water_list as $waste_water)
                                                                    <option value="{{$waste_water->waste_water_id}}" @if($contract_list->waste_water == $waste_water->waste_water_id) selected @endif>{{$waste_water->waste_water_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="waste_water_error">
                                                                排水は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 排水 -->

                                                        <!-- 備考 -->
                                                        <div class="col-12 col-md-6 col-lg-7 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>備考
                                                            <input type="text" class="form-control" name="waste_water_name" id="waste_water_name" value="{{ $contract_list->waste_water_name }}" placeholder="例：汲取費用　年間/5,000円">
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="waste_water_name_error">
                                                            </div>
                                                        </div>
                                                        <!-- 備考 -->

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 台所 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>台所
                                                            
                                                            <select class="form-select " name="kitchen" id="kitchen" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->kitchen == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="kitchen_error">
                                                                台所は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 台所 -->

                                                        <!-- 種別 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>種別
                                                            
                                                            <select class="form-select " name="kitchen_exclusive_type_id" id="kitchen_exclusive_type_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($exclusive_type_list as $exclusive_type)
                                                                    <option value="{{$exclusive_type->exclusive_type_id}}" @if($contract_list->cooking_exclusive_type_id == $exclusive_type->exclusive_type_id) selected @endif>{{$exclusive_type->exclusive_type_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="kitchen_exclusive_type_id_error">
                                                                種別は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 種別 -->

                                                        <div class="w-100"></div>

                                                        <!-- コンロ -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>コンロ
                                                            
                                                            <select class="form-select " name="cooking_stove" id="cooking_stove" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->cooking_stove == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="cooking_stove_error">
                                                                コンロは必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- コンロ -->

                                                        <!-- 種別 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>種別
                                                            
                                                            <select class="form-select " name="cooking_stove_exclusive_type_id" id="cooking_stove_exclusive_type_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($exclusive_type_list as $exclusive_type)
                                                                    <option value="{{$exclusive_type->exclusive_type_id}}" @if($contract_list->cooking_exclusive_type_id == $exclusive_type->exclusive_type_id) selected @endif>{{$exclusive_type->exclusive_type_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="icooking_exclusive_type_id_error">
                                                                種別は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 種別 -->

                                                        <div class="w-100"></div>

                                                        <!-- 浴室 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>浴室
                                                            
                                                            <select class="form-select " name="bath" id="bath" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->bath == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="bath_error">
                                                                浴室は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 浴室 -->

                                                        <!-- 種別 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>種別
                                                            
                                                            <select class="form-select " name="bath_exclusive_type_id" id="bath_exclusive_type_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($exclusive_type_list as $exclusive_type)
                                                                    <option value="{{$exclusive_type->exclusive_type_id}}" @if($contract_list->bath_exclusive_type_id == $exclusive_type->exclusive_type_id) selected @endif>{{$exclusive_type->exclusive_type_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="bath_exclusive_type_id_error">
                                                                種別は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 種別 -->

                                                        <div class="w-100"></div>

                                                        <!-- トイレ -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>トイレ
                                                            
                                                            <select class="form-select " name="toilet" id="toilet" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->toilet == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="toilet_error">
                                                                トイレは必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- トイレ -->

                                                        <!-- 種別 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>種別
                                                            
                                                            <select class="form-select " name="toilet_exclusive_type_id" id="toilet_exclusive_type_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($exclusive_type_list as $exclusive_type)
                                                                    <option value="{{$exclusive_type->exclusive_type_id}}" @if($contract_list->toilet_exclusive_type_id == $exclusive_type->exclusive_type_id) selected @endif>{{$exclusive_type->exclusive_type_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="toilet_exclusive_type_id_error">
                                                                種別は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 種別 -->

                                                        <div class="w-100"></div>

                                                        <!-- 給湯 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>給湯
                                                            
                                                            <select class="form-select " name="water_heater" id="water_heater" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->water_heater == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="water_heater_error">
                                                                給湯は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 給湯 -->

                                                        <!-- 種別 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>種別
                                                            
                                                            <select class="form-select " name="water_heater_exclusive_type_id" id="water_heater_exclusive_type_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($exclusive_type_list as $exclusive_type)
                                                                    <option value="{{$exclusive_type->exclusive_type_id}}" @if($contract_list->water_heater_exclusive_type_id == $exclusive_type->exclusive_type_id) selected @endif>{{$exclusive_type->exclusive_type_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="water_heater_exclusive_type_id_error">
                                                                種別は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 種別 -->

                                                        <div class="w-100"></div>

                                                        <!-- 冷暖房設備 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>冷暖房設備
                                                            
                                                            <select class="form-select " name="air_conditioner" id="air_conditioner" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->air_conditioner == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="air_conditioner_exclusive_type_name_error">
                                                                冷暖房設備は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 冷暖房設備 -->

                                                        <!-- 台数 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>台数
                                                            <input type="number" class="form-control" name="air_conditioner_exclusive_type_name" id="air_conditioner_exclusive_type_name" value="{{ $contract_list->air_conditioner_exclusive_type_name }}" placeholder="例：1" style="text-align:right">
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="air_conditioner_exclusive_type_name_error">
                                                                台数は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 台数 -->

                                                        <div class="w-100"></div>

                                                        <!-- エレベーター -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>エレベーター
                                                            
                                                            <select class="form-select " name="elevator" id="elevator" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->elevator == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="elevator_error">
                                                                エレベーターは必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- エレベーター -->

                                                        <!-- 台数 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>台数
                                                            <input type="number" class="form-control" name="elevator_exclusive_type_name" id="elevator_exclusive_type_name" value="{{ $contract_list->elevator_exclusive_type_name }}" placeholder="例：1" style="text-align:right">
                                                            <div class="real_estate_facilities-tab invalid-feedback" id ="elevator_exclusive_type_name_error">
                                                            </div>
                                                        </div>
                                                        <!-- 台数 -->
                                                    </div>

                                                </div>
                                                <!-- 整備・設備状況 -->

                                                <!-- 契約期間 -->
                                                <div class="tab-pane fade" id="nav-contract_span" role="tabpanel" aria-labelledby="nav-contract_span-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 契約期間 -->
                                                        <div class="col-12 col-md-12 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>契約期間
                                                            <!-- テキストボックスの右側に文字表示 -->
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="contract_start_date" name="contract_start_date" value="{{ $contract_list->contract_start_date }}" placeholder="例：2022/1/1" autocomplete="off" required>
                                                                <span class="d-flex align-items-center ms-1 me-1">～</span>
                                                                <input type="text" class="form-control" id="contract_end_date" name="contract_end_date" value="{{ $contract_list->contract_end_date }}" autocomplete="off" placeholder="例：2024/12/31" required>
                                                                <!-- バリデーション -->
                                                                <div class="contract_span-tab invalid-feedback" id ="contract_start_date_error">
                                                                    契約期間は必須です。
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 更新期間 -->
                                                        <div class="col-12 col-md-6 col-lg-2 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>契約更新期間
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="contract_update_span" id="contract_update_span" value="{{ $contract_list->contract_update_span }}" placeholder="例：2" style="text-align:right" required>
                                                                <span class="d-flex align-items-center ms-1 me-1">年</span>
                                                                <div class="contract_span-tab invalid-feedback" id ="contract_update_span_error">
                                                                    契約更新期間は必須です。
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 更新に必要な事項 -->
                                                        <div class="col-6 col-md-8 col-lg-8 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>更新に必要な事項
                                                            <textarea class="form-control" name="contract_update_item" id="contract_update_item" rows="2" placeholder="例：契約更新料:22000円">{{ $contract_list->contract_update_item }}</textarea>
                                                            <div class="contract_span-tab invalid-feedback" id ="contract_update_item_error"></div> 
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 解約時の日割計算 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>解約時の日割計算
                                                            <select class="form-select " name="daily_calculation" id="daily_calculation" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->daily_calculation == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="contract_span-tab invalid-feedback" id ="daily_calculation_error">
                                                                解約時の日割計算は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 解約時の日割計算 -->
                                                                                                        
                                                    </div>

                                                </div>
                                                <!-- 契約期間 -->

                                                <!-- 用途・利用の制限 -->
                                                <div class="tab-pane fade" id="nav-use_limitation" role="tabpanel" aria-labelledby="nav-use_limitation-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 用途の制限 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>用途の制限
                                                            <select class="form-select " name="limit_use_id" id="limit_use_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($limit_uses_list as $limit_uses)
                                                                    <option value="{{$limit_uses->limit_use_id}}" @if($contract_list->limit_use == $limit_uses->limit_use_id) selected @endif>{{$limit_uses->limit_use_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="use_limitation-tab invalid-feedback" id ="limit_use_id_error">
                                                                用途の制限は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 用途の制限 -->

                                                        <div class="w-100"></div>

                                                        <!-- 利用の制限 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>利用の制限
                                                            <input type="text" class="form-control" name="limit_type_name" id="limit_type_name" value="{{ $contract_list->limit_type }}" placeholder="例：事務所使用を禁止" required>
                                                            <div class="use_limitation-tab invalid-feedback" id ="limit_type_name_error">
                                                                利用の制限は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 利用の制限 -->

                                                        <div class="col-5 col-md-12 col-lg-5 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="" id="cb_limit_type_name">
                                                                <label class="form-check-label" for="flexCheckIndeterminate">
                                                                    システムの契約書を使用
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <!-- 用途・利用の制限 -->

                                                <!-- 契約解約及び解除 -->
                                                <div class="tab-pane fade" id="nav-contract_cancel" role="tabpanel" aria-labelledby="nav-contract_cancel-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 解約予告 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>解約予告
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="announce_cancel_date" id="announce_cancel_date" value="{{ $contract_list->announce_cancel_date }}" placeholder="例：2" style="text-align:right" required>
                                                                <span class="d-flex align-items-center ms-1 me-1">ヵ月前</span>
                                                                <div class="contract_cancel-tab invalid-feedback" id ="announce_cancel_date_error">
                                                                    解約予告は必須です。
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- 解約予告 -->

                                                        <!-- 即時解約 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>即時解約
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="soon_cancel_date" id="soon_cancel_date" value="{{ $contract_list->soon_cancel_date }}" placeholder="例：2" style="text-align:right" required>
                                                                <span class="d-flex align-items-center ms-1 me-1">ヵ月前</span>
                                                                <div class="contract_cancel-tab invalid-feedback" id ="soon_cancel_date_error">
                                                                    即時解約は必須です。
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <!-- 即時解約 -->

                                                        <div class="w-100"></div>

                                                        <!-- 解約時の日割計算 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>解約時の日割計算
                                                            
                                                            <select class="form-select " name="cancel_fee_count" id="cancel_fee_count" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($cancel_fee_count_list as $cancel_fee_count)
                                                                    <option value="{{$cancel_fee_count->cancel_fee_count_id}}" @if($contract_list->cancel_fee_count == $cancel_fee_count->cancel_fee_count_id) selected @endif>{{$cancel_fee_count->cancel_fee_count_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="contract_cancel-tab invalid-feedback" id ="cancel_fee_count_error">
                                                                解約時の日割計算は必須です。
                                                            </div> 
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 契約の解除 -->
                                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>契約の解除
                                                            <input type="text" class="form-control" name="cancel_contract_document" id="cancel_contract_document" value="{{ $contract_list->cancel_contract_document }}" placeholder="例：契約書案第〇条参照" required>
                                                            <div class="contract_cancel-tab invalid-feedback" id ="cancel_contract_document_error">
                                                                契約の解除は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 契約の解除 -->

                                                        <div class="col-5 col-md-12 col-lg-5 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="" id="cb_cancel_contract_document">
                                                                <label class="form-check-label" for="flexCheckIndeterminate">
                                                                    システムの契約書を使用
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 契約の消滅 -->
                                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>契約の消滅
                                                            <input type="text" class="form-control" name="remove_contract_document" id="remove_contract_document" value="{{ $contract_list->remove_contract_document }}" placeholder="例：契約書案第〇条参照" required>
                                                            <div class="contract_cancel-tab invalid-feedback" id ="remove_contract_document_error">
                                                                契約の消滅は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 契約の消滅 -->

                                                        <div class="col-5 col-md-12 col-lg-5 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="" id="cb_remove_contract_document">
                                                                <label class="form-check-label" for="flexCheckIndeterminate">
                                                                    システムの契約書を使用
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <!-- 契約解約及び解除 -->

                                                <!-- 損害賠償・違約金・免責 -->
                                                <div class="tab-pane fade" id="nav-indemnity_fee" role="tabpanel" aria-labelledby="nav-indemnity_fee-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 違約金 -->
                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>違約金
                                                            <textarea class="form-control" name="penalty_fee" id="penalty_fee" rows="2" placeholder="例：本契約は、一年未満の解約の場合、総賃料の2ヵ月分の違約金が掛かるものとする。一年以上、二年未満の場合、違約金として総賃料等の1ヵ月分が掛かるものとする。">{{ $contract_list->penalty_fee }}</textarea>
                                                            <div class="indemnity_fee-tab invalid-feedback" id ="penalty_fee_error"></div> 
                                                        </div>
                                                        <!-- 違約金 -->

                                                        <div class="w-100"></div>

                                                        <!-- 支払遅延損害金 -->
                                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>支払遅延損害金
                                                            <input type="text" class="form-control" name="penalty_fee_late_document" id="penalty_fee_late_document" value="{{ $contract_list->penalty_fee_late_document }}" placeholder="例：法廷利率に準ずる。" required>
                                                            <div class="indemnity_fee-tab invalid-feedback" id ="penalty_fee_late_document_error">
                                                                支払遅延損害金は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 支払遅延損害金 -->

                                                        <div class="col-5 col-md-12 col-lg-5 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="" id="cb_penalty_fee_late_document">
                                                                <label class="form-check-label" for="flexCheckIndeterminate">
                                                                    システムの契約書を使用
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 損害賠償 -->
                                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>損害賠償
                                                            <input type="text" class="form-control" name="claim_fee_document" id="claim_fee_document" value="{{ $contract_list->claim_fee_document }}" placeholder="例：契約書案第〇条参照" required>
                                                            <div class="indemnity_fee-tab invalid-feedback" id ="claim_fee_document_error">
                                                                損害賠償は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 損害賠償 -->

                                                        <div class="col-5 col-md-12 col-lg-5 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="" id="cb_claim_fee_document">
                                                                <label class="form-check-label" for="flexCheckIndeterminate">
                                                                    システムの契約書を使用
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>

                                                        <!-- 入居中の修繕に関する事項 -->
                                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>入居中の修繕に関する事項
                                                            <input type="text" class="form-control" name="fix_document" id="fix_document" value="{{ $contract_list->fix_document }}" placeholder="例：契約書案第〇条参照" required>
                                                            <div class="indemnity_fee-tab invalid-feedback" id ="fix_document_error">
                                                                入居中の修繕に関する事項は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 入居中の修繕に関する事項 -->

                                                        <div class="col-5 col-md-12 col-lg-5 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="" id="cb_fix_document">
                                                                <label class="form-check-label" for="flexCheckIndeterminate">
                                                                    システムの契約書を使用
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="w-100"></div>
                                                        
                                                        <!-- 明渡し及び原状回復 -->
                                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>明渡し及び原状回復
                                                            <input type="text" class="form-control" name="recovery_document" id="recovery_document" value="{{ $contract_list->recovery_document }}" placeholder="例：契約書案第〇条参照" required>
                                                            <div class="indemnity_fee-tab invalid-feedback" id ="recovery_document_error">
                                                                明渡し及び原状回復は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 明渡し及び原状回復 -->

                                                        <div class="col-5 col-md-12 col-lg-5 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="" id="cb_recovery_document">
                                                                <label class="form-check-label" for="flexCheckIndeterminate">
                                                                    システムの契約書を使用
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- 損害賠償・違約金・免責 -->

                                                <!-- 集金口座 -->
                                                <div class="tab-pane fade" id="nav-bank" role="tabpanel" aria-labelledby="nav-bank-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 銀行名 -->
                                                        <div class="col-7 col-md-4 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>家賃振込先
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="bank_name" id="bank_name" value="{{ $contract_list->bank_name }}" placeholder="例：〇〇〇〇銀行" required>
                                                                <button id="btnBankModal" type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bankModal"><i class="fas fa-search"></i></button>
                                                                <div class="bank-tab invalid-feedback" id="bank_name_error">
                                                                    家賃振込先は必須です。
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- 銀行名 -->

                                                        <div class="w-100"></div>

                                                        <!-- 支店名 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>支店名
                                                            <input type="text" class="form-control" name="bank_branch_name" id="bank_branch_name" value="{{ $contract_list->bank_branch_name }}" placeholder="例：〇〇〇〇支店" required>
                                                            <div class="bank-tab invalid-feedback" id ="bank_branch_name_error">
                                                                支店名は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 支店名 -->

                                                        <div class="w-100"></div>

                                                        <!-- 種別 -->
                                                        <div class="col-6 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>種別
                                                            <select class="form-select " name="bank_type_id" id="bank_type_id" value="" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($bank_type_list as $bank_type)
                                                                    <option value="{{$bank_type->bank_type_id}}" @if($contract_list->bank_type_id == $bank_type->bank_type_id) selected @endif>{{$bank_type->bank_type_name}}</option>
                                                                @endforeach
                                                            </select>

                                                            <div class="bank-tab invalid-feedback" id ="bank_type_id_error">
                                                                種別は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 種別 -->

                                                        <div class="w-100"></div>
                                                        
                                                        <!-- 口座番号 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>口座番号
                                                            <input type="text" class="form-control" name="bank_number" id="bank_number" value="{{ $contract_list->bank_number }}" placeholder="例：12345678" required>
                                                            <div class="bank-tab invalid-feedback" id ="bank_number_error">
                                                                口座番号は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 口座番号 -->

                                                        <div class="w-100"></div>

                                                        <!-- 口座名義 -->
                                                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>口座名義
                                                            <input type="text" class="form-control" name="bank_account_name" id="bank_account_name" value="{{ $contract_list->bank_account_name }}" placeholder="例：オオサカタロウ" required>
                                                            <div class="bank-tab invalid-feedback" id ="bank_account_name_error">
                                                                口座名義は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 口座名義 -->

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 家賃支払日 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>家賃支払日
                                                            <input type="text" class="form-control" name="rent_fee_payment_date" id="rent_fee_payment_date" value="{{ $contract_list->rent_fee_payment_date }}" placeholder="例：末日" required>
                                                            <div class="bank-tab invalid-feedback" id ="rent_fee_payment_date_error">
                                                                家賃支払日は必須です。
                                                            </div>
                                                        </div>

                                                        <!-- 銀行id -->
                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <input type="hidden" name="bank_id" id="bank_id" value="{{ $contract_list->bank_id }}">
                                                        </div>

                                                    </div>

                                                </div>
                                                <!-- 集金口座 -->    

                                                <!-- その他 -->
                                                <div class="tab-pane fade" id="nav-other" role="tabpanel" aria-labelledby="nav-other-tab">
                                                    
                                                    <div class="row row-cols-2">

                                                        <!-- 取引形態 -->
                                                        <div class="col-6 col-md-8 col-lg-4 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>取引形態
                                                            <select class="form-select " name="trade_type_id" id="trade_type_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($trade_type_list as $trade_type)
                                                                    <option value="{{$trade_type->trade_type_id}}" @if($contract_list->trade_type_id == $trade_type->trade_type_id) selected @endif>{{$trade_type->trade_type_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="other-tab invalid-feedback" id ="trade_type_id_error">
                                                                取引形態は必須です。
                                                            </div>
                                                        </div>
                                                        <!-- 取引形態 -->

                                                        <div class="w-100"></div>

                                                        <!-- ポスト番号 -->
                                                        <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>ポスト番号
                                                            <input type="text" class="form-control" name="mail_box_number" id="mail_box_number" value="{{ $contract_list->mail_box_number }}" placeholder="例：右2回0・左1回3">
                                                            <div class="other-tab invalid-feedback" id ="mail_box_number_error">
                                                            </div>
                                                        </div>
                                                        <!-- ポスト番号 -->

                                                        <div class="w-100"></div>

                                                        <div class="col-6 col-md-8 col-lg-12 mt-3">
                                                            <hr>
                                                        </div>

                                                        <!-- 連帯保証人有無 -->
                                                        <div class="col-6 col-md-8 col-lg-3 mt-3">
                                                            <label class="label_required mb-2" for="textBox"></label>連帯保証人の有無
                                                            
                                                            <select class="form-select " name="guarantor_need_id" id="guarantor_need_id" required>
                                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                                <option></option>
                                                                @foreach($need_list as $need)
                                                                    <option value="{{$need->need_id}}" @if($contract_list->guarantor_need_id == $need->need_id) selected @endif>{{$need->need_name}}</option>
                                                                @endforeach
                                                                
                                                            </select>
                                                            <div class="other-tab invalid-feedback" id ="guarantor_need_id_error">
                                                                連帯保証人の有無は必須です。
                                                            </div> 
                                                        </div>
                                                        <!-- 連帯保証人有無 -->

                                                        <!-- 極度額 -->
                                                        <div class="col-12 col-md-6 col-lg-3 mt-3">
                                                            <label class="label_any mb-2" for="textBox"></label>極度額
                                                            <input type="number" class="form-control" name="guarantor_max_payment" id="guarantor_max_payment" value="{{ $contract_list->guarantor_max_payment }}" placeholder="例：100000" style="text-align:right" @if($contract_list->guarantor_need_id !== 1)readonly="readonly"@endif>
                                                            <div class="other-tab invalid-feedback" id ="guarantor_max_payment_error"></div>
                                                        </div>
                                                        <!-- 極度額 -->

                                                    </div>

                                                </div>
                                                <!-- その他 -->   
                                                
                                                <!-- 特約事項 -->
                                                <div class="tab-pane fade" id="nav-special_contract" role="tabpanel" aria-labelledby="nav-special_contract-tab">
                                                    
                                                    <!-- 上部ボタン -->
                                                    <div class="row">
                                                        <!-- 左 -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                            <button id="btn_chcked" class="btn btn-outline-primary btn-default"><i class="fas fa-check me-2"></i>選択</button>
                                                            ※特約事項でデフォルトにチェックがあるものはチェックされています。
                                                        </div>

                                                        <!-- 右 -->
                                                        <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                            <button id="btn_clear" class="btn btn-outline-primary btn-default"><i class="fas fa-trash-alt me-2"></i>初期化</button>
                                                        </div>

                                                    </div>
                                                    <!-- 上部ボタン -->

                                                    <div class="row row-cols-2">

                                                            <!-- 一覧 -->
                                                            <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                                <div class="card">
                                                                    <!-- カードボディ -->
                                                                    <div class="card-body">
                                                                        <!-- スクロール -->
                                                                        <div class="overflow-auto" style="height:38.3em;">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover table-condensed">
                                                                                    <!-- テーブルヘッド -->
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th scope="col" id="create_user_id" style="display:none">id</th>
                                                                                            <th scope="col" id="create_user_name">選択</th>
                                                                                            <th scope="col" id="create_user_name">特約事項</th>
                                                                                        </tr>
                                                                                    </thead>

                                                                                    <!-- テーブルボディ -->
                                                                                    <tbody>
                                                                                        @foreach($special_contract_list as $special_contract)
                                                                                            <tr>
                                                                                                <td id="special_contract_id_{{$special_contract->special_contract_id}}" class="click_class" style="display:none"></td>
                                                                                                <td id="special_contract_select_{{$special_contract->special_contract_id}}" class="click_class"><input class="form-check-input" type="checkbox" id="cb_{{$special_contract->special_contract_id}}" @if($special_contract->special_contract_default_id == 1) checked @endif></td>
                                                                                                <td id="special_contract_detail_{{$special_contract->special_contract_id}}" class="click_class">{{$special_contract->special_contract_name}}</td>
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

                                                            <!-- 一覧(選択後) -->
                                                            <div class="col-12 col-md-12 col-lg-6 mt-3">
                                                                <textarea class="form-control" name="textarea_checked" id="textarea_checked" rows="25" placeholder="右側の特約事項から選択してください。">{{ $contract_list->special_contract_detail_name }}</textarea>
                                                            </div>
                                                            <!-- 一覧 -->

                                                        </div>
                                                        <!-- 契約進捗 -->

                                                    </div>

                                                </div>
                                                <!-- 特約事項 -->

                                            </div>
                                            <!-- 内容 -->
                                        </div>
                                    </div>
                                    <!-- タブ内のコンテンツ -->
                                    
                                    <!-- 境界線 -->
                                    <hr>

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
                                                    <button id="btn_contract" class="btn btn-outline-primary btn-default">帳票作成</button>
                                                @endif
                                                <button id="btn_temporarily" class="btn btn-outline-primary btn-default">一時登録</button>
                                                <button id="btn_edit" class="btn btn-outline-primary btn-default">登録</button>
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
                                <input type="text" name="contract_housemate_id" id="contract_housemate_id" value="">
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

		@component('component.back_js')
		@endcomponent

		<!-- 自作js -->
		<script src="{{ asset('back/js/back_contract_detail_edit.js') }}"></script>

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