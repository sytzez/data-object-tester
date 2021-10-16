<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Strategies\CaseGenerators;

use Generator;
use Sytzez\DataObjectTester\Contracts\Strategies\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyCase;
use Sytzez\DataObjectTester\DataObjects\PropertyDescription;

final class MaximalCaseGenerator implements CaseGeneratorStrategy
{
    private ClassDescription $classDescription;
    private int $numOfCases;

    public function __construct(
        private int $maxCases = 100,
    ) {
    }

    /**
     * @param ClassDescription $classDescription
     * @return Generator<ObjectCase>
     */
    public function generate(ClassDescription $classDescription): Generator
    {
        $this->numOfCases = 0;
        $this->classDescription = $classDescription;

        yield from $this->generatePossibilities(
            [],
            $classDescription->getPropertyDescriptions(),
        );

        return $this->numOfCases;
    }

    /**
     * @param array<PropertyCase> $existingPropertyCases
     * @param array<PropertyDescription> $propertyDescriptions
     * @return iterable<ObjectCase>
     */
    private function generatePossibilities(array $existingPropertyCases, array $propertyDescriptions): iterable
    {
        if ($this->numOfCases >= $this->maxCases) {
            return;
        }

        if (count($propertyDescriptions) === 0) {
            $this->numOfCases++;

            yield new ObjectCase($this->classDescription, $existingPropertyCases);

            return;
        }

        $currentProperty = $propertyDescriptions[0];
        $remainingProperties = array_slice($propertyDescriptions, 1);

        foreach ($currentProperty->getCases() as $propertyCase) {
            yield from $this->generatePossibilities(
                [...$existingPropertyCases, $propertyCase],
                $remainingProperties
            );
        }
    }
}
