<!DOCTYPE html>
<html lang="ja">

	<head>
        <title>アカウント詳細/ADMIN</title>

		<!-- head -->
		@component('component.admin_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('admin/css/admin_user_edti.css') }}">  
		
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

            <!-- 入力項目 -->
            <div class="container mt-3">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">

                        <form id="editForm" class="needs-validation" novalidate>
                
                            <div class="info_title mt-3">
                                <i class="far fa-gem icon_blue me-2"></i>アカウント情報
                            </div>

                            <!-- 境界線 -->
                            <hr>

                            <!-- カード -->
                            <div class="card border border-0">

                                <!-- タブ -->
                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12 mt-2">
                                        
                                        <!-- ナビゲーションの設定 -->
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-link active" id="nav-user-tab" data-bs-toggle="tab" href="#nav-user" role="tab" aria-controls="nav-user" aria-selected="true">アカウント情報</a>
                                                <a class="nav-link" id="nav-company_license-tab" data-bs-toggle="tab" href="#nav-company_license" role="tab" aria-controls="nav-company_license" aria-selected="false">免許情報</a>
                                                <a class="nav-link" id="nav-legal_places-tab" data-bs-toggle="tab" href="#nav-legal_places" role="tab" aria-controls="nav-legal_places" aria-selected="false">法務局</a>
                                                <a class="nav-link" id="nav-guaranty_societies-tab" data-bs-toggle="tab" href="#nav-guaranty_societies" role="tab" aria-controls="nav-guaranty_societies" aria-selected="false">保証協会</a>
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
                                        
                                            <!-- アカウント情報 -->
                                            <div class="tab-pane fade show active" id="nav-user" role="tabpanel" aria-labelledby="nav-user-tab">
                                                
                                                <div class="row row-cols-2">

                                                    <!-- 名前 -->
                                                    <div class="col-12 col-md-10 col-lg-6 mt-4">
                                                        <label class="label_required mb-2" for="textBox"></label>名前
                                                        <input type="text" class="form-control" name="create_user_name" id="create_user_name" value="{{ $user_list->create_user_name }}" placeholder="例：株式会社xx不動産" required>
                                                        <!-- エラーメッセージ -->
                                                        <div class="user-tab invalid-feedback" id ="create_user_name_error">
                                                            名前は必須です。
                                                        </div>
                                                    </div>

                                                    <div class="w-100"></div>

                                                    <!-- mail -->
                                                    <div class="col-12 col-md-10 col-lg-6 mt-3">
                                                        <label class="label_required mb-2" for="textBox"></label>E-mail
                                                        <input type="mail" class="form-control" name="create_user_mail" id="create_user_mail" value="{{ $user_list->create_user_mail }}" placeholder="例：xxxx@xxxx.com" required>
                                                        <!-- エラーメッセージ -->
                                                        <div class="user-tab invalid-feedback" id ="create_user_mail_error">
                                                            E-mailは必須です
                                                        </div>
                                                    </div>

                                                    <!-- 改行 -->
                                                    <div class="w-100"></div>

                                                    <!-- 郵便番号、検索ボタン -->
                                                    <div class="col-7 col-md-7 col-lg-2 mt-3">
                                                        <label class="label_required mb-2" for="textBox"></label>郵便番号
                                                        <div class="input-group">
                                                            <input type="text" id="create_user_post_number" class="form-control" name="create_user_post_number" value="{{ $user_list->create_user_post_number }}" placeholder="例：1111111" required>
                                                            <button class="btn btn-outline-primary btn_zip" type="button" id="create_user_post_number-btn-zip"><i class="fas fa-search"></i></button>
                                                            <div class="user-tab invalid-feedback" id="create_user_post_number_error">
                                                                郵便番号は必須です。
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- 郵便番号、検索ボタン -->

                                                    <!-- 改行 -->
                                                    <div class="w-100"></div>

                                                    <!-- 所在地 -->
                                                    <div class="col-12 col-md-12 col-lg-10 mt-3">
                                                        <label class="label_required mb-2" for="textBox"></label>所在地
                                                        <input type="text" class="form-control" name="create_user_address" id="create_user_address" value="{{ $user_list->create_user_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy" required>
                                                        <div class="user-tab invalid-feedback" id ="create_user_address_error">
                                                            所在地は必須です。
                                                        </div>
                                                    </div>

                                                    <!-- Tel -->
                                                    <div class="col-12 col-md-6 col-lg-5 mt-3">
                                                        <label class="label_required mb-2" for="textBox"></label>Tel
                                                        <input type="text" class="form-control" name="create_user_tel" id="create_user_tel" value="{{ $user_list->create_user_tel }}" placeholder="例：0612345678" required>
                                                        <div class="user-tab invalid-feedback" id ="create_user_tel_error">
                                                            Telは必須です。
                                                        </div>
                                                    </div>

                                                    <!-- Fax -->
                                                    <div class="col-12 col-md-6 col-lg-5 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>Fax
                                                        <input type="text" class="form-control" name="create_user_fax" id="create_user_fax" value="{{ $user_list->create_user_fax }}" placeholder="例：0612345678">
                                                        <div class="user-tab invalid-feedback" id ="create_user_fax_error">
                                                        </div>
                                                    </div>

                                                    <!-- パスワード -->
                                                    <div class="col-12 col-md-6 col-lg-5 mt-3">
                                                        <label class="label_required mb-2" for="textBox"></label>パスワード
                                                        <input type="password" class="form-control" name="password_reqest" id="password" value="{{ $user_list->password }}" autocomplete="off" required>
                                                        <div class="user-tab invalid-feedback" id ="password_error">
                                                            パスワードは必須です。
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <!-- アカウント情報 -->

                                            <!-- 免許情報 -->
                                            <div class="tab-pane fade" id="nav-company_license" role="tabpanel" aria-labelledby="nav-company_licenses-tab">
                                                <div class="row row-cols-2">
                                                    
                                                    <!-- 商号 -->
                                                    <div class="col-12 col-md-10 col-lg-5 mt-4">
                                                        <label class="label_any mb-2" for="textBox"></label>商号
                                                        <input type="text" class="form-control" name="company_license_name" id="company_license_name" value="{{ $user_list->company_license_name }}" placeholder="例：株式会社xx不動産">
                                                        <!-- エラーメッセージ -->
                                                        <div class="company_license-tab invalid-feedback" id ="company_licence_name_error">
                                                            商号は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 商号 -->

                                                    <div class="w-100"></div>

                                                    <!-- 代表者 -->
                                                    <div class="col-12 col-md-10 col-lg-5 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>代表者
                                                        <input type="text" class="form-control" name="company_license_representative" id="company_license_representative" value="{{ $user_list->company_license_representative }}" placeholder="例：大阪　太郎">
                                                        <!-- エラーメッセージ -->
                                                        <div class="company_license-tab invalid-feedback" id ="company_licence_representative_error">
                                                            代表者は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 代表者 -->

                                                    <div class="w-100"></div>

                                                    <!-- 所在地 -->
                                                    <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>所在地
                                                        <input type="text" class="form-control" name="company_license_address" id="company_license_address" value="{{ $user_list->company_license_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy">
                                                        <div class="company_license-tab invalid-feedback" id ="company_licence_address_error">
                                                            所在地は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 所在地 -->

                                                    <div class="w-100"></div>

                                                    <!-- Tel -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>Tel
                                                        <input type="text" class="form-control" name="company_license_tel" id="company_license_tel" value="{{ $user_list->company_license_tel }}" placeholder="例：0612345678">
                                                        <div class="company_license-tab invalid-feedback" id ="company_licence_tel_error">
                                                            Telは必須です。
                                                        </div>
                                                    </div>

                                                    <!-- Fax -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>Fax
                                                        <input type="text" class="form-control" name="company_license_fax" id="company_license_fax" value="{{ $user_list->company_license_fax }}" placeholder="例：0612345678">
                                                        <div class="company_license-tab invalid-feedback" id ="company_licence_fax_error">
                                                        </div>
                                                    </div>

                                                    <div class="w-100"></div>

                                                    <!-- 免許番号 -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>免許番号
                                                        <input type="text" class="form-control" name="company_license_number" id="company_license_number" value="{{ $user_list->company_license_number }}" placeholder="例：大阪府知事(x)第xxxxxx">
                                                        <div class="company_license-tab invalid-feedback" id ="company_license_number_error">
                                                        </div>
                                                    </div>

                                                    <!-- 免許年月日 -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>免許年月日
                                                        <input type="text" class="form-control" name="company_license_span" id="company_license_span" value="{{ $user_list->company_license_span }}" placeholder="例：令和x年x月x日～令和x年x月xx日">
                                                        <div class="company_license-tab invalid-feedback" id ="company_license_span_error">
                                                        </div>
                                                    </div>

                                                    <div class="w-100"></div>

                                                    <!-- 専任取引士 -->
                                                    <div class="col-5 col-md-12 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>専任取引士
                                                        <select class="form-select" name="full_time_user_license_id" id="full_time_user_license_id" value="{{ $user_list->full_time_user_license_id }}">
                                                            <option></option>
                                                            @foreach($user_license_list as $user_license)
                                                                <option value="{{$user_license->user_license_id}}" @if($user_list->full_time_user_license_id == $user_license->user_license_id) selected @endif>{{ $user_license->user_license_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="company_license-tab invalid-feedback" id="full_time_user_license_id_error">
                                                            専任取引士は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 専任取引士 -->

                                                    <!-- 登録番号 -->
                                                    <div class="col-12 col-md-12 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>登録番号
                                                        <input type="text" class="form-control" name="full_time_user_license_number" id="full_time_user_license_number" value="{{ $user_list->full_time_user_license_number }}" readonly>
                                                        <div class="company_license-tab invalid-feedback" id ="full_time_user_license_number_error">
                                                            登録番号は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 登録番号 -->

                                                    <div class="col-12 col-md-6 col-lg-12 mt-3">
                                                        <hr>
                                                    </div>

                                                    <!-- 取扱店 -->
                                                    <div class="col-12 col-md-6 col-lg-5 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>取扱店
                                                        <input type="text" class="form-control" name="company_nick_name" id="company_nick_name" value="{{ $user_list->company_nick_name }}" placeholder="例：大阪不動産">
                                                        <div class="company_license-tab invalid-feedback" id ="fax_error">
                                                        </div>
                                                    </div>
                                                    <!-- 取扱店 -->

                                                    <div class="w-100"></div>

                                                    <!-- 所在地 -->
                                                    <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>所在地
                                                        <input type="text" class="form-control" name="company_nick_address" id="company_nick_address" value="{{ $user_list->company_nick_address }}" placeholder="例：大阪府大阪市梅田1丁目xx-yy">
                                                        <div class="company_license-tab invalid-feedback" id ="company_nick_address_error">
                                                            所在地は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 所在地 -->

                                                    <div class="w-100"></div>

                                                </div>
                                            </div>
                                            <!-- 免許情報 -->   

                                            <!-- 法務局 -->
                                            <div class="tab-pane fade" id="nav-legal_places" role="tabpanel" aria-labelledby="nav-legal_places-tab">
                                                <div class="row row-cols-2">

                                                    <!-- 名称 -->
                                                    <div class="col-5 col-md-12 col-lg-4 mt-4">
                                                        <label class="label_any mb-2" for="textBox"></label>法務局名
                                                        <select class="form-select" name="legal_place_id" id="legal_place_id" value="{{ $user_list->legal_place_id }}" >
                                                            <option></option>
                                                            @foreach($legal_place_list as $legal_place)
                                                                <option value="{{ $legal_place->legal_place_id }}" @if($user_list->legal_place_id == $legal_place->legal_place_id) selected @endif>{{ $legal_place->legal_place_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback" id="legal_place_id_error">
                                                            不動産保証協会
                                                        </div>
                                                    </div>
                                                    <!-- 名称 -->

                                                    <div class="w-100"></div>

                                                    <!-- 郵便番号 -->
                                                    <div class="col-12 col-md-12 col-lg-2 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>郵便番号
                                                        <input type="text" class="form-control" name="legal_place_post_number" id="legal_place_post_number" value="{{ $user_list->legal_place_post_number }}" readonly>
                                                        <div class="invalid-feedback" id ="legal_place_post_number_error">
                                                            郵便番号は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 郵便番号 -->

                                                    <div class="w-100"></div>

                                                    <!-- 所在地 -->
                                                    <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>所在地
                                                        <input type="text" class="form-control" name="legal_place_address" id="legal_place_address" value="{{ $user_list->legal_place_address }}" readonly>
                                                        <div class="invalid-feedback" id ="legal_place_address_error">
                                                            所在地は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 所在地 -->

                                                    <div class="w-100"></div>

                                                    <!-- Tel -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>Tel
                                                        <input type="text" class="form-control" name="legal_place_tel" id="legal_place_tel" value="{{ $user_list->legal_place_tel }}" readonly>
                                                        <div class="invalid-feedback" id ="legal_place_tel_error">
                                                            Telは必須です。
                                                        </div>
                                                    </div>

                                                    <!-- Fax -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>Fax
                                                        <input type="text" class="form-control" name="legal_place_fax" id="legal_place_fax" value="{{ $user_list->legal_place_fax }}" readonly>
                                                        <div class="invalid-feedback" id ="legal_place_fax_error">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- 法務局 -->

                                            <!-- 不動産保証協会 -->
                                            <div class="tab-pane fade" id="nav-guaranty_societies" role="tabpanel" aria-labelledby="nav-guaranty_societies-tab">
                                                <div class="row row-cols-2">

                                                    <!-- 名称 -->
                                                    <div class="col-5 col-md-12 col-lg-4 mt-4">
                                                        <label class="label_any mb-2" for="textBox"></label>保証協会
                                                        <select class="form-select" name="guaranty_association_id" id="guaranty_association_id" value="{{ $user_list->guaranty_association_id }}">
                                                            <option></option>
                                                            @foreach($guaranty_association_list as $guaranty_associations)
                                                                <option value="{{ $guaranty_associations->guaranty_association_id }}" @if($user_list->guaranty_association_id == $guaranty_associations->guaranty_association_id) selected @endif>{{ $guaranty_associations->guaranty_association_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback" id="guaranty_association_id_error">
                                                            保証協会は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 名称 -->

                                                    <div class="w-100"></div>

                                                    <!-- 郵便番号 -->
                                                    <div class="col-12 col-md-12 col-lg-2 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>郵便番号
                                                        <input type="text" class="form-control" name="guaranty_association_post_number" id="guaranty_association_post_number" value="{{ $user_list->guaranty_association_post_number }}" readonly>
                                                        <div class="invalid-feedback" id ="guaranty_association_post_number_error"> 
                                                            郵便番号は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 郵便番号 -->

                                                    <div class="w-100"></div>

                                                    <!-- 所在地 -->
                                                    <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>所在地
                                                        <input type="text" class="form-control" name="guaranty_association_address" id="guaranty_association_address" value="{{ $user_list->guaranty_association_address }}" readonly>
                                                        <div class="invalid-feedback" id ="guaranty_association_address_error">
                                                            所在地は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 所在地 -->

                                                    <div class="w-100"></div>

                                                    <!-- Tel -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>Tel
                                                        <input type="text" class="form-control" name="guaranty_association_tel" id="guaranty_association_tel" value="{{ $user_list->guaranty_association_tel }}" readonly>
                                                        <div class="invalid-feedback" id ="guaranty_association_tel_error">
                                                            Telは必須です。
                                                        </div>
                                                    </div>

                                                    <!-- Fax -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>Fax
                                                        <input type="text" class="form-control" name="guaranty_association_fax" id="guaranty_association_fax" value="{{ $user_list->guaranty_association_fax }}" readonly>
                                                        <div class="invalid-feedback" id ="guaranty_association_fax_error">
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-12 col-md-12 col-lg-12 mt-3">
                                                        <hr>
                                                    </div>

                                                    <!-- 保証協会所属地方 -->
                                                    <div class="col-5 col-md-12 col-lg-4 mt-4">
                                                        <label class="label_any mb-2" for="textBox"></label>保証協会所属地方
                                                        <select class="form-select" name="guaranty_association_region_id" id="guaranty_association_region_id" value="{{ $user_list->guaranty_association_id }}">
                                                            <option></option>
                                                            @foreach($guaranty_association_list as $guaranty_associations)
                                                                <option value="{{ $guaranty_associations->guaranty_association_id }}" @if($user_list->guaranty_association_region_id == $guaranty_associations->guaranty_association_id) selected @endif>{{ $guaranty_associations->guaranty_association_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback" id="guaranty_association_region_id_error">
                                                            保証協会所属地方は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 名称 -->

                                                    <div class="w-100"></div>

                                                    <!-- 郵便番号 -->
                                                    <div class="col-12 col-md-12 col-lg-2 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>郵便番号
                                                        <input type="text" class="form-control" name="guaranty_association_region_post_number" id="guaranty_association_region_post_number" value="{{ $user_list->guaranty_association_region_post_number }}" readonly>
                                                        <div class="invalid-feedback" id ="guaranty_association_region_post_number_error"> 
                                                            郵便番号は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 郵便番号 -->

                                                    <div class="w-100"></div>

                                                    <!-- 所在地 -->
                                                    <div class="col-12 col-md-12 col-lg-8 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>所在地
                                                        <input type="text" class="form-control" name="guaranty_association_region_address" id="guaranty_association_region_address" value="{{ $user_list->guaranty_association_region_address }}" readonly>
                                                        <div class="invalid-feedback" id ="guaranty_association_region_address_error">
                                                            所在地は必須です。
                                                        </div>
                                                    </div>
                                                    <!-- 所在地 -->

                                                    <div class="w-100"></div>

                                                    <!-- Tel -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>Tel
                                                        <input type="text" class="form-control" name="guaranty_association_region_tel" id="guaranty_association_region_tel" value="{{ $user_list->guaranty_association_region_tel }}" readonly>
                                                        <div class="invalid-feedback" id ="guaranty_association_region_tel_error">
                                                            Telは必須です。
                                                        </div>
                                                    </div>

                                                    <!-- Fax -->
                                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                                        <label class="label_any mb-2" for="textBox"></label>Fax
                                                        <input type="text" class="form-control" name="guaranty_association_region_fax" id="guaranty_association_region_fax" value="{{ $user_list->guaranty_association_region_fax }}" readonly>
                                                        <div class="invalid-feedback" id ="guaranty_association_region_fax_error">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- 保証協会 -->

                                        </div>
                                        <!-- 内容 -->
                                    </div>
                                </div>
                                <!-- タブ内のコンテンツ -->
                                
                                <!-- 境界線 -->
                                <hr>

                                <!-- ボタン -->
                                <div class="row row-cols-2 mb-5">
                                    <!-- 登録、帳票 -->
                                    <div class="col-12 col-md-12 col-lg-12 mt-3">
                                        <button id="btn_edit" class="btn btn-outline-primary float-end btn-default">登録</button>
                                    </div>
                                </div>     
                                <!-- ボタン -->

                                <!-- アカウントid -->
                                <input type="hidden" name="create_user_id" id="create_user_id" value="{{ $user_list->create_user_id }}">
                                
                            </div>
                            <!-- カード -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- コンテンツ -->

		</main>
		<!-- page-content" -->

		</div>
		<!-- page-wrapper -->

		@component('component.admin_js')
		@endcomponent

		<!-- 自作js -->
		<script src="{{ asset('admin/js/admin_user_edit.js') }}"></script>
	</body>
	
</html>