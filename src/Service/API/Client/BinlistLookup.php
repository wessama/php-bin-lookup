<?php

namespace WessamA\BinLookup\Service\API\Client;

use WessamA\BinLookup\Service\API\ApiEnum;
use WessamA\BinLookup\Service\API\HttpService;

class BinlistLookup extends HttpService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://lookup.binlist.net/';
    }

    public function endpoint(): string
    {
        return $this->baseUrl;
    }

    protected function getType(): string
    {
        return ApiEnum::BIN_LOOKUP_API;
    }
}
