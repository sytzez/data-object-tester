<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Generators;

use Generator;
use Sytzez\DataObjectTester\Builders\ObjectCaseBuilder;
use Sytzez\DataObjectTester\Contracts\Generators\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;

final class MinimalCaseGenerator implements CaseGeneratorStrategy
{
    private ClassExpectation $classExpectation;

    /**
     * @param ClassExpectation $classExpectation
     * @return Generator<ObjectCase>
     */
    public function generate(ClassExpectation $classExpectation): Generator
    {
        $this->classExpectation = $classExpectation;

        $maxCases = $this->getMaxCases();

        for ($i = 0; $i < $maxCases; $i++) {
            yield $this->buildCase($i);
        }

        return $maxCases;
    }

    private function buildCase(int $offset): ObjectCase
    {
        $builder = new ObjectCaseBuilder($this->classExpectation);

        foreach ($this->classExpectation->getPropertyExpectations() as $propertyExpectation) {
            $builder->addPropertyCase(
                $propertyExpectation->getCases()[$offset % count($propertyExpectation->getCases())]
            );
        }

        return $builder->getResult();
    }

    private function getMaxCases(): int
    {
        if (count($this->classExpectation->getPropertyExpectations()) === 0) {
            return 0;
        }

        return max(
            ...array_map(
                static fn (PropertyExpectation $propertyExpectation) => count($propertyExpectation->getInputOutputPairs()),
                $this->classExpectation->getPropertyExpectations()
            )
        );
    }
}
