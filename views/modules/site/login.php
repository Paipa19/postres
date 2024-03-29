
<?php

require_once("../../../app/Controllers/UsuariosController.php");
require_once("../../partials/routes.php");

?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Login</title>
    <?php require("../../partials/head_imports.php"); ?>
</head>
<body class="hold-transition login-page">


    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <div class="login-box">
                <div class="login-logo">
                    <a href="login.php"><strong>Dessert Store</strong></a>
                </div>
            <p class="login-box-msg">Ingrese sus datos para iniciar sesión</p>
            <form action="../../../app/Controllers/MainController.php?controller=Usuarios&action=login" method="post">
                <div class="input-group mb-3">
                    <input type="number" id="numeroIdentificacion" name="numeroIdentificacion" class="form-control" placeholder="Número de Documento">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Contraseña">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                    </div>
                    <!-- /.col -->
                </div>
                <br>
                <?php if (!empty($_GET['respuesta'])) { ?>
                    <?php if ( !empty($_GET['respuesta']) && $_GET['respuesta'] != "correcto" ) { ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Error al Ingresar: </h5> <?= $_GET['mensaje'] ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </form>

        </div>

    </div>
</div>

<?php require('../../partials/scripts.php'); ?>

</body>
</html>
