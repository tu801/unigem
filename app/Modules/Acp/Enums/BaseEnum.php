<?php
/**
 * Author: tmtuan
 * Created date: 04/11/2025
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