<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify;

class ShopifyClientNonOAuth extends ShopifyClient
{
    private $apiKey;
    private $secretKey;
    private $shopName;
    private $httpClient;

    private static $resources = [
        "order",
        "fulfillment",
        "checkout",
        "fulfillment_event",
        "product",
        "shop",
        "variant"
    ];

    public function __construct($apiKey, $secretKey, $shopName)
    {
        foreach (self::$resources as $resource) {
            $className = 'Shopify\Shopify' . str_replace("_", "", ucwords($resource, "_"));
            $this->{$resource . "s"} = new $className($this);
        }
        $this->setApiKey($apiKey);
        $this->setSecretKey($secretKey);
        $this->setShopName($shopName);
        $this->setHttpClient();
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
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

    private function authHeaders()
    {
        $authorization = base64_encode($this->apiKey.':'.$this->secretKey);
        return [
            'Content-Type: application/json',
            'authorization: Basic '.$authorization
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
        $this->httpClient = ($client ? $client : new CurlRequest());
    }
}
