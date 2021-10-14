<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

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
     * @param array<mixed> $values
     * @return PropertyDescription
     */
    public static function create(string $getterName, array $values): PropertyDescription
    {
        $builder = new PropertyDescriptionBuilder($getterName);

        foreach ($values as $value) {
            if ($value instanceof  InputOutputPair) {
                $builder->addInputOutputPair($value);
            } else {
                $builder->addInputOutputPair(
                    new InputOutputPair($value, $value),
                );
            }
        }

        return $builder->getResult();
    }
}
