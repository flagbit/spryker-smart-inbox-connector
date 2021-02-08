<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * Class OneAndOneMailConnectorPersistenceFactory
 *
 * @method \OneAndOne\Zed\OneAndOneMailConnector\OneAndOneMailConnectorConfig getConfig()
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepositoryInterface getRepository()
 *
 * @package OneAndOne\Zed\OneAndOneMailConnector\Persistence
 */
class OneAndOneMailConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSpySalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }
}
