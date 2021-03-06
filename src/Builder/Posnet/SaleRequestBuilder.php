<?php
namespace Paranoia\Builder\Posnet;

use Paranoia\Common\Serializer\Serializer;
use Paranoia\Request;

class SaleRequestBuilder extends BaseRequestBuilder
{
    const TRANSACTION_TYPE = 'sale';
    const ENVELOPE_NAME    = 'posnetRequest';

    public function build(Request $request)
    {
        $data = array_merge(
            $this->buildBaseRequest($request),
            [
                self::TRANSACTION_TYPE => array_merge(
                    [
                        'amount' => $this->amountFormatter->format($request->getAmount()),
                        'currencyCode' => $this->currencyCodeFormatter->format($request->getCurrency()),
                        'orderID' => $this->orderIdFormatter->format($request->getOrderId())
                    ],
                    $this->buildCard($request)
                )
            ]
        );

        if ($request->getInstallment()) {
            $data[self::TRANSACTION_TYPE]['installment'] = $this->installmentFormatter->format(
                $request->getInstallment()
            );
        }

        $serializer = new Serializer(Serializer::XML);
        return $serializer->serialize($data, ['root_name' => self::ENVELOPE_NAME]);
    }
}
