<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

final class PropertyCase
{
    public function __construct(
        private PropertyDescription $description,
        private $value,
    ) {
    }

    public function getDescription(): PropertyDescription
    {
        return $this->description;
    }

    public function getValue()
    {
        return $this->value;
    }
}
