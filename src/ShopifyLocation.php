<?php

namespace Shopify;

class ShopifyLocation extends ShopifyObject
{
    use CommonRead;
    use CommonReadList;

    const PLURAL = "locations";
    const SINGULAR = "location";
}
