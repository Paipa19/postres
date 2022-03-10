<?php
require "..\app\Models\Ventas.php";

use App\Models\Ventas;

$usuario = Ventas::searchForId(3);
$usuario->setTotal(5000);
if($usuario->update()){
    echo "BIen";
}else{
    echo "Error";
}