<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * Class OneAndOneMailConnectorFacade
 *
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Business\OneAndOneMailConnectorBusinessFactory getFactory()
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepositoryInterface getRepository()
 *
 * @package OneAndOne\Zed\OneAndOneMailConnector\Business
 */
class OneAndOneMailConnectorFacade extends AbstractFacade implements OneAndOneMailConnectorFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function expendOrderMailTransfer(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer
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
