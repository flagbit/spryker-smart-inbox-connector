<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business;

use EinsUndEins\SchemaOrgMailBody\Model\Order;
use EinsUndEins\SchemaOrgMailBody\Model\ParcelDelivery;
use EinsUndEins\SchemaOrgMailBody\Renderer\OrderRenderer;
use EinsUndEins\SchemaOrgMailBody\Renderer\ParcelDeliveryRenderer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\OneAndOneMailConnectorTransfer;
use OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpanderInterface;
use OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpander;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class OneAndOneMailConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @param OneAndOneMailConnectorTransfer $transfer
     *
     * @return Order
     */
    public function createOrder(OneAndOneMailConnectorTransfer $transfer): Order
    {
        return new Order($transfer->getOrderNumber(), $transfer->getOrderStatus(), $transfer->getShopName());
    }

    /**
     * @param OneAndOneMailConnectorTransfer $transfer
     *
     * @return ParcelDelivery
     */
    public function createParcelDelivery(OneAndOneMailConnectorTransfer $transfer): ParcelDelivery {
        return new ParcelDelivery(
            $transfer->getDeliveryName(),
            $transfer->getTrackingNumber(),
            $transfer->getOrderNumber(),
            $transfer->getOrderStatus(),
            $transfer->getShopName());
    }

    /**
     * @param Order $order
     *
     * @return OrderRenderer
     */
    public function createOrderRenderer(Order $order): OrderRenderer
    {
        return new OrderRenderer($order);
    }

    /**
     * @param ParcelDelivery $parcelDelivery
     *
     * @return ParcelDeliveryRenderer
     */
    public function createParcelDeliveryRenderer(ParcelDelivery $parcelDelivery): ParcelDeliveryRenderer
    {
        return new ParcelDeliveryRenderer($parcelDelivery);
    }

    /**
     * @return SchemaOrgOrderMailExpanderInterface
     */
    public function createSchemaOrgOrderMailExpander(): SchemaOrgOrderMailExpanderInterface
    {
        return new SchemaOrgOrderMailExpander($this->getConfig());
    }

    /**
     * @return MailTemplateTransfer
     */
    public function createMailTemplateTransfer(): MailTemplateTransfer
    {
        return new MailTemplateTransfer();
    }
}
