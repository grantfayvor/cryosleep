<!DOCTYPE html>
<html data-ng-app="app.crypto">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cryosleep | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="plugins/pace/pace.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .fixed-side-bar {
            position: fixed;
            z-index: 1;
            top: 0;
            padding-top: 50px;
            width: 230px;
        }

        .fixed-header-main {
            position: fixed;
            z-index: 1000;
            top: 0;
            width: 100%;
            max-width: 1250px;
        }

        @media screen and (max-width: 760px) {
            .fixed-side-bar,
            .content-wrapper {
                padding-top: 100px;
            }
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini layout-boxed">
    <div class="wrapper">

        <header class="main-header fixed-header-main">

            <!-- Logo -->
            <a href="/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">
                    <b>CS</b>
                </span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">
                    <b>Cryosleep</b>
                </span>
            </a>

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="javascript:void(0)" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                                <span class="hidden-xs">{{ $user->username }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                    <p>
                                        {{ $user->full_name }}
                                        <small>Member since <span data-ng-bind="convertDate('{{ $user->created_at }}')"></span></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 text-center">
                                            Refer members using &nbsp;
                                            <input type="text" id="referralCode" class="form-control" readonly>
                                            <button type="button" onclick="copyReferralCode()" class="btn btn-default">
                                                <span class="fa fa-bookmark-o"></span>
                                            </button>
                                            <input type="hidden" id="hiddenReferral" value="{{ $user->referral_code }}">
                                            <input type="hidden" id="noOfReferrals" value="{{ $user->referrals }}">
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    {{--
                                    <div class="pull-left">
                                        <a href="javascript:void(0)" class="btn btn-default btn-flat">Profile</a>
                                    </div>--}}
                                    <div class="pull-right">
                                        <form method="POST" action="/logout">
                                            @csrf
                                            <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar fixed-side-bar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p> {{ $user->username }}</p>
                        <a href="javascript:;">
                            <i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li>
                        <a href="javascript:void(0);" ui-sref="dashboard" ui-sref-active="active">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="javascript:;">
                            <i class="fa fa-files-o"></i>
                            <span>Crypto Account</span>
                            <span class="pull-right-container">
                                <span class="fa fa-angle-left pull-right"></span>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="javascript:void(0);" ui-sref="new_crypto_account" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> New Crypto Account</a>
                            </li>
                            @if($user && $user->isAn('ADMIN'))
                            <li>
                                <a href="javascript:void(0);" ui-sref="view_crypto_accounts" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> View Crypto Accounts</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="javascript:;">
                            <i class="fa fa-laptop"></i>
                            <span>Deposits</span>
                            <span class="pull-right-container">
                                <span class="fa fa-angle-left pull-right"></span>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="javascript:void(0);" ui-sref="new_deposit" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> Transfer Funds</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ui-sref="view_deposits" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> My Transactions</a>
                            </li>
                            @if($user && $user->isAn('ADMIN'))
                            <li>
                                <a href="javascript:void(0);" ui-sref="view_all_deposits" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> View All Transactions</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="javascript:;">
                            <i class="fa fa-briefcase"></i>
                            <span>Withdrawals</span>
                            <span class="pull-right-container">
                                <span class="fa fa-angle-left pull-right"></span>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="javascript:void(0);" ui-sref="withdrawal_request" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> Make Request</a>
                            </li>
                            @if($user && $user->isAn('ADMIN'))
                            <li>
                                <a href="javascript:void(0);" ui-sref="approve_withdrawal" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> Approve Withdrawals</a>
                            </li>
                            @endif
                            <li>
                                <a href="javascript:void(0);" ui-sref="view_withdrawals" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> My Transactions</a>
                            </li>
                            @if($user && $user->isAn('ADMIN'))
                            <li>
                                <a href="javascript:void(0);" ui-sref="view_all_withdrawals" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> View All Transactions</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @if($user && $user->isAn('ADMIN'))
                    <li class="treeview">
                        <a href="javascript:;">
                            <i class="fa fa-th"></i>
                            <span>Transaction Plan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="javascript:void(0);" ui-sref="new_transaction_plan" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> New Transaction Plan</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ui-sref="view_transaction_plans" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> View Transaction Plans</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @if($user && $user->isAn('ADMIN'))
                    <li class="treeview">
                        <a href="javascript:;">
                            <i class="fa fa-edit"></i>
                            <span>Transaction Types</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="javascript:void(0);" ui-sref="new_transaction_type" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> New Transaction Type</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ui-sref="view_transaction_types" ui-sref-active="active">
                                    <i class="fa fa-circle-o"></i> View Transaction Types</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @if($user && $user->isAn('ADMIN'))
                        <li class="treeview">
                            <a href="javascript:;">
                                <i class="fa fa-user"></i>
                                <span>Users</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="javascript:void(0);" ui-sref="manage_users" ui-sref-active="active">
                                        <i class="fa fa-circle-o"></i> Manage Users</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <li>
                        <a href="javascript:void(0);" ui-sref="referrals" ui-sref-active="active">
                            <i class="fa fa-map-o"></i> <span> Referrals</span></a>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" ui-view>

        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            {{--
            <div class="pull-right hidden-xs">--}} {{--
                <b>Version</b> 2.3.6--}} {{--
            </div>--}}
            <strong>Copyright &copy; 2018
                <?php echo Date('Y') == 2018 ? '' : '-' . Date('Y'); ?>
                <a href="/">Cryosleep</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li>
                    <a href="#control-sidebar-home-tab" data-toggle="tab">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>
                    <a href="#control-sidebar-settings-tab" data-toggle="tab">
                        <i class="fa fa-gears"></i>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-user bg-yellow"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                    <p>New phone +1(800)555-1234</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                    <p>nora@example.com</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-file-code-o bg-green"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                    <p>Execution time 5 seconds</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="label label-danger pull-right">70%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Update Resume
                                    <span class="label label-success pull-right">95%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Laravel Integration
                                    <span class="label label-warning pull-right">50%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Back End Framework
                                    <span class="label label-primary pull-right">68%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->

                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Allow mail redirect
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Other sets of options are available
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Expose author name in posts
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Allow the user to show his name in blog posts
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <h3 class="control-sidebar-heading">Chat Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Show me as online
                                <input type="checkbox" class="pull-right" checked>
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Turn off notifications
                                <input type="checkbox" class="pull-right">
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label id="referralContainer" class="control-sidebar-subheading">
                                Delete chat history
                                <a href="javascript:void(0)" class="text-red pull-right">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                                <input type="text" id="referralInput">
                            </label>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 2.2.3 -->
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    {{--
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>--}} {{--
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>--}}
    <!-- SlimScroll 1.3.0 -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/pace/pace.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    {{--
    <script src="plugins/chartjs/Chart.min.js"></script>--}}
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {{--
    <script src="dist/js/pages/dashboard2.js"></script>--}}
    <!-- AdminLTE for demo purposes -->
    {{--
    <script src="dist/js/demo.js"></script>--}}

    <script>
        var referralLink;
        $(function() {
            var referral = document.getElementById('referralCode');
            referral.value = referralLink = window.location.hostname + '/register?rf={{ $user->referral_code }}';
//            document.getElementById('referralInput').value = referral.innerHTML;
        });
        function copyReferralCode() {
            document.getElementById('referralCode').select();
            document.execCommand("copy");
            alert("Referral link copied");
        }
    </script>

    <script src="app/compiled/js/crypto.js"></script>
</body>

</html>