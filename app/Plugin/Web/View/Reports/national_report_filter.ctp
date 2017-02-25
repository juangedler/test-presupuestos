<?php   
	$this->Paginator->options(array(
		'url'=> array('controller' => 'reports', 'action' => 'nationalReportFilter', '?' => array(
			'start' => $start,
			'end' => $end,
			'dealership'=> $dealership,
			'state' => $state
		)),
	    'update' => '#filteredReports',
	    'evalScripts' => true,
	    'before' => '$("#filteredReports").html($("#loader-container").clone().removeAttr("id style"));',
     	'complete' => ''
	)); 
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<table id ="table" class="table" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th># de solicitud</th>
				<th>Concesionario</th>
				<th>Título</th>
				<th>Monto Nacional anterior</th>
				<th>Monto</th>
				<th>Estado</th>
				<th>Fecha</th>
			</tr>
		</thead>
		<tbody id="table_body">
			<?php foreach ($movements as $keyMovement => $movement) { ?>
				<tr>
					<td style="text-align: center;">
					<?php
						if($movement['NationalSingleMovement']['national_movement_id'] == NULL || $movement['NationalSingleMovement']['national_movement_id'] ==""){
							echo("-");
						}else{
							echo($movement['NationalSingleMovement']['national_movement_id']);
						}
					?>
					</td>
					<td><?php echo($movement['Group']['name']);?></td>
					<td><?php echo(trim($movement['NationalMovement']['title']));?></td>
					<td><?php echo("$".number_format($movement['NationalSingleMovement']['national_before'], 0, ',', '.')) ?></td>
					<td><?php echo("$".number_format($movement['NationalSingleMovement']['national'], 0, ',', '.')) ?></td>
					<td><?php echo(trim($movement['NationalSingleMovement']['type'])) ?></td>
					<td><?php 
						$dt = new DateTime($movement['NationalSingleMovement']['created']);
						echo($dt->format('d/m/Y')); ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 paging">
			<ul class="pagination pagination-sm">
				<?php echo $this->Paginator->prev('< Anterior', array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a')); ?>
				<?php echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a')); ?>
				<?php echo $this->Paginator->next('Siguiente >', array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a')); ?>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a target="_blank" href="/web/reports/nationalReportExcel?start=<?php echo($start);?>&end=<?php echo($end);?>&dealership=<?php echo($dealership);?>&state=<?php echo($state);?>" class="btn btn-primary" role="button">Descargar reporte</a>
		</div>
	</div>
</div>
<?php echo $this->Js->writeBuffer(); ?>
