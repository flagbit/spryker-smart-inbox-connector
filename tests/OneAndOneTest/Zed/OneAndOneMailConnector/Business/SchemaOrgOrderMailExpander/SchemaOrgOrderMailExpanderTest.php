<?php

namespace OneAndOneTest\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander;

use Codeception\Test\Unit;
use OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpander;

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
            ],
            2 => [
                'state' => 'cancelled',
                'schemaOrgState' => 'OrderCancelled',
            ],
            3 => [
                'state' => 'shipped',
                'schemaOrgState' => 'OrderInTransit',
            ],
        ];
        $lastItemValue = [
            'id' => 2,
            'state' => 'cancelled',
            'schemaOrgState' => 'OrderCancelled',
            'lastChange' => 3,
        ];
        $carrierName = 'delivery-bot';

        $carrierTransfer = $this->tester->createCarrierTransferMock($this, $carrierName);
        $shipmentTransfer = $this->tester->createShipmentTransferMock($this, $carrierTransfer);
        $items = $this->tester->createItemTransferMocks($this, $itemValues, $shipmentTransfer);
        $orderTransfer = $this->tester->createOrderTransferMock($this, $items);
        $mailTemplateTransfer = $this->tester->createMailTemplateTransferMock($this);
        $parcelDelivery = $this->tester->createParcelDeliveryMock($this, $carrierName);
        $schemaOrgTransfer = $this->tester->createSchemaOrgTransferMock($this, $shopName, $parcelDelivery);
        $mailTransfer = $this->tester->createMailTransferMock($this, $mailTemplateTransfer, $schemaOrgTransfer);
        $config = $this->tester->createConfigMock($this, $statusMatrix, $shopName);
        $spySalesOrderItem = $this->tester->createSpySalesOrderItemMocks($this, $lastItemValue);
        $repository = $this->tester->createRepositoryMock($this, $itemValues, $spySalesOrderItem);
        $parcelDeliveryFactory = $this->tester->createParcelDeliveryFactoryMock($this, $parcelDelivery);

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
}
