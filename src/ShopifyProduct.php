<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify;

class ShopifyProduct extends ShopifyObject
{
    use Countable;
    use CommonRead;
    use CommonReadList;
    use CommonCreate;
    use CommonDestroy;
    use CommonUpdate;
    use CommonReadCount;

    const PLURAL = "products";
    const SINGULAR = "product";

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
}
