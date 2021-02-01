<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business\SchemaOrgOrderMailExpander;

use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface SchemaOrgOrderMailExpanderInterface
{
    public function expandOrderMailTransfer(MailTransfer $mailTransfer, OrderTransfer $orderTransfer, MailTemplateTransfer $mailTemplateTransfer): MailTransfer;
}
