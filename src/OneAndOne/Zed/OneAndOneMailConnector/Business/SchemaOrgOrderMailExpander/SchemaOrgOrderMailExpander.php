<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander;

use EinsUndEins\SchemaOrgMailBody\Model\Order;
use EinsUndEins\SchemaOrgMailBody\Model\ParcelDelivery;
use EinsUndEins\SchemaOrgMailBody\Renderer\OrderRenderer;
use EinsUndEins\SchemaOrgMailBody\Renderer\ParcelDeliveryRenderer;
use Exception;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig;
use OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepositoryInterface;

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
     *
     * @return MailTransfer
     * @throws Exception
     */
    public function expandOrderMailTransfer(
        MailTransfer $mailTransfer,
        OrderTransfer $orderTransfer,
        MailTemplateTransfer $mailTemplateTransfer
    ): MailTransfer {
        $mailTemplateTransfer->setContent(
            $this->renderOrderInformation($orderTransfer) .
            $this->renderParcelDeliveryInformation($orderTransfer)
        );
        $mailTemplateTransfer->setIsHtml(true);
        $mailTemplateTransfer->setName('OneAndOneMailExpander');
        $mailTransfer->addTemplate($mailTemplateTransfer);

        return $mailTransfer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     * @throws Exception
     */
    protected function renderOrderInformation(OrderTransfer $orderTransfer): string
    {
        return (new OrderRenderer(
            new Order(
                (string)$orderTransfer->getIdSalesOrder(),
                $this->getSchemaStatusWithOrderStatus($this->getLastChangesStatus($orderTransfer)),
                $this->getShopName()
            )
        ))->render();
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function renderParcelDeliveryInformation(OrderTransfer $orderTransfer): string
    {
        $parcelDeliveryExpanding = '';
        foreach ($orderTransfer->getItems() as $item) {
            $schemaParcelDelivery    = new ParcelDelivery(
                $item->getShipment()->getMethod()->getName(),
                //                $item->getShipment()->getCarrier()->getName(),
                'trackingNumber',
                (string)$orderTransfer->getIdSalesOrder(),
                $this->getSchemaStatusWithOrderStatus($item->getFkOmsOrderItemState()),
                $this->getShopName()
            );
            $parcelDeliveryExpanding .= (new ParcelDeliveryRenderer($schemaParcelDelivery))->render();
        }

        return $parcelDeliveryExpanding;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     * @throws Exception
     */
    protected function getLastChangesStatus(OrderTransfer $orderTransfer): string
    {
        $orderIds = [];
        foreach ($orderTransfer->getItems() as $item) {
            $orderIds[] = $item->getIdSalesOrderItem();
        }
        $salesOrderItems = $this->repository->findSpySalesOrderItemsById($orderIds);

        $lastChangedSalesOrderItem = null;
        foreach ($salesOrderItems as $salesOrderItem) {
            if (null === $lastChangedSalesOrderItem
                || $salesOrderItems->getLastStateChange()
                > $lastChangedSalesOrderItem->getLastStateChange()) {
                $lastChangedSalesOrderItem = $salesOrderItem;
            }
        }

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
}
