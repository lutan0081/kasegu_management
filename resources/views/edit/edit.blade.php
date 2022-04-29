<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>顧客登録/KASEGU</title>

        <!-- css -->
        @component('component.head')
        @endcomponent

        <!-- edit.css -->
        <link rel="stylesheet" href="{{ asset('edit/css/edit.css') }}">

        <style>
            .container{
                font-family: 'Nunito', sans-serif;
            }
            /* 添付書類の画像大きさ調整 */
            .card-img, .card-img-top{
                height: 400px;
            }

            /* 画像詳細ボタンの調整 */
            .btn-group, .btn-group-vertical {
                z-index: 2;
            }

            /* mobile画面になった時
            　 画面いっぱいの大きさにする */
            @media (max-width: 768px) {
                .container{
                    width: 100%;
                }
            }
            
            /* ボタンのデフォルト値 */
            .btn-default{
                width: 6rem;
            }
        </style>
    </head>

    <body>

        <!--ヘッダー(URLからの場合、ヘッダー非表示)-->
        @if( $application_Flag !== "true") 
            @component('component.header')
            @endcomponent
        @endif

        <!-- ローディング画面の表示 -->
        <div id="overlay">
            <div class="cv-spinner">
                <span class="spinner"></span>
            </div>
        </div>
            
        <!-- form -->
        <form id="editForm" class="needs-validation" novalidate>
            <!-- コンテナ -->
            <div class="container box zoomInTrigger">
                <!-- カード -->
                <div class="card border border-0">

                    <!-- row -->
                    <div class="row row-cols-1">
                        <!-- タブタイトル -->
                        <div class="col-12 col-md-12 col-lg-12 mt-5">
                            <!-- ナビゲーションの設定 -->
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-link active" id="nav-user-tab" data-bs-toggle="tab" href="#nav-user" role="tab" aria-controls="nav-user" aria-selected="true">不動産業者</a>
                                    <a class="nav-link" id="nav-trade-tab" data-bs-toggle="tab" href="#nav-trade" role="tab" aria-controls="nav-trade" aria-selected="false">募集要項</a>
                                    <a class="nav-link" id="nav-contract-tab" data-bs-toggle="tab" href="#nav-contract" role="tab" aria-controls="nav-contract" aria-selected="false">契約者</a>
                                    <a class="nav-link" id="nav-housemate-tab" data-bs-toggle="tab" href="#nav-housemate" role="tab" aria-controls="nav-housemate" aria-selected="false">同居人</a>
                                    <a class="nav-link" id="nav-emergency-tab" data-bs-toggle="tab" href="#nav-emergency" role="tab" aria-controls="nav-emergency" aria-selected="false">緊急連絡先</a>
                                    <a class="nav-link" id="nav-guarantor-tab" data-bs-toggle="tab" href="#nav-guarantor" role="tab" aria-controls="nav-guarantor" aria-selected="false">連帯保証人</a>
                                    <a class="nav-link" id="nav-document-tab" data-bs-toggle="tab" href="#nav-document" role="tab" aria-controls="nav-document" aria-selected="false">添付書類</a>
                                </div>
                            </nav>
                            <!-- ナビゲーションの設定 -->
                        </div>
                        <!-- タブタイトル -->
                    </div>
                    <!-- row -->

                    <!-- タブ内のコンテンツ -->
                    <div class="row row-cols-3">
                        <div class="col-12 col-md-12 col-lg-12">
                            <!-- 内容 -->
                            <div class="tab-content" id="nav-tabContent">

                                <!-- 業者 -->
                                <div class="tab-pane fade show active" id="nav-user" role="tabpanel" aria-labelledby="nav-user-tab">
                                    
                                    <div class="row row-cols-2">

                                        <!-- 進捗状況 -->
                                        <div class="col-6 col-md-8 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>進捗情報
                                            
                                            <select class="form-select" name="contract_progress" id="contract_progress" aria-label=".form-select_application_types">
                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                <option></option>
                                                @foreach(Common::$CONTRACT_PROGRESS as $key => $value)
                                                    <option value="{{ $key }}" @if ($list[0]->contract_progress == $key) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 仲介業者名 -->
                                        <div class="col-12 col-md-12 col-lg-6 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>仲介業者
                                            <input type="text" class="form-control" name="broker_name" id="broker_name" value="{{ $list[0]->broker_name }}"required>
                                            <!-- バリデーション -->
                                            <div class="invalid-feedback" id ="broker_name_error">
                                                仲介業者名は必須です。
                                            </div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>
                                        
                                        <!-- 担当者 -->
                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>担当者
                                            <input type="text" class="form-control" name="manager_name" id="manager_name" value="{{ $list[0]->manager_name }}"required>
                                            <!-- バリデーション -->
                                            <div class="invalid-feedback" id ="manager_name_error">
                                                担当者は必須です。
                                            </div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 仲介業者Tel -->
                                        <div class="col-6 col-md-8 col-lg-6 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>Tel
                                            <input type="text" class="form-control" name="broker_tel" id="broker_tel" value="{{ $list[0]->broker_tel }}"required>
                                            <!-- バリデーション -->
                                            <div class="invalid-feedback" id ="broker_tel_error">
                                                仲介業者Telは必須です。
                                            </div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 仲介業者mail -->
                                        <div class="col-12 col-md-12 col-lg-6 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>E-mail
                                            <input type="mail" class="form-control" name="broker_mail" id="broker_mail" value="{{ $list[0]->broker_mail }}"required>
                                            <!-- バリデーション -->
                                            <div class="invalid-feedback" id ="broker_mail_error">
                                                E-mailは必須です。
                                            </div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>
                                    </div>
                                </div>
                                <!-- 業者 -->

                                <!-- 条件 -->
                                <div class="tab-pane fade" id="nav-trade" role="tabpanel" aria-labelledby="nav-trade-tab">
                                    <div class="row row-cols-2">

                                        <!-- 申込区分 -->
                                        <div class="col-5 col-md-12 col-lg-3 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>申込区分
                                            <select class="form-select" name="application_type_id" id="application_type_id" aria-label=".form-select_application_types" required>
                                                <option></option>
                                                @foreach($list_types as $types)
                                                    <option value="{{ $types->application_type_id }}" @if($list[0]->application_type_id == $types->application_type_id) selected @endif>{{ $types->application_type_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="application_type_error">
                                                申込区分は必須です。
                                            </div>
                                        </div>

                                        <!-- 物件用途 -->
                                        <div class="col-5 col-md-12 col-lg-3 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>物件用途
                                            <select class="form-select" name="application_use_id" id="application_use_id" aria-label=".form-select_application_types" required>
                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                <option></option>
                                                @foreach($list_uses as $uses)
                                                    <option value="{{ $uses->application_use_id }}" @if($list[0]->application_use_id == $uses->application_use_id) selected @endif>{{ $uses->application_use_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- 入居予定日 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>入居予定日
                                            <input type="text" class="form-control" name="contract_start_date" id="contract_start_date" value="{{ $list[0]->contract_start_date }}" placeholder="2020/02/02">
                                            <div class="invalid-feedback" id ="contract_start_date_error"></div>
                                        </div>

                                        <!-- 物件名 -->
                                        <div class="col-12 col-md-12 col-lg-9 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>物件名
                                            <input type="text" class="form-control" name="real_estate_name" id="real_estate_name" value="{{ $list[0]->real_estate_name }}" required="">
                                            <div class="invalid-feedback" id ="real_estate_name_error">
                                                物件名は必須です。
                                            </div>
                                        </div>

                                        <!-- 物件カナ -->
                                        <div class="col-12 col-md-12 col-lg-9 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>物件名カナ
                                            <input type="text" class="form-control" name="real_estate_ruby" id="real_estate_ruby" value="{{ $list[0]->real_estate_ruby }}" required="">
                                            <div class="invalid-feedback" id ="real_estate_ruby_error">
                                                物件名カナは必須です。
                                            </div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>
                                    
                                        <!-- 号室 -->
                                        <div class="col-5 col-md-12 col-lg-2 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>号室
                                            <input type="text" class="form-control" name="room_name" value="{{ $list[0]->room_name }}" id="room_name" required="">
                                            <div class="invalid-feedback" id ="room_name_error">
                                                号室は必須です。
                                            </div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 郵便番号 -->
                                        <div class="col-7 col-md-4 col-lg-2 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>郵便番号
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="post_number" id="post_number" value="{{ $list[0]->real_estate_post_number }}" required>
                                                <button id="real_estate_agent-btn-zip" class="btn btn-outline-primary btn_zip">検索</button>
                                                <div class="invalid-feedback" id="post_error">
                                                    郵便番号は必須です。
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- 住所 -->
                                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>住所
                                            <input type="text" class="form-control" name="address" id="address" value="{{ $list[0]->real_estate_address }}" required="">
                                            <div class="invalid-feedback" id ="room_name_error">
                                                住所は必須です。
                                            </div>       
                                        </div>

                                        <!-- ペット飼育有無 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>ペット飼育
                                            <select class="form-select" name="pet_bleeding_name" id="pet_bleeding_name" aria-label=".form-select_application_types">
                                                <option></option>
                                                <option value="1" @if($list[0]->pet_bleeding_name=='1') selected @endif>有</option>
                                                <option value="2" @if($list[0]->pet_bleeding_name=='2') selected @endif>無</option>
                                            </select>
                                        </div>

                                        <!-- ペット種類 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>ペット種類
                                            <input type="text" class="form-control" name="pet_kind_name" id="pet_kind_name" value="{{$list[0]->pet_kind_name}}" placeholder="小型犬・猫"> 
                                            <div class="invalid-feedback" id ="pet_kind_name_error"></div>  
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 駐車台数 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>駐車台数
                                            <input type="number" class="form-control" name="bicycle_parking_number" id="bicycle_parking_number" value="{{$list[0]->bicycle_parking_number}}" required="">
                                            <div class="invalid-feedback" id ="bicycle_parking_number_error">
                                                駐車台数は必須です。
                                            </div>   
                                        </div>

                                        <!-- 駐輪台数 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_required mb-2" for="textBox"></label>駐輪台数
                                            <input type="number" class="form-control" name="car_parking_number" id="car_parking_number" value="{{$list[0]->car_parking_number}}" required="">
                                            <div class="invalid-feedback" id ="car_parking_number_error">
                                                駐輪台数は必須です。
                                            </div>  
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 保証金 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>保証金
                                            <input type="number" class="form-control" name="deposit_money" id="deposit_money" value="{{$list[0]->security_deposit}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="deposit_money_error"></div>  
                                        </div>

                                        <!-- 解約引き -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>解約引き
                                            <input type="number" class="form-control" name="deposit_refund" id="deposit_refund" value="{{$list[0]->deposit_refund}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="deposit_refund_error"></div>  
                                        </div>

                                        <!-- 敷金 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>敷金
                                            <input type="number" class="form-control" name="security_deposit" id="security_deposit" value="{{$list[0]->deposit_money}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="security_deposit_error"></div> 
                                        </div>

                                        <!-- 礼金 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>礼金
                                            <input type="number" class="form-control" name="key_money" id="key_money" value="{{$list[0]->key_money}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="key_money_error"></div> 
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 賃料 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>賃料
                                            <input type="number" class="form-control money_text" name="rent_fee" id="rent_fee" value="{{$list[0]->rent_fee}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="rent_fee_error"></div> 
                                        </div>

                                        <!-- 共益費 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>共益費
                                            <input type="number" class="form-control money_text" name="service_fee" id="service_fee" value="{{$list[0]->service_fee}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="service_fee_error"></div> 
                                        </div>

                                        <!-- 水道代 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>水道代
                                            <input type="number" class="form-control money_text" name="water_fee" id="water_fee" value="{{$list[0]->water_fee}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="water_fee_error"></div> 
                                        </div>

                                        <!-- その他 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>その他
                                            <input type="number" class="form-control money_text" name="ohter_fee" id="ohter_fee" value="{{$list[0]->ohter_fee}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="ohter_fee_error"></div> 
                                        </div>

                                        <!-- 総賃料 -->
                                        <div class="col-6 col-md-6 col-lg-2 mt-3">
                                            <label for="">月額賃料</label>
                                            <input type="number" disabled="disabled" class="form-control" name="total_rent_fee" id="total_rent_fee" value="{{$list[0]->total_rent_fee}}" style="text-align:right">
                                        </div>
                        
                                    </div>
                                </div>
                                <!-- 条件 -->

                                <!-- 契約者 -->
                                <div class="tab-pane fade" id="nav-contract" role="tabpanel" aria-labelledby="nav-contract-tab">
                                    <div class="row row-cols-2">
                                        <!-- 契約者名 -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>契約者
                                            <input type="text" class="form-control" name="contract_name" id="contract_name" value="{{ $list[0]->contract_name }}" required>
                                            <div class="invalid-feedback" id ="contract_name_error">
                                                契約者は必須です。
                                            </div>  
                                        </div>

                                        <!-- 契約者カナ -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>契約者カナ
                                            <input type="text" class="form-control" name="contract_ruby" id="contract_ruby" value="{{ $list[0]->contract_ruby }}" required>
                                            <div class="invalid-feedback" id ="contract_ruby_error">
                                                契約者カナは必須です。
                                            </div>  
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 郵便番号 -->
                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>郵便番号
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="contract_post_number" id="contract_post_number" value="{{ $list[0]->contract_post_number }}">
                                                <button id="contracts-btn-zip" class="btn btn-outline-primary btn_zip">検索</button>
                                                <div class="invalid-feedback" id="post_error">
                                                郵便番号は必須です。
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 住所 -->
                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>住所
                                            <input type="text" class="form-control" name="contract_address" id="contract_address" value="{{ $list[0]->contract_address }}">
                                            <div class="invalid-feedback" id ="contract_address_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 性別 -->
                                        <div class="col-6 col-md-5 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>性別
                                            <select class="form-select" name="contract_sex_id" id="contract_sex_id" aria-label=".form-select_application_types">
                                                <option></option> 
                                                <option value="1" @if($list[0]->contract_sex_id=='1') selected @endif>男</option>
                                                <option value="2" @if($list[0]->contract_sex_id=='2') selected @endif>女</option>
                                                <div class="invalid-feedback" id ="contract_sex_id_error"></div>
                                            </select>
                                        </div>

                                        <!-- 生年月日 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>生年月日
                                            <input type="text" class="form-control" name="contract_birthday" id="contract_birthday" value="{{ $list[0]->contract_birthday }}">
                                            <div class="invalid-feedback" id ="contract_birthday_error"></div>
                                        </div>

                                        <!-- 年齢 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>年齢
                                            <input type="number" class="form-control" name="contract_age" id="contract_age" value="{{ $list[0]->contract_age }}">
                                            <div class="invalid-feedback" id ="contract_age_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 自宅電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>自宅Tel
                                            <input type="text" class="form-control" name="contract_home_tel" id="contract_home_tel" value="{{ $list[0]->contract_home_tel }}">
                                            <div class="invalid-feedback" id ="contract_home_tel_error"></div>
                                        </div>

                                        <!-- 携帯電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>携帯Tel
                                            <input type="text" class="form-control" name="contract_mobile_tel" id="contract_mobile_tel" value="{{ $list[0]->contract_mobile_tel }}">
                                            <div class="invalid-feedback" id ="contract_mobile_error"></div>
                                        </div>

                                        <!-- 勤務先名 -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>勤務先名
                                            <input type="text" class="form-control" name="contract_work_place_name" id="contract_work_place_name" value="{{ $list[0]->contract_work_place_name }}">
                                            <div class="invalid-feedback" id ="contract_work_place_name_error"></div>
                                        </div>

                                        <!-- 勤務先フリガナ -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>勤務先名カナ
                                            <input type="text" class="form-control" name="contract_work_place_ruby" id="contract_work_place_ruby" value="{{$list[0]->contract_work_place_ruby}}">
                                            <div class="invalid-feedback" id ="contract_work_place_ruby_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 勤務先郵便番号 -->
                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>郵便番号
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="contract_work_place_post_number" id="contract_work_place_post_number" value="{{$list[0]->contract_work_place_post_number}}">
                                                <button id="contract_work_place-btn-zip" class="btn btn-outline-primary btn_zip">検索</button>
                                                <div class="invalid-feedback" id="post_error">
                                                郵便番号は必須です。
                                                </div>
                                            </div>
                                        </div>
                                        <!-- 勤務先郵便番号 -->

                                        <!-- 勤務先住所 -->
                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>所在地
                                            <input type="text" class="form-control" name="contract_work_place_address" id="contract_work_place_address" value="{{$list[0]->contract_work_place_address}}">
                                            <div class="invalid-feedback" id ="contract_work_place_address_error"></div>
                                        </div>

                                        <!-- 勤務先電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>勤務先Tel
                                            <input type="text" class="form-control" name="contract_work_place_tel" id="contract_work_place_tel" value="{{$list[0]->contract_work_place_tel}}">
                                            <div class="invalid-feedback" id ="contract_work_place_tel_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 業種 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>業種
                                            <input type="text" class="form-control" name="contract_work_place_Industry" id="contract_work_place_Industry" value="{{$list[0]->contract_work_place_Industry}}">
                                            <div class="invalid-feedback" id ="contract_work_place_Industry_error"></div>
                                        </div>

                                        <!-- 職種 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>職種
                                            <input type="text" class="form-control" name="contract_work_place_occupation" id="contract_work_place_occupation" value="{{$list[0]->contract_work_place_occupation}}">
                                            <div class="invalid-feedback" id ="contract_work_place_occupation_error"></div>
                                        </div>

                                        <!-- 雇用形態 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>雇用形態
                                            <input type="text" class="form-control" name="contract_employment_status" id="contract_employment_status" value="{{$list[0]->contract_employment_status}}">
                                            <div class="invalid-feedback" id ="contract_employment_status_error"></div>
                                        </div>

                                        <!-- 勤続年数 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>勤続年数
                                            <input type="number" class="form-control" name="contract_work_place_years" id="contract_work_place_years" value="{{$list[0]->contract_work_place_years}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="contract_work_place_years_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 年収 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>年収
                                            <input type="number" class="form-control" name="contract_annual_income" id="contract_annual_income" value="{{$list[0]->contract_annual_income}}" style="text-align:right">
                                            <div class="invalid-feedback" id ="contract_annual_income_error"></div>
                                        </div>

                                        <!-- 健康保険 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>健康保険
                                            <select class="form-select" name="contracts_insurance_type_id" id="contracts_insurance_type_id" aria-label=".form-select_application_types">
                                                <option></option>
                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                @foreach($list_insurance as $insurances)
                                                    <option value="{{$insurances->insurance_type_id}}" @if($list[0]->contracts_insurance_type_id == $insurances->insurance_type_id) selected @endif>{{ $insurances->insurance_type_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id ="contracts_insurance_type_id_error"></div>
                                        </div>

                                    </div>
                                </div>
                                <!-- 契約者 -->
                                
                                <!-- 同居人 -->
                                <div class="tab-pane fade" id="nav-housemate" role="tabpanel" aria-labelledby="nav-housemate-tab">
                                    <div class="row row-cols-2">

                                        <!-- 同居人 -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>同居人
                                            <input type="text" class="form-control" name="housemate_name" id="housemate_name" value="{{ $list[0]->housemate_name }}">
                                            <div class="invalid-feedback" id ="housemate_name_error"></div>
                                        </div>

                                        <!-- 同居人カナ -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>同居人カナ
                                            <input type="text" class="form-control" name="housemate_ruby" id="housemate_ruby" value="{{ $list[0]->housemate_ruby }}">
                                            <div class="invalid-feedback" id ="housemate_ruby_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 性別 -->
                                        <div class="col-4 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>性別
                                            <select class="form-select" name="housemates_sex_id" id="housemates_sex_id" aria-label=".form-select_application_types">
                                                <option></option>    
                                                <option value="1" @if($list[0]->housemates_sex_id=='1') selected @endif>男</option>
                                                <option value="2" @if($list[0]->housemates_sex_id=='2') selected @endif>女</option>
                                            </select>
                                            <div class="invalid-feedback" id ="housemates_sex_id_error"></div>
                                        </div>

                                        <!-- 続柄 -->
                                        <div class="col-4 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>続柄
                                                <select class="form-select" name="housemate_relationship_id" id="housemate_relationship_id" aria-label=".form-select_application_types">
                                                <option></option>
                                                @foreach($list_relation as $relations)
                                                    <option value="{{$relations->relationship_id}}" @if($list[0]->housemates_relationship_id == $relations->relationship_id) selected @endif>{{ $relations->relationship_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id ="housemate_relationship_id_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 生年月日 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>生年月日
                                            <input type="text" class="form-control" name="housemate_birthday" id="housemate_birthday" value="{{ $list[0]->housemate_name_birthday }}">
                                            <div class="invalid-feedback" id ="housemate_birthday_error"></div>
                                        </div>

                                        <!-- 年齢 -->
                                        <div class="col-4 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>年齢
                                            <input type="number" class="form-control" name="housemate_age" id="housemate_age" value="{{ $list[0]->housemate_age }}" style="text-align:right">
                                            <div class="invalid-feedback" id ="housemate_age_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 郵便番号 -->
                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>郵便番号
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="housemate_post_number" id="housemate_post_number" value="{{ $list[0]->housemate_post_number }}">
                                                <button id="housemates-btn-zip" class="btn btn-outline-primary btn_zip">検索</button>
                                                <div class="invalid-feedback" id="post_error">
                                                    郵便番号は必須です。
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 住所 -->
                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>住所
                                            <input type="text" class="form-control" name="housemate_address" id="housemate_address" value="{{ $list[0]->housemate_address }}">
                                            <div class="invalid-feedback" id ="housemate_address_error"></div>
                                        </div>

                                        <!-- 自宅電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>自宅Tel
                                            <input type="text" class="form-control" name="housemate_home_tel" id="housemate_home_tel" value="{{ $list[0]->housemate_home_tel }}">
                                            <div class="invalid-feedback" id ="housemate_home_tel_error"></div>
                                        </div>

                                        <!-- 携帯電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>携帯Tel
                                            <input type="text" class="form-control" name="housemate_mobile_tel" id="housemate_mobile_tel" value="{{ $list[0]->housemate_mobile_tel }}">
                                            <div class="invalid-feedback" id ="housemate_mobile_tel_error"></div>
                                        </div>

                                    </div>
                                </div>
                                <!-- 同居人 -->

                                <!-- 緊急連絡先 -->
                                <div class="tab-pane fade" id="nav-emergency" role="tabpanel" aria-labelledby="nav-emergency-tab">
                                    <div class="row row-cols-2">

                                        <!-- 緊急連絡先 -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>緊急連絡先
                                            <input type="text" class="form-control" name="emergency_contacts_name" id="emergency_contacts_name" value="{{ $list[0]->emergency_contacts_name }}">
                                            <div class="invalid-feedback" id ="emergency_contacts_name_error"></div>
                                        </div>

                                        <!-- 緊急連絡先カナ -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>緊急連絡先カナ
                                            <input type="text" class="form-control" name="emergency_contacts_ruby" id="emergency_contacts_ruby" value="{{$list[0]->emergency_contacts_ruby}}">
                                            <div class="invalid-feedback" id ="emergency_contacts_ruby_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 性別 -->
                                        <div class="col-4 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>性別
                                            <select class="form-select" name="emergency_contacts_sex_id" id="emergency_contacts_sex_id" aria-label=".form-select_application_types">
                                                <option selected></option>
                                                <option value="1" @if($list[0]->emergency_contacts_sex_id=='1') selected @endif>男</option>
                                                <option value="2" @if($list[0]->emergency_contacts_sex_id=='2') selected @endif>女</option>
                                            </select>
                                            <div class="invalid-feedback" id ="emergency_contacts_sex_id_error"></div>
                                        </div>

                                        <!-- 続柄 -->
                                        <div class="col-4 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>続柄
                                            <select class="form-select" name="emergency_contacts_relationships_id" id="emergency_contacts_relationships_id" aria-label=".form-select_application_types">
                                                <option></option>
                                                @foreach($list_relation as $relations)
                                                    <option value="{{$relations->relationship_id}}" @if($list[0]->emergency_contacts_relationships_id == $relations->relationship_id) selected @endif>{{ $relations->relationship_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id ="emergency_contacts_relationships_id_error"></div>
                                        </div>
                                        
                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 生年月日 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>生年月日
                                            <input type="text" class="form-control" name="emergency_contacts_birthday" id="emergency_contacts_birthday" value="{{ $list[0]->emergency_birthday }}">
                                            <div class="invalid-feedback" id ="emergency_contacts_birthday_error"></div>
                                        </div>

                                        <!-- 年齢 -->
                                        <div class="col-4 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>年齢
                                            <input type="number" class="form-control" name="emergency_contract_age" id="emergency_contract_age" value="{{ $list[0]->emergency_age }}" style="text-align:right">
                                            <div class="invalid-feedback" id ="emergency_contract_age_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>
                                        
                                        <!-- 郵便番号 -->
                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>郵便番号
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="emergency_contacts_post_number" id="emergency_contacts_post_number" value="{{ $list[0]->emergency_post_number }}">
                                                <button id="emergency_contacts-btn-zip" class="btn btn-outline-primary btn_zip">検索</button>
                                                <div class="invalid-feedback" id="post_error">
                                                郵便番号は必須です。
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 住所 -->
                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>住所
                                            <input type="text" class="form-control" name="emergency_contacts_post_address" id="emergency_contacts_post_address" value="{{ $list[0]->emergency_address }}">
                                            <div class="invalid-feedback" id ="emergency_contacts_post_address_error"></div>
                                        </div>

                                        <!-- 自宅電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>自宅Tel
                                            <input type="text" class="form-control" name="emergency_contacts_home_tel" id="emergency_contacts_home_tel" value="{{ $list[0]->emergency_home_tel }}">
                                            <div class="invalid-feedback" id ="emergency_contacts_home_tel_error"></div>
                                        </div>

                                        <!-- 携帯電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>携帯Tel
                                            <input type="text" class="form-control" name="emergency_contacts_mobile_tel" id="emergency_contacts_mobile_tel" value="{{ $list[0]->emergency_mobile_tel }}">
                                            <div class="invalid-feedback" id ="emergency_contacts_mobile_tel_error"></div>
                                        </div>

                                    </div>
                                </div>
                                <!-- 緊急連絡先 -->
                                
                                <!-- 連帯保証人 -->
                                <div class="tab-pane fade" id="nav-guarantor" role="tabpanel" aria-labelledby="nav-guarantor-tab">
                                    <div class="row row-cols-2">

                                        <!-- 連帯保証人名 -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>連帯保証人
                                            <input type="text" class="form-control" name="guarantor_name" id="guarantor_name" value="{{ $list[0]->guarantor_name }}">
                                            <div class="invalid-feedback" id ="guarantor_name_error"></div>
                                        </div>

                                        <!-- 連帯保証人カナ -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>連帯保証人カナ
                                            <input type="text" class="form-control" name="guarantor_ruby" id="guarantor_ruby" value="{{ $list[0]->guarantor_ruby }}">
                                            <div class="invalid-feedback" id ="guarantor_ruby_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 性別 -->
                                        <div class="col-4 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>性別
                                            <select class="form-select" name="guarantor_sex_id" id="guarantor_sex_id" value="{{ $list[0]->guarantors_sex_name }}" aria-label=".form-select_application_types">
                                                <option></option>
                                                <option value="1" @if($list[0]->guarantors_sex_id=='1') selected @endif>男</option>
                                                <option value="2" @if($list[0]->guarantors_sex_id=='2') selected @endif>女</option>
                                            </select>
                                            <div class="invalid-feedback" id ="guarantor_sex_id_error"></div>
                                        </div>

                                        <!-- 続柄 -->
                                        <div class="col-4 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>続柄
                                            <select class="form-select" name="guarantors_relationship_id" id="guarantors_relationship_id" value="{{ $list[0]->guarantors_relationships_name }}" aria-label=".form-select_application_types">
                                                <option></option>
                                                @foreach($list_relation as $relations)
                                                    <option value="{{$relations->relationship_id}}" @if($list[0]->guarantors_relationship_id == $relations->relationship_id) selected @endif>{{$relations->relationship_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id ="guarantors_relationship_id_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 生年月日 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>生年月日
                                            <input type="text" class="form-control" name="guarantor_birthday" id="guarantor_birthday" value="{{ $list[0]->guarantor_birthday }}">
                                            <div class="invalid-feedback" id ="guarantor_birthday_error"></div>
                                        </div>

                                        <!-- 年齢 -->
                                        <div class="col-4 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>年齢
                                            <input type="number" class="form-control" name="guarantor_age" id="guarantor_age" value="{{ $list[0]->guarantor_age }}" style="text-align:right">
                                            <div class="invalid-feedback" id ="guarantor_age_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 郵便番号 -->
                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>郵便番号
                                            <div class="input-group">
                                            <input type="number" class="form-control" name="guarantor_post_number" id="guarantor_post_number" value="{{ $list[0]->guarantor_post_number }}">
                                            <button id="guarantors-btn-zip" class="btn btn-outline-primary btn_zip">検索</button>
                                                <div class="invalid-feedback" id="post_error">
                                                    郵便番号は必須です。
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 住所 -->
                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>住所
                                            <input type="text" class="form-control" name="guarantor_address" id="guarantor_address" value="{{ $list[0]->contract_address }}">
                                            <div class="invalid-feedback" id ="guarantor_address_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 自宅電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>自宅Tel
                                            <input type="text" class="form-control" name="guarantor_home_tel" id="guarantor_home_tel" value="{{ $list[0]->guarantor_home_tel }}">
                                            <div class="invalid-feedback" id ="guarantor_home_tel_error"></div>
                                        </div>

                                        <!-- 携帯電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>携帯Tel
                                            <input type="text" class="form-control" name="guarantor_mobile_tel" id="guarantor_mobile_tel" value="{{ $list[0]->guarantor_mobile_tel }}">
                                            <div class="invalid-feedback" id ="guarantor_mobile_tel_error"></div>
                                        </div>

                                        <!-- 勤務先名 -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>勤務先名
                                            <input type="text" class="form-control" name="guarantor_work_place_name" id="guarantor_work_place_name" value="{{ $list[0]->guarantor_work_place_name }}">
                                            <div class="invalid-feedback" id ="guarantor_work_place_name_error"></div>
                                        </div>

                                        <!-- 勤務先フリガナ -->
                                        <div class="col-12 col-md-12 col-lg-10 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>フリガナ
                                            <input type="text" class="form-control" name="guarantor_work_place_ruby" id="guarantor_work_place_ruby" value="{{ $list[0]->guarantor_work_place_ruby }}">
                                            <div class="invalid-feedback" id ="guarantor_work_place_ruby_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 郵便番号 -->
                                        <div class="col-6 col-md-3 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>郵便番号
                                            <div class="input-group">
                                            <input type="number" class="form-control" name="guarantor_work_place_post_number" id="guarantor_work_place_post_number" value="{{ $list[0]->guarantor_work_place_post_number }}">
                                            <button id="guarantor_work_place-btn-zip" class="btn btn-outline-primary btn_zip">検索</button>
                                                <div class="invalid-feedback" id="post_error">
                                                    郵便番号は必須です。
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 勤務先住所 -->
                                        <div class="col-12 col-md-12 col-lg-11 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>所在地
                                            <input type="text" class="form-control" name="guarantor_work_place_address" id="guarantor_work_place_address" value="{{ $list[0]->guarantor_work_place_address }}">
                                            <div class="invalid-feedback" id ="guarantor_work_place_address_error"></div>
                                        </div>

                                        <!-- 勤務先電話番号 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>勤務先Tel
                                            <input type="text" class="form-control" name="guarantor_work_place_tel" id="guarantor_work_place_tel" value="{{ $list[0]->guarantor_work_place_tel }}">
                                            <div class="invalid-feedback" id ="guarantor_work_place_tel_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 業種 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>業種
                                            <input type="text" class="form-control" name="guarantor_work_place_Industry" id="guarantor_work_place_Industry" value="{{ $list[0]->guarantor_work_place_Industry }}">
                                            <div class="invalid-feedback" id ="guarantor_work_place_Industry_error"></div>
                                        </div>

                                        <!-- 職種 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>職種
                                            <input type="text" class="form-control" name="guarantor_work_place_occupation" id="guarantor_work_place_occupation" value="{{ $list[0]->guarantor_work_place_occupation }}">
                                            <div class="invalid-feedback" id ="guarantor_work_place_occupation_error"></div>
                                        </div>

                                        <!-- 雇用形態 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>雇用形態
                                            <input type="text" class="form-control" name="guarantor_status" id="guarantor_status" value="{{ $list[0]->guarantor_status }}">
                                            <div class="invalid-feedback" id ="guarantor_status_error"></div>
                                        </div>

                                        <!-- 勤続年数 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>勤続年数
                                            <input type="number" class="form-control" name="guarantor_work_place_years" id="guarantor_work_place_years" value="{{ $list[0]->guarantor_work_place_years }}" style="text-align:right">
                                            <div class="invalid-feedback" id ="guarantor_work_place_years_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 年収 -->
                                        <div class="col-6 col-md-12 col-lg-2 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>年収
                                            <input type="number" class="form-control" name="guarantor_annual_income" id="guarantor_annual_income" value="{{ $list[0]->guarantor_annual_income }}" style="text-align:right">
                                            <div class="invalid-feedback" id ="guarantor_annual_income_error"></div>
                                        </div>

                                        <!-- 健康保険 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label class="label_Any mb-2" for="textBox"></label>健康保険
                                            <select class="form-select" name="guarantor_insurance_type_id" id="guarantor_insurance_type_id" aria-label=".form-select_application_types">
                                                <!-- タグ内に値を追加、値追加後同一の場合選択する -->
                                                <option value=""></option>
                                                @foreach($list_insurance as $insurances)
                                                    <option value="{{ $insurances->insurance_type_id}}" @if($list[0]->guarantor_insurance_type_id == $insurances->insurance_type_id) selected @endif>{{$insurances->insurance_type_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id ="guarantor_insurance_type_id_error"></div>
                                        </div>

                                    </div>
                                </div>
                                <!-- 連帯保証人 -->

                                <!-- 添付書類 -->
                                <div class="tab-pane fade" id="nav-document" role="tabpanel" aria-labelledby="nav-document-tab">
                                    <div class="row row-cols-3">

                                        <!-- 添付書類 -->
                                        <div class="col-12 col-md-12 col-lg-12 mt-4">
                                            <input class="form-control" type="file" id="file_img">
                                            <div class="invalid-feedback" id ="file_img_error"></div>
                                        </div>

                                        <!-- 種別 -->
                                        <div class="col-6 col-md-12 col-lg-3 mt-3">
                                            <label for="">種別</label>
                                            <select class="form-select" name="file_img_type" id="file_img_type" aria-label=".form-select_application_types">
                                                <option selected></option>
                                                @foreach(Common::$FILE_IMG_TYPE as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id ="file_img_type_error"></div>
                                        </div>

                                        <!-- 改行 -->
                                        <div class="w-100"></div>

                                        <!-- 補足 -->
                                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                                            <label for="">補足</label>
                                            <textarea class="form-control" name="file_img_type_textarea" id="file_img_type_textarea" rows="5"></textarea>
                                            <div class="invalid-feedback" id ="file_img_type_textarea_error"></div>
                                        </div>
                                        
                                        <!-- 添付画像スライドショー -->
                                        <div class="col-12 col-md-12 col-lg-7 mt-3 mx-auto">
                                            <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
                                                
                                                <!-- 画像遷移のアンダーバー -->
                                                <ol class="carousel-indicators">
                                                    @for ($i = 0; $i < count($list_img); $i++)
                                                        @php
                                                            $active = '';
                                                            if($i == 0){
                                                                $active = 'active';
                                                            }
                                                        @endphp
                                                        <li data-bs-target="#carouselExampleDark" data-bs-slide-to="{{ $i }}" class="{{ $active }}"></li>    
                                                    @endfor
                                                </ol>

                                                <div class="carousel-inner">
                                                    <!-- index=foreach番号 -->
                                                    @foreach($list_img as $index => $data)
                                                        <div class="carousel-item @if($index==0) active @endif" data-bs-interval="5000">
                                                            <div class="container mt-3">
                                                                <div class="row row-cols-1">
                                                                    <div class="col-12 col-md-12 col-lg-12">
                                                                        <div class="card shadow-sm">
                                                                            <!-- 画像 -->
                                                                            <img src="../storage/{{ $data->img_path }}" class="card-img-top">

                                                                            <div class="card-body">
                                                                                    
                                                                                <p class="card-text">種別: 
                                                                                    @if($data->img_type != "")
                                                                                        {{ Common::$FILE_IMG_TYPE[$data->img_type] }}
                                                                                    @endif
                                                                                </p>

                                                                                <!-- 内容 -->
                                                                                <p class="card-text">補足:{{ $data->img_memo }}</p>
                                                                                <!-- 編集ボタン -->
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <div class="btn-group">
                                                                                        <button type="button" id="btn_{{ $data->img_id }}" class="btn btn-outline-danger btn_delete_detail">削除</button>
                                                                                    </div>
                                                                                    <!-- 削除ボタン -->
                                                                                    <div class="btn-group">
                                                                                        <button type="button" id="btn_{{ $data->img_id }}" class="btn btn-outline-primary btn_img_edit">編集</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach   
                                                </div>

                                                <!-- 画像がない場合は、左右の画像遷移ボタンを表示しない -->
                                                @if(count($list_img) !== 0)
                                                    <!-- 画像遷移ボタン左右 -->
                                                    <a class="carousel-control-prev" href="#carouselExampleDark" role="button" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </a>
                                                    <a class="carousel-control-next" href="#carouselExampleDark" role="button" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </a>
                                                @endif
                                            </div><!-- 添付画像スライドショー -->
                                            
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <!-- 内容 -->
                        </div>
                    </div>
                    <!-- タブ内のコンテンツ -->
                </div>
                <!-- カード -->

                <!-- 境界線 -->
                <hr>
                <!-- 境界線 -->
                
                <!-- 下段ボタン -->
                <div class="row row-cols-2 mb-5">

                    <!-- 削除ボタン -->
                    <div class="col-6 col-md-6 col-lg-6 mt-3">
                        @if($application_Flag=="false")
                            <button id="btn_delete" class="btn btn-outline-danger btn-default">削除</button>
                        @endif
                    </div>
                    <!-- 削除ボタン -->
                    
                    <!-- 編集・帳票作成 -->
                    <div class="col-6 col-md-6 col-lg-6 mt-3">
                        <div class="btn-group float-end" role="group">
                            <!-- 登録ボタン -->
                            <button id="btn_edit" class="btn btn-outline-primary btn-default">登録</button>
                            
                            <!-- プルダウンボタン -->
                            <div class="btn-group" role="group">
                                <button type="button" id="btnGroupDrop1" class="btn btn-outline-primary dropdown-toggle btn-default" data-bs-toggle="dropdown" aria-expanded="false">
                                帳票
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item" href="#">申込書</a></li>
                                    <li><a class="dropdown-item" href="#">全保連</a></li>
                                    <li><a class="dropdown-item" href="#">JRAG</a></li>
                                    <li><a class="dropdown-item" href="#">日本セーフティー</a></li>
                                </ul>
                            </div>
                            <!-- プルダウンボタン -->     
                        </div>
                    </div>
                    <!-- 編集・帳票作成 -->

                </div>     
                <!-- 下段ボタン -->

            </div>
            <!-- コンテナ -->

            <!-- id設定 -->
            <!-- 不動産業者Id -->
            <input type="hidden" name="application_form_id" id="application_form_id" value="{{ $list[0]->application_form_id }}">
            <!-- 賃借人Id -->
            <input type="hidden" name="contract_id" id="contract_id" value="{{ $list[0]->contract_id }}">
            <!-- 同居人Id -->
            <input type="hidden" name="housemate_id" id="housemate_id" value="{{ $list[0]->housemate_id }}">
            <!-- 緊急連絡先Id -->
            <input type="hidden" name="emergency_contact_id" id="emergency_contact_id" value="{{ $list[0]->emergency_contact_id }}">
            <!-- 保証人Id -->
            <input type="hidden" name="guarantor_contracts_id" id="guarantor_contracts_id" value="{{ $list[0]->guarantor_contracts_id }}">
            <!-- URLからの場合true/URLからでない場合false -->
            <input type="hidden" name="application_Flag" id="application_Flag" value="{{ $application_Flag }}">
            <!-- create_user_id -->
            <input type="hidden" name="session_id" id="session_id" value="{{ $session_id }}">
            <!-- url_send_flag -->
            <input type="hidden" name="url_send_flag" id="url_send_flag" value="{{ $list[0]->url_send_flag }}">
        </form>
        <!-- form -->

        <!-- フッダー-->
        @component('component.footer')
        @endcomponent

        <!-- main.blede.phpのリンク先指定 -->
        <input type="hidden" id="main_url" value="{{ url('mainInit') }}" />

        <input type="hidden" id="edit_url" value="{{ url('editInit') }}" />

        <!-- js -->
        @component('component.js')
        @endcomponent
		
        <!-- edit -->
        <script src="{{ asset('edit/js/edit.js') }}"></script>
    </body>
</html>