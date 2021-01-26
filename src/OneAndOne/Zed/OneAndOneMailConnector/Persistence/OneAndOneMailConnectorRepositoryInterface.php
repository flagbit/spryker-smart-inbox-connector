<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Persistence;

interface OneAndOneMailConnectorRepositoryInterface
{
    public function findSpySalesOrderItemById(int $idOrderItem);
}
