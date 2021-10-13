<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Factories\ClassDescriptionFactory;

final class ClassDescription
{
    public function __construct(
        private string $fqn,
        private iterable $propertyDescriptions,
    ) {
        if (! class_exists($this->fqn)) {
            throw new InvalidArgumentException('Class does not exist');
        }
    }

    public function getFqn(): string
    {
        return $this->fqn;
    }

    /**
     * @return iterable<PropertyDescription>
     */
    public function getPropertyDescriptions(): iterable
    {
        return $this->propertyDescriptions;
    }

    /**
     * @param string $fqn
     * @param iterable<string, iterable<mixed>> $description
     * @return ClassDescription
     */
    public static function create(string $fqn, array $description): self
    {
        return ClassDescriptionFactory::create($fqn, $description);
    }
}
