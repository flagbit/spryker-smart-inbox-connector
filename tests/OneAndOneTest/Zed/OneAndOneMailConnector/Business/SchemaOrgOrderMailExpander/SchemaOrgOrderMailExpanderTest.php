<?php

namespace OneAndOneTest\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ParcelDeliveryTransfer;
use Generated\Shared\Transfer\SchemaOrgTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use OneAndOne\Zed\OneAndOneMailConnector\Business\ParcelDelivery\ParcelDeliveryFactory;
use OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpander;
use OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig;
use OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepository;
use PHPUnit\Framework\MockObject\MockObject;

class SchemaOrgOrderMailExpanderTest extends Unit
{
    /**
     * @var \OneAndOneTest\Zed\OneAndOneMailConnector\OneAndOneMailConnectorBusinessTester
     */
    protected $tester;

    /**
     * Test expandOrderMailTransfer method
     *
     * @return void
     */
    public function testExpandOrderMailTransfer(): void
    {
        // Arrange
        $shopName = 'shop.com';
        $statusMatrix = [
            'new' => 'OrderProcessing',
            'cancelled' => 'OrderCancelled',
            'shipped' => 'OrderInTransit',
            'delivered' => 'OrderDelivered',
            'returned' => 'OrderReturned',
        ];
        $itemValues = [
            1 => [
                'state' => 'new',
                'schemaOrgState' => 'OrderProcessing',
                'lastChange' => 1,
            ],
            2 => [
                'state' => 'cancelled',
                'schemaOrgState' => 'OrderCancelled',
                'lastChange' => 3,
            ],
            3 => [
                'state' => 'shipped',
                'schemaOrgState' => 'OrderInTransit',
                'lastChange' => 2,
            ],
        ];
        $carrierName = 'delivery-bot';

        $carrierTransfer = $this->createCarrierTransferMock($carrierName);
        $shipmentTransfer = $this->createShipmentTransferMock($carrierTransfer);
        $items = $this->createItemTransferMocks($itemValues, $shipmentTransfer);
        $orderTransfer = $this->createOrderTransferMock($items);
        $mailTemplateTransfer = $this->createMailTemplateTransferMock();
        $parcelDelivery = $this->createParcelDeliveryMock($carrierName);
        $schemaOrgTransfer = $this->createSchemaOrgTransferMock($shopName, $parcelDelivery);
        $mailTransfer = $this->createMailTransferMock($mailTemplateTransfer, $schemaOrgTransfer);
        $config = $this->createConfigMock($statusMatrix, $shopName);
        $spySalesOrderItems = $this->createSpySalesOrderItemMocks($itemValues);
        $repository = $this->createRepositoryMock($itemValues, $spySalesOrderItems);
        $parcelDeliveryFactory = $this->createParcelDeliveryFactoryMock($parcelDelivery);

        $schemaOrgOrderMailExpander = new SchemaOrgOrderMailExpander($config, $repository, $parcelDeliveryFactory);

        // Act
        $mailTransferResult = $schemaOrgOrderMailExpander->expandOrderMailTransfer(
            $mailTransfer,
            $orderTransfer,
            $mailTemplateTransfer,
            $schemaOrgTransfer
        );

        // Assert
        $this->assertSame($mailTransfer, $mailTransferResult);
    }

    /**
     * @param array $statusMatrix
     * @param string $shopName
     *
     * @return \OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createConfigMock(array $statusMatrix, string $shopName): OneAndOneMailConnectorConfig
    {
        $config = $this->createMock(OneAndOneMailConnectorConfig::class);
        $config->method('getShopName')
            ->willReturn($shopName);
        $config->method('getStatusMatrix')
            ->willReturn($statusMatrix);

        return $config;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\MailTemplateTransfer
     */
    protected function createMailTemplateTransferMock(): MailTemplateTransfer
    {
        $mailTemplateTransfer = $this->getMockBuilder('Generated\Shared\Transfer\MailTemplateTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods(
                [
                    'setIsHtml',
                    'setName',
                ]
            )
            ->getMock();
        $mailTemplateTransfer->method('setIsHtml')
            ->with(true);
        $mailTemplateTransfer->method('setName')
            ->with('oneAndOneMailConnector/mail/schema_org_order_connector.html.twig');

        return $mailTemplateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTemplateTransfer $mailTemplateTransfer
     * @param \Generated\Shared\Transfer\SchemaOrgTransfer $schemaOrgTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\MailTransfer
     */
    protected function createMailTransferMock(
        MailTemplateTransfer $mailTemplateTransfer,
        SchemaOrgTransfer $schemaOrgTransfer
    ): MailTransfer {
        $mailTransfer = $this->getMockBuilder('Generated\Shared\Transfer\MailTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods(
                [
                    'addTemplate',
                    'setSchemaOrg',
                ]
            )
            ->getMock();
        $mailTransfer->method('addTemplate')
            ->with($mailTemplateTransfer);
        $mailTransfer->method('setSchemaOrg')
            ->with($schemaOrgTransfer);

        return $mailTransfer;
    }

    /**
     * @param array $items
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransferMock(array $items): OrderTransfer
    {
        $orderTransfer = $this->getMockBuilder('Generated\Shared\Transfer\OrderTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods([ 'getItems' ])
            ->getMock();
        $orderTransfer->method('getItems')
            ->willReturn($items);

        return $orderTransfer;
    }

    /**
     * @param array $itemValues
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return array
     */
    protected function createItemTransferMocks(array $itemValues, ShipmentTransfer $shipmentTransfer): array
    {
        $items = [];
        foreach ($itemValues as $id => $values) {
            $stateTransfer = $this->createItemStateTransferMock($values['state']);
            $items[] = $this->createItemTransferMock($id, $stateTransfer, $shipmentTransfer);
        }

        return $items;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $carrierTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createShipmentTransferMock(MockObject $carrierTransfer): ShipmentTransfer
    {
        $shipmentTransfer = $this->getMockBuilder('Generated\Shared\Transfer\ShipmentTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods([ 'getCarrier' ])
            ->getMock();
        $shipmentTransfer->method('getCarrier')
            ->willReturn($carrierTransfer);

        return $shipmentTransfer;
    }

    /**
     * @param string $carrierName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    protected function createCarrierTransferMock(string $carrierName): ShipmentCarrierTransfer
    {
        $carrierTransfer = $this->getMockBuilder('Generated\Shared\Transfer\ShipmentCarrierTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods([ 'getName' ])
            ->getMock();
        $carrierTransfer->method('getName')
            ->willReturn($carrierName);

        return $carrierTransfer;
    }

    /**
     * @param string $status
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ItemStateTransfer
     */
    protected function createItemStateTransferMock(string $status): ItemStateTransfer
    {
        $stateTransfer = $this->getMockBuilder('Generated\Shared\Transfer\ItemStateTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods([ 'getName' ])
            ->getMock();
        $stateTransfer->method('getName')
            ->willReturn($status);

        return $stateTransfer;
    }

    /**
     * @param int $id
     * @param \PHPUnit\Framework\MockObject\MockObject $stateTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransferMock(
        int $id,
        MockObject $stateTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ItemTransfer {
        $itemTransfer = $this->getMockBuilder('Generated\Shared\Transfer\ItemTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods(
                [
                    'getIdSalesOrderItem',
                    'getState',
                    'getShipment',
                ]
            )
            ->getMock();
        $itemTransfer->method('getIdSalesOrderItem')
            ->willReturn($id);
        $itemTransfer->method('getState')
            ->willReturn($stateTransfer);
        $itemTransfer->method('getShipment')
            ->willReturn($shipmentTransfer);

        return $itemTransfer;
    }

    /**
     * @param array $itemValues
     *
     * @return array
     */
    protected function createSpySalesOrderItemMocks(array $itemValues): array
    {
        $spySalesOrderItems = [];
        foreach ($itemValues as $id => $values) {
            $spySalesOrderItem = $this->getMockBuilder('Spryker\Zed\Sales\Persistence\Base\SpySalesOrderItem')
                // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
                ->setMethods(
                    [
                        'getLastStateChange',
                        'getState',
                    ]
                )
                ->getMock();
            $spySalesOrderItem->method('getState')
                ->willReturn($this->createItemStateTransferMock($values['state']));
            $spySalesOrderItem->method('getLastStateChange')
                ->willReturn($values['lastChange']);
            $spySalesOrderItems[] = $spySalesOrderItem;
        }

        return $spySalesOrderItems;
    }

    /**
     * @param array $itemValues
     * @param array $spySalesOrderItems
     *
     * @return \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createRepositoryMock(array $itemValues, array $spySalesOrderItems): OneAndOneMailConnectorRepository
    {
        $repository = $this->createMock(OneAndOneMailConnectorRepository::class);
        $repository->method('findSpySalesOrderItemsById')
            ->with(array_keys($itemValues))
            ->willReturn($spySalesOrderItems);

        return $repository;
    }

    /**
     * @param string $carrierName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ParcelDeliveryTransfer
     */
    protected function createParcelDeliveryMock(string $carrierName): ParcelDeliveryTransfer
    {
        $parcelDelivery = $this->getMockBuilder('Generated\Shared\Transfer\ParcelDeliveryTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods(
                [
                    'setDeliveryName',
                    'setStatus',
                ]
            )
            ->getMock();
        $parcelDelivery->method('setDeliveryName')
            ->with($carrierName);
        $parcelDelivery->method('setStatus')
            ->withConsecutive(
                [ 'OrderProcessing' ],
                [ 'OrderCancelled' ],
                [ 'OrderInTransit' ]
            );

        return $parcelDelivery;
    }

    /**
     * @param string $shopName
     * @param \PHPUnit\Framework\MockObject\MockObject $parcelDelivery
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\SchemaOrgTransfer
     */
    protected function createSchemaOrgTransferMock(string $shopName, MockObject $parcelDelivery): SchemaOrgTransfer
    {
        $schemaOrgTransfer = $this->getMockBuilder('Generated\Shared\Transfer\SchemaOrgTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods(
                [
                    'addParcelDelivery',
                    'setShopName',
                    'setStatus',
                ]
            )
            ->getMock();
        $schemaOrgTransfer->method('setShopName')
            ->with($shopName);
        $schemaOrgTransfer->method('addParcelDelivery')
            ->with($parcelDelivery);
        $schemaOrgTransfer->method('setStatus')
            ->with('OrderCancelled');

        return $schemaOrgTransfer;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ParcelDeliveryTransfer $parcelDelivery
     *
     * @return \OneAndOne\Zed\OneAndOneMAilConnector\Business\ParcelDelivery\ParcelDeliveryFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createParcelDeliveryFactoryMock($parcelDelivery): ParcelDeliveryFactory
    {
        $parcelDeliveryFactory = $this->createMock(ParcelDeliveryFactory::class);
        $parcelDeliveryFactory->method('create')
            ->willReturn($parcelDelivery);

        return $parcelDeliveryFactory;
    }
}
