<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use Generator;
use InvalidArgumentException;
use Sytzez\DataObjectTester\Contracts\PropertyCaseContract;
use Sytzez\DataObjectTester\Factories\ObjectCaseFactory;
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;

final class ObjectCase
{
    /**
     * @param ClassExpectation $classExpectation
     * @param array<PropertyCaseContract> $propertyCases
     */
    public function __construct(
        private ClassExpectation $classExpectation,
        private array $propertyCases,
    ) {
        $this->validateCompleteness();
        $this->validateDefaultProperties();
    }

    public function getClassExpectation(): ClassExpectation
    {
        return $this->classExpectation;
    }

    /**
     * @return array<PropertyCaseContract>
     */
    public function getPropertyCases(): array
    {
        return $this->propertyCases;
    }

    /**
     * @return Generator<string>
     */
    public function getConstructorExceptions(): Generator
    {
        foreach ($this->propertyCases as $propertyCase) {
            yield from $propertyCase->getConstructorExceptions();
        }
    }

    /**
     * @return Generator<mixed>
     */
    public function getConstructorArguments(): Generator
    {
        foreach ($this->propertyCases as $propertyCase) {
            yield from $propertyCase->getConstructorArguments();
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

    private function validateCompleteness(): void
    {
        if (count($this->propertyCases) !== count($this->classExpectation->getPropertyExpectations())) {
            throw new InvalidArgumentException(
                'Number of property cases on object case (' . count($this->propertyCases) . ')'
                . ' does not equal number of properties on class expectation ('
                . count($this->classExpectation->getPropertyExpectations()) . ')'
            );
        }
    }

    protected function validateDefaultProperties(): void
    {
        $hasSeenDefault = false;

        foreach($this->propertyCases as $propertyCase) {
            if ($propertyCase instanceof DefaultPropertyCase) {
                $hasSeenDefault = true;
            } else if ($hasSeenDefault) {
                throw new InvalidArgumentException('All property cases after a default property case must also be default');
            }
        }
    }
}
