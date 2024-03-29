<?php

require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\PagosController;
use App\Controllers\VentasController;
use App\Models\GeneralFunctions;
use App\Enums\EstadoPago;

$nameModel = "Pago";
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
                                                    <label for="abono" class="col-sm-2 col-form-label">Abono</label>
                                                    <div class="col-sm-10">
                                                        <input required type="number" class="form-control" id="abono" name="abono"
                                                               placeholder="Ingrese el abono" value="<?= $frmSession['abono'] ?? '' ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="saldo" class="col-sm-2 col-form-label">Saldo</label>
                                                    <div class="col-sm-10">
                                                        <input required type="number" class="form-control" id="saldo"
                                                               name="saldo" placeholder="Ingrese el saldo"
                                                               value="<?= $frmSession['saldo'] ?? '' ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="fechaPago" class="col-sm-2 col-form-label">Fecha de pago</label>
                                                    <div class="col-sm-10">
                                                        <input required type="date" minlength="6" class="form-control"
                                                               id="fechaPago" name="fechaPago" placeholder="Ingrese la fecha de pago"
                                                               value="<?= $frmSession['fechaPago'] ?? '' ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="descuento" class="col-sm-2 col-form-label">Descuento</label>
                                                    <div class="col-sm-10">
                                                        <input required type="number" minlength="6" class="form-control"
                                                               id="descuento" name="descuento" placeholder="Ingrese el valor del descuento"
                                                               value="<?= $frmSession['descuento'] ?? '' ?>">
                                                    </div>
                                                </div>

                                                    <div class="form-group row">
                                                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                        <div class="col-sm-10">
                                                            <select required id="estado" name="estado" class="custom-select">
                                                                <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "Cancelado") ? "selected" : ""; ?> value="Cancelado">Cancelado</option>
                                                                <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "Pendiente") ? "selected" : ""; ?> value="Pendiente">Pendiente</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <div class="form-group row">
                                                    <label for="Venta_idVenta" class="col-sm-2 col-form-label">Venta </label>
                                                    <div class="col-sm-10">
                                                        <?= VentasController::selectVentas(
                                                            array(
                                                                'id' => 'Venta_idVenta',
                                                                'name' => 'Venta_idVenta',
                                                                'class' => 'form-control select2bs4 select2-info',
                                                                'where' => ''
                                                            )
                                                        )
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

    </body>
    </html>
<?php
