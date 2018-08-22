<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify;

class ShopifyLocation extends ShopifyObject
{
    use CommonRead;
    use CommonReadList;

    const PLURAL = "locations";
    const SINGULAR = "location";
}
