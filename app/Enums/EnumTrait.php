<?php

namespace App\Enums;

trait EnumTrait
{
    /**
     * Determine if the given value exists.
     *
     * @param  int|string  $value
     * @return bool
     */
    public static function exists(mixed $value): bool
    {
        return in_array($value, self::values());
    }

    /**
     * Get the list of case values.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the list of keyed values.
     *
     * @return array
     */
    public static function keyedValues(): array
    {
        return array_combine($values = self::values(), $values);
    }

    /**
     * Get the list of case-named values.
     *
     * @return array
     */
    public static function namedValues(): array
    {
        $data = [];

        foreach (self::cases() as $value) {
            $data[$value->name] = $value->value;
        }

        return $data;
    }
}
