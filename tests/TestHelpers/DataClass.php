<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

final class DataClass
{
    public function __construct(
        private string $string,
        private int $int,
        private array $array,
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

    public function getArray(): array
    {
        return $this->array;
    }
}
