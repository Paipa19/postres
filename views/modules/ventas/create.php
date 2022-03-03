<?php

require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\DomiciliosController;
use App\Controllers\UsuariosController;
use App\Controllers\VentasController;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Venta";
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
                            <h1>Crear una Nueva <?= $nameModel ?></h1>
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
                            <div class="card card-info">
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
                                                    <label for="numeroVenta" class="col-sm-2 col-form-label">Número de venta</label>
                                                    <div class="col-sm-10">
                                                        <input required type="number" class="form-control" id="numeroVenta" name="numeroVenta"
                                                               placeholder="Ingrese su número de venta" value="<?= $frmSession['numeroVenta'] ?? '' ?>">
                                                    </div>
                                                </div>

                                                <div class="col-sm-10">
                                                    <div class="form-group row">
                                                        <label for="fecha" class="col-sm-2 col-form-label">Fecha </label>
                                                        <div class="col-sm-10">
                                                            <input required type="date" class="form-control" id="fecha" name="fecha"
                                                                   placeholder="Ingrese fecha" value="<?= $frmSession['fecha'] ?? '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="fecha" class="col-sm-2 col-form-label">Cliente </label>
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
                                                        <label for="total" class="col-sm-2 col-form-label">Total </label>
                                                        <div class="col-sm-10">
                                                            <input required type="number" class="form-control" id="total"
                                                                   name="total" placeholder="Ingrese total"
                                                                   value="<?= $frmSession['total'] ?? '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="fecha" class="col-sm-2 col-form-label">Domicilio </label>
                                                        <div class="col-sm-10">
                                                            <?= DomiciliosController::selectDomicilios(
                                                                array(
                                                                    'id' => 'domicilio_idDomicilio',
                                                                    'name' => 'domicilio_idDomicilio',
                                                                    'class' => 'form-control select2bs4 select2-info',
                                                                    'where' => 'idDomicilio = 0'
                                                                )
                                                            )
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="costoDomicilio" class="col-sm-2 col-form-label">Costo Domicilio</label>
                                                        <div class="col-sm-10">
                                                            <input required type="text" minlength="10" class="form-control"
                                                                   id="costoDomicilio" name="costoDomicilio" placeholder="Ingrese costo domicilio"
                                                                   value="<?= $frmSession['costoDomicilio'] ?? '' ?>">
                                                        </div>
                                                    </div>


                                                    <div class="form-group row">
                                                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                        <div class="col-sm-10">
                                                            <select required id="estado" name="estado" class="custom-select">
                                                                <option <?= (!empty($frmSession['estado']) && $frmSession['estado'] == "Aprobada") ? "selected" : ""; ?> value="Aprobada">Aprobada</option>
                                                                <option <?= (!empty($frmSession['estado']) && $frmSession['estado'] == "No aprobada") ? "selected" : ""; ?> value="No aprobada">No aprobada</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                <hr>
                                                <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-info">Enviar</button>
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
            $('#Usuario_idUsuario').on('change', function() {
                $.post("../../../app/Controllers/MainController.php?controller=Domicilios&action=selectDomicilios", {
                    isMultiple: false,
                    isRequired: true,
                    id: "domicilio_idDomicilio",
                    nombre: "domicilio_idDomicilio",
                    defaultValue: "",
                    class: "form-control select2bs4 select2-info",
                    where: "Usuario_idUsuario = "+$('#Usuario_idUsuario').val()+" ",
                    request: 'ajax'
                }, function(e) {
                    if (e)
                        console.log(e);
                    $("#domicilio_idDomicilio").html(e).select2({ height: '100px'});
                });
            });
            $('.btn-file span').html('Seleccionar');
        });
    </script>
    </body>
    </html>
<?php
