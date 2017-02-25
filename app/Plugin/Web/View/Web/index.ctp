<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css');?>"/>
<div class="content ">

<div class="row">
    <div class="col-lg-12 text-center m-t-md">
        <h2>
            Bienvenido <?php echo $this->Session->read('name');?>
        </h2>
    </div>
</div>

<?php
	switch ($this->Session->read('type')) {
		case 'Concesionario':
?>
<!-- Panel para concesionario -->
<div class="row">
<div class="col-lg-12">
    <div class="hpanel">
        <div class="panel-heading">
            Resumen de solicitudes por estado
        </div>
    

        <div class="row">
            <div class="col-lg-4">
                <a href="<?php echo $this->Html->url('/web/concesionario/ver/1')?>">
                    <div class="hpanel hbgyellow">
                        <div class="panel-body">
                            <div class="text-center">
                                <h3>Solicitudes pendientes</h3>
                                <p class="text-big font-light">
                                    <?php echo $requests['Pendientes'];?>
                                </p>
                                <small>
                                    Cantidad de solicitudes totales pendientes
                                </small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4">
                <a href="<?php echo $this->Html->url('/web/concesionario/ver/3')?>">
                    <div class="hpanel hbggreen">
                        <div class="panel-body">
                            <div class="text-center">
                                <h3>Solicitudes aprobadas</h3>
                                <p class="text-big font-light">
                                    <?php echo $requests['Aprobadas'];?>
                                </p>
                                <small>
                                    Cantidad de solicitudes totales aprobadas
                                </small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4">
                <a href="<?php echo $this->Html->url('/web/concesionario/ver/4')?>">
                    <div class="hpanel hbgred">
                        <div class="panel-body">
                            <div class="text-center">
                                <h3>Solicitudes rechazadas</h3>
                                <p class="text-big font-light">
                                    <?php echo $requests['Rechazadas'];?>
                                </p>
                                <small>
                                    Cantidad de solicitudes totales rechazadas
                                </small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>


<div class="col-md-12">
    <div class="hpanel stats">
        <div class="panel-body h-200">
            <div class="stats-title pull-left">
                <h4>Monto Disponible</h4>
            </div>
            <div class="stats-icon pull-right">
                <i class="pe-7s-cash fa-4x"></i>
            </div>
            <?php
            	foreach ($balances as $b) {
            ?>
	            <div class="m-t-xl">
	                <h4 class="text-info"><?php echo $b['Group']['name'];?></h4>
	                <h1 class="text-success">$<?php echo number_format((float)($b['Balance']['balance'] - $b['Balance']['pending']), 0,',','.');?></h1>
	            </div>
            <?php
            	}
            ?>
            <div class="m-t-xl">
                <small>
                    Monto disponible del presupuesto asignado
                </small>
            </div>
        </div>
    </div>
</div>
</div>


<?php
			break;
		case 'JWT':
        if(in_array('5', $group_types)){
?>
        <!-- Panel para JWT -->
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Resumen de <b>Solicitudes Concesionarios</b> por estado
                </div>
            
                <div class="row">

                	<div class="col-lg-4">
                        <a href="<?php echo $this->Html->url('/web/agencia/ver/1')?>">
                            <div class="hpanel hbgyellow">
                                <div class="panel-body">
                                    <div class="text-center">
                                        <h3>Solicitudes pendientes</h3>
                                        <p class="text-big font-light">
                                   		<?php echo $requests['Pendientes'];?>
                                        </p>
                                        <small>
                                            Cantidad de solicitudes totales pendientes
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-4">
                        <a href="<?php echo $this->Html->url('/web/agencia/ver/2')?>">
                            <div class="hpanel hbggreen">
                                <div class="panel-body">
                                    <div class="text-center">
                                        <h3>Solicitudes aprobadas</h3>
                                        <p class="text-big font-light">
                                   		<?php echo $requests['Aprobadas'];?>
                                        </p>
                                        <small>
                                            Cantidad de solicitudes totales aprobadas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-4">
                        <a href="<?php echo $this->Html->url('/web/agencia/ver/4')?>">
                            <div class="hpanel hbgred">
                                <div class="panel-body">
                                    <div class="text-center">
                                        <h3>Solicitudes rechazadas</h3>
                                        <p class="text-big font-light">
                                   		<?php echo $requests['Rechazadas'];?>
                                        </p>
                                        <small>
                                            Cantidad de solicitudes totales rechazadas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

<?php
        }
        if(in_array('3', $group_types)){
?>
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Resumen de <b>Presupuestos JWT</b> por estado
                </div>
            
                <div class="row">

                    <div class="col-lg-4">
                        <a href="<?php echo $this->Html->url('/web/agencia/listar/1')?>">
                            <div class="hpanel hbgyellow">
                                <div class="panel-body">
                                    <div class="text-center">
                                        <h3>Solicitudes pendientes</h3>
                                        <p class="text-big font-light">
                                        <?php echo $requests['PendientesJWT'];?>
                                        </p>
                                        <small>
                                            Cantidad de solicitudes totales pendientes
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-4">
                        <a href="<?php echo $this->Html->url('/web/agencia/listar/2')?>">
                            <div class="hpanel hbggreen">
                                <div class="panel-body">
                                    <div class="text-center">
                                        <h3>Solicitudes aprobadas</h3>
                                        <p class="text-big font-light">
                                        <?php echo $requests['AprobadasJWT'];?>
                                        </p>
                                        <small>
                                            Cantidad de solicitudes totales aprobadas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-4">
                        <a href="<?php echo $this->Html->url('/web/agencia/listar/3')?>">
                            <div class="hpanel hbgred">
                                <div class="panel-body">
                                    <div class="text-center">
                                        <h3>Solicitudes rechazadas</h3>
                                        <p class="text-big font-light">
                                        <?php echo $requests['RechazadasJWT'];?>
                                        </p>
                                        <small>
                                            Cantidad de solicitudes totales rechazadas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
<?php
        }
			break;
		case 'Ford':
?>

<!-- Panel para usuario FORD -->
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
            <div class="panel-heading">
                Montos totales
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="hpanel">
                            <div class="panel-body text-center h-200">
                                <i class="pe-7s-cash fa-4x"></i>

                                <h1 id="total" class="m-xs">$<?php echo $total;?></h1>

                                <h3 class="font-extra-bold no-margins text-success">
                                    Total solicitudes aprobadas
                                </h3>
                                <br>
                                <a href="<?php echo $this->Html->url('/web/administrador/ver/3')?>" class="btn btn-success btn-sm">Solicitudes aprobadas</a>
                            </div>
                            <div class="panel-footer">
                                Seleccione concesionario
                                <select id="concesionarios" class="form-control m-b" name="account">
                                    <option value="<?php echo $total;?>">Todos los concesionarios</option>
                                    <?php
                                    foreach ($aprobadas as $a) {
                                    	echo '<option value="'.number_format((float)($a[0]['suma']), 0,',','.').'">'.$a['Group']['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="hpanel">
                            <div class="panel-body text-center h-200">
                                <i class="pe-7s-cash fa-4x"></i>

                                <h1 class="m-xs">$<?php echo $nacional;?></h1>

                                <h3 class="font-extra-bold no-margins text-success">
                                    Presupuesto nacional 
                                    <?php 
                                    	$months = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
                                    	echo $months[intval(date('m'))].' '.date('Y');
                                    ?>
                                </h3>
                            </div>
                            <div class="panel-footer">
                            <i class="fa fa-clock-o"></i> &Uacute;ltima actualizaci&oacute;n 
                            	<?php echo date('d/m/Y H:i:s'); ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-4">
        <a href="<?php echo $this->Html->url('/web/administrador/ver/2')?>">
            <div class="hpanel hbgyellow">
                <div class="panel-body">
                    <div class="text-center">
                        <h3>Solicitudes pendientes</h3>
                        <p class="text-big font-light">
                       		<?php echo $requests['Pendientes'];?>
                        </p>
                        <small>
                            Cantidad de solicitudes totales pendientes
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="<?php echo $this->Html->url('/web/administrador/ver/3')?>">
            <div class="hpanel hbggreen">
                <div class="panel-body">
                    <div class="text-center">
                        <h3>Solicitudes aprobadas</h3>
                        <p class="text-big font-light">
                       		<?php echo $requests['Aprobadas'];?>
                        </p>
                        <small>
                            Cantidad de solicitudes totales aprobadas
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="<?php echo $this->Html->url('/web/administrador/ver/5')?>">
            <div class="hpanel hbgred">
                <div class="panel-body">
                    <div class="text-center">
                        <h3>Solicitudes rechazadas</h3>
                        <p class="text-big font-light">
                       		<?php echo $requests['Rechazadas'];?>
                        </p>
                        <small>
                            Cantidad de solicitudes totales rechazadas
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <div class="hpanel">
        <div class="panel-heading">
            Resumen de montos totales por medios
        </div>
        <div class="panel-body list">
            <div class=" input-group date">
                <input id="datepicker" type="text" class="requerido form-control" value="<?php 
                            setlocale(LC_ALL,"es_ES");
                            echo date('d/m/Y');
                        ?>"><span class="input-group-addon"><i id="icono" class="pe-7s-date"></i></span>
            </div>
            <div id="medios">
                
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="hpanel">
        <div class="panel-heading">
            Resumen de solicitudes por concesionarios
        </div>
        <div class="panel-body list">
            <div class="table-responsive project-list">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Concesionario</th>
                        <th style="text-align: center;">Nro. de solicitud</th>
                        <th style="text-align: center;">D&iacute;as desde creaci&oacute;n</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                	foreach ($pendientes as $p) {
                    ?>
                    <tr>
                        <td><?php echo $p['Group']['name'];?>
                            <br/>
                            <small><i class="fa fa-clock-o"></i> Creada <?php echo date('d/m/Y',strtotime($p['Request']['created']))?></small>
                        </td>
                        <td align="center">
                            <span><?php echo str_pad($p['Request']['id'], 8, "0", STR_PAD_LEFT);?></span>
                        </td>
                        <td align="center"><strong>
                        <?php 
                            $now = time();
						    $your_date = strtotime($p['Request']['created']);
						    $datediff = $now - $your_date;
						    echo floor($datediff/86400);
                    	?>
                    	</strong></td>
                    </tr>
                    <?php
                	}
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.es.min.js');?>"></script>
<script>
    $('#datepicker').datepicker({
        language: 'es', 
        format: "MM yyyy",
        viewMode: "months", 
        minViewMode: "months",
    });

    var d = new Date();
    var currMonth = d.getMonth();
    var currYear = d.getFullYear();
    var startDate = new Date(currYear,currMonth,1);
    $('#datepicker').datepicker('setDate',startDate);

	$('#concesionarios').on('change',function(){
		$('#total').html('$'+$(this).val());
	});

    $('#datepicker').datepicker().on('changeMonth',function(e){
        var currMonth = new Date(e.date).getMonth() + 1;
        var currYear = String(e.date).split(" ")[3];
        console.log(currMonth);
        console.log(currYear);
        $.ajax({
            method:'POST',
            data: { 
                month: currMonth,
                year: currYear
            },
            url: "<?= $this->Html->url('web/monto_medios')?>",
            success: function(data){
                $('#medios').html(data);
                console.log(data);
            }
        });
    });

    $.ajax({
        method:'POST',
        data: { 
            month: currMonth+1,
            year: currYear
        },
        url: "<?= $this->Html->url('web/monto_medios')?>",
        success: function(data){
            $('#medios').html(data);
            console.log(data);
        }
    });

    $('#datepicker').on('keypress keyup keydown',function(e){
        e.preventDefault();
    });

    $('#icono').on('click', function(){
        $('#datepicker').datepicker('show');
    });

</script>
<?php
			break;
	}
?>
</div>
