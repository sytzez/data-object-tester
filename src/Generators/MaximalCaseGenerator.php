<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Generators;

use DomainException;
use Generator;
use Sytzez\DataObjectTester\Builders\ObjectCaseBuilder;
use Sytzez\DataObjectTester\Contracts\Generators\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\Contracts\PropertyCaseContract;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;
use Sytzez\DataObjectTester\Factories\ObjectCaseWithDefaultValuesFactory;
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;

final class MaximalCaseGenerator implements CaseGeneratorStrategy
{
    private ClassExpectation $classExpectation;
    private int $numOfCases;

    public function __construct(
        private int $maxCases = 100,
    ) {
    }

    /**
     * @param ClassExpectation $classExpectation
     * @return Generator<ObjectCase>
     */
    public function generate(ClassExpectation $classExpectation): Generator
    {
        $this->numOfCases = 0;
        $this->classExpectation = $classExpectation;

        yield from $this->generatePossibilities(
            [],
            $classExpectation->getPropertyExpectations(),
        );

        return $this->numOfCases;
    }

    /**
     * @param array<PropertyCaseContract> $existingPropertyCases
     * @param array<PropertyExpectation> $propertyExpectations
     * @return Generator<ObjectCase>
     */
    private function generatePossibilities(array $existingPropertyCases, array $propertyExpectations): Generator
    {
        if ($this->numOfCases >= $this->maxCases) {
            return;
        }

        if (count($propertyExpectations) === 0) {
            $this->numOfCases++;

            yield new ObjectCase($this->classExpectation, $existingPropertyCases);

            return;
        }

        $currentProperty = $propertyExpectations[0];
        $remainingProperties = array_slice($propertyExpectations, 1);

        foreach ($currentProperty->getCases() as $propertyCase) {
            if ($propertyCase instanceof DefaultPropertyCase) {
                $this->numOfCases++;

                yield ObjectCaseWithDefaultValuesFactory::create(
                    $this->classExpectation,
                    $existingPropertyCases,
                );

                continue;
            }

            yield from $this->generatePossibilities(
                [...$existingPropertyCases, $propertyCase],
                $remainingProperties
            );
        }
    }
}
