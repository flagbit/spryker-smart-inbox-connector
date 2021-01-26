<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Persistence;

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
}
