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

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    protected function authHeaders()
    {
        $authorization = base64_encode($this->apiKey.':'.$this->secretKey);
        return [
            'Content-Type: application/json',
            'authorization: Basic '.$authorization
        ];
    }
}
