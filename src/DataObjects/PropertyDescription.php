<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use Sytzez\DataObjectTester\Factories\PropertyDescriptionFactory;

final class PropertyDescription
{
    /**
     * @param string $getterName
     * @param array<InputOutputPair> $inputOutputPairs
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
            fn (InputOutputPair $pair): PropertyCase => new PropertyCase($this, $pair),
            $this->getInputOutputPairs()
        );
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
