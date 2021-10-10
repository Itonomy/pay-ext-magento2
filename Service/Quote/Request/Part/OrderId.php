<?php
/**
 * Copyright © CM.com. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace CM\Payments\Service\Quote\Request\Part;

use CM\Payments\Api\Service\Order\Request\RequestPartByQuoteInterface;
use CM\Payments\Client\Model\Request\OrderCreate;
use Magento\Framework\Math\Random as MathRandom;
use Magento\Quote\Api\Data\CartInterface;

class OrderId implements RequestPartByQuoteInterface
{
    /**
     * @var MathRandom
     */
    private $mathRandom;

    /**
     * OrderId constructor
     *
     * @param MathRandom $mathRandom
     */
    public function __construct(MathRandom $mathRandom)
    {
        $this->mathRandom = $mathRandom;
    }
    /**
     * @inheritDoc
     */
    public function process(CartInterface $quote, OrderCreate $orderCreate): OrderCreate
    {
        $orderCreate->setOrderId($this->getOrderId($quote));

        return $orderCreate;
    }

    /**
     * @param CartInterface $quote
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getOrderId(CartInterface $quote): string
    {
        if ($quote->getReservedOrderId()) {
            return $quote->getReservedOrderId();
        }

        return $this->mathRandom->getUniqueHash('Q_');
    }
}
