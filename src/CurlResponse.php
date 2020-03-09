<?php
/**
 * @copyright Copyright (c) 2017 Shopify Inc.
 * @license MIT
 */

namespace Shopify;

class CurlResponse implements HttpResponseInterface
{
    private $headers;
    private $responseBody;
    private $status;
    private $pageInfo = [];

    public function __construct($curlResponse)
    {
        $data = explode("\r\n\r\n", $curlResponse);
        $this->setHeaders($data);
        $this->extractPageInfo();
        $this->responseBody = $data[1];
    }

    public function httpStatus()
    {
        return $this->status;
    }

    public function parsedResponse()
    {
        $response = json_decode($this->responseBody);
        if (is_object($response) && count(get_object_vars($response)) === 1) {
            foreach ($response as $item) {
                return $item;
            }
        }
        return $response;
    }

    public function body()
    {
        return $this->responseBody;
    }

    public function creditLeft()
    {
        return $this->creditLimit() - $this->creditUsed();
    }

    public function creditLimit()
    {
        return (int)explode("/", $this->bucket())[1];
    }

    private function bucket()
    {
        return $this->headers["X-Shopify-Shop-Api-Call-Limit"];
    }

    public function creditUsed()
    {
        return (int)explode("/", $this->bucket())[0];
    }

    private function setHeaders($data)
    {
        $headers = explode("\r\n", $data[0]);
        $statusCodeHeader = explode(" ", $headers[0]);
        $this->status = (int) $statusCodeHeader[1];
        $this->headers = [];
        foreach ($headers as $header) {
            $pair = explode(": ", $header);
            if (count($pair) === 2) {
                $this->headers[$pair[0]] = $pair[1];
            }
        }
    }

    private function extractPageInfo()
    {
        if (!isset($this->headers['Link'])) {
            return [];
        }
        $links = [
            'next' => null,
            'previous' => null,
        ];
        foreach (array_keys($links) as $type) {
            $matched = preg_match(
                str_replace('{type}', $type, '/<(.*page_info=([a-z0-9\-]+).*)>; rel="?{type}"?/i'),
                $this->headers['Link'],
                $matches
            );
            if ($matched) {
                $links[$type] = $matches[2];
            }
        }
        $this->pageInfo = $links;
    }

    /**
     * Get the next page of data.
     *
     * @return string
     */
    public function next()
    {
        if (!$this->hasNext()) {
            throw new \RuntimeException("There's no next page");
        }

        return $this->pageInfo['next'];
    }

    /**
     * Checks whether has next page.
     *
     * @return bool
     */
    public function hasNext()
    {
        return !empty($this->pageInfo['next']);
    }

    /**
     * Get the previous page of data.
     *
     * @return string
     */
    public function prev()
    {
        if (!$this->hasPrev()) {
            throw new \RuntimeException("There's no previous page");
        }
        return $this->pageInfo['previous'];
    }

    /**
     * Checks whether has previous page.
     *
     * @return bool
     */
    public function hasPrev()
    {
        return !empty($this->pageInfo['previous']);
    }
}
