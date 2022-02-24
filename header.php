
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Blank Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= $url ?>assets/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $url ?>assets/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= $url ?>assets/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= $url ?>assets/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $url ?>assets/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= $url ?>assets/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-yellow sidebar-mini fixed">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!-- <span class="logo-mini"><b>A</b>LT</span> -->
      <!-- logo for regular state and mobile devices -->
      <span class="">Analisa<b>Sentimen</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <!-- <div class="user-panel"> -->
        <!-- <div class="pull-left image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div> -->
      <!-- </div> -->
      <!-- search form -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php
      //untuk class active di menu
      $page = isset($_GET['page']) ? $_GET['page'] : '';
      $treeview_a = ['cleaning', 'case-folding', 'tokenizing', 'normalisasi', 'filtering', 'stemming'];
      $treeview_b = ['pembobotan', 'pelatihan', 'pengujian'];
      ?>
      <ul class="sidebar-menu" data-widget="tree">
        <!-- <li class="header">MAIN NAVIGATION</li> -->
        <li class="<?= $page ==  ''? 'active' : ''?>">
          <a href="<?= $url ?>">
            <i class="fa fa-home"></i> <span>Beranda</span>
          </a>
        </li>
        <li class="<?= $page ==  'data'? 'active' : ''?>">
          <a href="<?= $url ?>?page=data">
            <i class="fa fa-database"></i> <span>Data</span>
          </a>
        </li>
        <li class="treeview <?= in_array($page, $treeview_a) ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-cogs"></i> <span>Preprocessing</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?= $page ==  'cleaning'? 'active' : ''?>"><a href="<?= $url ?>?page=cleaning"><i class="fa fa-circle-o"></i>Cleaning</a></li>
            <li class="<?= $page ==  'case-folding'? 'active' : ''?>"><a href="<?= $url ?>?page=case-folding"><i class="fa fa-circle-o"></i>Case Folding</a></li>
            <li class="<?= $page ==  'tokenizing'? 'active' : ''?>"><a href="<?= $url ?>?page=tokenizing"><i class="fa fa-circle-o"></i>Tokenizing</a></li>
            <li class="<?= $page ==  'normalisasi'? 'active' : ''?>"><a href="<?= $url ?>?page=normalisasi"><i class="fa fa-circle-o"></i>Normalisasi</a></li>
            <li class="<?= $page ==  'filtering'? 'active' : ''?>"><a href="<?= $url ?>?page=filtering"><i class="fa fa-circle-o"></i>Filtering</a></li>
            <li class="<?= $page ==  'stemming'? 'active' : ''?>"><a href="<?= $url ?>?page=stemming"><i class="fa fa-circle-o"></i>Stemming</a></li>
          </ul>
        </li>
        <li class="treeview <?= in_array($page, $treeview_b) ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Klasifikasi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?= $page ==  'pembobotan'? 'active' : ''?>"><a href="<?= $url ?>?page=pembobotan"><i class="fa fa-circle-o"></i>Pembobotan</a></li>
            <li class="<?= $page ==  'pelatihan'? 'active' : ''?>"><a href="<?= $url ?>?page=pelatihan"><i class="fa fa-circle-o"></i>Pelatihan</a></li>
            <li class="<?= $page ==  'pengujian'? 'active' : ''?>"><a href="<?= $url ?>?page=pengujian"><i class="fa fa-circle-o"></i>Pengujian</a></li>
          </ul>
        </li>
        <li class="<?= $page ==  'prediksi'? 'active' : ''?>">
          <a href="<?= $url ?>?page=prediksi">
            <i class="fa fa-database"></i> <span>Prediksi</span>
          </a>
        </li>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->
