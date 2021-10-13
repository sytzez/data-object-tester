<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Factories;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Builders\ClassDescriptionBuilder;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\PropertyDescription;

final class ClassDescriptionFactory
{
    private function __construct()
    {
    }

    /**
     * @param string $fqn
     * @param iterable<string, iterable<mixed>> $description
     * @return ClassDescription
     */
    public static function create(string $fqn, iterable $description): ClassDescription
    {
        $builder = new ClassDescriptionBuilder($fqn);

        foreach ($description as $getterName => $values) {
            if (! is_string($getterName)) {
                throw new InvalidArgumentException(
                    sprintf('Getter name must be a string, %s given', gettype($getterName)),
                );
            }

            if (! is_iterable($values)) {
                throw new InvalidArgumentException(
                    sprintf("Getter values for '$getterName'' must be an iterable, %s given", gettype($values)),
                );
            }

            $builder->addPropertyDescription(
                new PropertyDescription($getterName, $values),
            );
        }

        return $builder->getResult();
    }
}
