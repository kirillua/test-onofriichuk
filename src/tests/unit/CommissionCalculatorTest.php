<?php

namespace App\tests\unit;

use App\Exceptions\ExternalApiException;
use App\Services\Api\BinApiService;
use App\Services\Api\CurrencyApiService;
use App\Services\CommissionCalculator;
use App\Services\InputReaderInterface;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private $inputReaderMock;
    private $binApiServiceMock;
    private $currencyApiServiceMock;
    private $commissionCalculator;

    protected function setUp(): void
    {
        $this->inputReaderMock = $this->createMock(InputReaderInterface::class);
        $this->binApiServiceMock = $this->createMock(BinApiService::class);
        $this->currencyApiServiceMock = $this->createMock(CurrencyApiService::class);

        $this->commissionCalculator = new CommissionCalculator(
            $this->inputReaderMock,
            $this->binApiServiceMock,
            $this->currencyApiServiceMock
        );
    }

    public function testCalculateCommissionWithEmptyTransaction()
    {
        $this->inputReaderMock->method('read')->willReturn('');
        $this->assertEquals('', $this->commissionCalculator->calculateCommission());
    }

    public function testCalculateCommissionWithInvalidBin()
    {
        $this->inputReaderMock->method('read')->willReturn('45717360,100.00,EUR"}');
        $this->binApiServiceMock->method('getResultByCardCode')->willThrowException(new ExternalApiException('Bin result not found'));

        $this->expectException(ExternalApiException::class);
        $this->commissionCalculator->calculateCommission();
    }

//    public function testCalculateCommissionWithInvalidCurrency()
//    {
//        $this->inputReaderMock->method('read')->willReturn('45717360,100.00,USD"}');
//        $this->binApiServiceMock->method('getResultByCardCode')->willReturn(['country' => ['alpha2' => 'DE']]);
//        $this->currencyApiServiceMock->method('getCurrenciesRates')->willReturn(['rates' => ['EUR' => 1.0]]);
//
//        $this->assertEquals('2.00' . PHP_EOL, $this->commissionCalculator->calculateCommission());
//    }
//
//    public function testCalculateCommissionWithValidTransaction()
//    {
//        $this->inputReaderMock->method('read')->willReturn('45717360,100.00,EUR"}');
//        $this->binApiServiceMock->method('getResultByCardCode')->willReturn(['country' => ['alpha2' => 'DE']]);
//        $this->currencyApiServiceMock->method('getCurrenciesRates')->willReturn(['rates' => ['EUR' => 1.0]]);
//
//        $this->assertEquals('1.00' . PHP_EOL, $this->commissionCalculator->calculateCommission());
//    }
//
//    public function testCalculateCommissionWithNonEuropeCountry()
//    {
//        $this->inputReaderMock->method('read')->willReturn('45717360,100.00,USD"}');
//        $this->binApiServiceMock->method('getResultByCardCode')->willReturn(['country' => ['alpha2' => 'US']]);
//        $this->currencyApiServiceMock->method('getCurrenciesRates')->willReturn(['rates' => ['USD' => 1.1]]);
//
//        $this->assertEquals('1.82' . PHP_EOL, $this->commissionCalculator->calculateCommission());
//    }
}