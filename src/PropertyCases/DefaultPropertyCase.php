<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;

final class DefaultPropertyCase extends AbstractSuccessfulPropertyCase
{
    public function __construct(
        private mixed $expectedOutput,
    ) {
    }

    /**
     * @return Generator<mixed>
     */
    public function getConstructorArguments(): Generator
    {
        yield from [];
    }

    protected function getExpectedOutput(): mixed
    {
        return $this->expectedOutput;
    }
}
