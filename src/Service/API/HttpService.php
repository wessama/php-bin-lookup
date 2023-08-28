<?php

namespace WessamA\BinLookup\Service\API;

use Exception;

abstract class HttpService
{
    /**
     * Send an HTTP request using curl.
     *
     * @param  string  $url The endpoint URL.
     * @param  array  $params The parameters to send with the request.
     * @param  string  $method The HTTP method (GET, POST, etc.).
     * @param  array  $headers Optional headers to send with the request.
     * @param  bool  $returnRaw If true, returns raw response without decoding.
     * @return mixed The response data.
     *
     * @throws Exception
     */
    public function sendRequest(string $url, array $params = [], string $method = 'GET', array $headers = [], bool $returnRaw = false): mixed
    {
        $ch = curl_init();

        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                break;
            case 'GET':
                $url .= '?' . http_build_query($params);
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        if (! empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            // Handle non-200 responses
            $this->handleError("HTTP response code: $httpCode during request to $url from {$this->getType()} API.");
        }

        if (curl_errno($ch)) {
            $this->handleError(curl_error($ch));
        }

        curl_close($ch);

        if ($returnRaw) {
            return $response;
        }

        return json_decode($response, true);
    }

    /**
     * Handle thrown errors.
     *
     * @param  string  $error The error message.
     *
     * @throws Exception
     */
    protected function handleError(string $error): void
    {
        // Handle any errors that might occur during the request
        throw new Exception('API Request Error: ' . $error);
    }
}
