<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify\Resources;

use Shopify\Countable;

class Fulfillment extends ShopifyObject
{
    const PLURAL = "fulfillments";
    const SINGULAR = "fulfillment";

    use Countable;

    public function readList($orderId = null, array $options = [])
    {
        return $this->getList($options, $this->prefix($orderId));
    }

    public function readCount($orderId, array $options = [])
    {
        return $this->getCount($orderId, $options);
    }

    public function read($id, $orderId)
    {
        return $this->get($id, $this->prefix($orderId));
    }

    public function create($data)
    {
        return $this->post($data);
    }

    public function update($id, $orderId, $data)
    {
        return $this->put($id, $data, $this->prefix($orderId));
    }

    protected function prefix($orderId)
    {
        return ShopifyOrder::PLURAL . DIRECTORY_SEPARATOR . $orderId . DIRECTORY_SEPARATOR;
    }
}
