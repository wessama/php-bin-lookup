<?php

namespace WessamA\BinLookup\Service;

use Exception;
use WessamA\BinLookup\Database\SQLiteManager;
use WessamA\BinLookup\Model\Bank;
use WessamA\BinLookup\Model\BinCode;
use WessamA\BinLookup\Service\API\Client\BinlistLookup;

class BankLogoService
{
    private SQLiteManager $dbManager;

    private BinlistLookup $binListLookupApi;

    private Evaluator $evaluator;

    private array $config;

    public function __construct(SQLiteManager $dbManager, array $config)
    {
        $this->dbManager = $dbManager;
        $this->binListLookupApi = new BinlistLookup;
        $this->evaluator = new Evaluator;
        $this->config = $config;
    }

    /**
     * @throws Exception
     */
    public function fetchAndSaveLogo(string $bin): ?Bank
    {
        // Step 1: Check if BIN exists in bin_codes table
        $binCode = $this->findBinCode($bin);
        if ($binCode) {
            return $this->findBankById($binCode->getBankId());
        }

        // Step 2: Fetch bank data and logo
        $bankData = $this->getBankData($bin);
        $bank = $this->mapApiResponseToBankEntity($bankData);

        $bankDetails = $bankData['bank'] ?? [];

        if (!empty($bankDetails)) {
            $this->fetchAndSetBankLogo($bank, $bankData['bank']['url'] ?? $bankData['bank']['name'] ?? null);
        }

        // Step 3: Save bank data to database
        $this->saveBankAndBinCode($bank, $bin);

        return $bank;
    }

    /**
     * @throws Exception
     */
    private function fetchAndSetBankLogo(Bank $bank, string $source = null): void
    {
        $api = $this->evaluator->determineApi($source, $this->config);
        $binaryImage = $api->getLogoUrl($bank, $source);

        if ($binaryImage) {
            $bank->setLogo($binaryImage);
        }
    }

    /**
     * @throws Exception
     */
    private function saveBankAndBinCode(Bank $bank, string $bin): void
    {
        try {
            $this->dbManager->beginTransaction();

            // Check if bank already exists
            $existingBank = $this->findBankByDetails($bank->getTitle(), $bank->getCountryCode());
            if (! $existingBank) {
                $newBank = $this->dbManager->insert('banks', $bank->toArray());
                $bankId = $newBank['id'];
            } else {
                $bankId = $existingBank->getId();
            }

            $binCode = new BinCode(binCode: $bin, bankId: $bankId);
            $this->dbManager->insert('bin_codes', $binCode->toArray());

            $this->dbManager->commit();
        } catch (Exception $e) {
            $this->dbManager->rollBack();
            throw new Exception('Failed to save bank data to database: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function getBankData(string $bin): array
    {
        $baseUrl = $this->binListLookupApi->endpoint();

        // Send a GET request to the API
        return $this->binListLookupApi->sendRequest($baseUrl . $bin);
    }

    private function findBinCode(string $bin): ?BinCode
    {
        $query = 'SELECT * FROM bin_codes WHERE bin_code = :bin';
        $params = [':bin' => $bin];

        $result = $this->dbManager->fetchOne($query, $params);

        if ($result) {
            $binCode = new BinCode($result['bin_code'], $result['id']);
            $binCode->setId($result['id']);

            return $binCode;
        }

        return null;
    }

    private function findBankById(int $bankId): ?Bank
    {
        $result = $this->dbManager->find('banks', $bankId);

        if ($result) {
            return $this->getBankEntity($result);
        }

        return null;
    }

    private function findBankByDetails(string $title, string $countryCode): ?Bank
    {
        $query = 'SELECT * FROM banks WHERE title = :title ANd country_code = :country_code';
        $params = [':title' => $title, ':country_code' => ''];

        $result = $this->dbManager->fetchOne($query, $params);

        if ($result) {
            return $this->getBankEntity($result);
        }

        return null;
    }

    private function mapApiResponseToBankEntity(array $apiResponse): Bank
    {
        $bankData = $apiResponse['bank'] ?? [];
        $countryData = $apiResponse['country'] ?? [];

        $title = $bankData['name'] ?? '';
        $countryCode = $countryData['alpha2'] ?? '';
        $country = $countryData['name'] ?? '';

        $bankData = [
            'title' => $title,
            'country_code' => $countryCode,
            'country' => $country,
            'bank' => $title,
        ];

        // Create a new Bank entity with the mapped data.
        return $this->getBankEntity($bankData);
    }

    private function getBankEntity(array $bankData): Bank
    {
        // Create a new Bank entity with the mapped data.
        $bank = new Bank(
            title: $bankData['title'],
            countryCode: $bankData['country_code'],
            country: $bankData['country'],
            city: $bankData['city'] ?? null,
            bank: $bankData['bank'],
            branch: $bankData['branch'] ?? null,
            address: $bankData['address'] ?? null,
            code: $bankData['code'] ?? null,
            swiftCode: $bankData['swift_code'] ?? null,
            logo: $bankData['logo'] ?? null,
        );

        if (isset($bankData['id'])) {
            $bank->setId($bankData['id']);
        }

        return $bank;
    }
}
