<?php

namespace App\Services\Api;

use GuzzleHttp\Client;

class CurrencyApiService extends BaseApiService
{
    protected string $hostName = 'https://api.exchangeratesapi.io/';

    public function getCurrenciesRates(): array
    {
        return $this->decode(
            $this->makeRequest(
                'get',
                'latest',
                [
                    'access_key' => '8b5e178148c0a838f9cfccb1b62c372e',
                ]
            )
        );
    }
}