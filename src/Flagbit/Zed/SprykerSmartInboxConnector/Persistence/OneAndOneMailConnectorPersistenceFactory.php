<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\OneAndOneMailConnectorConfig getConfig()
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\Persistence\OneAndOneMailConnectorRepositoryInterface getRepository()
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
