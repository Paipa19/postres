<?php

require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\DomiciliosController;
use App\Controllers\MunicipiosController;
use App\Controllers\DepartamentosController;
use App\Controllers\UsuariosController;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "domicilio";
$nameForm = 'frmCreate'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title><?= $_ENV['TITLE_SITE'] ?> | Crear <?= $nameModel ?></title>
        <?php require("../../partials/head_imports.php"); ?>
    </head>
    <body class="hold-transition sidebar-mini">

    <!-- Site wrapper -->
    <div class="wrapper">
        <?php require("../../partials/navbar_customization.php"); ?>

        <?php require("../../partials/sliderbar_main_menu.php"); ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Crear un Nuevo <?= $nameModel ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?=  $adminlteURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                                <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                                <li class="breadcrumb-item active">Crear</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <!-- Generar Mensaje de alerta -->
                <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Horizontal Form -->
                            <div class="card card-olive">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Información del <?= $nameModel ?></h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                                data-source="create.php" data-source-selector="#card-refresh-content"
                                                data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                class="fas fa-expand"></i></button>
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <!-- form start -->
                                    <form class="form-horizontal" enctype="multipart/form-data" method="post" id="<?= $nameForm ?>"
                                          name="<?= $nameForm ?>"
                                          action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=create">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <div class="form-group row">
                                                    <label for="direccion" class="col-sm-2 col-form-label">Direccion</label>
                                                    <div class="col-sm-10">
                                                        <input required type="text" class="form-control" id="direccion" name="direccion"
                                                               placeholder="Ingrese su direcccion" value="<?= $frmSession['direccion'] ?? '' ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="telefono" class="col-sm-2 col-form-label">Telefono</label>
                                                    <div class="col-sm-10">
                                                        <input required type="number" class="form-control" id="telefono"
                                                               name="telefono" placeholder="Ingrese su telefono"
                                                               value="<?= $frmSession['telefono'] ?? '' ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="Usuario_idUsuario" class="col-sm-2 col-form-label">Usuario </label>
                                                    <div class="col-sm-10">
                                                        <?= UsuariosController::selectUsuario(
                                                            array(
                                                                'id' => 'Usuario_idUsuario',
                                                                'name' => 'Usuario_idUsuario',
                                                                'class' => 'form-control select2bs4 select2-info',
                                                                'where' => "estado = 'Activo'"
                                                            )
                                                        )
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="municipio_id" class="col-sm-2 col-form-label">Municipio</label>
                                                    <div class="col-sm-5">
                                                        <?= DepartamentosController::selectDepartamentos(
                                                            array(
                                                                'id' => 'id',
                                                                'name' => 'id',
                                                                'defaultValue' => '15', //Boyacá
                                                                'class' => 'form-control select2bs4 select2-info',
                                                                'where' => "estado = 'Activo'"
                                                            )
                                                        )
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-5 ">
                                                        <?= MunicipiosController::selectMunicipios(array (
                                                            'id' => 'idMunicipio',
                                                            'name' => 'idMunicipio',
                                                            'defaultValue' => (!empty($frmSession['idMunicipio'])) ? $frmSession['idMunicipio'] : '',
                                                            'class' => 'form-control select2bs4 select2-info',
                                                            'where' => "departamento_id = 15 and estado = 'Activo'"))
                                                        ?>
                                                    </div>
                                                </div>


                                        <hr>
                                        <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-secondary">Enviar</button>
                                        <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                        <!-- /.card-footer -->
                                    </form>
                                </div>
                                <!-- /.card-body -->

                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php require('../../partials/footer.php'); ?>
    </div>
    <!-- ./wrapper -->
    <?php require('../../partials/scripts.php'); ?>
    <script>
        $(function() {
            $('#id').on('change', function() {
                $.post("../../../app/Controllers/MainController.php?controller=Municipios&action=selectMunicipios", {
                    isMultiple: false,
                    isRequired: true,
                    id: "idMunicipio",
                    nombre: "idMunicipio",
                    defaultValue: "",
                    class: "form-control select2bs4 select2-info",
                    where: "departamento_id = "+$('#id').val()+" and estado = 'Activo'",
                    request: 'ajax'
                }, function(e) {
                    $("#idMunicipio").html(e).select2({ height: '100px'});
                });
            });
            $('.btn-file span').html('Seleccionar');
        });
    </script>
    </body>
    </html>
<?php
