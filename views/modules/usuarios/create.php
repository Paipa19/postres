<?php

require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\UsuariosController;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Usuario";
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
                                            <div class="form-group row">
                                                <label for="numeroIdentificacion" class="col-sm-2 col-form-label">Numero de identificacion</label>
                                                <div class="col-sm-10">
                                                    <input required type="number" class="form-control" id="numeroIdentificacion" name="numeroIdentificacion"
                                                           placeholder="Ingrese su numero de idetificacion" value="<?= $frmSession['numeroIdentificacion'] ?? '' ?>">
                                                </div>
                                            </div>

                                        <div class="col-sm-10">
                                            <div class="form-group row">
                                                <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="nombre" name="nombre"
                                                           placeholder="Ingrese su nombre" value="<?= $frmSession['nombre'] ?? '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="apellido" class="col-sm-2 col-form-label">Apellido</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="apellido"
                                                           name="apellido" placeholder="Ingrese su apellido"
                                                           value="<?= $frmSession['apellido'] ?? '' ?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="telefono" class="col-sm-2 col-form-label">Telefono</label>
                                                <div class="col-sm-10">
                                                    <input required type="number" minlength="10" class="form-control"
                                                           id="telefono" name="telefono" placeholder="Ingrese su telefono"
                                                           value="<?= $frmSession['telefono'] ?? '' ?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="correo" class="col-sm-2 col-form-label">Correo</label>
                                                <div class="col-sm-10">
                                                    <input required type="email" minlength="6" class="form-control"
                                                           id="correo" name="correo" placeholder="Ingrese su correo electronico"
                                                           value="<?= $frmSession['correo'] ?? '' ?>">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="rol" class="col-sm-2 col-form-label">Rol</label>
                                                <div class="col-sm-10">
                                                    <select required id="rol" name="rol" class="custom-select">
                                                        <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "Administrador") ? "selected" : ""; ?> value="Administrador">Administrador</option>
                                                        <option <?= (!empty($frmSession['rol']) && $frmSession['rol'] == "Empleado") ? "selected" : ""; ?> value="Empleado">Empleado</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="contrasena" class="col-sm-2 col-form-label">Contraseña</label>
                                                <div class="col-sm-10">
                                                    <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña">
                                                </div>
                                            </div>


                                                <div class="form-group row">
                                                    <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                    <div class="col-sm-10">
                                                        <select required id="estado" name="estado" class="custom-select">
                                                            <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "Activo") ? "selected" : ""; ?> value="Activo">Activo</option>
                                                            <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "Inactivo") ? "selected" : ""; ?> value="Inactivo">Inactivo</option>
                                                        </select>
                                                    </div>
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
