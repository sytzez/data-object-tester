<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use Generator;
use InvalidArgumentException;
use Sytzez\DataObjectTester\Factories\ObjectCaseFactory;

final class ObjectCase
{
    /**
     * @param ClassExpectation $classExpectation
     * @param array<PropertyCase> $propertyCases
     */
    public function __construct(
        private ClassExpectation $classExpectation,
        private array $propertyCases,
    ) {
        $this->validateCompleteness();
    }

    public function getClassExpectation(): ClassExpectation
    {
        return $this->classExpectation;
    }

    /**
     * @return array<PropertyCase>
     */
    public function getPropertyCases(): array
    {
        return $this->propertyCases;
    }

    public function getConstructorArguments(): Generator
    {
        foreach($this->classExpectation->getPropertyExpectations() as $propertyExpectation) {
            $propertyCase = $this->findCaseByExpectation($propertyExpectation);

            yield $propertyCase->getExpectation()->getInput();
        }
    }

    private function findCaseByExpectation(PropertyExpectation $propertyExpectation): PropertyCase
    {
        foreach ($this->propertyCases as $propertyCase) {
            if ($propertyExpectation->getGetterName() === $propertyCase->getGetterName()) {
                return $propertyCase;
            }
        }

        throw new InvalidArgumentException("Getter '" . $propertyExpectation->getGetterName() . "' has no provided case");
    }

    private function findExpectationByCase(PropertyCase $propertyCase): PropertyExpectation
    {
        foreach ($this->classExpectation->getPropertyExpectations() as $propertyExpectation) {
            if ($propertyCase->getGetterName() === $propertyExpectation->getGetterName()) {
                return $propertyExpectation;
            }
        }

        throw new InvalidArgumentException(
            "Getter '"
            . $propertyCase->getGetterName()
            . "' does not exist on class expectation"
        );
    }

    private function validateCompleteness(): void
    {
        foreach($this->classExpectation->getPropertyExpectations() as $propertyExpectation) {
            $this->findCaseByExpectation($propertyExpectation);
        }

        foreach($this->propertyCases as $propertyCase) {
            $this->findExpectationByCase($propertyCase);
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
