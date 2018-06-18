<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$title?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
    <link rel="stylesheet" href="<?=PATH_URL?>assets/dist/css/adminlte.min.css">
    <!-- dataTables -->
    <link rel="stylesheet" type="text/css" href="<?=PATH_URL?>assets/css/dataTables.bootstrap.css"/>
    <!-- datepicker -->
    <link rel="stylesheet" type="text/css" href="<?=PATH_URL?>assets/plugins/datepicker/datepicker3.css"/>
    <!-- app css -->
	<link rel="stylesheet" href="<?=PATH_URL?>assets/css/app.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<!-- jQuery -->
    <script src="<?=PATH_URL?>assets/plugins/jquery/jquery.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#">
						<i class="fa fa-bars"></i>
					</a>
				</li>
			</ul>

			<!-- SEARCH FORM -->
			<form class="form-inline ml-3">
				<div class="input-group input-group-sm">
					<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
					<div class="input-group-append">
						<button class="btn btn-navbar" type="submit">
							<i class="fa fa-search"></i>
						</button>
					</div>
				</div>
			</form>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="<?=PATH_URL?>assets/index3.html" class="brand-link">
				<img src="<?=PATH_URL?>assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
				<span class="brand-text font-weight-light">AdminLTE 3</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user (optional) -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img src="<?=PATH_URL?>assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
					</div>
					<div class="info">
						<a href="#" class="d-block"><?=isset($_SESSION['account_info'])?ucwords($_SESSION['account_info']['username']):''?></a>
					</div>
				</div>

                <!-- Sidebar Menu -->
                <?=modules::run('admincp_layout/menu/renderMenu')?>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
            <!-- breadcrumbs -->
			<?php $this->breadcrumbs->show() ?>
            <!-- ./breadcrumbs -->

            <!-- Main content -->
            <section class="content">
                <!-- Modal confirm -->
                <?php $this->load->view('admincp/modal_confirm'); ?>
				<!-- ./Modal confirm -->
				
                <!-- Alert -->
				<div class="col-md-12" id="alertArea">
				</div>
                <!-- ./Alert -->

				<!-- Content action -->
				<div class="col-md-12">
					<?php $this->load->view($template); ?>
				</div>
                <!-- ./Content action -->
            </section>
            <!-- ./Main content -->
        </div>
		<!-- /.content-wrapper -->

		<footer class="main-footer">
			<div class="float-right d-none d-sm-block">
				<b>Version</b> 3.0.0-alpha
			</div>
			<strong>Copyright &copy; 2014-2018
				<a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
		</footer>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>
		<!-- /.control-sidebar -->
	</div>
	<!-- ./wrapper -->

    <script>
        var root = '<?=PATH_URL?>';
        var module = '<?=$this->uri->segment(1)?>';
		var controller = '<?=$this->uri->segment(2)?>';
		
		if (controller != '') {
			var route_url = root + '/' + module + '/' + controller;
		} else {
			var route_url = root + '/' + module;
		}
		
        var url = window.location;
        $('ul.nav-treeview a').filter(function() {
            return this.href == url || url.href.indexOf(this.href) == 0;
        })
        .parentsUntil(".has-treeview > .nav-link")
        .addClass('menu-open')
        .children(".nav-link")
		.addClass('active');
		
		$(document).ready(function() {
			$('#dateFrom, #dateTo').datepicker({
				format: "yyyy-mm-dd"
			});
		});
    </script>

    <!-- app js -->
	<script src="<?=PATH_URL?>assets/js/admin/admin.js"></script>
	<!-- Bootstrap 4 -->
	<script src="<?=PATH_URL?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- FastClick -->
	<!-- <script src="<?=PATH_URL?>assets/plugins/fastclick/fastclick.js"></script> -->
	<!-- AdminLTE App -->
	<script src="<?=PATH_URL?>assets/dist/js/adminlte.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?=PATH_URL?>assets/dist/js/demo.js"></script>
	<!-- datepicker -->
	<script src="<?=PATH_URL?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
</body>
</html>
