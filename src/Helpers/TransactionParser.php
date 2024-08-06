<?php

namespace App\Helpers;

use App\Exceptions\ParseDataException;
use Throwable;

class TransactionParser
{
    private const VALUE_INDEX = 1;

    public static function parseValue(array $transactionData, int $searchIndex, string $trimCharacter = '"'): string
    {
        try {
            $data = explode(':', $transactionData[$searchIndex]);

            return trim($data[self::VALUE_INDEX], $trimCharacter);
        } catch (Throwable $e) {
            throw new ParseDataException('Cannot parse transaction data');
        }

    }
}