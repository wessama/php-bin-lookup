<?php

namespace WessamA\BinLookup\Service\API\Client;

use Exception;
use WessamA\BinLookup\Model\Bank;
use WessamA\BinLookup\Service\API\ApiEnum;
use WessamA\BinLookup\Service\API\BaseApi;

class GoogleImageSearch extends BaseApi
{
    private const MAX_RESULTS = 1;

    private const SEARCH_TYPE = 'image';

    private const IMAGE_SIZE_LG = 'large';

    private string $apiKey;

    private string $searchEngineId;

    /**
     * @throws Exception
     */
    public function __construct(array $config)
    {
        parent::__construct();

        if (! isset($config['google_custom_search_api_key'])) {
            throw new Exception('GOOGLE_CUSTOM_SEARCH_API_KEY is not set.');
        }

        if (! isset($config['google_custom_search_engine_id'])) {
            throw new Exception('GOOGLE_CUSTOM_SEARCH_ENGINE_ID is not set.');
        }

        $this->baseUrl = 'https://www.googleapis.com/customsearch/v1';

        $this->apiKey = $config['google_custom_search_api_key'];

        $this->searchEngineId = $config['google_custom_search_engine_id'];
    }

    /**
     * {@inheritDoc}
     */
    public function endpoint(): string
    {
        return $this->baseUrl;
    }

    /**
     * @throws Exception
     */
    public function getLogoUrl(Bank $bank, string $source): ?string
    {
        // Google search query
        $bankName = $bank->getTitle() ?? $bank->getBank();
        $country = $bank->getCountry() ?? $bank->getCountryCode();

        $query = "{$bankName} {$country} bank logo";

        $response = $this->sendRequest($this->endpoint(), [
            'q' => $query,
            'num' => self::MAX_RESULTS,
            'start' => 1,
            'imgSize' => self::IMAGE_SIZE_LG,
            'searchType' => self::SEARCH_TYPE,
            'key' => $this->apiKey,
            'cx' => $this->searchEngineId,
        ]);

        if (isset($response['items'][0]['link'])) {
            return $this->getImageData($response['items'][0]['link']);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    protected function getType(): string
    {
        return ApiEnum::GOOGLE_IMG_SEARCH_API;
    }
}
