<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
require("../../../app/Controllers/PagosController.php");

use App\Controllers\ProductosController;
use App\Controllers\PagosController;
use App\Controllers\VentasController;
use App\Models\GeneralFunctions;
use App\Models\Pagos;
use Carbon\Carbon;

$nameModel = "Pago";
$nameForm = 'frmEdit'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE']  ?> | Editar <?= $nameModel ?></title>
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
                        <h1>Editar <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensajes de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <?= (empty($_GET['id'])) ? GeneralFunctions::getAlertDialog('error', 'Faltan Criterios de Búsqueda') : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-olive">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i>&nbsp; Información del <?= $nameModel ?></h3>
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
                            <?php if (!empty($_GET["id"]) && isset($_GET["id"])) { ?>
                                <p>
                                <?php

                                $DataPago = PagosController::searchForId(["idPago" => $_GET["id"]]);
                                /* @var $DataPago Pagos */
                                if (!empty($DataPago)) {
                                    ?>
                                    <!-- form start -->
                                    <div class="card-body">
                                        <form class="form-horizontal" enctype="multipart/form-data" method="post" id="<?= $nameForm ?>"
                                              name="<?= $nameForm ?>"
                                              action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=edit">
                                            <input id="idPago" name="idPago" value="<?= $DataPago->getIdPago(); ?>" hidden
                                                   required="required" type="text">
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <div class="form-group row">
                                                        <label for="abono" class="col-sm-2 col-form-label">Abono</label>
                                                        <div class="col-sm-10">
                                                            <input required type="text" class="form-control" id="abono"
                                                                   name="abono" value="<?= $DataPago->getAbono(); ?>"
                                                                   placeholder="Ingrese el abono">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="saldo" class="col-sm-2 col-form-label">Saldo</label>
                                                        <div class="col-sm-10">
                                                            <input required type="text" class="form-control" id="saldo"
                                                                   name="saldo" value="<?= $DataPago->getSaldo(); ?>"
                                                                   placeholder="Ingrese el saldo">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="fechaPago" class="col-sm-2 col-form-label">Fecha de pago</label>
                                                        <div class="col-sm-10">
                                                            <input required type="date" minlength="6" class="form-control"
                                                                   id="fechaPago" name="fechaPago"
                                                                   value="<?= $DataPago->getFechaPago()->toDateString(); ?>"
                                                                   placeholder="Ingrese la fecha de pago">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="descuento" class="col-sm-2 col-form-label">Descuento</label>
                                                        <div class="col-sm-10">
                                                            <input required type="number" minlength="6" class="form-control"
                                                                   id="descuento" name="descuento"
                                                                   value="<?= $DataPago->getDescuento(); ?>"
                                                                   placeholder="Ingrese el valor del descuento">
                                                        </div>
                                                    </div>


                                                        <div class="form-group row">
                                                            <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                            <div class="col-sm-10">
                                                                <select required id="estado" name="estado" class="custom-select">
                                                                    <option <?= ($DataPago->getEstadoPago() == "Cancelado") ? "selected" : ""; ?> value="Cancelado">Cancelado</option>
                                                                    <option <?= ($DataPago->getEstadoPago() == "Pendiente") ? "selected" : ""; ?> value="Pendiente">Pendiente</option>
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
                                                                    'defaultValue' => $DataPago?->getVentaIdVenta() ?? '',
                                                                    'where' => ''
                                                                )
                                                            )
                                                            ?>
                                                        </div>
                                                    </div>

                                            <hr>
                                            <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-secondary">Enviar</button>
                                            <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                        </form>
                                    </div>
                                    <!-- /.card-body -->

                                <?php } else { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        No se encontro ningun registro con estos parametros de
                                        busqueda <?= ($_GET['mensaje']) ?? "" ?>
                                    </div>
                                <?php } ?>
                                </p>
                            <?php } ?>
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

