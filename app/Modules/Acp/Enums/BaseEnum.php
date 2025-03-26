<?php
/**
 * Author: tmtuan
 * Created date: 8/6/2023
 * Project: thuthuatonline
 **/

namespace Modules\Acp\Enums;


class BaseEnum
{
    public static function toArray(): array
    {
        $class = get_called_class();
        $reflector = new \ReflectionClass($class);

        return $reflector->getConstants();
    }

    public static function toString(): string
    {
        $class = get_called_class();
        $reflector = new \ReflectionClass($class);

        return implode(',', array_values($reflector->getConstants()));
    }
}