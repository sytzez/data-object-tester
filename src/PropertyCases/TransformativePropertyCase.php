<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;

final class TransformativePropertyCase extends AbstractSuccessfulPropertyCase
{
    public function __construct(
        private $input,
        $expectedOutput,
    ) {
        $this->expectedOutput = $expectedOutput;
    }

    public function getConstructorArguments(): Generator
    {
        yield $this->input;
    }
}
