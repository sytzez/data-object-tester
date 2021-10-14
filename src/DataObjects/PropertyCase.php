<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

final class PropertyCase
{
    public function __construct(
        private PropertyDescription $description,
        private InputOutputPair $inputOutputPair,
    ) {
    }

    public function getDescription(): PropertyDescription
    {
        return $this->description;
    }

    public function getInput()
    {
        return $this->inputOutputPair->getInput();
    }

    public function getExpectedOutput()
    {
        return $this->inputOutputPair->getExpectedOutput();
    }
}
