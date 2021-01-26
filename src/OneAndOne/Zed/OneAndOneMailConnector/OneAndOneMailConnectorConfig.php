<?php

namespace OneAndOne\Zed\OneAndOneMailConnector;

use OneAndOne\Shared\OneAndOneMailConnector\OneAndOneMailConnectorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OneAndOneMailConnectorConfig extends AbstractBundleConfig
{
    public function getShopName(): string
    {
        return $this->get(OneAndOneMailConnectorConstants::SHOP_NAME);
    }

    public function getStatusMatrix(): array
    {
        return $this->get(OneAndOneMailConnectorConstants::MATRIX_KEY);
    }
}