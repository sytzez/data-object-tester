<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use Generator;

class InputOutputExpectation
{
    public function __construct(
        private $input,
        private $expectedOutput,
    ) {
    }

    public function getConstructorArguments(): Generator
    {
        yield $this->input;
    }

    public function getExpectedOutput()
    {
        return $this->expectedOutput;
    }
}
