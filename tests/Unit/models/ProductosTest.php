<?php

namespace Tests\Unit\models;

use App\Enums\EstadoProducto;
use App\Models\Productos;
use PHPUnit\Framework\TestCase;

class ProductosTest extends TestCase
{

    public function testInsert()
    {
        $Producto = new Productos(['idProducto' => null,
                'nombre' => 'vainilla',
                'descripcion' => 'dfd',
                 'valorUnitario'=> 4000,
                 'estado' =>EstadoProducto::DISPONIBLE,
                 'stock' =>1,
            ]
        );
        $Producto->insert();
        $this->assertSame(true, $Producto->productoRegistrado('vainilla'));
    }

}

