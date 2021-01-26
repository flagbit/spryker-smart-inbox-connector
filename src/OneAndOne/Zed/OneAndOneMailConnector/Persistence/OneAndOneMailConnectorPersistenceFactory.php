<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class OneAndOneMailConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    public function createSpySalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }
}
