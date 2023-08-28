<?php

namespace WessamA\BinLookup\Service;

use WessamA\BinLookup\Service\API\BaseApi;
use WessamA\BinLookup\Service\API\Client\FaviconLookup;
use WessamA\BinLookup\Service\API\Client\GoogleImageSearch;

class Evaluator
{
    /**
     * Determine which API to use to fetch bank info.
     *
     * @throws \Exception
     */
    public function determineApi(?string $source, array $config): BaseApi
    {
        // $source is not null and it's a URL
        if (! empty($source) && (str_contains($source, 'http') || str_contains($source, 'www') || str_contains($source, '.com'))) {
            return new FaviconLookup;
        }

        return new GoogleImageSearch($config);
    }
}
