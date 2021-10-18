<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

final class DataClassWithDefaults
{
    public const DEFAULT_STRING = 'Default string';
    public const DEFAULT_INT = 42;

    public function __construct(
        private string $string = self::DEFAULT_STRING,
        private int $int = self::DEFAULT_INT,
    ) {
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function getInt(): int
    {
        return $this->int;
    }
}
