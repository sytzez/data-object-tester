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
     * @param array<string, array<array<mixed>> $description
     * @return ClassDescription
     */
    public static function create(string $fqn, iterable $description): ClassDescription
    {
        $builder = new ClassDescriptionBuilder($fqn);

        foreach ($description as $getterName => $inputOutputPairs) {
            if (! is_string($getterName)) {
                throw new InvalidArgumentException(
                    sprintf('Getter name must be a string, %s given', gettype($getterName)),
                );
            }

            if (! is_array($inputOutputPairs)) {
                throw new InvalidArgumentException(
                    sprintf("Getter values for '$getterName'' must be an iterable, %s given", gettype($inputOutputPairs)),
                );
            }

            $builder->addPropertyDescription(
                PropertyDescriptionFactory::create($getterName, $inputOutputPairs)
            );
        }

        return $builder->getResult();
    }
}
