<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

use Sytzez\DataObjectTester\Builders\PropertyExpectationBuilder;
use Sytzez\DataObjectTester\Contracts\PropertyCaseContract;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;
use Sytzez\DataObjectTester\PropertyCases\SimplePropertyCase;

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
            $builder->addCase(
                $value instanceof  PropertyCaseContract
                    ? $value
                    : new SimplePropertyCase($value)
            );
        }

        return $builder->getResult();
    }
}
