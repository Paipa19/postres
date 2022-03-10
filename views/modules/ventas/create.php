<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");

use App\Controllers\DomiciliosController;
use App\Controllers\ProductosController;
use App\Controllers\UsuariosController;
use App\Controllers\VentasController;
use App\Models\DetalleVentas;
use App\Models\GeneralFunctions;
use App\Models\Ventas;
use Carbon\Carbon;

$nameModel = "Venta";
$nameForm = 'frmCreate'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>

<?php
/* @var $dataVenta Ventas */
$dataVenta = null;
if (!empty($_GET['id'])) {
    /* @var $dataVenta Ventas */
    $dataVenta = VentasController::searchForID(["idVenta" => $_GET['id']]);
    if ($dataVenta->getEstadoVenta() != "En proceso"){
        header('Location: index.php?respuesta=warning&mensaje=La venta ya ha finalizado');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Crear <?= $nameModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-responsive/css/responsive.bootstrap4.css">
    <link rel="stylesheet" href="<?= $adminlteURL ?>/plugins/datatables-buttons/css/buttons.bootstrap4.css">
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>

    <?php require("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Generar Mensaje de alerta -->
        <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Crear una nueva <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Crear</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-olive">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-shopping-cart"></i> &nbsp; Información de la
                                    <?= $nameModel ?></h3>
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

                            <div class="card-body">
                                <form class="form-horizontal" method="post" id="<?= $nameForm ?>" name="<?= $nameForm ?>"
                                      action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=create">
                                    <div class="form-group row">
                                        <label for="cliente_id" class="col-sm-4 col-form-label">Costo Domicilio</label>
                                        <div class="col-sm-8">
                                            <input required type="number" class="form-control" id="costoDomicilio" name="costoDomicilio"
                                                   placeholder="Costo del Domicilio" value="<?= $frmSession['costoDomicilio'] ?? '' ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="Usuario_idUsuario" class="col-sm-4 col-form-label">Usuario </label>
                                        <div class="col-sm-8">
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
                                        <label for="domicilio_idDomicilio" class="col-sm-4 col-form-label">Domicilio </label>
                                        <div class="col-sm-8">
                                            <?= DomiciliosController::selectDomicilios(
                                                array(
                                                    'id' => 'domicilio_idDomicilio',
                                                    'name' => 'domicilio_idDomicilio',
                                                    'class' => 'form-control select2bs4 select2-info',
                                                    'where' => ''
                                                )
                                            )
                                            ?>
                                        </div>
                                    </div>

                                    <?php
                                    if (!empty($dataVenta)) {
                                        ?>
                                        <div class="form-group row">
                                            <label for="numeroVenta" class="col-sm-4 col-form-label">Numero
                                                Venta</label>
                                            <div class="col-sm-8">
                                                <?= $dataVenta->getNumeroVenta() ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="fecha" class="col-sm-4 col-form-label">Fecha
                                                Venta</label>
                                            <div class="col-sm-8">
                                                <?= $dataVenta->getFecha() ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="total" class="col-sm-4 col-form-label">Total</label>
                                            <div class="col-sm-8">
                                                <?= GeneralFunctions::formatCurrency($dataVenta->getTotal()) ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <hr>
                                    <button type="submit" class="btn btn-secondary">Enviar</button>
                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="col-md-8">
                        <div class="card card-olive">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-parachute-box"></i> &nbsp; Detalle Venta</h3>
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

                            <div class="card-body">
                                <?php if (!empty($_GET['id'])) { ?>
                                    <div class="row">
                                        <div class="col-auto mr-auto"></div>
                                        <div class="col-auto">
                                            <button id="new-producto"
                                               class="btn btn-info float-right"
                                               style="margin-right: 5px;">
                                                <i class="fas fa-plus"></i> Añadir Producto
                                            </button>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col">
                                        <table id="tblDetalleProducto"
                                               class="datatable table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Valor unitario</th>
                                                <th>Precio final</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($dataVenta) and !empty($dataVenta->getIdVenta())) {
                                                $arrDetalleVentas = DetalleVentas::search("SELECT * FROM postres.detalleventa WHERE Venta_idVenta = ".$dataVenta->getIdVenta());
                                                if(count($arrDetalleVentas) > 0) {
                                                    /* @var $arrDetalleVentas DetalleVentas[] */
                                                    foreach ($arrDetalleVentas as $detalleVenta) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $detalleVenta->getIdDetalleVenta(); ?></td>
                                                            <td><?= $detalleVenta->getProducto()->getNombre(); ?></td>
                                                            <td><?= $detalleVenta->getCantidad(); ?></td>
                                                            <td><?= GeneralFunctions::formatCurrency($detalleVenta->getPrecioVenta()); ?></td>
                                                            <td><?= GeneralFunctions::formatCurrency($detalleVenta->getTotalProducto()); ?></td>
                                                            <td>
                                                                <a type="button"
                                                                   href="../../../app/Controllers/MainController.php?controller=DetalleVentas&action=deleted&id=<?= $detalleVenta->getIdDetalleVenta(); ?>"
                                                                   data-toggle="tooltip" title="Eliminar"
                                                                   class="btn docs-tooltip btn-danger btn-xs"><i
                                                                            class="fa fa-times-circle"></i></a>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                }
                                            }?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Valor unitario</th>
                                                <th>Precio final</th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div id="modals">
        <div class="modal fade" id="modal-add-producto">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Agregar Producto a Venta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="../../../app/Controllers/MainController.php?controller=DetalleVentas&action=create" method="post">
                        <div class="modal-body">
                            <input id="Venta_idVenta" name="Venta_idVenta" value="<?= !empty($dataVenta) ? $dataVenta->getIdVenta() : ''; ?>" hidden
                                   required="required" type="text">
                            <div class="form-group row">
                                <label for="producto_id" class="col-sm-4 col-form-label">Producto</label>
                                <div class="col-sm-8">
                                    <?= ProductosController::selectProducto(
                                        array (
                                            'id' => 'Producto_idProducto',
                                            'name' => 'Producto_idProducto',
                                            'defaultValue' => '',
                                            'class' => 'form-control select2bs4 select2-info',
                                            'where' => "estado = 'Disponible' and stock > 0"
                                        )
                                    )
                                    ?>
                                    <div id="divResultProducto">
                                        <span class="text-muted">Precio Venta: </span> <span id="spPrecioVenta"></span>,
                                        <span class="text-muted">Stock: </span> <span id="spStock"></span>.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cantidad" class="col-sm-4 col-form-label">Cantidad</label>
                                <div class="col-sm-8">
                                    <input required type="number" min="1" class="form-control" step="1" id="cantidad" name="cantidad"
                                           placeholder="Ingrese la cantidad">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="precio_venta" class="col-sm-4 col-form-label">Precio Unitario</label>
                                <div class="col-sm-8">
                                    <input required readonly type="number" min="1" class="form-control" id="precioVenta" name="precioVenta"
                                           placeholder="0.0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total_producto" class="col-sm-4 col-form-label">Total Producto</label>
                                <div class="col-sm-8">
                                    <input required readonly type="number" min="1" class="form-control" id="total_producto" name="total_producto"
                                           placeholder="0.0">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>
<!-- Scripts requeridos para las datatables -->
<?php require('../../partials/datatables_scripts.php'); ?>

<script>

    $(function () {
        $('#new-producto').on('click', function (){
           $('#modal-add-producto').modal('show');
        });

        $("#divResultProducto").hide();

        $('#Producto_idProducto').on('change', function () {
            var id = $(this).val();
            var dataProducto = null;
            if(id > 0){
                $.post("../../../app/Controllers/MainController.php?controller=Productos&action=searchForID",
                    {
                        idProducto: $(this).val(),
                        request: 'ajax'
                    }, "json"
                )
                    .done(function( resultProducto ) {
                        dataProducto = resultProducto;
                    })
                    .fail(function(err) {
                        console.log( "Error al realizar la consulta"+err );
                    })
                    .always(function() {
                        updateDataProducto(dataProducto);
                    });
            }else{
                updateDataProducto(dataProducto);
            }
        });

        function updateDataProducto(dataProducto){
            if(dataProducto !== null){
                $("#divResultProducto").slideDown();
                $("#spPrecioVenta").html("$"+dataProducto.valorUnitario);
                $("#spStock").html(dataProducto.stock+" Unidad(es)");
                $("#cantidad").attr("max",dataProducto.stock);
                $("#precioVenta").val(dataProducto.valorUnitario);
            }else{
                $("#divResultProducto").slideUp();
                $("#spPrecioVenta").html("");
                $("#spStock").html("");
                $("#cantidad").removeAttr("max").val('0');
                $("#precioVenta").val('0.0');
                $("#total_producto").val('0.0');
            }
        }

        $( "#cantidad" ).on( "change keyup focusout", function() {
            $("#total_producto").val($( "#cantidad" ).val() *  $("#precioVenta").val());
        });

    });
</script>


</body>
</html>
