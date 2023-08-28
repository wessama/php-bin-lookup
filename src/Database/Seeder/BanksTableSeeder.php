<?php

namespace WessamA\BinLookup\Database\Seeder;

use PDOException;
use RuntimeException;
use WessamA\BinLookup\Database\SQLiteManager;
use WessamA\BinLookup\Model\Bank;

class BanksTableSeeder
{
    private SQLiteManager $dbManager;

    private string $apiBaseUrl = 'https://strapi.transfez.com/swift-codes';

    public function __construct(SQLiteManager $dbManager)
    {
        $this->dbManager = $dbManager;
    }

    public function seedFromJsonFile(string $directoryPath = __DIR__ . '/../data'): void
    {
        // Check if the directory exists
        if (! is_dir($directoryPath)) {
            throw new RuntimeException("The directory {$directoryPath} does not exist.");
        }

        // Get all JSON files in the directory
        $files = glob($directoryPath . '/*.json');
        if (! $files) {
            throw new RuntimeException("No JSON files found in the directory {$directoryPath}.");
        }

        foreach ($files as $filePath) {
            $data = json_decode(file_get_contents($filePath), true);
            if (isset($data['list']) && is_array($data['list'])) {
                foreach ($data['list'] as $bankData) {
                    $apiData = $this->fetchBankDataFromApi($bankData['swift_code']);
                    $mergedData = array_merge($apiData, $bankData);
                    $this->saveBankToDatabase($mergedData);
                    // Respect API rate limits (e.g., sleep for 1 second between requests)
                    sleep(1);
                }
            }
        }
    }

    private function fetchBankDataFromApi(string $swiftCode): array
    {
        $url = $this->apiBaseUrl . '?swift_code_eq=' . $swiftCode;
        $response = file_get_contents($url);

        return json_decode($response, true)[0] ?? [];
    }

    private function saveBankToDatabase(array $bankData): void
    {
        try {
            $this->dbManager->beginTransaction();

            $bank = new Bank(
                title: $bankData['title'],
                countryCode: $bankData['country_code'],
                country: $bankData['country'],
                city: $bankData['city'],
                bank: $bankData['bank'],
                branch: $bankData['branch'],
                address: $bankData['address'],
                code: $bankData['code'],
                swiftCode: $bankData['swift_code'],
            );

            $this->dbManager->insert('banks', $bank->toArray());

            $this->dbManager->commit();
        } catch (PDOException $e) {
            $this->dbManager->rollBack();
            throw $e;
        }
    }
}
