<?php echo $this->fetch('script'); ?>
<?php echo $this->Html->css('Web.custom'); ?>
<?php echo $this->Html->css('Web.jquery-ui.min'); ?>

<?php echo $this->Html->script('Web.jquery.js'); ?>
<?php echo $this->Html->script('Web.jquery-ui-min.js'); ?>
<?php echo $this->Html->script('Web.jquery.form.js'); ?>
<script src="<?php echo $this->Html->url('/cms/vendor/metisMenu/dist/metisMenu.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/iCheck/icheck.min.js')?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/bootstrap/dist/js/bootstrap.min.js')?>"></script>

<div class="content">
	<form id="formReportsFilter" action="<?php echo $this->Html->url('requestReportFilter');?>" method="get" enctype="multipart/form-data">
	    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        <div class="hpanel hblue">
	    		<div class="panel-heading hbuilt row">
	    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h3>Reporte Solicitudes</h3>
				  	</div>
	            </div>

	        	<div class="panel-body row">
		        	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
						<div class="form-group">
					        <label class="control-label">Proceso</label>
					        <div class="input-group complete-input">
								<select class="form-control" id="filterProcess" name="process">
									<option value="0" selected="selected">Seleccione</option>
		                            <?php foreach ($processes as $process):?>
		                          		<option value="<?php echo($process['Process']["id"]); ?>"><?php echo($process['Process']["name"]); ?></option> 
		                            <?php endforeach?>
		                        </select>
					        </div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
						<div class="form-group" id="sectionState" hidden>
					        <label class="control-label">Estado</label>
					        <div class="input-group complete-input">
								<select class="form-control" id="filterState" name="state">
									<option value="0" selected="selected">Seleccione</option>
		                            <?php foreach ($states as $keyState => $state):?>
		                          		<option value="<?php echo($state['State']["id"]); ?>" class="proceso_<?php echo($state['State']["process_id"]); ?> procesos"><?php echo($state['State']["name"]); ?></option> 
		                            <?php endforeach?>
		                        </select>
					        </div>
						</div>
					</div>

		        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					</div>
	        		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				        <div class="form-group">
				            <label class="control-label">Fecha inicio</label>
				            <div class="input-group complete-input">
				                <span class="input-group-addon input-group-addon-special"><i class="glyphicon glyphicon-calendar"></i></span>
				                <input class="form-control start-date-request datepicker" type="text" id="startDateFilter" name="start" />
				            </div>
				        </div>
				    </div>
				    
				    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
				        <div class="form-group">
				            <label class="control-label">Fecha fin</label>
				            <div class="input-group complete-input">
				                <span class="input-group-addon input-group-addon-special"><i class="glyphicon glyphicon-calendar"></i></span>
				                <input class="form-control end-date-request datepicker" type="text" id="endDateFilter" name="end" />
				            </div>
				        </div>
				    </div>
				    
				    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
						<div class="form-group">
					        <label class="control-label">Grupo</label>
					        <div class="input-group complete-input">
								<select class="form-control" id="filterDealership" name="dealership">
									<option value="0" selected="selected">Seleccione</option>
		                            <?php foreach ($carDealerships as $keyDealership => $dealership):?>
		                          		<option value="<?php echo($dealership["id"]); ?>"><?php echo($dealership["name"]); ?></option> 
		                            <?php endforeach?>
		                        </select>
					        </div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
						<div class="form-group">
					        <label class="control-label">Ciudad</label>
					        <div class="input-group complete-input">
								<select class="form-control" id="filterCity" name="city">
									<option value="0" selected="selected">Seleccione</option>
		                            <?php foreach ($cities as $keyCity => $city):?>
		                          		<option value="<?php echo($city); ?>"><?php echo($city); ?></option> 
		                            <?php endforeach?>
		                        </select>
					        </div>
						</div>
					</div>
	
					

					
					
					<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
				        <button type="submit" class="btn btn-primary" id="request" style="margin-top: 25px;">Buscar</button>
				    </div>
	        	</div>
	        	
				<div id="filteredReports" class="panel-body filtered-requests row">

				</div>
	        </div>
		</div>
	</form>
	<div class="row" id="loader-container" style="display:none;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center padding-top-double padding-bottom-double">
			<img style="margin-left: auto; margin-right: auto;" src="<?php echo $this->Html->url("/cms/images/loader.gif");?>" width="120" height="120" />
    	</div>
    </div>
</div>

<?php echo $this->Html->script('Web.reports.js?'.date("His")); ?>
<script>
	$(document).ready(function() {
	    $("#formReportsFilter").submit();
	});

	$('#filterProcess').on('change', function(){
		if($(this).val() == 0) $('#sectionState').fadeOut(0);
		else $('#sectionState').fadeIn(0);
		
		$('.procesos').fadeOut(0);
		$('.proceso_'+$(this).val()).fadeIn(0);
	});
</script>
