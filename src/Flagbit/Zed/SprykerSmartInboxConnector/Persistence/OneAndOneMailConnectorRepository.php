<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\Persistence\OneAndOneMailConnectorPersistenceFactory getFactory()
 */
class OneAndOneMailConnectorRepository extends AbstractRepository implements OneAndOneMailConnectorRepositoryInterface
{
    /**
     * @param array $idOrderItems
     *
     * @return mixed
     */
    public function findSpySalesOrderItemByIdWithLastStateChange(array $idOrderItems)
    {
        return $this
            ->getFactory()
            ->createSpySalesOrderItemQuery()
            ->orderByLastStateChange(Criteria::DESC)
            ->filterByIdSalesOrderItem($idOrderItems, Criteria::IN)
            ->findOne();
    }
}
