<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector;

use Flagbit\Shared\SprykerSmartInboxConnector\OneAndOneMailConnectorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OneAndOneMailConnectorConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getShopName(): string
    {
        return $this->get(OneAndOneMailConnectorConstants::SHOP_NAME);
    }

    /**
     * @return array
     */
    public function getStatusMatrix(): array
    {
        return $this->get(OneAndOneMailConnectorConstants::MATRIX_KEY);
    }
}
