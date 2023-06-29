<?php

namespace Shopify\Resources;

use Shopify\CommonRead;
use Shopify\CommonReadList;

class InventoryLevel extends ShopifyObject
{
    use CommonRead;
    use CommonReadList;

    const PLURAL = "inventory_levels";
    const SINGULAR = "inventory_level";

    /**
     * @param string $locationId
     * @return mixed
     */
    public function readLocationLevels($locationId)
    {
        $resource = $this->buildResource('locations' . DIRECTORY_SEPARATOR . $locationId . DIRECTORY_SEPARATOR . static::PLURAL);

        return $this->client->call("GET", $resource, null, []);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function setLocationLevels(array $data)
    {
        $resource = $this->buildResource(static::PLURAL . DIRECTORY_SEPARATOR . 'set');

        return $this->client->call("POST", $resource, $data, []);
    }
}
