<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface;

class OneAndOneMailConnectorOrderMailExpanderPlugin extends AbstractPlugin implements OmsOrderMailExpanderPluginInterface
{
    /**
     * @method \Spryker\Zed\OneAndOneMailConnector\Business\OneAndOneMailConnectorFacade getFacade()
     *
     * @param MailTransfer  $mailTransfer
     * @param OrderTransfer $orderTransfer
     *
     * @return MailTransfer
     */
    public function expand(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer
    {
        return $this->getFacade()->expendOrderMailTransfer($mailTransfer, $orderTransfer);
    }
}
