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
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/toastr/build/toastr.min.css')?>">
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/fonts/pe-icon-7-stroke/css/helper.css')?>" />
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/styles/style.css')?>">
    <link rel="stylesheet" href="<?php echo $this->Html->url('/cms/styles/static_custom.css')?>">
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
<script src="<?php echo $this->Html->url('/cms/vendor/socket.io-client/dist/socket.io.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/toastr/build/toastr.min.js')?>"></script>

<!-- App scripts -->
<script src="<?php echo $this->Html->url('/cms/scripts/homer.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/scripts/charts.js')?>"></script>

<!-- Simple splash screen-->
<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>FORD - WEB</h1><p>Aplicación de gestión de presupuestos.</p><img src="<?php echo $this->Html->url('/cms/images/loading-bars.svg')?>" width="64" height="64" /> </div> </div>
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
            <span class="text-primary">FORD WEB</span>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <a href="<?php echo $this->Html->url('/web/login/out');?>">
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
            <span class="font-extra-bold font-uppercase"><?php echo $this->Session->read('name');?></span>

            <div class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                    <small class="text-muted"><?php echo $this->Session->read('type');?> <b class="caret"></b></small>
                </a>
                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                    <li><a href="<?php echo $this->Html->url('/web/perfil');?>">Editar Perfil</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo $this->Html->url('/web/login/out');?>">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>

    <ul class="nav" id="side-menu">
        
        <?php
            switch ($this->Session->read('type')) {
                case 'Concesionario':
        ?>
            <li class="root">
            <a href="#"><span class="nav-label" style="font-size:12px;">Fondo de Publicidad Regional</span><span class="fa arrow"></span> </a>
            <ul class="nav nav-second-level">
                <li><a href="<?php echo $this->Html->url('/web')?>"><span class="nav-label"> Dashboard</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/concesionario/solicitud')?>"><span class="nav-label">Crear Solicitud</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/concesionario/ver/1')?>"><span class="nav-label"> Solicitudes Pendientes</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/concesionario/ver/3')?>"><span class="nav-label"> Solicitudes Aprobadas</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/concesionario/ver/4')?>"><span class="nav-label"> Solicitudes Rechazadas</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/concesionario/ver/999')?>"><span class="nav-label"> Solicitudes Anuladas</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/concesionario/cuenta')?>"><span class="nav-label">Estado de Cuenta</span></a></li>
            </ul>
            </li>
        <?php
                    break;
                case 'JWT':
                if(in_array('5', $group_types)){
        ?>
            <li class="root">
            <a href="#"><span class="nav-label" style="font-size:12px;">Fondo de Publicidad Regional</span><span class="fa arrow"></span> </a>
            <ul class="nav nav-second-level">
                <li><a href="<?php echo $this->Html->url('/web')?>"><span class="nav-label"> Dashboard</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/ver/1')?>"><span class="nav-label"> Solicitudes Pendientes</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/ver/2')?>"><span class="nav-label"> Solicitudes Aprobadas</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/ver/4')?>"><span class="nav-label"> Solicitudes Rechazadas</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/ver/3')?>"><span class="nav-label"> Solicitudes Aprobadas por Ford</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/ver/5')?>"><span class="nav-label"> Solicitudes Rechazadas por Ford</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/administrador/ver/999')?>"><span class="nav-label"> Solicitudes Anuladas por Ford</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/administrador/presupuesto')?>"><span class="nav-label">Asignar Presupuesto</span></a></li>
            </ul>
            </li>
            
            <li class="root">
	            <a href="#"><span class="nav-label" style="font-size:12px;">Reportes</span><span class="fa arrow"></span> </a>
	            <ul class="nav nav-second-level">
	            	<li><a href="<?php echo $this->Html->url('/web/reportes/solicitudes')?>"><span class="nav-label">Reporte Solicitudes</span></a></li>
            		<li><a href="<?php echo $this->Html->url('/web/reportes/actividades')?>"><span class="nav-label">Reporte Actividades</span></a></li>
            		<li><a href="<?php echo $this->Html->url('/web/reportes/saldos_disponibles')?>"><span class="nav-label">Reporte Saldos Disponibles</span></a></li>
            		<li><a href="<?php echo $this->Html->url('/web/reportes/estado_de_cuenta')?>"><span class="nav-label">Reporte Estado de Cuenta</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/reportes/nacional')?>"><span class="nav-label"> Reporte Montos Nacionales</span></a></li>
        		</ul>
            </li>     	
            <?php
                }
                if(in_array('3', $group_types)){
            ?>
            <li class="root">
            <a href="#"><span class="nav-label" style="font-size:12px;">Presupuesto Publicitario JWT</span><span class="fa arrow"></span> </a>
            <ul class="nav nav-second-level">
                <li><a href="<?php echo $this->Html->url('/web')?>"><span class="nav-label"> Dashboard</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/solicitud')?>"><span class="nav-label"> Cargar Nuevo</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar/1')?>"><span class="nav-label"> Pendientes</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar/2')?>"><span class="nav-label"> Aprobados</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar/3')?>"><span class="nav-label"> Rechazados</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/administrador/listar/998')?>"><span class="nav-label"> Anulados</span></a></li>
            </ul>
            </li>
        <?php
                    }
                    break;
                case 'Ford':
        ?>
            <li class="root">
            <a href="#"><span class="nav-label" style="font-size:12px;">Fondo de Publicidad Regional</span><span class="fa arrow"></span> </a>
            <ul class="nav nav-second-level">
                <li><a href="<?php echo $this->Html->url('/web')?>"><span class="nav-label"> Dashboard</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/administrador/ver/2')?>"><span class="nav-label"> Solicitudes Pendientes</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/administrador/ver/3')?>"><span class="nav-label"> Solicitudes Aprobadas</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/administrador/ver/5')?>"><span class="nav-label"> Solicitudes Rechazadas</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/administrador/ver/999')?>"><span class="nav-label"> Solicitudes Anuladas</span></a></li>
            </ul>
            </li>
            <li class="root">
            <a href="#"><span class="nav-label" style="font-size:12px;">Reportes</span><span class="fa arrow"></span> </a>
            <ul class="nav nav-second-level">
                <li><a href="<?php echo $this->Html->url('/web/reportes/solicitudes')?>"><span class="nav-label"> Reporte Solicitudes</span></a></li>
            	<li><a href="<?php echo $this->Html->url('/web/reportes/actividades')?>"><span class="nav-label"> Reporte Actividades</span></a></li>
            	<li><a href="<?php echo $this->Html->url('/web/reportes/saldos_disponibles')?>"><span class="nav-label"> Reporte Saldos Disponibles</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/reportes/estado_de_cuenta')?>"><span class="nav-label"> Reporte Estado de Cuenta</span></a></li>
            	<li><a href="<?php echo $this->Html->url('/web/reportes/nacional')?>"><span class="nav-label"> Reporte Montos Nacionales</span></a></li>
            </ul>
            </li>
            <?php
                if(in_array('4', $group_types)){
            ?>
                <li class="root">
                <a href="#"><span class="nav-label" style="font-size:12px;">Presupuesto Publicitario JWT</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/1')?>"><span class="nav-label"> Pendientes</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/2')?>"><span class="nav-label"> Aprobados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/4')?>"><span class="nav-label"> Rechazados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/3')?>"><span class="nav-label"> Aprobados Finales</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/5')?>"><span class="nav-label"> Rechazados Finales</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/998')?>"><span class="nav-label"> Anulados</span></a></li>
                </ul>
                </li>
                <li class="root">
                <a href="#"><span class="nav-label" style="font-size:12px;">Presupuesto Publicitario Mindshare</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                <li>
                <a href="#"><span class="nav-label" style="font-size:12px;">Pautas de Medios</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/6')?>"><span class="nav-label"> Pendientes</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/7')?>"><span class="nav-label"> Aprobados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/9')?>"><span class="nav-label"> Rechazados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/8')?>"><span class="nav-label"> Aprobados Finales</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/10')?>"><span class="nav-label"> Rechazados Finales</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/14')?>"><span class="nav-label"> Finalizados</span></a></li>
                </ul>
                </li>
                <li class="root">
                <a href="#"><span class="nav-label" style="font-size:12px;">Presupuestos Publicitarios</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar_presupuestos/1')?>"><span class="nav-label"> Pendientes</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar_presupuestos/2')?>"><span class="nav-label"> Aprobados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar_presupuestos/3')?>"><span class="nav-label"> Rechazados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar_presupuestos/4')?>"><span class="nav-label"> Aprobados Finales</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar_presupuestos/5')?>"><span class="nav-label"> Rechazados Finales</span></a></li>
                </ul>
                </li>
                </ul>
                </li>
            <?php
                }
                else if(in_array('6', $group_types)){
            ?>
                <li class="root">
                <a href="#"><span class="nav-label" style="font-size:12px;">Presupuesto Publicitario JWT</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/1')?>"><span class="nav-label"> Pendientes</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/2')?>"><span class="nav-label"> Aprobados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/3')?>"><span class="nav-label"> Rechazados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/998')?>"><span class="nav-label"> Anulados</span></a></li>
                </ul>
                </li>
                <li class="root">
                <a href="#"><span class="nav-label" style="font-size:12px;">Presupuesto Publicitario Mindshare</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                <li>
                <a href="#"><span class="nav-label" style="font-size:12px;">Pauta de Medios</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/6')?>"><span class="nav-label"> Pendientes</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/7')?>"><span class="nav-label"> Aprobados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/8')?>"><span class="nav-label"> Rechazados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar/14')?>"><span class="nav-label"> Finalizados</span></a></li>
                </ul>
                </li>
                <li class="root">
                <a href="#"><span class="nav-label" style="font-size:12px;">Presupuesto Publicitario</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar_presupuestos/1')?>"><span class="nav-label"> Pendientes</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar_presupuestos/2')?>"><span class="nav-label"> Aprobados</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/listar_presupuestos/3')?>"><span class="nav-label"> Rechazados</span></a></li>
                </ul>
                </li>
                </ul>
                </li>
        <?php
                }
        ?>
                <li class="root">
                <a href="#"><span class="nav-label" style="font-size:12px;">Monto Nacional</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo $this->Html->url('/web/administrador/nacional/crear')?>"><span class="nav-label"> Nueva Solicitud</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/nacional/listar')?>"><span class="nav-label"> Listar Solicitudes</span></a></li>
                    <li><a href="<?php echo $this->Html->url('/web/administrador/nacional/eliminadas')?>"><span class="nav-label"> Solicitudes Eliminadas</span></a></li>
                </ul>
                </li>
        <?php
                break;
            case 'Mindshare':
                if(in_array('8', $group_types)){
        ?>
            <li class="root">
            <a href="#"><span class="nav-label" style="font-size:12px;">Pauta de Medios</span><span class="fa arrow"></span> </a>
            <ul class="nav nav-second-level">
                <li><a href="<?php echo $this->Html->url('/web')?>"><span class="nav-label"> Dashboard</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/nueva_pauta')?>"><span class="nav-label"> Cargar Nuevo</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar/1')?>"><span class="nav-label"> Pendientes</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar/2')?>"><span class="nav-label"> Aprobados</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar/3')?>"><span class="nav-label"> Rechazados</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar_pautas/2')?>"><span class="nav-label">Finalizados</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar_presupuestos/1')?>"><span class="nav-label"> Presupuestos por Firmar</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar_presupuestos/3')?>"><span class="nav-label"> Presupuestos Firmados</span></a></li>
            </ul>
            </li>
            <?php
                }
                else if(in_array('9', $group_types)){
            ?>
            <li class="root">
            <a href="#"><span class="nav-label" style="font-size:12px;">Pauta de Medios</span><span class="fa arrow"></span> </a>
            <ul class="nav nav-second-level">
                <li><a href="<?php echo $this->Html->url('/web')?>"><span class="nav-label"> Dashboard</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar_pautas')?>"><span class="nav-label"> Pautas Abiertas</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar_pautas/2')?>"><span class="nav-label"> Pautas Finalizadas</span></a></li>
            </ul>
            </li>
            <?php
                }
                else if(in_array('10', $group_types)){
            ?>
            <li class="root">
            <a href="#"><span class="nav-label" style="font-size:12px;">Pauta de Medios</span><span class="fa arrow"></span> </a>
            <ul class="nav nav-second-level">
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar_presupuestos/2')?>"><span class="nav-label"> Presupuestos por Firmar</span></a></li>
                <li><a href="<?php echo $this->Html->url('/web/agencia/listar_presupuestos/4')?>"><span class="nav-label"> Presupuestos Firmados</span></a></li>
            </ul>
            </li>
            <?php
                }
                break;
            }
        ?>
    </ul>
</div>
</aside>

<!-- Main Wrapper -->
<div id="wrapper">
<?php echo $this->fetch('content'); ?>
</div>

<script type="text/javascript">
var idleTime = 0;

$(document).ready(function () {
    var idleInterval = setInterval(timerIncrement, 60000);

    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });

    $( "li a" ).each(function( index ) {
      if($(this).attr('href') == "<?=$this->here?>") $(this).parents('.root').children('a').click();
    });
});

function timerIncrement() {
    if (idleTime > 29) {
        location.href = "<?php echo $this->Html->url('/web');?>"
    }
    else idleTime = idleTime + 1;
}

var gotogo =  function(){
    location.href = "www.google.com";
}

toastr.options = {
    "newestOnTop": false,
    "positionClass": "toast-bottom-right",
    "closeButton": true,
    "debug": false,
    // "onclick": gotogo,
    "showDuration": "2000",
    "hideDuration": "1000",
    "timeOut": "5000",
    "hideEasing": "linear",
    "extendedTimeOut": "1000",
    "toastClass": "animated fadeInUp",
    "hideMethod": "fadeOut"
};

var socket = io('<?php echo $_SERVER['HTTP_HOST']?>:3000');

socket.emit('authenticate', {
    'user_id': "<?=$_SESSION['id']?>",
    'user_name': "<?=$_SESSION['name']?>",
    'user_type_id': "<?=$_SESSION['type_id']?>",
    'user_type': "<?=$_SESSION['type']?>",
});

socket.on('chat message', function(msg){
    toastr.info('Info - Bee Pruebas');
});

socket.on('new user', function(msg){
    toastr.info(msg);
})
</script> 
</body>
</html>