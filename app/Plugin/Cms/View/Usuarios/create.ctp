<div class="content">
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel hblue">
            <div class="panel-heading hbuilt">
                Nuevo Usuario
            </div>
            <div class="panel-body">

                <form name="simpleForm" novalidate id="simpleForm" action="#" method="post">

                     <div class="text-center m-b-md" id="wizardControl">
                        <a class="btn btn-primary" href="#step1" data-toggle="tab">Paso 1 - Creación de Usuario</a>
                        <a class="btn btn-default" href="#step2" data-toggle="tab">Paso 2 - Selección de Grupos</a>
                        <a class="btn btn-default" href="#step3" data-toggle="tab">Paso 3 - Guardar Usuario</a>
                    </div>

                    <div class="tab-content">
                    <div id="step1" class="p-m tab-pane active">

                        <div class="row">
                            <div class="col-lg-3 text-center">
                                <i class="pe-7s-user fa-5x text-muted"></i>
                                <p class="small m-t-md">
                                    <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard.
                                    <br/><br/>Lorem Ipsum has been the industry's dummy text of the printing and typesetting
                                </p>
                            </div>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label>Username</label>
                                        <input type="" value="" id="" class="form-control" name="username" placeholder="username">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Nombre</label>
                                        <input type="" value="" id="" class="form-control" name="" placeholder="Nombre" name="nombre">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Apellido</label>
                                        <input type="" value="" id="" class="form-control" name="" placeholder="Apellido" name="apellido">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Email</label>
                                        <input type="" value="" id="" class="form-control" name="" placeholder="user@email.com" name="email">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right m-t-xs">
                            <a class="btn btn-default prev" href="#">Previous</a>
                            <a class="btn btn-default next" href="#">Next</a>
                        </div>
                    </div>
                    <div id="step2" class="p-m tab-pane">

                            <div class="row">
                                <div class="col-lg-3 text-center">
                                    <i class="pe-7s-users fa-5x text-muted"></i>
                                    <p class="small m-t-md">
                                        <strong>It is a long</strong> established fact that a reader will be distracted by the readable
                                        <br/><br/>Many desktop publishing packages and web page editors now use
                                    </p>
                                </div>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Grupos</label>
                                            <select class="js-source-states-2" multiple="multiple" style="width: 100%">
                                                    <option>Seleccione un grupo</option>
                                                    <option>Concesionario Caracas</option>
                                                    <option>JWT</option>
                                                    <option>Ford</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right m-t-xs">
                                <a class="btn btn-default prev" href="#">Previous</a>
                                <a class="btn btn-default next" href="#">Next</a>
                            </div>

                        </div>
                        <div id="step3" class="tab-pane">
                            <div class="row text-center m-t-lg m-b-lg">
                                <div class="col-lg-12">
                                    <i class="pe-7s-check fa-5x text-muted"></i>
                                    <p class="small m-t-md">
                                        <strong>There are many</strong> variations of passages of Lorem Ipsum available, but the majority have suffered
                                    </p>
                                </div>
                                <div class="checkbox col-lg-12">
                                    <input type="checkbox" class="i-checks approveCheck" name="approve">
                                    Approve this form
                                </div>
                            </div>
                            <div class="text-right m-t-xs">
                                <a class="btn btn-default prev" href="#">Previous</a>
                                <a class="btn btn-default next" href="#">Next</a>
                                <a class="btn btn-success submitWizard" href="#">Submit</a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="m-t-md">

                    <p>
                        This is an example of a wizard form which can be easy adjusted. Since each step is a tab, and each clik to next tab is a function you can easily add validation or any other functionality.
                    </p>

                </div>

            </div>
        </div>
    </div>
</div>
</div>

<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/sweetalert/lib/sweet-alert.css');?>" />
<script src="<?php echo $this->Html->url('/cms/vendor/sweetalert/lib/sweet-alert.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/select2-3.5.2/select2.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/select2-3.5.2/select2.css');?>" />
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/select2-bootstrap/select2-bootstrap.css');?>" />

<script>
    $(".js-source-states-2").select2();

    $(function(){

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('a[data-toggle="tab"]').removeClass('btn-primary');
            $('a[data-toggle="tab"]').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        })

        $('.next').click(function(){
            var nextId = $(this).parents('.tab-pane').next().attr("id");
            $('[href=#'+nextId+']').tab('show');
        })

        $('.prev').click(function(){
            var prevId = $(this).parents('.tab-pane').prev().attr("id");
            $('[href=#'+prevId+']').tab('show');
        })

        $('.submitWizard').click(function(){

            var approve = $(".approveCheck").is(':checked');
            if(approve) {
                // Got to step 1
                $('[href=#step1]').tab('show');

                // Serialize data to post method
                var datastring = $("#simpleForm").serialize();

                // Show notification
                swal({
                    title: "Thank you!",
                    text: "You approved our example form!",
                    type: "success"
                });
//            Example code for post form
//            $.ajax({
//                type: "POST",
//                url: "your_link.php",
//                data: datastring,
//                success: function(data) {
//                    // Notification
//                }
//            });
            } else {
                // Show notification
                swal({
                    title: "Error!",
                    text: "You have to approve form checkbox.",
                    type: "error"
                });
            }
        })
    });
</script>