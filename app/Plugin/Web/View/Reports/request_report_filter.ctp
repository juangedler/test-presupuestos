<?php   
	$this->Paginator->options(array(
		'url'=> array('controller' => 'reports', 'action' => 'requestReportFilter', '?' => array(
			'start' => $start,
			'end' => $end,
			'dealership'=> $dealership,
			'city' => $city,
			'state' => $state,
			'process' => $process
		)),
	    'update' => '#filteredReports',
	    'evalScripts' => true,
	    'before' => '$("#filteredReports").html($("#loader-container").clone().removeAttr("id style"));',
     	'complete' => ''
	)); 

	$process_name = [1=>'FPR',2=>'PPJWT',3=>'FPMS'];
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<table id ="table" class="table" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th># de solicitud</th>
				<th>Grupo</th>
				<th>Ciudad</th>
				<th>Monto</th>
				<th>Detalle</th>
				<th>Estado</th>
				<th>Proceso</th>
				<th>Fecha</th>
			</tr>
		</thead>
		<tbody id="table_body">
			<?php foreach ($request as $keyRequest => $event) { ?>
				<tr>
					<td><?php echo($event['Request']['id']);?></td>
					<td><?php echo($event['Group']['name']);?></td>
					<td><?php echo(trim($event['Group']['city']));?></td>
					<td><?php echo("$".number_format($event['Request']['amount'], 0, ',', '.')) ?></td>
					<td><?php echo($event['Request']['title']) ?></td>
					<td><?php echo(trim($event['State']['name'])) ?></td>
					<td><?php echo $process_name[(trim($event['Request']['process_id']))] ?></td>
					<td><?php 
						$dt = new DateTime($event['Request']['created']);
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
			<a target="_blank" href="/web/reports/requestReportExcel?start=<?php echo($start);?>&end=<?php echo($end);?>&dealership=<?php echo($dealership);?>&city=<?php echo($city);?>&state=<?php echo($state);?>" class="btn btn-primary" role="button">Descargar reporte</a>
		</div>
	</div>
</div>
<?php echo $this->Js->writeBuffer(); ?>
