<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Builders;

use Sytzez\DataObjectTester\DataObjects\InputOutputPair;
use Sytzez\DataObjectTester\DataObjects\PropertyDescription;

final class PropertyDescriptionBuilder
{
    private array $inputOutputPairs = [];

    public function __construct(
        private string $getterName,
    ) {
    }

    public function addInputOutputPair(InputOutputPair $inputOutputPair): self
    {
        $this->inputOutputPairs[] = $inputOutputPair;

        return $this;
    }

    public function getResult(): PropertyDescription
    {
        return new PropertyDescription($this->getterName, $this->inputOutputPairs);
    }
}
