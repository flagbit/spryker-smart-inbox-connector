<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Dependency\Plugin;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface;

class OneAndOneMailConnectorOrderMailExpanderPlugin extends AbstractPlugin implements OmsOrderMailExpanderPluginInterface
{
    public function expand(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer
    {
        return $this->getFacade()->expendOrderMailTransfer($mailTransfer, $orderTransfer);
    }
}
