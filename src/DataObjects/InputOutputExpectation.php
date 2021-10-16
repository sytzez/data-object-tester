<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

final class InputOutputExpectation
{
    public function __construct(
        private $input,
        private $expectedOutput,
    ) {
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getExpectedOutput()
    {
        return $this->expectedOutput;
    }
}
