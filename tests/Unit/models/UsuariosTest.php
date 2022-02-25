<?php

namespace Tests\Unit\models;

use App\Enums\Estado;
use App\Enums\Rol;
use App\Models\Usuarios;
use PHPUnit\Framework\TestCase;

class UsuariosTest extends TestCase
{


    public function testInsert()
    {
        $Usuario = new Usuarios(['idUsuario' => null,
                'numeroIdentificacion' => 1555,
                'nombre' => 'duvan',
                'apellido' => 'paipa',
                'telefono' => 321707941,
                'correo'=> 'adpaipa@misena.du.co',
                'rol' => Rol::ADMINISTRADOR,
                'contrasena' => 12344,
                'estado' => Estado::ACTIVO]

        );

        $Usuario->insert();
        $this->assertSame(true, $Usuario->usuarioRegistrado(1555));
    }

}
