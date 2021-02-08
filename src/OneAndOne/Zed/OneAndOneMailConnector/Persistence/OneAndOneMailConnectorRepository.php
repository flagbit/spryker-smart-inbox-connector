<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * Class OneAndOneMailConnectorRepository
 *
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorPersistenceFactory getFactory()
 *
 * @package OneAndOne\Zed\OneAndOneMailConnector\Persistence
 */
class OneAndOneMailConnectorRepository extends AbstractRepository implements OneAndOneMailConnectorRepositoryInterface
{
    /**
     * @param array $idOrderItems
     *
     * @return mixed
     */
    public function findSpySalesOrderItemsById(array $idOrderItems)
    {
        return $this
            ->getFactory()
            ->createSpySalesOrderItemQuery()
            ->filterByIdSalesOrderItem($idOrderItems, Criteria::IN)
            ->find();
    }
}
