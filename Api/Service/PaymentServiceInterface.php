<?php
/**
 * Copyright © CM.com. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace CM\Payments\Api\Service;

use CM\Payments\Api\Data\BrowserDetailsInterface;
use CM\Payments\Api\Data\CardDetailsInterface;
use CM\Payments\Api\Model\Domain\PaymentOrderStatusInterface;
use CM\Payments\Client\Api\CMPaymentInterface;
use CM\Payments\Exception\EmptyPaymentIdException;
use Magento\Framework\Exception\NoSuchEntityException;

interface PaymentServiceInterface
{
    /**
     * @param int $orderId
     * @param CardDetailsInterface $cardDetails
     * @param BrowserDetailsInterface $browserDetails
     * @return CMPaymentInterface
     * @throws NoSuchEntityException
     * @throws EmptyPaymentIdException
     */
    public function create(
        int $orderId,
        CardDetailsInterface $cardDetails,
        BrowserDetailsInterface $browserDetails
    ): CMPaymentInterface;

    /**
     * @param string $paymentId
     * @return PaymentOrderStatusInterface
     */
    public function getPaymentStatus(string $paymentId): PaymentOrderStatusInterface;
}
