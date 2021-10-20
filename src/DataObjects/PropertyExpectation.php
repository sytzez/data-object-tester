<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use Sytzez\DataObjectTester\Contracts\PropertyCaseContract;
use Sytzez\DataObjectTester\Factories\PropertyExpectationFactory;
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;

final class PropertyExpectation
{
    /**
     * @param string $getterName
     * @param array<PropertyCaseContract> $cases
     */
    public function __construct(
        private string $getterName,
        private array $cases,
    ) {
        foreach ($this->cases as $case) {
            $case->setGetterName($this->getterName);
        }
    }

    public function getGetterName(): string
    {
        return $this->getterName;
    }

    public function getCases(): array
    {
        return $this->cases;
    }

    public function getDefaultCase(): ?DefaultPropertyCase
    {
        foreach ($this->cases as $case) {
            if ($case instanceof DefaultPropertyCase) {
                return $case;
            }
        }

        return null;
    }

    /**
     * @param string $getterName
     * @param array<mixed> $values
     * @return PropertyExpectation
     */
    public static function create(string $getterName, array $values): PropertyExpectation
    {
        return PropertyExpectationFactory::create($getterName, $values);
    }
}
