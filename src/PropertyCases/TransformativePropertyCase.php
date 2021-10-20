<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;

final class TransformativePropertyCase extends AbstractSuccessfulPropertyCase
{
    public function __construct(
        private mixed $input,
        mixed $expectedOutput,
    ) {
        $this->expectedOutput = $expectedOutput;
    }

    /**
     * @return Generator<mixed>
     */
    public function getConstructorArguments(): Generator
    {
        yield $this->input;
    }
}
