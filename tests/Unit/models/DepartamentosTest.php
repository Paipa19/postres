<?php

namespace Tests\Unit\models;



use App\Enums\Estado;
use App\models\Departamentos;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

require_once (__DIR__.'/../../../vendor/autoload.php');

class DepartamentosTest extends TestCase
{


    public function testInsert()
    {
        $Departamento = new Departamentos(['id' => null,
                'nombre' => 'Magdalena',
                'region' => 'Caribe',
                'estado' => Estado::ACTIVO,
                'created_at' => Carbon::parse('02-12-21')->format('d-m-Y'),
                'updated_at' => Carbon::parse('02-12-21')->format('d-m-Y'),
                'deleted_at' => Carbon::parse('02-12-21')->format('d-m-Y'),
            ]
        );

        $Departamento->insert();
        $this->assertSame(true, $Departamento->departamentoRegistrado('Magdalena'));
    }

}