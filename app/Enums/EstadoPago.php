<?php

namespace App\Enums;

enum EstadoPago: String
{

    case CANCELADO = 'Cancelado';
    case DEBE = 'Debe';

    public  function toString(): string
    {

        return match ($this) {
            self::CANCELADO =>'Cancelado',
            self::DEBE=>'Debe',
        };
    }
}