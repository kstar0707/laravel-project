
    <!-- MENU SIDEBAR-->
<aside class="menu-sidebar d-none d-lg-block">
    <div class="logo">
        <a href="#">
            <h3>ダービードリーム</h3>
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                <li class="active">
                    <a href="/dashboard">
                        <i class="fas fa-chart-bar"></i>ダッシュボード</a>
                </li>
                <li>
                    <a href="/identify"><i class="fa fa-user"></i>本人確認</a>
                </li>
                <li>
                    <a class="js-arrow" href="#">
                        <i class="fas fa-database"></i>ベースデータ</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li>
                            <a href="/residence">居住地</a>
                        </li>
                        <li>
                            <a href="/community">コミュニティ</a>
                        </li>
                        <li>
                            <a href="/category">コミュニティカテゴリー</a>
                        </li>
                        <li>
                            <a href="/bodytype">体型</a>
                        </li>
                        <li>
                            <a href="/usepurpose">利用目的</a>
                        </li>
                        <li>
                            <a href="/introbadge">紹介バッジ</a>
                        </li>
                        {{-- <li>
                            <a href="/paidplantype">有料プランタイプ</a>
                        </li> --}}
                    </ul>
                </li>
                <li>
                    <a href="/customer">
                        <i class="fa fa-user"></i>ユーザー</a>
                </li>
                <li>
                    <a href="/admin">
                        <i class="fa fa-user"></i>マネージャー</a>
                </li>
                {{-- <li>
                    <a href="<?= route('todayrecomm')?>">
                        <i class="fa fa-thumbs-up"></i>本日のおすすめ</a>
                </li> --}}
                <li>
                    <a class="js-arrow" href="#">
                        <i class="fas fa-solid fa-comment"></i>ボード</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li>
                            <a href="/actboard">自分ボード</a>
                        </li>
                        <li>
                            <a href="/resboard">応答ボード</a>
                        </li>
                    </ul>
                </li>
                {{-- <li>
                    <a href="<?= route('matching')?>">
                        <i class="fa fa-heart"></i>マッチング中</a>
                </li> --}}
                <li>
                    <a href="/violation">
                        <i class="fa fa-exclamation-triangle "></i>違反報告</a>
                </li>
                <li>
                    <a href="<?= route('message')?>">
                        <i class="fas fa-solid fa-comment "></i>メッセージ</a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<!-- END MENU SIDEBAR-->

