<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;

final class TransformativePropertyCase extends AbstractSuccessfulPropertyCase
{
    public function __construct(
        private mixed $input,
        private mixed $expectedOutput,
    ) {
    }

    /**
     * @return Generator<mixed>
     */
    public function getConstructorArguments(): Generator
    {
        yield $this->input;
    }

    protected function getExpectedOutput(): mixed
    {
        return $this->expectedOutput;
    }
}
