<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use Sytzez\DataObjectTester\Factories\PropertyDescriptionFactory;

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

    /**
     * @param string $getterName
     * @param array<mixed> $values
     * @return PropertyDescription
     */
    public static function create(string $getterName, array $values): PropertyDescription
    {
        return PropertyDescriptionFactory::create($getterName, $values);
    }
}
