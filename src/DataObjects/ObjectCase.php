<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Factories\ObjectCaseFactory;

final class ObjectCase
{
    /**
     * @param ClassExpectation $classExpectation
     * @param iterable<PropertyCase> $propertyCases
     */
    public function __construct(
        private ClassExpectation $classExpectation,
        private iterable $propertyCases,
    ) {
        $this->validateCompleteness();
    }

    public function getClassExpectation(): ClassExpectation
    {
        return $this->classExpectation;
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
        foreach($this->classExpectation->getPropertyExpectations() as $propertyExpectation) {
            $propertyCase = $this->findCaseByExpectation($propertyExpectation);

            yield $propertyCase->getInput();
        }
    }

    private function findCaseByExpectation(PropertyExpectation $propertyExpectation): PropertyCase
    {
        foreach ($this->propertyCases as $propertyCase) {
            if ($propertyExpectation->getGetterName() === $propertyCase->getExpectation()->getGetterName()) {
                return $propertyCase;
            }
        }
    }

    private function validateCompleteness(): void
    {
        foreach($this->classExpectation->getPropertyExpectations() as $propertyExpectation) {
            $propertyCase = $this->findCaseByExpectation($propertyExpectation);

            if (! $propertyCase) {
                throw new InvalidArgumentException("Property '$propertyCase->getName()' has no provided case");
            }
        }
    }

    /**
     * @param ClassExpectation $classExpectation
     * @param array<string, mixed> $values
     * @return ObjectCase
     */
    public static function create(ClassExpectation $classExpectation, array $values): ObjectCase
    {
        return ObjectCaseFactory::create($classExpectation, $values);
    }
}
