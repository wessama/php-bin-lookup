<?php

namespace WessamA\BinLookup\Model;

/**
 * Class Bank
 * Represents a bank entity.
 */
class Bank extends BaseModel
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
        'title',
        'countryCode',
        'country',
        'city',
        'bank',
        'branch',
        'address',
        'code',
        'swiftCode',
        'logo',
    ];

    private string $title;

    private string $countryCode;

    private string $country;

    private ?string $city;

    private string $bank;

    private ?string $branch;

    private ?string $address;

    private ?string $code;

    private ?string $swiftCode;

    private ?string $logo;

    public function __construct(
        string $title,
        string $countryCode,
        string $country,
        ?string $city,
        string $bank,
        ?string $branch,
        ?string $address,
        ?string $code,
        ?string $swiftCode,
        ?string $logo = null,
    ) {
        $this->setTitle($title);
        $this->setCountryCode($countryCode);
        $this->setCountry($country);
        $this->setCity($city);
        $this->setBank($bank);
        $this->setBranch($branch);
        $this->setAddress($address);
        $this->setCode($code);
        $this->setSwiftCode($swiftCode);
        $this->setLogo($logo);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Bank
    {
        $this->title = $title;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): Bank
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): Bank
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): Bank
    {
        $this->city = $city;

        return $this;
    }

    public function getBank(): string
    {
        return $this->bank;
    }

    /**
     * @return $this
     */
    public function setBank(string $bank): Bank
    {
        $this->bank = $bank;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(?string $branch): Bank
    {
        $this->branch = $branch;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): Bank
    {
        $this->address = $address;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): Bank
    {
        $this->code = $code;

        return $this;
    }

    public function getSwiftCode(): ?string
    {
        return $this->swiftCode;
    }

    public function setSwiftCode(?string $swiftCode): Bank
    {
        $this->swiftCode = $swiftCode;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): Bank
    {
        $this->logo = $logo;

        return $this;
    }
}
