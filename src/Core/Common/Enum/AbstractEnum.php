<?php

namespace App\Core\Common\Enum;

use ReflectionClass;

abstract class AbstractEnum
{
    protected static array $cache = [];

    public static function getConstants(): array
    {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            $reflection = new ReflectionClass($class);

            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    public static function getValues(): array
    {
        return array_values(static::getConstants());
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public static function isValid($value): bool
    {
        return in_array($value, static::getConstants(), true);
    }

    public static function isValidKey(string $key): bool
    {
        return array_key_exists($key, static::getConstants());
    }
}
