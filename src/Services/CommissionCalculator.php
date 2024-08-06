<?php

namespace App\Services;

use App\Exceptions\ExternalApiException;
use App\Helpers\TransactionParser;
use App\Helpers\Transformer;
use App\Services\Api\BinApiService;
use App\Services\Api\CurrencyApiService;
use Throwable;

class CommissionCalculator
{
    private const BIN_DATA_INDEX = 0;

    private const AMOUNT_DATA_INDEX = 1;

    private const CURRENCY_DATA_INDEX = 2;

    private const CURRENCY_TRIM_CHARACTER = '"}';

    private const EUROPE_COMMISSION_RATE = 0.01;
    private const NON_EUROPE_COMMISSION_RATE = 0.02;

    protected array $europeCountries = [
        'AT' => 'AT',
        'BE' => 'BE',
        'BG' => 'BG',
        'CY' => 'CY',
        'CZ' => 'CZ',
        'DE' => 'DE',
        'DK' => 'DK',
        'EE' => 'EE',
        'ES' => 'ES',
        'FI' => 'FI',
        'FR' => 'FR',
        'GR' => 'GR',
        'HR' => 'HR',
        'HU' => 'HU',
        'IE' => 'IE',
        'IT' => 'IT',
        'LT' => 'LT',
        'LU' => 'LU',
        'LV' => 'LV',
        'MT' => 'MT',
        'NL' => 'NL',
        'PO' => 'PO',
        'PT' => 'PT',
        'RO' => 'RO',
        'SE' => 'SE',
        'SI' => 'SI',
        'SK' => 'SK',
    ];

    public function __construct(
        private InputReaderInterface $inputReader,
        private BinApiService $binApiService = new BinApiService(),
        private CurrencyApiService $currencyApiService = new CurrencyApiService(),
    ) {
    }

    public function calculateCommission(): string
    {
        $commission = '';
        $transactions = Transformer::toArray($this->inputReader->read());

        foreach ($transactions as $transaction) {
            if (empty($transaction)) {
                continue;
            }


            $transactionData = explode(',', $transaction);

            $cardCode = TransactionParser::parseValue($transactionData, self::BIN_DATA_INDEX);
            $amount = TransactionParser::parseValue($transactionData, self::AMOUNT_DATA_INDEX);
            $currency = TransactionParser::parseValue(
                $transactionData,
                self::CURRENCY_DATA_INDEX,
                self::CURRENCY_TRIM_CHARACTER
            );

            try {
                $binResults = $this->binApiService->getResultByCardCode($cardCode);

                if (empty($binResults)) {
                    var_dump($binResults);
                    throw new ExternalApiException('Bin result not found');
                }
            } catch (Throwable $e) {
                echo $e->getMessage();
                throw new ExternalApiException('Bin result not found');
            }

            $isEuropeCountry = $this->isEurope(($binResults['country']['alpha2'] ?? null));

            try {
                $rates = $this->currencyApiService->getCurrenciesRates();
            } catch (Throwable $exception) {
                echo $exception->getMessage();
                continue;
            }

            $currencyRate = $rates['rates'][$currency] ?? 0;

            $commissionRate = $currency === 'EUR' || $currencyRate === 0 ? $amount : $amount / $currencyRate;

            $commission .= sprintf(
                '%.14f' . PHP_EOL,
                $commissionRate * $isEuropeCountry ? self::EUROPE_COMMISSION_RATE : self::NON_EUROPE_COMMISSION_RATE
            );

        }

        return $commission;
    }

    private function isEurope(?string $countryCode): bool
    {
        return isset($this->europeCountries[$countryCode]);
    }
}