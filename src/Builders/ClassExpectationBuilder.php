<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Builders;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;

final class ClassExpectationBuilder
{
    /**
     * @var array<PropertyExpectation>
     */
    private array $propertyExpectations = [];

    public function __construct(
        private string $fqn,
    ) {
    }

    public function addPropertyExpectation(PropertyExpectation $propertyExpectation): self
    {
        $this->propertyExpectations[] = $propertyExpectation;

        return $this;
    }

    public function getResult(): ClassExpectation
    {
        return new ClassExpectation($this->fqn, $this->propertyExpectations);
    }
}
