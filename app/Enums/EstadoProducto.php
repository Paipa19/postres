<?php

namespace App\Enums;

namespace App\Enums;
enum EstadoProducto: String
{

    case DISPONIBLE = 'Disponible';
    case NODISPONIBLE = 'NoDisponible';

    public  function toString(): string
    {

        return match ($this) {
            self::DISPONIBLE =>'Disponible',
            self::NODISPONIBLE=>'NoDisponible',
        };
    }
}