<?php

namespace App\Factories;

use App\Services\FileManagerService;
use App\Services\InputReaderInterface;
use InvalidArgumentException;

class InputFactory
{
    private const INPUT_ARGUMENT = 1;

    /**
     * @param array $arguments
     * @throws InvalidArgumentException
     *
     * @return InputReaderInterface
     */
    public static function createReaderFromArguments(array $arguments): InputReaderInterface
    {
        if (empty($arguments[self::INPUT_ARGUMENT])) {
            throw new InvalidArgumentException("Input argument must not be empty.");
        }

        if(is_file($arguments[self::INPUT_ARGUMENT])) {
            return new FileManagerService($arguments[self::INPUT_ARGUMENT]);
        }

        throw new InvalidArgumentException("Unknown input argument.");
    }
}