<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />
<?php   
	$this->Paginator->options(array(
		'url'=> array('controller' => 'reports', 'action' => 'eventsReportFilter', '?' => array(
			'start' => $start,
			'end' => $end,
			'dealership'=> $dealership,
			'product' => $product,
			'media' => $media,
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
				<th>T&iacute;tulo</th>
				<th>Ciudad</th>
				<th>Monto</th>
				<th>Estado</th>
				<th>Producto</th>
				<th>Media</th>
				<th>Fecha solucitud</th>
				<th>Inicio actividad</th>
				<th>Fin actividad</th>
			</tr>
		</thead>
		<tbody id="table_body">
			<?php foreach ($events as $keyEvent => $event) { ?>
				<tr>
					<td><?php echo($event['Request']['id']);?></td>
					<td><?php echo($event['Group']['name']);?></td>
					<td><?php echo($event['Request']['title']) ?></td>
					<td><?php echo(trim($event['Group']['city']));?></td>
					<td><?php echo("$".number_format($event['Event']['amount'], 0, ',', '.')) ?></td>
					<td><?php echo(trim($event['State']['name'])) ?></td>
					<td><?php echo(trim($event['Event']['line'])) ?></td>
					<td><?php echo(trim($event['Media']['name'])) ?></td>
					<td><?php 
						$dt = new DateTime($event['Request']['created']);
						echo($dt->format('d/m/Y')); ?>
					</td>
					<td><?php 
						$dt = new DateTime($event['Date']['start']);
						echo($dt->format('d/m/Y')); ?>
					</td>
					<td><?php 
						$dt = new DateTime($event['Date']['end']);
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
			<a target="_blank" href="/web/reports/eventsReportExcel?start=<?php echo($start);?>&end=<?php echo($end);?>&dealership=<?php echo($dealership);?>&city=<?php echo($city);?>&state=<?php echo($state);?>&product=<?php echo($product);?>&media=<?php echo($media);?>" class="btn btn-primary" role="button">Descargar reporte</a>
		</div>
	</div>
</div>
<?php echo $this->Js->writeBuffer(); ?>

