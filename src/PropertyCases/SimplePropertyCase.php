<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;

final class SimplePropertyCase extends AbstractSuccessfulPropertyCase
{
    public function __construct(
        private mixed $value,
    ) {
    }

    /**
     * @return Generator<mixed>
     */
    public function getConstructorArguments(): Generator
    {
        yield $this->value;
    }

    protected function getExpectedOutput(): mixed
    {
        return $this->value;
    }
}
