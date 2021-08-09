<?php
/**
 * Copyright © CM.com. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace CM\Payments\Client\Request;

use CM\Payments\Client\Api\RequestInterface;
use CM\Payments\Client\Model\Request\OrderCreate;

class OrderCreateRequest implements RequestInterface
{
    /**
     * Order Create Endpoint
     */
    public const ENDPOINT = 'orders';

    /**
     * @var OrderCreate
     */
    private $orderCreate;

    /**
     * OrderCreateRequest constructor.
     *
     * @param OrderCreate $orderCreate
     */
    public function __construct(
        OrderCreate $orderCreate
    ) {
        $this->orderCreate = $orderCreate;
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    /**
     * @inheritDoc
     */
    public function getRequestMethod(): string
    {
        return RequestInterface::HTTP_POST;
    }

    /**
     * @inheritDoc
     */
    public function getPayload(): array
    {
        return $this->orderCreate->toArray();
    }
}
