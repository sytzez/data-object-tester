<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Builders;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyCase;

final class ObjectCaseBuilder
{
    private array $propertyCases = [];

    public function __construct(
        private ClassExpectation $classExpectation,
    ) {
    }

    public function addPropertyCase(PropertyCase $propertyCase): self
    {
        $this->propertyCases[] = $propertyCase;

        return $this;
    }

    public function getResult(): ObjectCase
    {
        return new ObjectCase($this->classExpectation, $this->propertyCases);
    }
}
