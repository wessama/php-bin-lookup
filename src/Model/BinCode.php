<?php

namespace WessamA\BinLookup\Model;

/**
 * Class BinCode
 * Represents a BIN code entity.
 */
class BinCode extends BaseModel
{
    /**
     * @var array|string[]
     */
    protected static array $guarded = [
        'id',
    ];

    /**
     * @var array|string[]
     */
    protected static array $fillable = [
        'binCode',
        'bankId',
    ];

    private string $binCode;

    private int $bankId;

    /**
     * @param  int  $binId
     */
    public function __construct(string $binCode, int $bankId)
    {
        $this->setBinCode($binCode);
        $this->setBankId($bankId);
    }

    public function getBinCode(): string
    {
        return $this->binCode;
    }

    public function setBinCode(string $binCode): BinCode
    {
        $this->binCode = $binCode;

        return $this;
    }

    public function getBankId(): int
    {
        return $this->bankId;
    }

    public function setBankId(int $bankId): BinCode
    {
        $this->bankId = $bankId;

        return $this;
    }
}
