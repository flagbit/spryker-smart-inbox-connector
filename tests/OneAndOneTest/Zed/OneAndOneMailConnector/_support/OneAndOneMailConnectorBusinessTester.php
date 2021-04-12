<?php

namespace OneAndOneTest\Zed\OneAndOneMailConnector;

use Codeception\Actor;
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
use OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig;
use OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepository;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class OneAndOneMailConnectorBusinessTester extends Actor
{
    use _generated\OneAndOneMailConnectorBusinessTesterActions;

    /**
     * @param \Codeception\Test\Unit $unit
     * @param array $statusMatrix
     * @param string $shopName
     *
     * @return \OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createConfigMock(Unit $unit, array $statusMatrix, string $shopName): OneAndOneMailConnectorConfig
    {
        $config = $unit->getMockBuilder(OneAndOneMailConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $config->method('getShopName')
            ->willReturn($shopName);
        $config->method('getStatusMatrix')
            ->willReturn($statusMatrix);

        return $config;
    }

    /**
     * @param \Codeception\Test\Unit $unit
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\MailTemplateTransfer
     */
    public function createMailTemplateTransferMock(Unit $unit): MailTemplateTransfer
    {
        $mailTemplateTransfer = $unit->getMockBuilder('Generated\Shared\Transfer\MailTemplateTransfer')
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
     * @param \Codeception\Test\Unit $unit
     * @param \Generated\Shared\Transfer\MailTemplateTransfer $mailTemplateTransfer
     * @param \Generated\Shared\Transfer\SchemaOrgTransfer $schemaOrgTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\MailTransfer
     */
    public function createMailTransferMock(
        Unit $unit,
        MailTemplateTransfer $mailTemplateTransfer,
        SchemaOrgTransfer $schemaOrgTransfer
    ): MailTransfer {
        $mailTransfer = $unit->getMockBuilder('Generated\Shared\Transfer\MailTransfer')
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
     * @param \Codeception\Test\Unit $unit
     * @param array $items
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderTransferMock(Unit $unit, array $items): OrderTransfer
    {
        $orderTransfer = $unit->getMockBuilder('Generated\Shared\Transfer\OrderTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods([ 'getItems' ])
            ->getMock();
        $orderTransfer->method('getItems')
            ->willReturn($items);

        return $orderTransfer;
    }

    /**
     * @param \Codeception\Test\Unit $unit
     * @param array $itemValues
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return array
     */
    public function createItemTransferMocks(Unit $unit, array $itemValues, ShipmentTransfer $shipmentTransfer): array
    {
        $items = [];
        foreach ($itemValues as $id => $values) {
            $stateTransfer = $this->createItemStateTransferMock($unit, $values['state']);
            $items[] = $this->createItemTransferMock($unit, $id, $stateTransfer, $shipmentTransfer);
        }

        return $items;
    }

    /**
     * @param \Codeception\Test\Unit $unit
     * @param \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ShipmentCarrierTransfer $carrierTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ShipmentTransfer
     */
    public function createShipmentTransferMock(Unit $unit, ShipmentCarrierTransfer $carrierTransfer): ShipmentTransfer
    {
        $shipmentTransfer = $unit->getMockBuilder('Generated\Shared\Transfer\ShipmentTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods([ 'getCarrier' ])
            ->getMock();
        $shipmentTransfer->method('getCarrier')
            ->willReturn($carrierTransfer);

        return $shipmentTransfer;
    }

    /**
     * @param \Codeception\Test\Unit $unit
     * @param string $carrierName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function createCarrierTransferMock(Unit $unit, string $carrierName): ShipmentCarrierTransfer
    {
        $carrierTransfer = $unit->getMockBuilder('Generated\Shared\Transfer\ShipmentCarrierTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods([ 'getName' ])
            ->getMock();
        $carrierTransfer->method('getName')
            ->willReturn($carrierName);

        return $carrierTransfer;
    }

    /**
     * @param \Codeception\Test\Unit $unit
     * @param array $itemValues
     *
     * @return \PHPUnit\Framework\MockObjec\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function createSpySalesOrderItemMocks(Unit $unit, array $itemValues): SpySalesOrderItem
    {
        $spySalesOrderItem = $unit->getMockBuilder('Orm\Zed\Sales\Persistence\SpySalesOrderItem')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods(
                [
                    'getLastStateChange',
                    'getState',
                ]
            )
            ->getMock();
        $spySalesOrderItem->method('getState')
            ->willReturn($this->createItemStateTransferMock($unit, $itemValues['state']));
        $spySalesOrderItem->method('getLastStateChange')
            ->willReturn($itemValues['lastChange']);

        return $spySalesOrderItem;
    }

    /**
     * @param \Codeception\Test\Unit $unit
     * @param array $itemValues
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderCollection
     *
     * @return \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createRepositoryMock(
        Unit $unit,
        array $itemValues,
        SpySalesOrderItem $orderCollection
    ): OneAndOneMailConnectorRepository {
        $repository = $unit->getMockBuilder(OneAndOneMailConnectorRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repository->method('findSpySalesOrderItemByIdWithLastStateChange')
            ->with(array_keys($itemValues))
            ->willReturn($orderCollection);

        return $repository;
    }

    /**
     * @param \Codeception\Test\Unit $unit
     * @param string $carrierName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ParcelDeliveryTransfer
     */
    public function createParcelDeliveryMock(Unit $unit, string $carrierName): ParcelDeliveryTransfer
    {
        $parcelDelivery = $unit->getMockBuilder('Generated\Shared\Transfer\ParcelDeliveryTransfer')
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
     * @param \Codeception\Test\Unit $unit
     * @param string $shopName
     * @param \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ParcelDeliveryTransfer $parcelDelivery
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\SchemaOrgTransfer
     */
    public function createSchemaOrgTransferMock(Unit $unit, string $shopName, ParcelDeliveryTransfer $parcelDelivery): SchemaOrgTransfer
    {
        $schemaOrgTransfer = $unit->getMockBuilder('Generated\Shared\Transfer\SchemaOrgTransfer')
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
     * @param \Codeception\Test\Unit $unit
     * @param \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ParcelDeliveryTransfer $parcelDelivery
     *
     * @return \OneAndOne\Zed\OneAndOneMAilConnector\Business\ParcelDelivery\ParcelDeliveryFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createParcelDeliveryFactoryMock(Unit $unit, $parcelDelivery): ParcelDeliveryFactory
    {
        $parcelDeliveryFactory = $unit->getMockBuilder(ParcelDeliveryFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parcelDeliveryFactory->method('create')
            ->willReturn($parcelDelivery);

        return $parcelDeliveryFactory;
    }

    /**
     * @param \Codeception\Test\Unit $unit
     * @param string $status
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ItemStateTransfer
     */
    protected function createItemStateTransferMock(Unit $unit, string $status): ItemStateTransfer
    {
        $stateTransfer = $unit->getMockBuilder('Generated\Shared\Transfer\ItemStateTransfer')
            // @TODO use deprecated setMethods because addMethods doesn't support unknown types. Change when it does.
            ->setMethods([ 'getName' ])
            ->getMock();
        $stateTransfer->method('getName')
            ->willReturn($status);

        return $stateTransfer;
    }

    /**
     * @param \Codeception\Test\Unit $unit
     * @param int $id
     * @param \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ItemStateTransfer $stateTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransferMock(
        Unit $unit,
        int $id,
        ItemStateTransfer $stateTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ItemTransfer {
        $itemTransfer = $unit->getMockBuilder('Generated\Shared\Transfer\ItemTransfer')
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
}
