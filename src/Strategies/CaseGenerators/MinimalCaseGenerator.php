<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Strategies\CaseGenerators;

use Sytzez\DataObjectTester\Builders\ObjectCaseBuilder;
use Sytzez\DataObjectTester\Contracts\Strategies\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyDescription;

final class MinimalCaseGenerator implements CaseGeneratorStrategy
{
    private ClassDescription $classDescription;

    /**
     * @param ClassDescription $classDescription
     * @return iterable<ObjectCase>
     */
    public function generate(ClassDescription $classDescription): iterable
    {
        $this->classDescription = $classDescription;

        $maxCases = $this->getMaxCases();

        for ($i = 0; $i < $maxCases; $i++) {
            yield $this->buildCase($i);
        }
    }

    private function buildCase(int $offset): ObjectCase
    {
        $builder = new ObjectCaseBuilder($this->classDescription);

        foreach ($this->classDescription->getPropertyDescriptions() as $propertyDescription) {
            $builder->addPropertyCase(
                $propertyDescription->getCases()[$offset % count($propertyDescription->getCases())]
            );
        }

        return $builder->getResult();
    }

    private function getMaxCases(): int
    {
        if (count($this->classDescription->getPropertyDescriptions()) === 0) {
            return 0;
        }

        return max(
            ...array_map(
                static fn (PropertyDescription $propertyDescription) => count($propertyDescription->getInputOutputPairs()),
                $this->classDescription->getPropertyDescriptions()
            )
        );
    }
}
