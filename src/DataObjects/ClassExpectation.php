<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Factories\ClassExpectationFactory;

final class ClassExpectation
{
    public function __construct(
        private string $fqn,
        private array $propertyExpectations,
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
    public function getPropertyExpectations(): array
    {
        return $this->propertyExpectations;
    }

    /**
     * @param string $fqn
     * @param array<string, array<mixed>> $expectation
     * @return ClassExpectation
     */
    public static function create(string $fqn, array $expectation): self
    {
        return ClassExpectationFactory::create($fqn, $expectation);
    }
}
