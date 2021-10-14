<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Factories\ObjectCaseFactory;

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
        $this->validateCompleteness();
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

            yield $propertyCase->getInput();
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

    private function validateCompleteness(): void
    {
        foreach($this->classDescription->getPropertyDescriptions() as $propertyDescription) {
            $propertyCase = $this->findCaseByDescription($propertyDescription);

            if (! $propertyCase) {
                throw new InvalidArgumentException("Property '$propertyCase->getName()' has no provided case");
            }
        }
    }

    /**
     * @param ClassDescription $classDescription
     * @param array<string, mixed> $values
     * @return ObjectCase
     */
    public static function create(ClassDescription $classDescription, array $values): ObjectCase
    {
        return ObjectCaseFactory::create($classDescription, $values);
    }
}
