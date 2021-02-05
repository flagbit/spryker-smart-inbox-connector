<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Persistence;

interface OneAndOneMailConnectorRepositoryInterface
{
    /**
     * @param array $idOrderItems
     *
     * @return mixed
     */
    public function findSpySalesOrderItemsById(array $idOrderItems);
}
