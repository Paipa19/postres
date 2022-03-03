<?php

require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\DetalleVentasController;
use App\Controllers\ProductosController;
use App\Controllers\VentasController;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Detalle Venta";
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
                                                <label for="fecha" class="col-sm-2 col-form-label">N° Venta </label>
                                                <div class="form-group row">
                                                    <?= VentasController::selectVentas(
                                                        array(
                                                            'id' => 'Venta_idVenta',
                                                            'name' => 'Venta_idVenta',
                                                            'class' => 'form-control select2bs4 select2-info',
                                                            'where' => "estado = 'Aprobada'"
                                                        )
                                                    )
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="col-sm-10">
                                                <label for="fecha" class="col-sm-2 col-form-label">Productos </label>
                                                <div class="form-group row">
                                                    <?= ProductosController::selectProducto(
                                                        array(
                                                            'id' => 'Producto_idProducto',
                                                            'name' => 'Producto_idProducto',
                                                            'class' => 'form-control select2bs4 select2-info',
                                                            'where' => "estado = 'Disponible'"
                                                        )
                                                    )
                                                    ?>
                                                </div>
                                            </div>



                                                <div class="col-sm-10">
                                                    <label for="cantidad" class="col-sm-2 col-form-label">Cantidad</label>
                                                    <div class="form-group row">
                                                        <input required type="number" class="form-control" id="numeroVenta" name="cantidad"
                                                               placeholder="cantidad" value="<?= $frmSession['cantidad'] ?? '' ?>">
                                                    </div>
                                                </div>


                                                    <div class="col-sm-10">
                                                        <label for="fechaVencimiento" class="col-sm-2 col-form-label">Fecha Vencimiento </label>
                                                        <div class="form-group row">
                                                            <input required type="date" class="form-control" id="fecha" name="fechaVencimiento"
                                                                   placeholder="Ingrese fecha de vencimiento" value="<?= $frmSession['fechaVencimiento'] ?? '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <label for="numDetalleVenta" class="col-sm-2 col-form-label">Numero Detalle Venta </label>
                                                        <div class="form-group row">
                                                            <input required type="number" class="form-control" id="numDetalleVenta"
                                                                   name="total" placeholder="Numero Detalle Venta"
                                                                   value="<?= $frmSession['numDetalleVenta'] ?? '' ?>">
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

    </body>
    </html>
<?php

