<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Builders\PropertyDescriptionBuilder;
use Sytzez\DataObjectTester\DataObjects\InputOutputPair;
use Sytzez\DataObjectTester\DataObjects\PropertyDescription;

final class PropertyDescriptionFactory
{
    private function __construct()
    {
    }

    /**
     * @param string $getterName
     * @param array<array<mixed>> $inputOutputPairs
     * @return PropertyDescription
     */
    public static function create(string $getterName, array $inputOutputPairs): PropertyDescription
    {
        $builder = new PropertyDescriptionBuilder($getterName);

        foreach ($inputOutputPairs as $inputOutputPair) {
            if (
                ! is_array($inputOutputPair)
                || count($inputOutputPair) !== 2
            ) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Input-output pair must be an array of two elements, %s given',
                        gettype($inputOutputPair),
                    )
                );
            }

            $builder->addInputOutputPair(
                new InputOutputPair($inputOutputPair[0], $inputOutputPair[1]),
            );
        }

        return $builder->getResult();
    }
}
