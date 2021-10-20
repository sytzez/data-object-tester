<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

use Sytzez\DataObjectTester\Builders\ObjectCaseBuilder;
use Sytzez\DataObjectTester\Contracts\PropertyCaseContract;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;

final class ObjectCaseWithDefaultValuesFactory
{
    private function __construct()
    {
    }

    /**
     * @param ClassExpectation $classExpectation
     * @param array<PropertyCaseContract> $propertyCases
     * @return ObjectCase
     */
    public static function create(ClassExpectation $classExpectation, array $propertyCases): ObjectCase
    {
        $builder = new ObjectCaseBuilder($classExpectation);

        foreach ($propertyCases as $propertyCase) {
            $builder->addPropertyCase($propertyCase);
        }

        $propertyExpectations = array_slice(
            $classExpectation->getPropertyExpectations(),
            count($propertyCases)
        );

        foreach ($propertyExpectations as $propertyExpectation) {
            $defaultCase = $propertyExpectation->getDefaultCase();

            assert($defaultCase, 'Existence has been validated by the ClassDescription');

            $builder->addPropertyCase($defaultCase);
        }

        return $builder->getResult();
    }
}
