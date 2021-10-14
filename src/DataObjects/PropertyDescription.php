<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

final class PropertyDescription
{
    /**
     * @param string $getterName
     * @param iterable<InputOutputPair> $inputOutputPairs
     */
    public function __construct(
        private string $getterName,
        private iterable $inputOutputPairs,
    ) { // TODO: canBeOmitted, defaultValue
    }

    public function getGetterName(): string
    {
        return $this->getterName;
    }

    public function getInputOutputPairs(): iterable
    {
        return $this->inputOutputPairs;
    }

    /**
     * @return iterable<PropertyCase>
     */
    public function getCases(): iterable
    {
        foreach ($this->getInputOutputPairs() as $inputOutputPair) {
            yield new PropertyCase($this, $inputOutputPair);
        }
    }
}
