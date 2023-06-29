<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify\Resources;

use Shopify\Countable;
use Shopify\ShopifyOrder;

class FulfillmentOrder extends ShopifyObject
{
    const PLURAL = "fulfillment_orders";
    const SINGULAR = "fulfillment_order";

    use Countable;

    public function readList($orderId = null, array $options = [])
    {
        return $this->getList($options, $this->prefix($orderId));
    }

    protected function prefix($orderId)
    {
        return ShopifyOrder::PLURAL . DIRECTORY_SEPARATOR . $orderId . DIRECTORY_SEPARATOR;
    }
}
