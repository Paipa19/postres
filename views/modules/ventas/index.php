<?php
require_once("../../../app/Controllers/VentasController.php");
require_once("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\VentasController;
use App\Models\GeneralFunctions;
use App\Models\Ventas;

$nameModel = "Venta";
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION['frm'.$pluralModel] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Gestión de <?= $pluralModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-responsive/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-buttons/css/buttons.bootstrap4.css">
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require_once("../../partials/navbar_customization.php"); ?>

    <?php require_once("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1> <strong> Dessert Store </strong> </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item active"><?= $pluralModel ?></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensajes de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="card card-olive">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i> &nbsp; Gestionar <?= $pluralModel ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                            data-source="index.php" data-source-selector="#card-refresh-content"
                                            data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                            class="fas fa-expand"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            data-toggle="tooltip" title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove"
                                            data-toggle="tooltip" title="Remove">
                                        <i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto mr-auto"></div>
                                    <div class="col-auto">
                                        <a role="button" href="create.php" class="btn btn-secondary float-right"
                                           style="margin-right: 5px;">
                                            <i class="fas fa-plus"></i> Crear <?= $nameModel ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <dtiv class="col">
                                        <table id="tbl<?= $pluralModel ?>" class="datatable table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Usuario</th>
                                                <th>Domicilio</th>
                                                <th>Número de Venta</th>
                                                <th>Fecha</th>
                                                <th>Total</th>
                                                <th>Costo Domicilio</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $arrVentas = VentasController::getAll();
                                            /* @var $arrVentas Ventas[] */
                                            if(is_array($arrVentas))
                                            foreach ($arrVentas as $venta) {
                                                ?>
                                                <tr>
                                                    <td><?= $venta->getDomicilioIdDomicilio(); ?></td>
                                                    <td><?= $venta->getUsuario()->getNombre(); ?></td>
                                                    <td><?= $venta->getIdVenta(); ?></td>
                                                    <td><?= $venta->getNumeroVenta(); ?></td>
                                                    <td><?= $venta->getFecha(); ?></td>
                                                    <td><?= $venta->getTotal(); ?></td>
                                                    <td><?= $venta->getCostoDomicilio(); ?></td>
                                                    <td><?= $venta->getEstadoVenta(); ?></td>
                                                    <td>
                                                        <a href="edit.php?id=<?=  $venta->getIdVenta(); ?>"
                                                           type="button" data-toggle="tooltip" title="Actualizar"
                                                           class="btn docs-tooltip btn-success btn-xs"><i
                                                                class="fa fa-edit"></i></a>
                                                        <a href="show.php?id=<?=  $venta->getIdVenta(); ?>"
                                                           type="button" data-toggle="tooltip" title="Ver"
                                                           class="btn docs-tooltip btn-warning btn-xs"><i
                                                                class="fa fa-eye"></i></a>
                                                        <a href="../ventas/create.php?id=<?= $venta->getIdVenta(); ?>"
                                                           type="button" data-toggle="tooltip" title="Gestionar detalle venta"
                                                           class="btn docs-tooltip btn-dark btn-xs"><i
                                                                    class="fa fa-photo-video"></i></a>

                                                        <?php if ($venta->getEstadoVenta() != "Aprobada") { ?>
                                                            <a href="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=cancel&idVenta=<?= $venta->getIdVenta(); ?>"
                                                               type="button" data-toggle="tooltip" title="Aprobada"
                                                               class="btn docs-tooltip btn-success btn-xs"><i
                                                                        class="fa fa-check-square"></i></a>
                                                        <?php } else { ?>

                                                            <a type="button"
                                                               href="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=cancel&idVenta=<?= $venta->getIdVenta(); ?>"
                                                               data-toggle="tooltip" title="No aprobada"
                                                               class="btn docs-tooltip btn-danger btn-xs"><i
                                                                        class="fa fa-times-circle"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Usuario</th>
                                                <th>Domicilio</th>
                                                <th>Número de Venta</th>
                                                <th>Fecha</th>
                                                <th>Total</th>
                                                <th>Costo Domicilio</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </dtiv>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">

                            </div>
                            <!-- /.card-footer-->
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
<!-- Scripts requeridos para las datatables -->
<?php require('../../partials/datatables_scripts.php'); ?>

</body>
</html>

