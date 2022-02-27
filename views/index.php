<?php require("partials/routes.php"); ?>
<?php  require("partials/check_login.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Inicio</title>
    <?php require("partials/head_imports.php"); ?>
</head>
<body class="hold-transition sidebar-mini">

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
                        <h1>Diarma</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?=$adminlteURL; ?>/views/index.php"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item active">Home</li>
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
                    <h3 class="card-title">Bienvenidos</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Tortor condimentum lacinia quis vel eros donec ac odio tempor. Vel eros donec ac odio tempor orci. Et ligula ullamcorper malesuada proin libero. Scelerisque viverra mauris in aliquam sem fringilla. Sed ullamcorper morbi tincidunt ornare. Nunc mi ipsum faucibus vitae aliquet nec ullamcorper sit. Aenean sed adipiscing diam donec adipiscing tristique risus nec feugiat. Mauris vitae ultricies leo integer malesuada nunc. Id nibh tortor id aliquet lectus. Orci nulla pellentesque dignissim enim sit amet venenatis urna. Enim nunc faucibus a pellentesque sit amet porttitor. Volutpat commodo sed egestas egestas. Imperdiet sed euismod nisi porta lorem mollis aliquam ut porttitor. Ac auctor augue mauris augue. Facilisi etiam dignissim diam quis enim lobortis scelerisque fermentum dui.
                    <br>
                    <br>
                    <center><img src="postres/views/public/img/OIP.jpg" width="300"
                                 height="201"></center>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    Gracias por ingresar a nuestro Programa
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