<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

class OneAndOneMailConnectorRepository extends AbstractRepository implements OneAndOneMailConnectorRepositoryInterface
{
    public function findSpySalesOrderItemById(int $idOrderItem)
    {
        $orderItemEntity = $this
            ->getFactory()
            ->createSpySalesOrderItemQuery()
            ->filterByIdSalesOrderItem($idOrderItem)
            ->findOne();

        if (!$orderItemEntity) {
            return null;
        }

        return null; // @TODO return value
    }

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
