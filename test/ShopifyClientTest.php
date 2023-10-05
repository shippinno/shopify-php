<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ShopifyClientTest extends TestCase
{
    /**
     * @expectException     InvalidArgumentException
     */
    public function testSetShopnameTooManyPeriods()
    {
        $this->expectException(InvalidArgumentException::class);
        new ShopifyClient("abc", "too.many.periods.myshopify.com");
    }

    /**
     * @expectException     InvalidArgumentException
     */
    public function testSetShopnameWithInvalidCharacters()
    {
        $this->expectException(InvalidArgumentException::class);
        new ShopifyClient("abc", "to*&^%$'abc.myshopify.com");
    }

    /**
     * @expectException     InvalidArgumentException
     */
    public function testSetShopnameTooShort()
    {
        $this->expectException(InvalidArgumentException::class);
        new ShopifyClient("abc", "abc.myshopify.com");
    }

    /**
     * @expectException     InvalidArgumentException
     */
    public function testSetShopnameTooLong()
    {
        $longString = "1234567890abcdefghijklmnopqrstuvwxyz";
        $shopName = $longString . $longString . $longString;
        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals(true, strlen($shopName) > 100);
        new ShopifyClient($shopName, ".myshopify.com");
    }

    /**
     * @expectException     InvalidArgumentException
     */
    public function testSetShopnameNotMyshopify()
    {
        $this->expectException(InvalidArgumentException::class);
        new ShopifyClient("abfc3re34wr43f5g2dgf432", "josh.joshstore.com");
    }

    /**
     * @expectException     InvalidArgumentException
     */
    public function testSetInvalidAccessToken()
    {
        $this->expectException(InvalidArgumentException::class);
        new ShopifyClient("abc", "040350450399894.myshopify.com");
    }

    /**
     * @expectException     InvalidArgumentException
     */
    public function testRequestOnlyAcceptsValidMethods()
    {
        $this->expectException(InvalidArgumentException::class);
        $client = new ShopifyClient("abc", "040350450399894.myshopify.com");
        $client->call("OPTIONS", "https://040350450399894.myshopify.com/admin/products.json", null, null);
    }

    public function testCall()
    {
        $client = new ShopifyClient("abnini3ruin4ruinc", "040350450399894.myshopify.com");
        $client->setHttpClient(new MockRequest());
        $response = $client->call("PUT", "messages/3094304723/templates", ['test' => 'call'], ['since_id' => 4]);
        $this->assertEquals("PUT", $response[0]);
        $this->assertEquals("https://040350450399894.myshopify.com/admin/messages/3094304723/templates.json", $response[1]);
        $this->assertEquals(['Content-Type: application/json', 'X-Shopify-Access-Token: abnini3ruin4ruinc'], $response[2]);
        $this->assertEquals(['test' => 'call'], $response[3]);
        $this->assertEquals(['since_id' => 4], $response[4]);
    }

    public function testSetShopNameAndAccessToken()
    {
        $client = new ShopifyClient("abnini3ruin4ruinc", "040350450399894.myshopify.com");
        $client->setHttpClient(new MockRequest());
        $client->setShopName('4234322.myshopify.com');
        $response = $client->call("PUT", "messages/3094304723/templates", ['test' => 'call'], ['since_id' => 4]);
        $this->assertEquals("https://4234322.myshopify.com/admin/messages/3094304723/templates.json", $response[1]);
        $this->assertEquals(['Content-Type: application/json', 'X-Shopify-Access-Token: abnini3ruin4ruinc'], $response[2]);
        $client->setAccessToken('xyzrf43f4ff434t43');
        $response = $client->call("PUT", "messages/3094304723/templates", ['test' => 'call'], ['since_id' => 4]);
        $this->assertEquals("https://4234322.myshopify.com/admin/messages/3094304723/templates.json", $response[1]);
        $this->assertEquals(['Content-Type: application/json', 'X-Shopify-Access-Token: xyzrf43f4ff434t43'], $response[2]);
    }
}
