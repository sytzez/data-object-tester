<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Builders;

use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\PropertyDescription;

final class ClassDescriptionBuilder
{
    private array $propertyDescriptions = [];

    public function __construct(
        private string $fqn,
    ) {
    }

    public function addPropertyDescription(PropertyDescription $propertyDescription): self
    {
        $this->propertyDescriptions[] = $propertyDescription;

        return $this;
    }

    public function getResult(): ClassDescription
    {
        return new ClassDescription($this->fqn, $this->propertyDescriptions);
    }
}
