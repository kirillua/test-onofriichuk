<?php

namespace App\Services\Api;

class BinApiService extends BaseApiService
{
    protected string $hostName = 'https://lookup.binlist.net/';

    public function getResultByCardCode(string $cardCode): array
    {
        return $this->decode(
            $this->makeRequest(
                'get',
                $cardCode
            )
        );
    }
}