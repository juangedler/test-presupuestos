<button type="button" class="close" data-dismiss="modal" style="margin-top:10px;margin-right:10px;">&times;</button>
<div id="modal-header-1" class="modal-header text-center">
    <h4 id="concesionario_modal" class="modal-title">Mindshare</h4>
    <small class="font-bold">Presupuesto Publicitario</small>
</div>
<div class="modal-body">
    <div id="modal-body-1">
        <div class="row">
            <div class="hpanel hblue">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="text-info pull-right">
                                &nbsp;$<span class="moneh"><?php echo $requestFile[0]['RequestFile']['amount'];?></span>
                            </h4>
                            <br>
                            <br>
                            <h4><?php echo $requestFile[0]['RequestFile']['title'];?></h4>

                            <p><u>Descripción</u>: <?php echo $requestFile[0]['RequestFile']['description'];?></p>

                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="project-label">#DOCUMENTO</div>
                                    <small><?php echo $requestFile[0]['RequestFile']['number'];?></small>
                                </div>
                                <div class="col-sm-5">
                                    <div class="project-label">FECHA DE CREACIÓN</div>
                                        <small><?php echo date('d/m/Y',strtotime($requestFile[0]['RequestFile']['created']));?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                if(count($requestFile['Note']) > 0){
            ?>
            <div class="hpanel hyellow">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Observaciones</h4>
                            <?php
                                if(isset($requestFile['Note'][0]) && count($requestFile['Note'][0]) != 0)
                                    echo '<p><u>Gerente de Comunicaciones Ford</u>: '.
                                        $requestFile['Note'][0]['RequestFileNote']['note'].'<br><sup>('.
                                        $requestFile['Note'][0]['User']['first_name'].' '.
                                        $requestFile['Note'][0]['User']['last_name'].' - '.
                                        date('d/m/Y',strtotime($requestFile['Note'][0]['RequestFileNote']['created'])).')</sup></p>';
                                if(isset($requestFile['Note'][1]) && count($requestFile['Note'][1]) != 0)
                                    echo '<p><u>Gerente de Mercadeo Ford:</u>: '.
                                        $requestFile['Note'][1]['RequestFileNote']['note'].'<br><sup>('.
                                        $requestFile['Note'][1]['User']['first_name'].' '.
                                        $requestFile['Note'][1]['User']['last_name'].' - '.
                                        date('d/m/Y',strtotime($requestFile['Note'][1]['RequestFileNote']['created'])).')</sup></p>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
                if(isset($requestFile[1])){
            ?>
            <div class="hpanel hgreen">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Versiones Anteriores</h4>
                            <?php
                                for($i = 1; $i < count($requestFile)-1; $i++){
                            ?>
                                <div class="col-sm-6" align="left">
                                <?php 
                                    if($requestFile[0]['Request']['request_type_id'] == 2)
                                    echo 'Tango ID: '.$requestFile[$i]['RequestFile']['number'].' - '.date('d/m/Y',strtotime($requestFile[$i]['RequestFile']['created']));
                                    else
                                    echo date('d/m/Y',strtotime($requestFile[$i]['RequestFile']['created']));

                                ?>
                                </div>
                                <div class="col-sm-6" align="right"><a class="" href="<?php echo Router::url('/') .$requestFile[$i]['RequestFile']['file'];?>" target="_blank" style="width: 100px;">Descargar</a></div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>

        </div>
    </div>
    <div id="modal-body-2" class="row">
    <?php
        if( ($requestFile[0]['RequestFile']['status'] == 5 && in_array('4', $group_types))  || 
            ($requestFile[0]['RequestFile']['status'] == 6 && in_array('6', $group_types))
        ){
    ?>
        <hr>
        <label>Observaciones:</label>
        <textarea type="text" class="requerido observaciones form-control" rows="2"></textarea>
        <br>
    <?php
        if(in_array($requestFile[0]['RequestFile']['status'],array(5))){
    ?>
        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal5">Solicitar Modificación</button>
    <?php
        }
    ?>
        <button type="button" class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target="#myModal2" style="margin-right:5px; width:75px;">Rechazar</button>
        <button type="button" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#myModal3" style="margin-right:5px; width:75px;">Aprobar</button>
    <?php
        }
    ?>
    </div>
</div>
<div id="modal-footer-1" class="modal-footer">
    <a id="descargar" type="button" class="btn btn-primary" href="<?php echo Router::url('/') .$requestFile[0]['RequestFile']['file'];?>" target="blank" style="width: 93px;">Descargar</a>
    <button id="cancelar" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 93px;">Cancelar</button>
</div>

<script>
    $('.moneh').mask('#.##0', {reverse: true});
    $('.modal').data('bs.modal').handleUpdate();
</script>