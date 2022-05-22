<a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
</a>

<!-- sidebar-wrapper  -->
<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">

        <!-- サイドメニュータイトル -->
        <div class="sidebar-brand">
            <!-- version -->
            @component('component.back_version')
            @endcomponent
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
                    Administrator
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
                    <a href="adminHomeInit">
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
                                <a href="adminAppInit">申込一覧</a>
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
                                <a href="adminContractInit">契約一覧</a>
                            </li>
                            <li>
                                <a href="">取引台帳</a>
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
                        <i class="fas fa-user"></i>
                        <span>アカウント管理</span>
                        <span class="badge badge-pill badge-danger"></span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="adminUserInit">アカウント一覧</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fas fa-chart-line"></i>
                        <span>ライフライン管理</span>
                        <span class="badge badge-pill badge-danger"></span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="#">ライフライン一覧</a>
                                <a href="#">提携業者一覧</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fas fa-bell"></i>
                        <span>新着情報</span>
                        <span class="badge badge-pill badge-danger"></span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="adminInformationInit">新着情報一覧</a>
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
                                <a href="adminConfigUserInit">アカウント情報</a>
                            </li>
                            <li>
                                <a href="adminConfigLegalPlaceInit">法務局</a>
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