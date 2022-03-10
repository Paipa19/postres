<?php

namespace App\Enums;

enum Estado: String
{

case ACTIVO = 'Activo';
case INACTIVO = 'Inactivo';

    public  function toString(): string
{

    return match ($this) {
        self::ACTIVO =>'Activo',
        self::INACTIVO=>'inactivo',
    };
}
}