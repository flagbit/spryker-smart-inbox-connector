# 1&1 Spryker Smart Inbox Connector

Extends Spryker transaction mails with a Schema.org conform HTML content

## Installation

### Require the module

To get the module you have to require it with composer:
`composer require flagbit/spryker-smart-inbox-connector`

### Generate transfer objects

You have to generate some transfer objects
`console transfer:generate`

### Add Plugin to OmsDependencyProvider

The Method `\Spryker\Zed\Oms\OmsDependencyProvider::getOmsOrderMailExpanderPlugins` returns the plugins which should expand the
order mail transfer. You have to extend this class and add a new object
of `\Flagbit\Zed\SprykerSmartInboxConnector\Communication\Plugin\OneAndOneMailConnectorOrderMailExpanderPlugin` to the return
array.

### Set configuration

#### Add project namespace

You have to add the namespace of the module to core-namespaces:

```php
$config[\Spryker\Shared\Kernel\KernelConstants::CORE_NAMESPACES] = [
    'SprykerShop',
    'SprykerEco',
    'Spryker',
    'SprykerSdk',
    'Flagbit',
];
```

#### Add shop-name

You have to add the shop-name to config

```php
$config[\Flagbit\Shared\SprykerSmartInboxConnector\OneAndOneMailConnectorConstants::SHOP_NAME] = 'your-shop-name';
```

#### Add status matrix

You have to add a list where every state of your orm points on one of those states [schema.org/OrderStatus](https://www.schema.org/OrderStatus)

```php
$config[\Flagbit\Shared\SprykerSmartInboxConnector\OneAndOneMailConnectorConstants::MATRIX_KEY] = [
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
```
