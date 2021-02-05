<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Business\ParcelDelivery;

use Generated\Shared\Transfer\ParcelDeliveryTransfer;

class ParcelDeliveryFactory
{
    public function create(): ParcelDeliveryTransfer
    {
        return new ParcelDeliveryTransfer();
    }
}
