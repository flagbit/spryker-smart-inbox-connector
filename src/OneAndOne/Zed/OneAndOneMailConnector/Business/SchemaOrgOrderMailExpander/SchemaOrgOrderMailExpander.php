<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander;

use EinsUndEins\SchemaOrgMailBody\Model\Order;
use EinsUndEins\SchemaOrgMailBody\Model\ParcelDelivery;
use EinsUndEins\SchemaOrgMailBody\Renderer\OrderRenderer;
use EinsUndEins\SchemaOrgMailBody\Renderer\ParcelDeliveryRenderer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig;

class SchemaOrgOrderMailExpander implements SchemaOrgOrderMailExpanderInterface
{
    private $config;

    public function __construct(OneAndOneMailConnectorConfig $config)
    {
        $this->config = $config;
    }

    public function expandOrderMailTransfer(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer
    {
        foreach ($mailTransfer->getTemplates() as $template) {
            $template->setContent(
                $template->getContent() .
                $this->renderOrderInformation($orderTransfer) .
                $this->renderParcelDeliveryInformation($orderTransfer)
            );
        }

        return $mailTransfer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
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
     */
    protected function getLastChangesStatus(OrderTransfer $orderTransfer): string
    {
        // @TODO get last changed status
        return 'status';
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
