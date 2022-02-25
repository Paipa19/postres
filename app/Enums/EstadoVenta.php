<?php

namespace App\Enums;

namespace App\Enums;
enum EstadoVenta: String
{

    case APROBADA= 'Aprobada';
    case NO_APROBADA = 'No_aprobada';

    public  function toString(): string
    {

        return match ($this) {
            self::APROBADA =>'Aprobada',
            self::NO_APROBADA=>'No_aprobada',
        };
    }
}