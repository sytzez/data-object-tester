<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Strategies\CaseGenerators;

use Sytzez\DataObjectTester\Builders\ObjectCaseBuilder;
use Sytzez\DataObjectTester\Contracts\Strategies\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\InputOutputPair;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyCase;
use Sytzez\DataObjectTester\DataObjects\PropertyDescription;

final class MinimalCaseGenerator implements CaseGeneratorStrategy
{
    /**
     * @param ClassDescription $classDescription
     * @return iterable<ObjectCase>
     */
    public function generate(ClassDescription $classDescription): iterable
    {
        foreach ($classDescription->getPropertyDescriptions() as $propertyDescription) {
            yield from $this->generateCasesForProperty($classDescription, $propertyDescription);
        }
    }

    /**
     * @param ClassDescription $classDescription
     * @param PropertyDescription $propertyDescription
     * @return iterable<ObjectCase>
     */
    private function generateCasesForProperty(
        ClassDescription $classDescription, PropertyDescription $propertyDescription
    ): iterable {

        foreach ($propertyDescription->getInputOutputPairs() as $inputOutputPair) {
            yield $this->createObjectCaseWithIOPair(
                $classDescription,
                $propertyDescription,
                $inputOutputPair,
            );
        }
    }

    private function createObjectCaseWithIOPair(
        ClassDescription $classDescription,
        PropertyDescription $selectedPropertyDescription,
        InputOutputPair $inputOutputPair,
    ): ObjectCase {

        $builder = new ObjectCaseBuilder();

        foreach ($classDescription->getPropertyDescriptions() as $propertyDescription) {
            if ($propertyDescription === $selectedPropertyDescription) {
                $builder->addPropertyCase(
                    new PropertyCase(
                        $propertyDescription,
                        $inputOutputPair,
                    )
                );
            } else {
                $builder->addPropertyCase(
                    new PropertyCase(
                        $propertyDescription,
                        $propertyDescription->getInputOutputPairs()[0],
                    )
                );
            }
        }

        return $builder->getResult();
    }
}
