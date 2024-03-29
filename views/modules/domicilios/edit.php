<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
require("../../../app/Controllers/DomiciliosController.php");

use App\Controllers\DepartamentosController;
use App\Controllers\MunicipiosController;
use App\Controllers\DomiciliosController;
use App\Controllers\UsuariosController;
use App\Models\GeneralFunctions;
use App\Models\Domicilios;

$nameModel = "Domicilio";
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

                                    $DataDomicilio = DomiciliosController::searchForId(["idDomicilio" => $_GET["id"]]);
                                    /* @var $DataDomicilio Domicilios */
                                    if (!empty($DataDomicilio)) {
                                        ?>
                                        <!-- form start -->
                                        <div class="card-body">
                                            <form class="form-horizontal" enctype="multipart/form-data" method="post" id="<?= $nameForm ?>"
                                                  name="<?= $nameForm ?>"
                                                  action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=edit">
                                                <input id="idDomicilio" name="idDomicilio" value="<?= $DataDomicilio->getIdDomicilio(); ?>" hidden
                                                       required="required" type="text">
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <div class="form-group row">
                                                            <label for="fecha" class="col-sm-2 col-form-label">Cliente </label>
                                                            <div class="col-sm-10">
                                                                <?= UsuariosController::selectUsuario(
                                                                    array(
                                                                        'id' => 'Usuario_idUsuario',
                                                                        'name' => 'Usuario_idUsuario',
                                                                        'defaultValue' => (!empty($DataDomicilio)) ? $DataDomicilio->getUsuario()->getIdUsuario() : '',
                                                                        'class' => 'form-control select2bs4 select2-info',
                                                                        'where' => "estado = 'Activo'"
                                                                    )
                                                                )
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="direccion" class="col-sm-2 col-form-label">Direccion</label>
                                                            <div class="col-sm-10">
                                                                <input required type="text" class="form-control" id="direccion"
                                                                       name="direccion" value="<?= $DataDomicilio->getDireccion(); ?>"
                                                                       placeholder="Ingrese su direccion">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="telefono" class="col-sm-2 col-form-label">Telefono</label>
                                                            <div class="col-sm-10">
                                                                <input required type="number" minlength="6" class="form-control"
                                                                       id="telefono" name="telefono"
                                                                       value="<?= $DataDomicilio->getTelefono(); ?>"
                                                                       placeholder="Ingrese su telefono">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="municipio_id" class="col-sm-2 col-form-label">Municipio</label>
                                                            <div class="col-sm-5">
                                                                <?=
                                                                DepartamentosController::selectDepartamentos(
                                                                    array(
                                                                        'id' => 'departamento_id',
                                                                        'name' => 'departamento_id',
                                                                        'defaultValue' => (!empty($DataDomicilio)) ? $DataDomicilio->getMunicipio()->getDepartamento()->getId() : '15',
                                                                        'class' => 'form-control select2bs4 select2-info',
                                                                        'where' => "estado = 'Activo'"
                                                                    )
                                                                )
                                                                ?>
                                                            </div>
                                                            <div class="col-sm-5 ">
                                                                <?= MunicipiosController::selectMunicipios(
                                                                    array (
                                                                        'id' => 'municipios_id',
                                                                        'name' => 'municipios_id',
                                                                        'defaultValue' => (!empty($DataDomicilio)) ? $DataDomicilio->getMunicipio()->getId() : '',
                                                                        'class' => 'form-control select2bs4 select2-info',
                                                                        'where' => "departamento_id = ".$DataDomicilio->getMunicipio()->getDepartamento()->getId()." and estado = 'Activo'")
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
    <script>
        $(function() {
            $('#departamento_id').on('change', function() {
                $.post("../../../app/Controllers/MainController.php?controller=Municipios&action=selectMunicipios", {
                    isMultiple: false,
                    isRequired: true,
                    id: "municipios_id",
                    nombre: "municipios_id",
                    defaultValue: "",
                    class: "form-control select2bs4 select2-info",
                    where: "departamento_id = "+$('#departamento_id').val()+" and estado = 'Activo'",
                    request: 'ajax'
                }, function(e) {
                    if (e)
                        console.log(e);
                    $("#municipios_id").html(e).select2({ height: '100px'});
                })
            });
        });
    </script>
    </body>
    </html>

<?php
