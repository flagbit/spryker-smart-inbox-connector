<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Business\ParcelDelivery;

use Generated\Shared\Transfer\ParcelDeliveryTransfer;

class ParcelDeliveryFactory
{
    /**
     * @return \Generated\Shared\Transfer\ParcelDeliveryTransfer
     */
    public function create(): ParcelDeliveryTransfer
    {
        return new ParcelDeliveryTransfer();
    }
}
