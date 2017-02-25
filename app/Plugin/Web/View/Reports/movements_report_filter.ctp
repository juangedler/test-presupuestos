<?php   
	$this->Paginator->options(array(
		'url'=> array('controller' => 'reports', 'action' => 'movementsReportFilter', '?' => array(
			'start' => $start,
			'end' => $end,
			'dealership'=> $dealership,
			'city' => $city,
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
				<th>Ciudad</th>
				<th>Monto</th>
				<th>Balance anterior</th>
				<th>% Merchandising</th>
				<th>Estado</th>
				<th>Fecha</th>
			</tr>
		</thead>
		<tbody id="table_body">
			<?php foreach ($movements as $keyMovement => $movement) { ?>
				<tr>
					<td style="text-align: center;">
					<?php
						if($movement['Movement']['request_id'] == NULL || $movement['Movement']['request_id'] ==""){
							echo("-");
						}else{
							echo($movement['Movement']['request_id']);
						}
					?>
					</td>
					<td><?php echo($movement['Group']['name']);?></td>
					<td><?php echo(trim($movement['Group']['city']));?></td>
					<td><?php echo("$".number_format($movement['Movement']['amount'], 0, ',', '.')) ?></td>
					<td><?php echo("$".number_format($movement['Movement']['balance_before'], 0, ',', '.')) ?></td>
					<td><?php echo($movement['Movement']['percentage']) ?></td>
					<td><?php echo(trim($movement['Movement']['type'])) ?></td>
					<td><?php 
						$dt = new DateTime($movement['Movement']['created']);
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
			<a target="_blank" href="/web/reports/movementsReportExcel?start=<?php echo($start);?>&end=<?php echo($end);?>&dealership=<?php echo($dealership);?>&city=<?php echo($city);?>&state=<?php echo($state);?>" class="btn btn-primary" role="button">Descargar reporte</a>
		</div>
	</div>
</div>
<?php echo $this->Js->writeBuffer(); ?>
