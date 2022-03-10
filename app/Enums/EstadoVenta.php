<?php

namespace App\Enums;

namespace App\Enums;
enum EstadoVenta: String
{

    case APROBADA= 'Aprobada';
    case EN_PROCESO= 'En proceso';
    case NO_APROBADA = 'No aprobada';

    public  function toString(): string
    {

        return match ($this) {
            self::APROBADA =>'Aprobada',
            self::EN_PROCESO =>'En proceso',
            self::NO_APROBADA=>'No aprobada',
        };
    }
}