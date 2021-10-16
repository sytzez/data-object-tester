<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use Sytzez\DataObjectTester\Factories\PropertyExpectationFactory;

final class PropertyExpectation
{
    /**
     * @param string $getterName
     * @param array<InputOutputExpectation> $inputOutputPairs
     */
    public function __construct(
        private string $getterName,
        private array $inputOutputPairs,
    ) { // TODO: canBeOmitted, defaultValue
    }

    public function getGetterName(): string
    {
        return $this->getterName;
    }

    public function getInputOutputPairs(): array
    {
        return $this->inputOutputPairs;
    }

    /**
     * @return array<PropertyCase>
     */
    public function getCases(): array
    {
        return array_map(
            fn (InputOutputExpectation $pair): PropertyCase => new PropertyCase($this, $pair),
            $this->getInputOutputPairs()
        );
    }

    /**
     * @param string $getterName
     * @param array<mixed> $values
     * @return PropertyExpectation
     */
    public static function create(string $getterName, array $values): PropertyExpectation
    {
        return PropertyExpectationFactory::create($getterName, $values);
    }
}
