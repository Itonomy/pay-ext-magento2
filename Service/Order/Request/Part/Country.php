<?php
/**
 * Copyright © CM.com. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace CM\Payments\Service\Order\Request\Part;

use CM\Payments\Api\Service\Order\Request\RequestPartByOrderInterface;
use CM\Payments\Client\Model\Request\OrderCreate;
use Magento\Sales\Api\Data\OrderInterface;

class Country implements RequestPartByOrderInterface
{
    /**
     * @inheritDoc
     */
    public function process(OrderInterface $order, OrderCreate $orderCreate): OrderCreate
    {
        $orderCreate->setCountry($this->getCountry($order));

        return $orderCreate;
    }

    /**
     * @param OrderInterface $order
     * @return string
     */
    private function getCountry(OrderInterface $order): string
    {
        if ($order->getShippingAddress()) {
            return $order->getShippingAddress()->getCountryId();
        }

        return $order->getBillingAddress()->getCountryId();
    }
}
