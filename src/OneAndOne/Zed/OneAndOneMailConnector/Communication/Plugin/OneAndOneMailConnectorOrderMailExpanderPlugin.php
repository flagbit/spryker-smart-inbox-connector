<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface;

/**
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Business\OneAndOneMailConnectorFacade getFacade()
 * @method \OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig getConfig()
 */
class OneAndOneMailConnectorOrderMailExpanderPlugin extends AbstractPlugin implements OmsOrderMailExpanderPluginInterface
{
    /**
     * @method \OneAndOne\Zed\OneAndOneMailConnector\Business\OneAndOneMailConnectorFacade getFacade()
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function expand(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer
    {
        return $this->getFacade()->expendOrderMailTransfer($mailTransfer, $orderTransfer);
    }
}
