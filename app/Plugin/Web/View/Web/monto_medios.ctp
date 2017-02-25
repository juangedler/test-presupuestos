<div class="panel-body list">
<?php
	if(count($query) == 0):
?>
	<p>No hay información disponible para el mes y año seleccionado.</p>
<?php
	else:
?>
    <div class="table-responsive project-list">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Medio</th>
                <th style="text-align: right;">Monto total</th>
            </tr>
            </thead>
            <tbody>
            <?php
        	foreach ($query as $p) {
            ?>
            <tr>
                <td>
                	<?= $p['Media']['name']?>
                </td>
                <td align="right">
                    $<span class="moneh">$<?=$p['0']['total']?></span>
                </td>
            </tr>
            <?php
        	}
            ?>
            </tbody>
        </table>
    </div>
<?php
	endif;
?>
</div>

<script src="<?php echo $this->Html->url('/web/js/jquery.mask.min.js');?>"></script>
<script>
	$('.moneh').mask('#.##0', {reverse: true});
</script>