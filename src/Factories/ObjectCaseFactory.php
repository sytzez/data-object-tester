<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Builders\ObjectCaseBuilder;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\InputOutputPair;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyCase;
use Sytzez\DataObjectTester\DataObjects\PropertyDescription;

final class ObjectCaseFactory
{
    private function __construct()
    {
    }

    /**
     * @param ClassDescription $classDescription
     * @param array<string, mixed> $values
     * @return ObjectCase
     */
    public static function create(ClassDescription $classDescription, array $values): ObjectCase
    {
        $builder = new ObjectCaseBuilder($classDescription);

        foreach ($values as $getterName => $value) {
            if (! is_string($getterName)) {
                throw new InvalidArgumentException(
                    sprintf('Getter name must be a string, %s given', gettype($getterName)),
                );
            }

            $propertyDescription = self::getPropertyByGetterName($classDescription, $getterName);

            $inputOutputPair = $value instanceof InputOutputPair
                ? $value
                : new InputOutputPair($value, $value);

            $builder->addPropertyCase(
                new PropertyCase(
                    $propertyDescription,
                    $inputOutputPair,
                ),
            );
        }

        return $builder->getResult();
    }

    private static function getPropertyByGetterName(
        ClassDescription $classDescription,
        string $getterName,
    ): PropertyDescription {

        foreach ($classDescription->getPropertyDescriptions() as $propertyDescription) {
            if ($propertyDescription->getGetterName() === $getterName) {
                return $propertyDescription;
            }
        }

        throw new InvalidArgumentException("Property with getter '$getterName' does not exist in class '$classDescription->getFqn()'");
    }
}
