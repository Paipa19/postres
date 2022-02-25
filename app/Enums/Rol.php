<?php

namespace App\Enums;

Enum Rol: string
{

    case ADMINISTRADOR="Administrador";
    case EMPLEADO="Empleado";


    public function toString(): string
    {
        return match ($this) {
            self::ADMINISTRADOR => "Administrador",
            self::EMPLEADO=>"Empleado",

        };

   }

}