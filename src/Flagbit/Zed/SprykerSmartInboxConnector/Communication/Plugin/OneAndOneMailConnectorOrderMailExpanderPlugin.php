<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface;

/**
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\Business\OneAndOneMailConnectorFacade getFacade()
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\OneAndOneMailConnectorConfig getConfig()
 */
class OneAndOneMailConnectorOrderMailExpanderPlugin extends AbstractPlugin implements OmsOrderMailExpanderPluginInterface
{
    /**
     * @method \Flagbit\Zed\SprykerSmartInboxConnector\Business\OneAndOneMailConnectorFacade getFacade()
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function expand(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer
    {
        return $this->getFacade()->expandOrderMailTransfer($mailTransfer, $orderTransfer);
    }
}
