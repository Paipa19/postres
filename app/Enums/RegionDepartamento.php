<?php

namespace App\Enums;

enum RegionDepartamento: String
{
    case CARIBE = 'Caribe';
    case CENTRO_ORIENTE = 'Centro Oriente';
    case CENTRO_SUR = 'Centro Sur';
    case EJE_CAFETERO = 'Eje Cafetero - Antioquia';
    case LLANO = 'Llano';
    case PACIFICO = 'Pacífico';

    public  function toString(): string
    {
        return match ($this) {
            self::CARIBE => 'Caribe',
            self::CENTRO_ORIENTE => 'Centro Oriente',
            self::CENTRO_SUR => 'Centro Sur',
            self::EJE_CAFETERO => 'Eje Cafetero - Antioquia',
            self::LLANO => 'Llano',
            self::PACIFICO => 'Pacífico'
        };
    }
}