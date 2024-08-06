<?php

namespace App\Helpers;

use App\Exceptions\TransformDataException;

class Transformer
{
    public static function toArray(string $data, string $separator = PHP_EOL): array
    {
        try {
            return explode($separator, $data);
        } catch (\Throwable $e) {
            throw new TransformDataException('Cannot transform data to array');
        }
    }
}