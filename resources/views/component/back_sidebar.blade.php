<a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
</a>

<!-- sidebar-wrapper  -->
<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">

        <!-- サイドメニュータイトル -->
        <div class="sidebar-brand">
            <a href="#">KASEGU Ver 1.05</a>
            <div id="close-sidebar">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <!-- サイドメニュータイトル -->

        <div class="sidebar-header">

            <div class="user-pic">
            <img class="img-responsive img-rounded" src="./icon/kasegu_icon_64.ico"
                alt="User picture">
            </div>

            <div class="user-info">

                <span class="user-name">
                    {{ Session::get('create_user_name') }}
                </span>

                <span class="user-role">
                    Members
                </span>

                <span class="user-status">
                    <i class="fa fa-circle"></i>
                    <span>Online</span>
                </span>
                
            </div>

        </div>
        <!-- sidebar-header  -->


        <!-- sidebar-menu  -->
        <div class="sidebar-menu">
            <!-- 親要素ul -->
            <ul>
                <li>
                    <a href="backHomeInit">
                        <i class="fas fa-laptop-house"></i>
                        <span>ホーム</span>
                    </a>
                </li>

                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fas fa-id-card"></i>
                        <span>申込管理</span>
                        <span class="badge badge-pill badge-danger"></span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="backAppInit">申込一覧</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fas fa-key"></i>
                        <span>契約管理</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="backContractInit">契約一覧</a>
                            </li>
                            <li>
                                <a href="adminAppInit">取引台帳</a>
                            </li>
                            <li>
                                <a href="backSpecialContractInit">特約事項</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fas fa-file"></i>
                        <span>ファイル管理</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="adminUserInit">ファイル一覧</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="far fa-gem"></i>
                        <span>設定</span>
                        <span class="badge badge-pill badge-danger"></span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="backUserInit">アカウント情報</a>
                            </li>
                            <li>
                                <a href="backLegalPlaceInit">法務局</a>
                            </li>
                            <li>
                                <a href="backGuarantyAssociationInit">不動産保証協会</a>
                            </li>
                            <li>
                                <a href="backUserLicenseInit">宅地建物取引士</a>
                            </li>
                            <li>
                                <a href="backBankInit">家賃振込先</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fas fa-toolbox"></i>
                        <span>各種サービス</span>
                        <span class="badge badge-pill badge-danger"></span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="#">書式ダウンロード</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#">
                        <i class="fas fa-comment-dots"></i>
                        <span>メッセージ</span>
                    </a>
                </li>

            </ul>
            <!-- 親要素ul -->
        </div>
        <!-- sidebar-menu  -->

    </div>

    <!-- sidebar-content(下段)  -->
    <div class="sidebar-footer">
        <!-- お知らせ -->
        <a href="#">
            <i class="fa fa-bell"></i>
            <span class="position-absolute top-0 start-90 badge rounded-pill bg-warning text-dark">3</span>
        </a>

        <!-- メッセージ -->
        <a href="#">
            <i class="fa fa-envelope"></i>
            <span class="position-absolute top-0 start-90 badge rounded-pill bg-success">7</span>
        </a>

        <!-- 設定 -->
        <a href="#">
            <i class="fa fa-cog"></i>
            <span class="badge-sonar"></span>
        </a>

        <!-- ログアウト -->
        <a href="logOut">
            <i class="fa fa-power-off"></i>
        </a>
    </div>
    <!-- sidebar-content(下段)  -->
</nav>
<!-- sidebar-wrapper  -->