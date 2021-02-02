<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business;

use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\OneAndOneMailConnectorTransfer;
use Generated\Shared\Transfer\SchemaOrgTransfer;
use OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpanderInterface;
use OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander\SchemaOrgOrderMailExpander;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class OneAndOneMailConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return SchemaOrgOrderMailExpanderInterface
     */
    public function createSchemaOrgOrderMailExpander(): SchemaOrgOrderMailExpanderInterface
    {
        return new SchemaOrgOrderMailExpander($this->getConfig(), $this->getRepository());
    }

    /**
     * @return MailTemplateTransfer
     */
    public function createMailTemplateTransfer(): MailTemplateTransfer
    {
        return new MailTemplateTransfer();
    }

    /**
     * @return SchemaOrgData
     */
    public function createSchemaOrgTransfer(): SchemaOrgData
    {
        return new SchemaOrgTransfer();
    }
}
