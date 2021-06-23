<?php

namespace CM\Payments\Test\Integration\Service;

use CM\Payments\Api\Client\ApiClientInterface;
use CM\Payments\Api\Model\Data\OrderInterfaceFactory;
use CM\Payments\Api\Model\Domain\CMOrderInterfaceFactory;
use CM\Payments\Api\Model\OrderRepositoryInterface as CMOrderRepositoryInterface;
use CM\Payments\Api\Service\OrderServiceInterface;
use CM\Payments\Logger\CMPaymentsLogger;
use CM\Payments\Service\OrderRequestBuilder;
use CM\Payments\Service\OrderService;
use CM\Payments\Test\Integration\IntegrationTestCase;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderRepository;
use PHPUnit\Framework\MockObject\MockObject;

class OrderServiceTest extends IntegrationTestCase
{
    /**
     * @var ApiClientInterface|MockObject
     */
    private $clientMock;
    /**
     * @var OrderServiceInterface
     */
    private $orderService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientMock = $this->createMock(ApiClientInterface::class);

        $this->orderService = $this->objectManager->create(OrderService::class, [
            'orderRepository' => $this->objectManager->create(OrderRepository::class),
            'apiClient' => $this->clientMock,
            'orderInterfaceFactory' => $this->objectManager->create(OrderInterfaceFactory::class),
            'cmOrderRepository' => $this->objectManager->create(\CM\Payments\Model\OrderRepository::class),
            'orderRequestBuilder' => $this->objectManager->create(OrderRequestBuilder::class),
            'cmOrderInterfaceFactory' => $this->objectManager->create(CMOrderInterfaceFactory::class),
            'cmPaymentsLogger' => $this->objectManager->create(CMPaymentsLogger::class, ['name' => 'CMPayments'])
        ]);
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testCreateOrder()
    {
        $this->clientMock->expects($this->once())->method('execute')->willReturn([
            'order_key' => '0287A1617D93780EF28044B98438BF2F',
            //phpcs:ignore
            'url' => 'https://testsecure.docdatapayments.com/ps/menu?merchant_name=itonomy_b_v&client_language=NL&payment_cluster_key=0287A1617D93780EF28044B98438BF2F',
            'expires_on' => '2021-07-12T08:10:57Z'
        ]);

        $magentoOrder = $this->loadOrderById('100000001');

        $order = $this->orderService->create($magentoOrder->getId());
        $this->assertSame(
            //phpcs:ignore
            'https://testsecure.docdatapayments.com/ps/menu?merchant_name=itonomy_b_v&client_language=NL&payment_cluster_key=0287A1617D93780EF28044B98438BF2F',
            $order->getUrl()
        );
    }

    /**
     *
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testSaveOrderReferenceInDatabase()
    {
        $this->clientMock->expects($this->once())->method('execute')->willReturn([
            'order_key' => '2287A1617D93780EF28044B98438BF2F',
            //phpcs:ignore
            'url' => 'https://testsecure.docdatapayments.com/ps/menu?merchant_name=itonomy_b_v&client_language=NL&payment_cluster_key=0287A1617D93780EF28044B98438BF2F',
            'expires_on' => '2021-07-12T08:10:57Z'
        ]);

        $magentoOrder = $this->loadOrderById('100000001');
        $this->orderService->create($magentoOrder->getId());

        /** @var OrderRepositoryInterface $cmOrderRepository */
        $cmOrderRepository = $this->objectManager->create(CMOrderRepositoryInterface::class);

        $result = $cmOrderRepository->getByOrderKey('2287A1617D93780EF28044B98438BF2F');

        $this->assertSame((int) $magentoOrder->getId(), $result->getOrderId());
    }

    /**
     * @param $orderId
     * @return OrderInterface
     */
    private function loadOrderById($orderId)
    {
        $repository = $this->objectManager->get(OrderRepositoryInterface::class);
        $builder = $this->objectManager->create(SearchCriteriaBuilder::class);
        $searchCriteria = $builder->addFilter('increment_id', $orderId, 'eq')->create();

        $orderList = $repository->getList($searchCriteria)->getItems();

        return array_shift($orderList);
    }
}
