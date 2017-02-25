<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>FORD | CMS</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->

    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/fontawesome/css/font-awesome.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/metisMenu/dist/metisMenu.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/animate.css/animate.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/bootstrap/dist/css/bootstrap.css')?>" />
    <?php //echo $this->Html->url('/cms/')?>
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/fonts/pe-icon-7-stroke/css/helper.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/styles/style.css')?>">
</head>
<body>
<!-- Vendor scripts -->
<script src="<?php echo $this->Html->url('/cms/vendor/jquery/dist/jquery.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/jquery-ui/jquery-ui.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/slimScroll/jquery.slimscroll.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/bootstrap/dist/js/bootstrap.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/jquery-flot/jquery.flot.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/jquery-flot/jquery.flot.resize.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/jquery-flot/jquery.flot.pie.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/flot.curvedlines/curvedLines.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/jquery.flot.spline/index.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/metisMenu/dist/metisMenu.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/iCheck/icheck.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/peity/jquery.peity.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/sparkline/index.js')?>"></script>

<!-- App scripts -->
<script src="<?php echo $this->Html->url('/cms/scripts/homer.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/scripts/charts.js')?>"></script>

<!-- Simple splash screen-->
<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>FORD - CMS</h1><p>Aplicación de administración de usuarios.</p><img src="<?php echo $this->Html->url('/cms/images/loading-bars.svg')?>" width="64" height="64" /> </div> </div>
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Header -->
<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version" style="background-color:#425968;">
        <span>
            <img src="<?php echo $this->Html->url('/cms/images/logo-go-further-down.jpg')?>" alt="Ford Colombia" style="margin-top: -15px;margin-left: 8px;">
        </span>
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary">FORD CMS</span>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <a href="<?php echo $this->Html->url('/cms/loginA/out');?>">
                        <i class="pe-7s-upload pe-rotate-90"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Navigation -->
<aside id="menu">
    <div id="navigation">
        <div class="profile-picture">
            <a href="index.html">
                <img heigth="76px" width="76px" src="<?php echo $this->Html->url('/cms/images/profile.jpg')?>" class="img-circle m-b" alt="logo" onclick="return false;">
            </a>

            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase"><?php echo $this->Session->read('admin_name');?></span>

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted"><?php echo $this->Session->read('admin_type');?> <b class="caret"></b></small>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="#">Editar Perfil</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $this->Html->url('/cms/loginA/out');?>">Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <ul class="nav" id="side-menu">
            <li>
                <a href="<?php echo $this->Html->url('/cms/users')?>"><span class="nav-label">Usuarios</span></a>
            </li>
            <li>
                <a href="<?php echo $this->Html->url('/cms/groups')?>"><span class="nav-label">Grupos</span></a>
            </li>
            <li>
                <a href="<?php echo $this->Html->url('/cms/groupMembers')?>"><span class="nav-label">Miembros de Grupos</span></a>
            </li>
        </ul>
    </div>
</aside>


<!-- Main Wrapper -->
<div id="wrapper">
<?php echo $this->fetch('content'); ?>
</div>

</body>
</html>

