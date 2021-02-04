<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Persistence;

interface OneAndOneMailConnectorRepositoryInterface
{
    public function findSpySalesOrderItemsById(array $idOrderItems);
}
