<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

final class PropertyCase
{
    public function __construct(
        private PropertyExpectation $expectation,
        private InputOutputExpectation $inputOutputExpectation,
    ) {
    }

    public function getExpectation(): PropertyExpectation
    {
        return $this->expectation;
    }

    public function getInput()
    {
        return $this->inputOutputExpectation->getInput();
    }

    public function getExpectedOutput()
    {
        return $this->inputOutputExpectation->getExpectedOutput();
    }
}
