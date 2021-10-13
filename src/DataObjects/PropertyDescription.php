<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

final class PropertyDescription
{
    public function __construct(
        private string $getterName,
        private iterable $validValues,
    ) {
    }

    public function getGetterName(): string
    {
        return $this->getterName;
    }

    public function getValidValues(): iterable
    {
        return $this->validValues;
    }

    /**
     * @return iterable<PropertyCase>
     */
    public function getValidCases(): iterable
    {
        foreach ($this->getValidValues() as $validValue) {
            yield new PropertyCase($this, $validValue);
        }
    }
}
