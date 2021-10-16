<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

use Sytzez\DataObjectTester\Builders\PropertyExpectationBuilder;
use Sytzez\DataObjectTester\DataObjects\InputOutputExpectation;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;

final class PropertyExpectationFactory
{
    private function __construct()
    {
    }

    /**
     * @param string $getterName
     * @param array<mixed> $values
     * @return PropertyExpectation
     */
    public static function create(string $getterName, array $values): PropertyExpectation
    {
        $builder = new PropertyExpectationBuilder($getterName);

        foreach ($values as $value) {
            if ($value instanceof  InputOutputExpectation) {
                $builder->addInputOutputPair($value);
            } else {
                $builder->addInputOutputPair(
                    new InputOutputExpectation($value, $value),
                );
            }
        }

        return $builder->getResult();
    }
}
