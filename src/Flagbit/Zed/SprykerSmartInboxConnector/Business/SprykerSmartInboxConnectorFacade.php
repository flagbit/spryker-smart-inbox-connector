<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Business;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\Business\SprykerSmartInboxConnectorBusinessFactory getFactory()
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\Persistence\SprykerSmartInboxConnectorRepositoryInterface getRepository()
 */
class SprykerSmartInboxConnectorFacade extends AbstractFacade implements SprykerSmartInboxConnectorFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function expandOrderMailTransfer(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer
    {
        return $this->getFactory()
            ->createSchemaOrgOrderMailExpander()
            ->expandOrderMailTransfer(
                $mailTransfer,
                $orderTransfer,
                $this->getFactory()->createMailTemplateTransfer(),
                $this->getFactory()->createSchemaOrgTransfer()
            );
    }
}
