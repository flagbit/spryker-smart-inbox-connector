<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Flagbit\Zed\SprykerSmartInboxConnector\Persistence\SprykerSmartInboxConnectorPersistenceFactory getFactory()
 */
class SprykerSmartInboxConnectorRepository extends AbstractRepository implements SprykerSmartInboxConnectorRepositoryInterface
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
