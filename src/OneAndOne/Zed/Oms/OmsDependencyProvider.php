<?php

namespace OneAndOne\Zed\Oms;

use OneAndOne\Zed\OneAndOneMailConnector\Communication\Plugin\OneAndOneMailConnectorOrderMailExpanderPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\OmsDependencyProvider AS SprykerOmsDependencyProvider;

class OmsDependencyProvider extends SprykerOmsDependencyProvider
{
    protected function getOmsOrderMailExpanderPlugins(Container $container)
    {
        return array_merge(parent::getOmsOrderMailExpanderPlugins($container), [new OneAndOneMailConnectorOrderMailExpanderPlugin()]);
    }
}
