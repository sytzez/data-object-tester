<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Builders\ObjectCaseBuilder;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\InputOutputExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyCase;

final class ObjectCaseFactory
{
    private function __construct()
    {
    }

    /**
     * @param ClassExpectation $classExpectation
     * @param array<string, mixed> $values
     * @return ObjectCase
     */
    public static function create(ClassExpectation $classExpectation, array $values): ObjectCase
    {
        $builder = new ObjectCaseBuilder($classExpectation);

        foreach ($values as $getterName => $value) {
            if (! is_string($getterName)) {
                throw new InvalidArgumentException(
                    sprintf('Getter name must be a string, %s given', gettype($getterName)),
                );
            }

            $inputOutputPair = $value instanceof InputOutputExpectation
                ? $value
                : new InputOutputExpectation($value, $value);

            $builder->addPropertyCase(
                new PropertyCase(
                    $getterName,
                    $inputOutputPair,
                ),
            );
        }

        return $builder->getResult();
    }
}
