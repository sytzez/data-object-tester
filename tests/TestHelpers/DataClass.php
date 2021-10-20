<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

final class DataClass
{
    /**
     * @param string $string
     * @param int $int
     * @param array<mixed> $array
     */
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

    /**
     * @return array<mixed>
     */
    public function getArray(): array
    {
        return $this->array;
    }
}
