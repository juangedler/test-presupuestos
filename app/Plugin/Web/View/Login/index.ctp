<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>FORD | WEB</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->

    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/fontawesome/css/font-awesome.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/metisMenu/dist/metisMenu.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/animate.css/animate.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/bootstrap/dist/css/bootstrap.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/toastr/build/toastr.min.css')?>" />
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/fonts/pe-icon-7-stroke/css/helper.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/styles/style.css')?>">
</head>
<body class="blank">
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
<script src="<?php echo $this->Html->url('/cms/vendor/toastr/build/toastr.min.js')?>"></script>

<!-- App scripts -->
<script src="<?php echo $this->Html->url('/cms/scripts/homer.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/scripts/charts.js')?>"></script>

<!-- Simple splash screen-->
<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>FORD - WEB</h1><p>Aplicación de gestión de presupuestos.</p><img src="<?php echo $this->Html->url('/cms/images/loading-bars.svg')?>" width="64" height="64" /> </div> </div>
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="login-container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center m-b-md">
                <img src="<?php echo $this->Html->url('/cms/images/ford-go-further_hi_res.png');?>" alt="FORD">
                <br>
                <h3>SISTEMA DE GESTIÓN DE PRESUPUESTOS</h3>
                <small>¡Bienvenido a sistema de solicitud y aprobación de Presupuestos de Ford Colombia!</small>
            </div>
            <div class="hpanel">
                <div class="panel-body">
                    <form action="<?php echo $this->Html->url('login');?>" id="loginForm" method="post">
                        <div class="form-group">
                            <label class="control-label" for="username">Usuario</label>
                            <input type="text" placeholder="usuario" title="Por favor coloque su nombre de usuario" required="" value="" name="username" id="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="password">Contraseña</label>
                            <input type="password" title="Por favor coloque su contraseña" placeholder="******" required="" value="" name="password" id="password" class="form-control">
                        </div>
                        <br>
                        <button class="btn btn-success btn-block" style="background-color:#003478;border-color:#91A4B1;">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <strong>FORD Colombia</strong> - Sistema de Gestión de Presupuestos <br/> 2015
        </div>
    </div>
</div>
<button class="homerDemo2 btn" style="display:none;"></button>
<button class="homerDemo4 btn" style="display:none;"></button>

</body>
</html>

<script>
    toastr.options = {
            "debug": false,
            "newestOnTop": false,
            "positionClass": "toast-top-center",
            "closeButton": true,
            "debug": false,
            "toastClass": "animated fadeInDown",
        };

    $('.homerDemo1').click(function (){
        toastr.info('Info - This is a custom Homer info notification');
    });

    $('.homerDemo2').click(function (){
        toastr.success('Desconectado correctamente del sistema');
    });

    $('.homerDemo3').click(function (){
        toastr.warning('Warning - This is a Homer warning notification');
    });

    $('.homerDemo4').click(function (){
        toastr.error('Usuario o Contraseña inválida');
    });


    <?php 
        if(isset($err) && $err == '1'){
    ?>
        $('.homerDemo4').click();
    <?php
        }
        else
        if(isset($out) && $out == '1'){
    ?>
        $('.homerDemo2').click();
    <?php
        }
    ?>

</script>