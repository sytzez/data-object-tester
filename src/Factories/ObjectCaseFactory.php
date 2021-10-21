<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Builders\ObjectCaseBuilder;
use Sytzez\DataObjectTester\Contracts\PropertyCaseContract;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\PropertyCases\SimplePropertyCase;

final class ObjectCaseFactory extends AbstractFactory
{
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

            $propertyCase = $value instanceof  PropertyCaseContract
                ? $value
                : new SimplePropertyCase($value);

            $propertyCase->setGetterName($getterName);

            $builder->addPropertyCase($propertyCase);
        }

        return $builder->getResult();
    }
}
