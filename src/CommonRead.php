<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify;

trait CommonRead
{
    public function read($id, $apiVersion = null)
    {
        $prefix = '';
        if (isset($apiVersion)) {
            $prefix = join(DIRECTORY_SEPARATOR, ['api', $apiVersion, '']);
        }
        return $this->get($id, $prefix);
    }
}
