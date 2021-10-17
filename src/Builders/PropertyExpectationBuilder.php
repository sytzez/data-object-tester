<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Builders;

use Sytzez\DataObjectTester\Contracts\PropertyCaseContract;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;

final class PropertyExpectationBuilder
{
    /**
     * @var array<PropertyCaseContract>
     */
    private array $cases = [];

    public function __construct(
        private string $getterName,
    ) {
    }

    public function addCase(PropertyCaseContract $case): self
    {
        $this->cases[] = $case;

        return $this;
    }

    public function getResult(): PropertyExpectation
    {
        return new PropertyExpectation($this->getterName, $this->cases);
    }
}
