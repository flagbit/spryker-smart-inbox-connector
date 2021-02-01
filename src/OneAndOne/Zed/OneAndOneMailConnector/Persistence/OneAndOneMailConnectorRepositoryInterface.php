<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Persistence;

interface OneAndOneMailConnectorRepositoryInterface
{
    public function findSpySalesOrderItemById(int $idOrderItem);

    public function findSpySalesOrderItemsById(array $idOrderItems);
}
