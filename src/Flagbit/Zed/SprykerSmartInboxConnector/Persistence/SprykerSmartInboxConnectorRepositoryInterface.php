<?php

namespace Flagbit\Zed\SprykerSmartInboxConnector\Persistence;

interface SprykerSmartInboxConnectorRepositoryInterface
{
    /**
     * @param array $idOrderItems
     *
     * @return mixed
     */
    public function findSpySalesOrderItemByIdWithLastStateChange(array $idOrderItems);
}
