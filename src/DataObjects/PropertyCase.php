<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

final class PropertyCase
{
    public function __construct(
        private string $getterName,
        private InputOutputExpectation $inputOutputExpectation,
    ) {
    }

    public function getGetterName(): string
    {
        return $this->getterName;
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
