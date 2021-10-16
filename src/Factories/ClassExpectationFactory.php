<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Builders\ClassExpectationBuilder;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;

final class ClassExpectationFactory
{
    private function __construct()
    {
    }

    /**
     * @param string $fqn
     * @param array<string, array<mixed> $expectation
     * @return ClassExpectation
     */
    public static function create(string $fqn, array $expectation): ClassExpectation
    {
        $builder = new ClassExpectationBuilder($fqn);

        foreach ($expectation as $getterName => $values) {
            if (! is_string($getterName)) {
                throw new InvalidArgumentException(
                    sprintf('Getter name must be a string, %s given', gettype($getterName)),
                );
            }

            if (! is_array($values)) {
                throw new InvalidArgumentException(
                    sprintf("Getter values for '$getterName'' must be an array, %s given", gettype($values)),
                );
            }

            $builder->addPropertyExpectation(
                PropertyExpectationFactory::create($getterName, $values)
            );
        }

        return $builder->getResult();
    }
}
