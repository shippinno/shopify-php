<?php

namespace Shopify;

class ShopifyInventoryLevel extends ShopifyObject
{
    use CommonRead;
    use CommonReadList;

    const PLURAL = "inventory_levels";
    const SINGULAR = "inventory_level";

    public function readLocationLevels($locationId)
    {
        $resource = 'locations' . DIRECTORY_SEPARATOR . $locationId . DIRECTORY_SEPARATOR . static::PLURAL;
        return $this->client->call("GET", $resource, null, []);
    }

    public function setLocationLevels($data)
    {
        $resource = static::PLURAL . DIRECTORY_SEPARATOR . 'set';
        return $this->client->call("POST", $resource, $data, []);
    }

}
