<?php
namespace Paranoia\Test\Builder\Posnet;

use Paranoia\Builder\Posnet\RefundRequestBuilder;
use Paranoia\Currency;
use Paranoia\Formatter\MoneyFormatter;
use Paranoia\Formatter\MultiDigitInstallmentFormatter;
use Paranoia\Formatter\Posnet\CustomCurrencyCodeFormatter;
use Paranoia\Formatter\Posnet\ExpireDateFormatter;
use Paranoia\Formatter\Posnet\OrderIdFormatter;
use Paranoia\Request;
use PHPUnit\Framework\TestCase;
use Paranoia\Configuration\Posnet as PosnetConfiguration;

class RefundRequestBuilderTest extends TestCase
{
    public function test()
    {
        $builder = $this->setupBuilder();
        $request = $this->setupRequest();
        $rawRequest = $builder->build($request);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../../samples/request/posnet/refund_request.xml',
            $rawRequest
        );
    }

    protected function setupConfiguration()
    {
        $configuration = new PosnetConfiguration();
        $configuration->setMerchantId('213456')
            ->setTerminalId('654321')
            ->setUsername('TEST')
            ->setPassword('TEST');
        return $configuration;
    }

    /**
     * @return Request
     */
    protected function setupRequest()
    {
        $request = new Request();
        $request->setTransactionId('12345678901')
            ->setAmount(25.4)
            ->setCurrency(Currency::CODE_EUR);
        return $request;
    }

    protected function setupBuilder()
    {
        return new RefundRequestBuilder(
            $this->setupConfiguration(),
            new CustomCurrencyCodeFormatter(),
            new MoneyFormatter(),
            new MultiDigitInstallmentFormatter(),
            new ExpireDateFormatter(),
            new OrderIdFormatter()
        );
    }
}