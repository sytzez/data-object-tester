<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use InvalidArgumentException;

final class ObjectCase
{
    /**
     * @param ClassDescription $classDescription
     * @param iterable<PropertyCase> $propertyCases
     */
    public function __construct(
        private ClassDescription $classDescription,
        private iterable $propertyCases,
    ) {
        $this->validate();
    }

    public function getClassDescription(): ClassDescription
    {
        return $this->classDescription;
    }

    /**
     * @return iterable<PropertyCase>
     */
    public function getPropertyCases(): iterable
    {
        return $this->propertyCases;
    }

    public function getConstructorArguments(): iterable
    {
        foreach($this->classDescription->getPropertyDescriptions() as $propertyDescription) {
            $propertyCase = $this->findCaseByDescription($propertyDescription);

            yield $propertyCase->getValue();
        }
    }

    private function findCaseByDescription(PropertyDescription $propertyDescription): PropertyCase
    {
        foreach ($this->propertyCases as $propertyCase) {
            if ($propertyDescription->getGetterName() === $propertyCase->getDescription()->getGetterName()) {
                return $propertyCase;
            }
        }
    }

    private function validate(): void
    {
        foreach($this->classDescription->getPropertyDescriptions() as $propertyDescription) {
            $propertyCase = $this->findCaseByDescription($propertyDescription);

            if (! $propertyCase) {
                throw new InvalidArgumentException("Property '$propertyCase->getName()' has no provided case");
            }
        }
    }
}
