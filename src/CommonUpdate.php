<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify;

trait CommonUpdate
{
    public function update($id, $data, $apiVersion = null)
    {
        $prefix = '';
        if (isset($apiVersion)) {
            $prefix = join(DIRECTORY_SEPARATOR, ['api', $apiVersion, '']);
        }

        return $this->put($id, $data, $prefix);
    }
}
