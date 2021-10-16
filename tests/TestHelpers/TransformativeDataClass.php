<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

final class TransformativeDataClass
{
    public function __construct(
        private string $string,
        private int $int,
        private array $array,
    ) {
    }

    public function getString(): string
    {
        return $this->string . $this->string;
    }

    public function getInt(): int
    {
        return $this->int * 2;
    }

    public function getArray(): array
    {
        return array_merge($this->array, $this->array);
    }
}
