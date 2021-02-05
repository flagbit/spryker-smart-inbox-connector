<?php

namespace OneAndOne\Zed\OneAndOneMailConnector;

use OneAndOne\Shared\OneAndOneMailConnector\OneAndOneMailConnectorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OneAndOneMailConnectorConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getShopName(): string
    {
        return $this->get(OneAndOneMailConnectorConstants::ONE_AND_ONE_MAIL_CONNECTOR_SHOP_NAME);
    }

    /**
     * @return array
     */
    public function getStatusMatrix(): array
    {
        return $this->get(OneAndOneMailConnectorConstants::ONE_AND_ONE_MAIL_CONNECTOR_MATRIX_KEY);
    }
}
