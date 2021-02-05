<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander;

use Generated\Shared\Transfer\ItemTransfer;
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
    /**
     * @var \OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig
     */
    private $config;

    /**
     * @var \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepositoryInterface
     */
    private $repository;

    /**
     * @param \OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig $config
     * @param \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepositoryInterface $repository
     */
    public function __construct(
        OneAndOneMailConnectorConfig $config,
        OneAndOneMailConnectorRepositoryInterface $repository
    ) {
        $this->config = $config;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MailTemplateTransfer $mailTemplateTransfer
     * @param \Generated\Shared\Transfer\SchemaOrgTransfer $schemaOrgTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getLastChangedStatus(OrderTransfer $orderTransfer): string
    {
        $salesOrderItems = $this->getSalesOrderItems($orderTransfer);
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
     * @param \Generated\Shared\Transfer\MailTemplateTransfer $mailTemplateTransfer
     *
     * @return void
     */
    protected function fillMailTemplateInfos(MailTemplateTransfer $mailTemplateTransfer): void
    {
        $mailTemplateTransfer->setIsHtml(true);
        $mailTemplateTransfer->setName('oneAndOneMailConnector/mail/schema_org_order_connector.html.twig');
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SchemaOrgTransfer $schemaOrgTransfer
     *
     * @return void
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
     * @param \Generated\Shared\Transfer\ParcelDeliveryTransfer $parcelDeliveryTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return void
     */
    protected function fillParcelDelivery(ParcelDeliveryTransfer $parcelDeliveryTransfer, ItemTransfer $item): void
    {
        $parcelDeliveryTransfer->setDeliveryName($item->getShipment()->getCarrier()->getName());
        $parcelDeliveryTransfer->setStatus($this->getSchemaStatusWithOrderStatus($item->getState()->getName()));
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
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
     * @param \Propel\Runtime\Collection\ObjectCollection $salesOrderItems
     *
     * @return \Spryker\Zed\Sales\Persistence\Base\SpySalesOrderItem|null
     */
    protected function findLastChangesSalesOrderItem(ObjectCollection $salesOrderItems): ?SpySalesOrderItem
    {
        $lastChangedSalesOrderItem = null;
        /** @var \Spryker\Zed\Sales\Persistence\Base\SpySalesOrderItem $salesOrderItem */
        foreach ($salesOrderItems as $salesOrderItem) {
            if (
                $lastChangedSalesOrderItem === null
                || $salesOrderItems->getLastStateChange()
                > $lastChangedSalesOrderItem->getLastStateChange()
            ) {
                $lastChangedSalesOrderItem = $salesOrderItem;
            }
        }

        return $lastChangedSalesOrderItem;
    }
}
