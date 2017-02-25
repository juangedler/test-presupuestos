<?php foreach ($supports as $keySupport => $support) {?>
	<div id="support-<?php echo($support['RequestSupport']['id']);?>" class="row" style="margin-bottom: 2px;">
		<div class="<?php if($isDealership == 1){ ?>col-xs-9 col-sm-9 col-md-9 col-lg-9 <?php }else{?>col-xs-10 col-sm-10 col-md-10 col-lg-10<?php }?>">
			<span><?php echo($support['RequestSupport']['file'])?></span>
		</div>
		<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
			<a class="viewSupport special-a" href="<?php echo $this->Html->url('/files/soportes/'.$support['RequestSupport']['file'])?>" target="blank">Ver</a>
		</div>
		<?php if($isDealership == 1 & $is_supportable){ ?>
			<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
				<a class="deleteSupport special-a" rel="<?php echo($support['RequestSupport']['id'])?>" href="#">Eliminar</a>
			</div>
		<?php }?>
	</div>
<?php } ?>