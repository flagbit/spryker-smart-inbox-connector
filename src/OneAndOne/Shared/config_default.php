<?php

use OneAndOne\Shared\OneAndOneMailConnector\OneAndOneMailConnectorConstants;
use Spryker\Shared\Kernel\KernelConstants;

$config[OneAndOneMailConnectorConstants::MATRIX_KEY] = [
    'new'                      => 'OrderProcessing',
    'payment pending'          => 'OrderProcessing',
    'invalid'                  => 'OrderCancelled',
    'confirmed'                => 'OrderProcessing',
    'paid'                     => 'OrderProcessing',
    'cancelled'                => 'OrderCancelled',
    'invoice generated'        => 'OrderProcessing',
    'waiting'                  => 'OrderProcessing',
    'gift card purchased'      => 'OrderProcessing',
    'gift card created'        => 'OrderProcessing',
    'gift card shipped'        => 'OrderProcessing',
    'exported'                 => 'OrderProcessing',
    'waiting for conformation' => 'OrderProcessing',
    'shipped'                  => 'OrderInTransit',
    'delivered'                => 'OrderDelivered',
    'closed'                   => 'OrderDelivered',
    'waiting for return'       => 'OrderInTransit',
    'returned'                 => 'OrderReturned',
    'return canceled'          => 'OrderInTransit',
    'shipped to customer'      => 'OrderDelivered',
    'refunded'                 => 'OrderCancelled',
];

$config[OneAndOneMailConnectorConstants::SHOP_NAME] = 'shop.com';

$config[KernelConstants::PROJECT_NAMESPACES] = array_merge($config[KernelConstants::PROJECT_NAMESPACES], ['OneAndOne']);
