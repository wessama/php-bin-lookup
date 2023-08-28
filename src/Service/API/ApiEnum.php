<?php

namespace WessamA\BinLookup\Service\API;

enum ApiEnum
{
    const FAVICON_API = 'FAVICON_LOOKUP_API';

    const GOOGLE_IMG_SEARCH_API = 'GOOGLE_IMAGE_SEARCH_API';

    const BIN_LOOKUP_API = 'BIN_LOOKUP_API';

    /**
     * Get all the API constants as an array.
     */
    public static function getAll(): array
    {
        return [
            self::FAVICON_API,
            self::GOOGLE_IMG_SEARCH_API,
            self::BIN_LOOKUP_API,
        ];
    }
}
