<?php

namespace App\Enums;

trait EnumHelper
{
    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }

    public static function toCases(): array
    {
        return  array_keys(self::toArray());
    }

    /**
     * Get the enum value by its case name.
     *
     * @param string $name
     * @return int|null
     */
    public static function fromName(string $name): ?int
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case->value;
            }
        }

        return null; // Return null if the name doesn't match any case
    }
}
