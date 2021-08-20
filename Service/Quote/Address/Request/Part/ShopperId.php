<?php
/**
 * Copyright © CM.com. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace CM\Payments\Service\Quote\Address\Request\Part;

use CM\Payments\Api\Service\Shopper\Request\RequestPartByQuoteAddressInterface;
use CM\Payments\Client\Model\Request\ShopperCreate;
use Magento\Quote\Api\Data\AddressInterface;

class ShopperId implements RequestPartByQuoteAddressInterface
{
    /**
     * @inheritDoc
     */
    public function process(AddressInterface $quoteAddress, ShopperCreate $shopperCreate): ShopperCreate
    {
        $shopperCreate->setShopperId($quoteAddress->getEmail());

        return $shopperCreate;
    }
}
