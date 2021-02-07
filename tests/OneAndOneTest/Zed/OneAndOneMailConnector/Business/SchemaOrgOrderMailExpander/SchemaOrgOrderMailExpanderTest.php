<?php

namespace OneAndOneTest\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SchemaOrgTransfer;
use OneAndOne\Zed\OneAndOneMailConnector\Business\ParcelDelivery\ParcelDeliveryFactory;
use OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpander;
use OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig;
use OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepository;
use PHPUnit\Framework\MockObject\MockObject;

class SchemaOrgOrderMailExpanderTest extends Unit
{
    protected $tester;

    public function testExpandOrderMailTransfer(): void
    {
        // Arrange
        $shopName     = 'shop.com';
        $statusMatrix = [
            'new'       => 'OrderProcessing',
            'cancelled' => 'OrderCancelled',
            'shipped'   => 'OrderInTransit',
            'delivered' => 'OrderDelivered',
            'returned'  => 'OrderReturned',
        ];
        $itemValues   = [
            1 => [
                'state'          => 'new',
                'schemaOrgState' => 'OrderProcessing',
                'lastChange'     => 1,
            ],
            2 => [
                'state'          => 'cancelled',
                'schemaOrgState' => 'OrderCancelled',
                'lastChange'     => 3,
            ],
            3 => [
                'state'          => 'shipped',
                'schemaOrgState' => 'OrderInTransit',
                'lastChange'     => 2,
            ],
        ];
        $carrierName  = 'delivery-bot';

        $carrierTransfer       = $this->createCarrierTransferMock($carrierName);
        $shipmentTransfer      = $this->createShipmentTransferMock($carrierTransfer);
        $items                 = $this->createItemTransferMocks($itemValues, $shipmentTransfer);
        $orderTransfer         = $this->createOrderTransferMock($items);
        $mailTemplateTransfer  = $this->createMailTemplateTransferMock();
        $parcelDelivery        = $this->createParcelDeliveryMock($carrierName);
        $schemaOrgTransfer     = $this->createSchemaOrgTransferMock($shopName, $parcelDelivery);
        $mailTransfer          = $this->createMailTransferMock($mailTemplateTransfer, $schemaOrgTransfer);
        $config                = $this->createConfigMock($statusMatrix, $shopName);
        $spySalesOrderItems    = $this->createSpySalesOrderItemMocks($itemValues);
        $repository            = $this->createRepositoryMock($itemValues, $spySalesOrderItems);
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
     * @param array  $statusMatrix
     * @param string $shopName
     *
     * @return OneAndOneMailConnectorConfig|MockObject
     */
    protected function createConfigMock(array $statusMatrix, string $shopName)
    {
        $config = $this->createMock(OneAndOneMailConnectorConfig::class);
        $config->method('getShopName')
            ->willReturn($shopName);
        $config->method('getStatusMatrix')
            ->willReturn($statusMatrix);

        return $config;
    }

    /**
     * @return MockObject|MailTemplateTransfer
     */
    protected function createMailTemplateTransferMock()
    {
        $mailTemplateTransfer = $this->getMockBuilder('Generated\Shared\Transfer\MailTemplateTransfer')
            ->disableOriginalConstructor()
            ->addMethods(
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
     * @param MailTemplateTransfer $mailTemplateTransfer
     * @param SchemaOrgTransfer    $schemaOrgTransfer
     *
     * @return MockObject|MailTransfer
     */
    protected function createMailTransferMock(
        MailTemplateTransfer $mailTemplateTransfer,
        SchemaOrgTransfer $schemaOrgTransfer
    ) {
        $mailTransfer = $this->getMockBuilder('Generated\Shared\Transfer\MailTransfer')
            ->disableOriginalConstructor()
            ->addMethods(
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
     * @return MockObject|OrderTransfer
     */
    protected function createOrderTransferMock(array $items)
    {
        $orderTransfer = $this->getMockBuilder('Generated\Shared\Transfer\OrderTransfer')
            ->disableOriginalConstructor()
            ->addMethods([ 'getItems' ])
            ->getMock();
        $orderTransfer->method('getItems')
            ->willReturn($items);

        return $orderTransfer;
    }

    /**
     * @param array $itemValues
     * @param       $shipmentTransfer
     *
     * @return array
     */
    protected function createItemTransferMocks(array $itemValues, $shipmentTransfer): array
    {
        $items = [];
        foreach ($itemValues as $id => $values) {
            $stateTransfer = $this->createItemStateTransferMock($values['state']);
            $items[]       = $this->createItemTransferMock($id, $stateTransfer, $shipmentTransfer);
        }

        return $items;
    }

    /**
     * @param MockObject $carrierTransfer
     *
     * @return MockObject|Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createShipmentTransferMock(MockObject $carrierTransfer)
    {
        $shipmentTransfer = $this->getMockBuilder('Generated\Shared\Transfer\ShipmentTransfer')
            ->disableOriginalConstructor()
            ->addMethods([ 'getCarrier' ])
            ->getMock();
        $shipmentTransfer->method('getCarrier')
            ->willReturn($carrierTransfer);

        return $shipmentTransfer;
    }

    /**
     * @param string $carrierName
     *
     * @return MockObject|Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    protected function createCarrierTransferMock(string $carrierName)
    {
        $carrierTransfer = $this->getMockBuilder('Generated\Shared\Transfer\ShipmentCarrierTransfer')
            ->disableOriginalConstructor()
            ->addMethods([ 'getName' ])
            ->getMock();
        $carrierTransfer->method('getName')
            ->willReturn($carrierName);

        return $carrierTransfer;
    }

    /**
     * @param $status
     *
     * @return MockObject|Generated\Shared\Transfer\ItemStateTransfer
     */
    protected function createItemStateTransferMock($status)
    {
        $stateTransfer = $this->getMockBuilder('Generated\Shared\Transfer\ItemStateTransfer')
            ->disableOriginalConstructor()
            ->addMethods([ 'getName' ])
            ->getMock();
        $stateTransfer->method('getName')
            ->willReturn($status);

        return $stateTransfer;
    }

    /**
     * @param int        $id
     * @param MockObject $stateTransfer
     * @param            $shipmentTransfer
     *
     * @return MockObject|Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransferMock(int $id, MockObject $stateTransfer, $shipmentTransfer)
    {
        $itemTransfer = $this->getMockBuilder('Generated\Shared\Transfer\ItemTransfer')
            ->disableOriginalConstructor()
            ->addMethods(
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
                ->addMethods(
                    [
                        'getLastStateChange',
                        'getState',
                    ]
                )
                ->getMock();
            $spySalesOrderItem->method('getState')
                ->willReturn($this->createItemStateTransferMock($values['schemaOrgState']));
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
     * @return OneAndOneMailConnectorRepository|MockObject
     */
    protected function createRepositoryMock(array $itemValues, array $spySalesOrderItems)
    {
        $repository = $this->createMock(OneAndOneMailConnectorRepository::class);
        $repository->method('findSpySalesOrderItemsById')
            ->with($itemValues)
            ->willReturn($spySalesOrderItems);

        return $repository;
    }

    /**
     * @param string $carrierName
     *
     * @return MockObject
     */
    protected function createParcelDeliveryMock(string $carrierName): MockObject
    {
        $parcelDelivery = $this->getMockBuilder('Generated\Shared\Transfer\ParcelDelivery')
            ->disableOriginalConstructor()
            ->addMethods(
                [
                    'setDeliveryName',
                    'setStatus'
                ]
            )
            ->getMock();
        $parcelDelivery->method('setDeliveryName')
            ->with($carrierName);
        $parcelDelivery->method('setStatus')
            ->withConsecutive(
                [
                    'OrderProcessing',
                    'OrderCancelled',
                    'OrderInTransit',
                ]
            );

        return $parcelDelivery;
    }

    /**
     * @param string     $shopName
     * @param MockObject $parcelDelivery
     *
     * @return MockObject|Generated\Shared\Transfer\SchemaOrgTransfer
     */
    protected function createSchemaOrgTransferMock(string $shopName, MockObject $parcelDelivery)
    {
        $schemaOrgTransfer = $this->getMockBuilder('Generated\Shared\Transfer\SchemaOrgTransfer')
            ->disableOriginalConstructor()
            ->addMethods(
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
     * @param MockObject $parcelDelivery
     *
     * @return ParcelDeliveryFactory|MockObject
     */
    protected function createParcelDeliveryFactoryMock(MockObject $parcelDelivery)
    {
        $parcelDeliveryFactory = $this->createMock(ParcelDeliveryFactory::class);
        $parcelDeliveryFactory->method('create')
            ->willReturn($parcelDelivery);

        return $parcelDeliveryFactory;
    }
}
