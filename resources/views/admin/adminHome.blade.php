<!DOCTYPE html>
<html lang="ja">

	<head>
		<title>ホーム/ADMIN</title>

		<!-- head -->
		@component('component.admin_head')
		@endcomponent

		<!-- 自作css -->
		<link rel="stylesheet" href="{{ asset('admin/css/admin_home.css') }}">  
		
        <style>

            /* ボタンデフォルト値 */
            .btn-default{
                width: 5rem;
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

                <!-- dashboard -->
                <div class="container">

                    <div class="info_title mt-3">
                        <i class="fas fa-clock icon_blue me-2"></i>Dashboard
                    </div>
                
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12 mt-3">

                            <div class="row">
                                <!-- ボックス1  -->
                                <div class="col-12 col-md-12 col-lg-3 dashboard_box">
                                    <div class="row">
                                        <!-- 子要素cssで95%に設定し、mx-autoで中央に配置 -->
                                        <div class="col-12 col-md-12 col-lg-12 dashboard_box_inner_1 mx-auto">
                                            <div class="row">

                                                <div class="col-12 col-md-12 col-lg-12 pt-5">
                                                    <span class="dashboard_box_title"><i class="fas fa-laptop me-2"></i></i>アクセス</span> 
                                                </div>
                                                
                                                <div class="col-12 col-md-12 col-lg-12 pt-2">
                                                    本日: <span class="count dashboard_box_num">{{ $access_list_today->accesses_count_today }}</span>
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-12">
                                                    合計: <span class="count dashboard_box_num">{{ $access_list->accesses_count }}</span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ボックス1 -->

                                <!-- ボックス2 -->
                                <div class="col-12 col-md-12 col-lg-3 dashboard_box">
                                    <div class="row">
                                        <!-- 子要素cssで95%に設定し、mx-autoで中央に配置 -->
                                        <div class="col-12 col-md-12 col-lg-12 dashboard_box_inner_2 mx-auto">
                                            <div class="row">

                                                <div class="col-12 col-md-12 col-lg-12 pt-5">
                                                    <span class="dashboard_box_title"><i class="fas fa-user me-2"></i>アカウント</span> 
                                                </div>
                                                
                                                <div class="col-12 col-md-12 col-lg-12 pt-2">
                                                    アカウント数: <span class="count dashboard_box_num">{{ $user_list->user_count }}</span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ボックス2  -->

                                <!-- ボックス3 -->
                                <div class="col-12 col-md-12 col-lg-3 dashboard_box">
                                    <div class="row">
                                        <!-- 子要素cssで95%に設定し、mx-autoで中央に配置 -->
                                        <div class="col-12 col-md-12 col-lg-12 dashboard_box_inner_3 mx-auto">
                                            <div class="row">

                                            <div class="col-12 col-md-12 col-lg-12 pt-5">
                                                    <span class="dashboard_box_title"><i class="fas fa-id-card me-2"></i></i>申込管理</span> 
                                                </div>
                                                
                                                <div class="col-12 col-md-12 col-lg-12 pt-2">
                                                    契約手続中: <span class="count dashboard_box_num">1111111111111</span>
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-12">
                                                    契約件数: <span class="count dashboard_box_num">1111111111111</span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ボックス3 -->
                            
                                <!-- ボックス4 -->
                                <div class="col-12 col-md-12 col-lg-3 buruburu dashboard_box">
                                    <div class="row">
                                        <!-- 子要素cssで95%に設定し、mx-autoで中央に配置 -->
                                        <div class="col-12 col-md-12 col-lg-12 dashboard_box_inner_4 mx-auto">
                                            <div class="row">

                                                <div class="col-12 col-md-12 col-lg-12 pt-5">
                                                    <span class="dashboard_box_title"><i class="fas fa-key me-2"></i></i></i>契約管理</span> 
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-12 pt-2">
                                                    契約件数: <span class="count dashboard_box_num">1111111111111</span>
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-12">
                                                    キャンセル: <span class="count dashboard_box_num">1111111111111</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ボックス4 -->

                                <!-- ボックス5 -->
                                <div class="col-12 col-md-12 col-lg-3 dashboard_box">
                                    <div class="row">
                                        <!-- 子要素cssで95%に設定し、mx-autoで中央に配置 -->
                                        <div class="col-12 col-md-12 col-lg-12 dashboard_box_inner_5 mx-auto">
                                            <div class="row">
                                                <div class="col-12 col-md-12 col-lg-12 pt-5">
                                                    <span class="dashboard_box_title"><i class="fas fa-file me-2"></i>ファイル</span> 
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-12 pt-2">
                                                    ファイル数: <span class="count dashboard_box_num">1111111111</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ボックス5 -->

                                <!-- ボックス6 -->
                                <div class="col-12 col-md-12 col-lg-3 dashboard_box">
                                    <div class="row">
                                        <!-- 子要素cssで95%に設定し、mx-autoで中央に配置 -->
                                        <div class="col-12 col-md-12 col-lg-12 dashboard_box_inner_6 mx-auto">
                                            <div class="row">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ボックス6 -->

                                <!-- ボックス7 -->
                                <div class="col-12 col-md-12 col-lg-3 dashboard_box">
                                    <div class="row">
                                        <!-- 子要素cssで95%に設定し、mx-autoで中央に配置 -->
                                        <div class="col-12 col-md-12 col-lg-12 dashboard_box_inner_7 mx-auto">
                                            <div class="row">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ボックス7 -->

                                <!-- ボックス8 -->
                                <div class="col-12 col-md-12 col-lg-3 dashboard_box">
                                    <div class="row">
                                        <!-- 子要素cssで95%に設定し、mx-autoで中央に配置 -->
                                        <div class="col-12 col-md-12 col-lg-12 dashboard_box_inner_8 mx-auto">
                                            <div class="row">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ボックス8 -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- dashboard -->

                <!-- お知らせ -->
                <div class="container mb-4 mt-3">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="overflow-auto" style="max-height:30rem;">
                                <div class="table-responsive">
                                    
                                    <span class="info_title">
                                        <i class="fas fa-bell icon_blue me-2"></i>Information
                                    </span>
                                    <hr class="info_hr">

                                    <table class="table table-hover table-condensed table-striped">
                                        
                                        <thead>
                                            <tr>
                                                <th scope="col" style="display:none">id</th>
                                                <th></th>
                                                <th scope="col">タイトル</th>
                                                <th scope="col">種別</th>
                                                <th scope="col">内容</th>
                                                <th scope="col">登録日</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($res as $information)
                                                <tr>
                                                    <td td id="id_{{ $information->information_id }}" class="click_class" style="display:none"></td>
                                                    <td td id="cb_{{ $information->information_id }}" class="click_class"><input id="check_box" class="form-check-input btn_radio" type="radio" name="flexRadioDisabled"></td>
                                                    <td td id="title_{{ $information->information_id }}" class="click_class">{{ $information->information_title }}</td>
                                                    <td td id="title_{{ $information->information_id }}" class="click_class">{{ $information->information_type_name }}</td>
                                                    <td td id="contents_{{ $information->information_id }}" class="click_class">{{ $information->information_contents }}</td>
                                                    <td td id="date_{{ $information->information_id }}" class="click_class">{{ $information->update_date }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ぺージネーション -->   
                        <div id="links" style="display:none;" class="mt-3">
                            {{ $res->links() }}
                        </div>

                    </div>
                </div>

            </main>
            <!-- page-content" -->

		</div>
		<!-- page-wrapper -->

		@component('component.admin_js')
		@endcomponent

		<!-- 自作js -->
		<script src="{{ asset('admin/js/admin_home.js') }}"></script>
	</body>
	
</html>