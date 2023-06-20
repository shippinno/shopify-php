<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify\Resources;

use Shopify\ShopifyClient;

class ShopifyObject
{
    protected $client;

    /**
     * @var string|null
     */
    protected $apiVersion;

    /**
     * @param ShopifyClient $client
     * @param string $version
     */
    public function __construct(ShopifyClient $client, ?string $apiVersion = null)
    {
        $this->client = $client;
        $this->apiVersion = $apiVersion;
    }

    protected function get($id, $prefix = '')
    {
        $resource = $this->buildResource($prefix . static::PLURAL . DIRECTORY_SEPARATOR . $id);

        return $this->client->call("GET", $resource, null, []);
    }

    protected function getList(array $options = [], $prefix = '')
    {
        $resource = $this->buildResource($prefix . static::PLURAL);

        return $this->client->call("GET", $resource, null, $options);
    }

    protected function post($data, $prefix = '')
    {
        $resource = $this->buildResource($prefix . static::PLURAL);

        return $this->client->call("POST", $resource, [static::SINGULAR => $data], []);
    }

    protected function delete($id, $prefix = '')
    {
        $resource = $this->buildResource($prefix . static::PLURAL . DIRECTORY_SEPARATOR . $id);

        return $this->client->call("DELETE", $resource, null, []);
    }

    protected function put($id, $data, $prefix = '')
    {
        $resource = $this->buildResource($prefix . static::PLURAL . DIRECTORY_SEPARATOR . $id);

        return $this->client->call("PUT", $resource, [static::SINGULAR => $data], []);
    }

    /**
     * @param string $resource
     * @return string
     */
    protected function buildResource(string $resource): string
    {
        if (isset($this->apiVersion)) {
            return join(DIRECTORY_SEPARATOR, [
                'api',
                $this->apiVersion,
                $resource
            ]);
        }

        return $resource;
    }
}
