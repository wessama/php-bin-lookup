<?php

namespace WessamA\BinLookup;

use Dotenv\Dotenv;

class ConfigLoader
{
    protected array $config = [];

    public function __construct()
    {
        if (function_exists('app') && app() instanceof \Illuminate\Contracts\Foundation\Application) {
            // Laravel environment
            $this->config = config('binlookup');
        } else {
            // Non-Laravel environment
            $config = [];
            self::loadEnv();

            $config['db_path'] = $_ENV['BIN_LOOKUP_DB_PATH'];
            $config['google_custom_search_api_key'] = $_ENV['GOOGLE_CUSTOM_SEARCH_API_KEY'];
            $config['google_custom_search_engine_id'] = $_ENV['GOOGLE_CUSTOM_SEARCH_ENGINE_ID'];

            $this->config = $config;
        }
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public static function loadEnv(string $path = __DIR__): void
    {
        $filePath = $path . '/../.env';

        if (file_exists($filePath)) {
            $dotenv = Dotenv::createImmutable($path . '/../');
            $dotenv->load();
        }
    }
}
