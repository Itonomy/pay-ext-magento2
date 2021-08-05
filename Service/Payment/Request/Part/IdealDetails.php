<?php
/**
 * Copyright © CM.com. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace CM\Payments\Service\Payment\Request\Part;

use CM\Payments\Api\Service\Payment\Request\RequestPartInterface;
use CM\Payments\Client\Model\Request\PaymentCreate;
use CM\Payments\Model\ConfigProvider;
use Magento\Sales\Api\Data\OrderInterface;

class IdealDetails implements RequestPartInterface
{
    /**
     * @inheritDoc
     */
    public function process(OrderInterface $order, PaymentCreate $paymentCreate): PaymentCreate
    {
        if ($order->getPayment()->getMethod() !== ConfigProvider::CODE_IDEAL) {
            return $paymentCreate;
        }

        $value = $this->getSelectedIssuer($order);
        $paymentCreate->setIdealDetails([
            'issuer_id' => $value
        ]);

        return $paymentCreate;
    }

    /**
     * @param OrderInterface $order
     * @return string
     */
    private function getSelectedIssuer(OrderInterface $order): string
    {
        $additionalData = $order->getPayment()->getAdditionalInformation();

        if (isset($additionalData['selected_issuer'])) {
            return $additionalData['selected_issuer'];
        }

        return '';
    }
}
