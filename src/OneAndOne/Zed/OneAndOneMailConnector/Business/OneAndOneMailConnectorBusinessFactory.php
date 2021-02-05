<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business;

use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\SchemaOrgTransfer;
use OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpander;
use OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpanderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig getConfig()
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepositoryInterface getRepository()
 */
class OneAndOneMailConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpanderInterface
     */
    public function createSchemaOrgOrderMailExpander(): SchemaOrgOrderMailExpanderInterface
    {
        return new SchemaOrgOrderMailExpander($this->getConfig(), $this->getRepository());
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
}
