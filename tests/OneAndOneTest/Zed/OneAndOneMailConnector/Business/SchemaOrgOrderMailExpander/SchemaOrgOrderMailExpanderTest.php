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

        $carrierTransfer = $this->tester->createCarrierTransferMock($this, $carrierName);
        $shipmentTransfer = $this->tester->createShipmentTransferMock($this, $carrierTransfer);
        $items = $this->tester->createItemTransferMocks($this, $itemValues, $shipmentTransfer);
        $orderTransfer = $this->tester->createOrderTransferMock($this, $items);
        $mailTemplateTransfer = $this->tester->createMailTemplateTransferMock($this);
        $parcelDelivery = $this->tester->createParcelDeliveryMock($this, $carrierName);
        $schemaOrgTransfer = $this->tester->createSchemaOrgTransferMock($this, $shopName, $parcelDelivery);
        $mailTransfer = $this->tester->createMailTransferMock($this, $mailTemplateTransfer, $schemaOrgTransfer);
        $config = $this->tester->createConfigMock($this, $statusMatrix, $shopName);
        $spySalesOrderItems = $this->tester->createSpySalesOrderItemMocks($this, $itemValues);
        $repository = $this->tester->createRepositoryMock($this, $itemValues, $spySalesOrderItems);
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
