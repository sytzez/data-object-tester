<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Strategies\CaseGenerators;

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
     * @return iterable<ObjectCase>
     */
    public function generate(ClassDescription $classDescription): iterable
    {
        $this->numOfCases = 0;
        $this->classDescription = $classDescription;

        return $this->generateObjectCases(
            [],
            $classDescription->getPropertyDescriptions(),
        );
    }

    /**
     * @param array<PropertyCase> $existingPropertyCases
     * @param array<PropertyDescription> $propertyDescriptions
     * @return array
     */
    public function generateObjectCases(array $existingPropertyCases, array $propertyDescriptions): array
    {
        if ($this->numOfCases >= $this->maxCases) {
            return [];
        }

        if (count($propertyDescriptions) === 0) {
            $this->numOfCases++;

            return [
                new ObjectCase($this->classDescription, $existingPropertyCases),
            ];
        }

        $currentProperty = $propertyDescriptions[0];
        $remainingProperties = array_slice($propertyDescriptions, 1);

        $objectCases = [];

        foreach ($currentProperty->getValidCases() as $case) {
            array_push(
                $objectCases,
                ...$this->generateObjectCases(
                    [...$existingPropertyCases, $case],
                    $remainingProperties
                )
            );
        }

        return $objectCases;
    }
}
