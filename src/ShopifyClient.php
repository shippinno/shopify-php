<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify;

use Psr\Log\LoggerInterface;

class ShopifyClient
{
    /**
     * @var string
     */
    protected $shopName;

    /**
     * @var string|null
     */
    private $accessToken = null;

    /**
     * @var
     */
    protected $httpClient;

    /**
     * @var LoggerInterface|null
     */
    protected $logger;

    protected function resources ()
    {
        return [
            "order",
            "fulfillment",
            "checkout",
            "fulfillment_event",
            "product",
            "shop",
            "variant",
            "location",
            "inventory_level",
        ];
    }

    public function __construct($shopName, $logger = null)
    {
        $this->logger = $logger;
        foreach ($this->resources() as $resource) {
            $className = 'Shopify\Shopify' . str_replace("_", "", ucwords($resource, "_"));
            $this->{$resource . "s"} = new $className($this);
        }
        $this->setShopName($shopName);
        $this->setHttpClient();
    }

    public function setAccessToken($accessToken)
    {
        if (preg_match('/^([a-zA-Z0-9]{10,100})$/', $accessToken)===0) {
            throw new \InvalidArgumentException("Access token should be between 10 and 100 letters and numbers");
        }
        $this->accessToken = $accessToken;
    }

    public function setShopName($shopName)
    {
        if (!$this->isValidShopName($shopName)) {
            throw new \InvalidArgumentException(
                'Shop name should be 3-100 letters, numbers, or hyphens e.g. your-store.myshopify.com'
            );
        }
        $this->shopName = $shopName;
    }

    private function isValidShopName($shopName)
    {
        if (preg_match('/^[a-zA-Z0-9\-]{3,100}\.myshopify\.(?:com|io)$/', $shopName)) {
            return true;
        }
        return false;
    }

    private function uriBuilder($resource)
    {
        return 'https://' . $this->shopName . '/admin/' . $resource . '.json';
    }

    protected function authHeaders()
    {
        return [
            'Content-Type: application/json',
            'X-Shopify-Access-Token: ' . $this->accessToken
        ];
    }

    public function call($method, $resource, $payload = null, $parameters = [])
    {
        if (!in_array($method, ["POST", "PUT", "PATCH", "GET", "DELETE", "HEAD"], true)) {
            throw new \InvalidArgumentException("Method not valid");
        }
        return $this->httpClient->request(
            $method,
            $this->uriBuilder($resource),
            $this->authHeaders(),
            $payload,
            $parameters
        );
    }

    public function setHttpClient(HttpRequestInterface $client = null)
    {
        $this->httpClient = ($client ? $client : new CurlRequest($this->logger));
    }
}
