<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Persistence;

interface OneAndOneMailConnectorRepositoryInterface
{
    /**
     * @param array $idOrderItems
     *
     * @return mixed
     */
    public function findSpySalesOrderItemByIdWithLastStateChange(array $idOrderItems);
}
