<?php

namespace WessamA\BinLookup\Service\API\Client;

use WessamA\BinLookup\Model\Bank;
use WessamA\BinLookup\Service\API\ApiEnum;
use WessamA\BinLookup\Service\API\BaseApi;

class FaviconLookup extends BaseApi
{
    public function __construct()
    {
        parent::__construct();

        $this->baseUrl = 'https://api.faviconkit.com/';
    }

    /**
     * {@inheritDoc}
     */
    public function endpoint(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get the favicon URL of a website.
     *
     * @param  string  $source The URL of the website.
     * @return string|null The favicon URL or null if not found.
     *
     * @throws \Exception
     */
    public function getLogoUrl(Bank $bank, string $source): ?string
    {
        $sourceUrl = null;

        // Pre-pend the protocol if not provided
        if (! preg_match('~^https?://~i', $source)) {
            $source = 'https://' . $source;
        }

        // $imageData = $this->sendRequest(url: $this->endpoint() . parse_url($source, PHP_URL_HOST) . '/144', returnRaw: true);

        // Fallback to favicon service
        $faviconUrl = $this->endpoint() . parse_url($source, PHP_URL_HOST) . '/144';
        if ($this->urlExists($faviconUrl)) {
            $sourceUrl = $faviconUrl;
        }

        return $this->getImageData($sourceUrl) ?? null;
    }

    /**
     * Check if a URL exists by checking its headers.
     *
     * @param  string  $url The URL to check.
     * @return bool True if the URL exists, false otherwise.
     */
    private function urlExists(string $url): bool
    {
        $headers = get_headers($url, 1);

        if (! $headers) {
            return false;
        }

        return str_contains($headers[0], '200 OK');
    }

    /**
     * {@inheritDoc}
     */
    protected function getType(): string
    {
        return ApiEnum::FAVICON_API;
    }
}
