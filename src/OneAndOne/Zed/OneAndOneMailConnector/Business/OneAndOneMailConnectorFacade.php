<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

class OneAndOneMailConnectorFacade extends AbstractFacade
    implements OneAndOneMailConnectorFacadeInterface
{
    public function expendOrderMailTransfer(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer
    {
        return $this->getFactory()
            ->createSchemaOrgOrderMailExpander()
            ->expandOrderMailTransfer($mailTransfer, $orderTransfer, $this->getFactory()->createMailTemplateTransfer());
    }
}
