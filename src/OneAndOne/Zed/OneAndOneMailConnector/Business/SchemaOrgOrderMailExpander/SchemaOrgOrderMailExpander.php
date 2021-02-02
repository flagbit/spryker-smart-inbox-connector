<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander;

use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ParcelDeliveryTransfer;
use Generated\Shared\Transfer\SchemaOrgTransfer;
use OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig;
use OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepositoryInterface;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Sales\Persistence\Base\SpySalesOrderItem;

class SchemaOrgOrderMailExpander implements SchemaOrgOrderMailExpanderInterface
{
    private $config;
    private $repository;

    public function __construct(
        OneAndOneMailConnectorConfig $config,
        OneAndOneMailConnectorRepositoryInterface $repository
    ) {
        $this->config     = $config;
        $this->repository = $repository;
    }

    /**
     * @param MailTransfer         $mailTransfer
     * @param OrderTransfer        $orderTransfer
     * @param MailTemplateTransfer $mailTemplateTransfer
     * @param SchemaOrgTransfer    $schemaOrgTransfer
     *
     * @return MailTransfer
     */
    public function expandOrderMailTransfer(
        MailTransfer $mailTransfer,
        OrderTransfer $orderTransfer,
        MailTemplateTransfer $mailTemplateTransfer,
        SchemaOrgTransfer $schemaOrgTransfer
    ): MailTransfer {
        $this->fillMailTemplateInfos($mailTemplateTransfer);
        $mailTransfer->addTemplate($mailTemplateTransfer);

        $this->fillSchemaOrgTransfer($orderTransfer, $schemaOrgTransfer);
        $mailTransfer->setSchemaOrg($schemaOrgTransfer);

        return $mailTransfer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getLastChangedStatus(OrderTransfer $orderTransfer): string
    {
        $salesOrderItems           = $this->getSalesOrderItems($orderTransfer);
        $lastChangedSalesOrderItem = $this->findLastChangesSalesOrderItem($salesOrderItems);

        return $lastChangedSalesOrderItem->getState()->getName();
    }

    /**
     * @return string
     */
    protected function getShopName(): string
    {
        return $this->config->getShopName();
    }

    /**
     * @param string $status
     *
     * @return string
     */
    protected function getSchemaStatusWithOrderStatus(string $status): string
    {
        return $this->config->getStatusMatrix()[$status];
    }

    /**
     * @param MailTemplateTransfer $mailTemplateTransfer
     */
    protected function fillMailTemplateInfos(MailTemplateTransfer $mailTemplateTransfer): void
    {
        $mailTemplateTransfer->setIsHtml(true);
        $mailTemplateTransfer->setName('oneAndOneMailConnector/mail/schema_org_order_connector.html.twig');
    }

    /**
     * @param OrderTransfer     $orderTransfer
     * @param SchemaOrgTransfer $schemaOrgTransfer
     */
    protected function fillSchemaOrgTransfer(OrderTransfer $orderTransfer, SchemaOrgTransfer $schemaOrgTransfer): void
    {
        foreach ($orderTransfer->getItems() as $item) {
            $parcelDeliveryTransfer = new ParcelDeliveryTransfer();
            $this->fillParcelDelivery($parcelDeliveryTransfer, $item);
            $schemaOrgTransfer->addParcelDelivery($parcelDeliveryTransfer);
        }

        $schemaOrgTransfer->setShopName($this->getShopName());
        $schemaOrgTransfer->setStatus($this->getSchemaStatusWithOrderStatus($this->getLastChangedStatus($orderTransfer)));
    }

    /**
     * @param ParcelDeliveryTransfer $parcelDeliveryTransfer
     * @param                        $item
     */
    protected function fillParcelDelivery(ParcelDeliveryTransfer $parcelDeliveryTransfer, $item): void
    {
        $parcelDeliveryTransfer->setDeliveryName($item->getShipment()->getCarrier()->getName());
        $parcelDeliveryTransfer->setTrackingNumber('trackingNumber');   // @TODO check for real trackingNumber
        $parcelDeliveryTransfer->setStatus($this->getSchemaStatusWithOrderStatus($item->getState()->getName()));
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return ObjectCollection
     */
    protected function getSalesOrderItems(OrderTransfer $orderTransfer)
    {
        $orderIds = [];
        foreach ($orderTransfer->getItems() as $item) {
            $orderIds[] = $item->getIdSalesOrderItem();
        }

        return $this->repository->findSpySalesOrderItemsById($orderIds);
    }

    /**
     * @param $salesOrderItems
     *
     * @return null|SpySalesOrderItem
     */
    protected function findLastChangesSalesOrderItem($salesOrderItems): ?SpySalesOrderItem
    {
        $lastChangedSalesOrderItem = null;
        /** @var SpySalesOrderItem $salesOrderItem */
        foreach ($salesOrderItems as $salesOrderItem) {
            if (null === $lastChangedSalesOrderItem
                || $salesOrderItems->getLastStateChange()
                > $lastChangedSalesOrderItem->getLastStateChange()) {
                $lastChangedSalesOrderItem = $salesOrderItem;
            }
        }

        return $lastChangedSalesOrderItem;
    }
}
