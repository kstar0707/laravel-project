<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
    <!-- Title Page-->
    <title>Greeme</title>

    <!-- Fontfaces CSS-->
    <link href="<?= asset("css/font-face.css") ?>" rel="stylesheet" media="all">
    <link href="<?= asset("vendor/font-awesome-4.7/css/font-awesome.min.css") ?>" rel="stylesheet" media="all">
    <link href="<?= asset("vendor/font-awesome-5/css/fontawesome-all.min.css") ?>" rel="stylesheet" media="all">
    <link href="<?= asset("vendor/mdi-font/css/material-design-iconic-font.min.css") ?>" rel="stylesheet" media="all">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">

    <!-- Bootstrap CSS-->
    <link href="<?= asset("vendor/bootstrap-4.1/bootstrap.min.css") ?>" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?= asset("vendor/animsition/animsition.min.css") ?>" rel="stylesheet" media="all">
    <link href="<?= asset("vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css") ?>" rel="stylesheet" media="all">
    <link href="<?= asset("vendor/wow/animate.css") ?>" rel="stylesheet" media="all">
    <link href="<?= asset("vendor/css-hamburgers/hamburgers.min.css") ?>" rel="stylesheet" media="all">
    <link href="<?= asset("vendor/slick/slick.css") ?>" rel="stylesheet" media="all">
    <!-- <link href="<?= asset("vendor/select2/select2.min.css") ?>" rel="stylesheet" media="all"> -->
    <link href="<?= asset("vendor/perfect-scrollbar/perfect-scrollbar.css") ?>" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <!-- Main CSS-->
    <link href="<?= asset("css/theme.css") ?>" rel="stylesheet" media="all">


    <script>
        var asset_url = "<?= asset("") ?>";
    </script>

</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- END HEADER MOBILE-->
        @include('admin.layout.sidebar')
        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">

                            </form>
                            <div class="header-button">
                                <div class="noti-wrap">
                                    {{-- <div class="noti__item js-item-menu">
                                        <i class="zmdi zmdi-notifications"></i>
                                        <span class="quantity">3</span>
                                        <div class="notifi-dropdown js-dropdown">
                                            <div class="notifi__title">
                                                <p>You have 3 Notifications</p>
                                            </div>
                                            <div class="notifi__item">
                                                <div class="bg-c1 img-cir img-40">
                                                    <i class="zmdi zmdi-email-open"></i>
                                                </div>
                                                <div class="content">
                                                    <p>You got a email notification</p>
                                                    <span class="date">April 12, 2018 06:50</span>
                                                </div>
                                            </div>
                                            <div class="notifi__item">
                                                <div class="bg-c2 img-cir img-40">
                                                    <i class="zmdi zmdi-account-box"></i>
                                                </div>
                                                <div class="content">
                                                    <p>Your account has been blocked</p>
                                                    <span class="date">April 12, 2018 06:50</span>
                                                </div>
                                            </div>
                                            <div class="notifi__item">
                                                <div class="bg-c3 img-cir img-40">
                                                    <i class="zmdi zmdi-file-text"></i>
                                                </div>
                                                <div class="content">
                                                    <p>You got a new file</p>
                                                    <span class="date">April 12, 2018 06:50</span>
                                                </div>
                                            </div>
                                            <div class="notifi__footer">
                                                <a href="#">All notifications</a>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">
                                            @if(session('userInfo'))
                                                {{ session('userInfo')->name }}
                                            @endif</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                    <h5 class="name">
                                                        <a href="#">{{ session('userInfo')->name }}</a>
                                                    </h5>
                                                    <span class="email">{{ session('userInfo')->email }}</span>
                                            </div>
                                            <div class="account-dropdown__body">
                                                {{-- <div class="account-dropdown__item">
                                                    <a href="#" data-toggle="modal" id="changeModal">
                                                        <i class="zmdi zmdi-account"></i>Change Password</a>
                                                </div> --}}
                                                {{-- <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-money-box"></i>Billing</a>
                                                </div> --}}
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="/logout">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->
            @yield('main-content')
        </div>
    </div>

    @include('admin.layout.footer')

