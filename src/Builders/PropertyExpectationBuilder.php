<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Builders;

use Sytzez\DataObjectTester\DataObjects\InputOutputExpectation;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;

final class PropertyExpectationBuilder
{
    private array $inputOutputPairs = [];

    public function __construct(
        private string $getterName,
    ) {
    }

    public function addInputOutputPair(InputOutputExpectation $inputOutputPair): self
    {
        $this->inputOutputPairs[] = $inputOutputPair;

        return $this;
    }

    public function getResult(): PropertyExpectation
    {
        return new PropertyExpectation($this->getterName, $this->inputOutputPairs);
    }
}
