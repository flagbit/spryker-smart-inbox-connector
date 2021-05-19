<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\SprykerSmartInboxConnectorConfig getConfig()
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\Persistence\SprykerSmartInboxConnectorRepositoryInterface getRepository()
 */
class SprykerSmartInboxConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSpySalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }
}
