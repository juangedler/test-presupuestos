<?php   
	$this->Paginator->options(array(
		'url'=> array('controller' => 'reports', 'action' => 'availableBalancesReportFilter', '?' => array(
			//'start'=> $start,
			'end' => $end,
			'dealership'=> $dealership,
			'city' => $city
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
				<th>Concesionario</th>
				<th>Ciudad</th>
				<th style="text-align: right;">Individual Acumulado</th>
				<th style="text-align: right;">Nacional Acumulado</th>
				<!-- <th>Pendiente</th> -->
				<th style="text-align: right;">Disponible</th>

			</tr>
		</thead>
		<tbody id="table_body">
			<?php 
				foreach ($balances as $keyBalance => $balance) { 
				$nsm = 0;
				if(isset($national_pending[$balance['Group']['id']]) && $national_pending[$balance['Group']['id']] != null) $nsm = $national_pending[$balance['Group']['id']];
			?>
				<tr>
					<td><?php echo($balance['Group']['name']);?></td>
					<td><?php echo(trim($balance['Group']['city']));?></td>
					<td style="text-align: right;"><?= "$".number_format($balance[0]['sumAmount'], 0, ',', '.')?></td>
					<td style="text-align: right;"><?= "$".number_format($balance[0]['sumNational'] - $nsm, 0, ',', '.')?></td>
					<?php
					/*
					<td><?= $balance[0]['sumPending']?></td>
					*/
					?>
					<td style="text-align: right;"><?= "$".number_format($balance[0]['sumAmount']-$balance[0]['sumPending'], 0, ',', '.')?></td>
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
			<a target="_blank" href="/web/reports/availableBalancesReportExcel?start=<?php //echo($start);?>&end=<?php echo($end);?>&dealership=<?php echo($dealership);?>&city=<?php echo($city);?>" class="btn btn-primary" role="button">Descargar reporte</a>
		</div>
	</div>
</div>
<?php echo $this->Js->writeBuffer(); ?>
