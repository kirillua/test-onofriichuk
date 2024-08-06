<?php

namespace App\Base;

use App\Factories\InputFactory;
use App\Services\CommissionCalculator;
use Throwable;

class Application
{
    public function run(): void
    {
        global $argv;

        try {
            $fileManagerService = InputFactory::createReaderFromArguments($argv);
            $commission = (new CommissionCalculator($fileManagerService))->calculateCommission();

            var_dump($commission);
        } catch (Throwable $exception) {
            echo "Error during commission calculation: " . $exception->getMessage() . PHP_EOL;
        }

    }
}