<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Factories\ClassExpectationFactory;

final class ClassExpectation
{
    public function __construct(
        private string $fqn,
        private iterable $propertyExpectations,
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
     * @return iterable<PropertyExpectation>
     */
    public function getPropertyExpectations(): iterable
    {
        return $this->propertyExpectations;
    }

    /**
     * @param string $fqn
     * @param iterable<string, iterable<mixed>> $expectation
     * @return ClassExpectation
     */
    public static function create(string $fqn, array $expectation): self
    {
        return ClassExpectationFactory::create($fqn, $expectation);
    }
}
