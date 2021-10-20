<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

final class TransformativeDataClass
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
        return $this->string . $this->string;
    }

    public function getInt(): int
    {
        return $this->int * 2;
    }


    /**
     * @return array<mixed>
     */
    public function getArray(): array
    {
        return array_merge($this->array, $this->array);
    }
}
