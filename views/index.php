<?php require("partials/routes.php"); ?>
<?php  require("partials/check_login.php"); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Inicio</title>
    <?php require("partials/head_imports.php"); ?>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse ">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("partials/navbar_customization.php"); ?>

    <?php require("partials/sliderbar_main_menu.php"); ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Dessert Store</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">

                            <li class="breadcrumb-item"><a href="<?=$adminlteURL; ?>/views/index.php"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item active">Inicio</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
                           <div class="card">
                               <div class="card-header">
                                   <h4 class="card-text"><strong>¡Bienvenidos!</strong> </h4>
                                   <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <h5>Te damos la bienvenida al sistema de inventarios de la empresa <strong>DESSERT STORE</strong>, este sistema esta diseñado con el fin de gestionar los diferentes aspectos que se destacan en la empresa, el software se diseño de una manera facil de comprender y poco compleja a la hora de ejecutar, esperamos sea gratificante el uso de este sistema. </h5>


                    <center>
                        <img src="<?= $baseURL ?>/views/public/img/logo.jpg" class="img-circle elevation-2" alt="Imagen principal">
                    </center>

                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    <h5> Gracias por ingresar a nuestro sistema.</h5>
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php require ('partials/footer.php');?>
</div>
<!-- ./wrapper -->
<?php require ('partials/scripts.php');?>
</body>
</html>