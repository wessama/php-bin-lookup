<?php

namespace WessamA\BinLookup\Service\API;

use Exception;
use WessamA\BinLookup\Model\Bank;

abstract class BaseApi extends HttpService
{
    protected string $type;

    protected string $baseUrl;

    public function __construct()
    {
    }

    /**
     * Determined fully qualified endpoint.
     */
    abstract public function endpoint(): string;

    /**
     * Get the logo URL.
     *
     * @return string
     */
    abstract public function getLogoUrl(Bank $bank, string $source): ?string;

    /**
     * Get API type.
     */
    abstract protected function getType(): string;

    /**
     * Get the favicon image data of a website.
     *
     * @param  string|null  $websiteUrl The URL of the website.
     * @return bool|string The favicon image data or null if not found.
     *
     * @throws Exception
     */
    public function getImageData(?string $websiteUrl): string|bool
    {
        if (is_null($websiteUrl)) {
            return false;
        }

        return $this->sendRequest(url: $websiteUrl, returnRaw: true);
    }
}
