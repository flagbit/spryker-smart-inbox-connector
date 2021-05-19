<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Business;

use Flagbit\Zed\SprykerSmartInboxConnector\Business\ParcelDelivery\ParcelDeliveryFactory;
use Flagbit\Zed\SprykerSmartInboxConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpander;
use Flagbit\Zed\SprykerSmartInboxConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpanderInterface;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\SchemaOrgTransfer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\SprykerSmartInboxConnectorConfig getConfig()
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\Persistence\SprykerSmartInboxConnectorRepositoryInterface getRepository()
 */
class SprykerSmartInboxConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Flagbit\Zed\SprykerSmartInboxConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpanderInterface
     */
    public function createSchemaOrgOrderMailExpander(): SchemaOrgOrderMailExpanderInterface
    {
        return new SchemaOrgOrderMailExpander($this->getConfig(), $this->getRepository(), $this->createParcelDeliveryFactory());
    }

    /**
     * @return \Generated\Shared\Transfer\MailTemplateTransfer
     */
    public function createMailTemplateTransfer(): MailTemplateTransfer
    {
        return new MailTemplateTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\SchemaOrgTransfer
     */
    public function createSchemaOrgTransfer(): SchemaOrgTransfer
    {
        return new SchemaOrgTransfer();
    }

    /**
     * @return \Flagbit\Zed\SprykerSmartInboxConnector\Business\ParcelDelivery\ParcelDeliveryFactory
     */
    protected function createParcelDeliveryFactory(): ParcelDeliveryFactory
    {
        return new ParcelDeliveryFactory();
    }
}
