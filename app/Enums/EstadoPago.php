<?php

namespace App\Enums;

enum EstadoPago: String
{

    case CANCELADO = 'Cancelado';
    case PENDIENTE = 'Pendiente';

    public  function toString(): string
    {

        return match ($this) {
            self::CANCELADO =>'Cancelado',
            self::PENDIENTE=>'Pendiente',
        };
    }
}