<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify;

class ShopifyOrder extends ShopifyObject
{
    use Countable;
    use CommonRead;
    use CommonDestroy;
    use CommonReadList;
    use CommonUpdate;
    use CommonCreate;
    use CommonReadCount;

    const PLURAL = "orders";
    const SINGULAR = "order";

    public function readList(array $options = [])
    {
        if (isset($options['apiVersion'])) {
            if (1 !== preg_match('/^[0-9]{4}-[0-9]{2}$|^unstable$/', $options['apiVersion'])) {
                throw new \InvalidArgumentException('Version string must be of YYYY-MM or unstable');
            }
            $resource = join(DIRECTORY_SEPARATOR, ['api', $options['apiVersion'], self::PLURAL]);
        } else {
            $resource = static::PLURAL;
        }
        return $this->client->call("GET", $resource, null, $options);
    }

    public function close($id)
    {
        $resource = join(DIRECTORY_SEPARATOR, [self::PLURAL, $id, "close"]);
        return $this->client->call("POST", $resource, []);
    }

    public function open($id)
    {
        $resource = join(DIRECTORY_SEPARATOR, [self::PLURAL, $id, "open"]);
        return $this->client->call("POST", $resource, []);
    }

    public function cancel($id, array $data = [])
    {
        $resource = join(DIRECTORY_SEPARATOR, [self::PLURAL, $id, "cancel"]);
        return $this->client->call("POST", $resource, $data);
    }
}
