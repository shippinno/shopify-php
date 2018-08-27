<?php

namespace Shopify;

class ShopifyLocation extends ShopifyObject
{
    use CommonRead;
    use CommonReadList;

    const PLURAL = "locations";
    const SINGULAR = "location";

    public function readLocationLevels($id)
    {
        $resource = static::PLURAL . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR .'inventory_levels';
        return $this->client->call("GET", $resource, null, []);
    }

}
