<?php

namespace WessamA\BinLookup\Model;

use ReflectionClass;

abstract class BaseModel
{
    protected static array $guarded = [];

    protected static array $fillable = [];

    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Convert the model's properties to an associative array.
     */
    public function toArray(): array
    {
        $result = [];
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            if (! in_array($property->getName(), $this::$guarded)
                && in_array($property->getName(), $this::$fillable)) {
                $property->setAccessible(true);
                $snakeCaseName = $this->camelToSnake($property->getName());
                $result[$snakeCaseName] = $property->getValue($this);
            }
        }

        return $result;
    }

    /**
     * Convert a camelCase string to snake_case.
     */
    private function camelToSnake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
