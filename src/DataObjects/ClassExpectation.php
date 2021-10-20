<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

use InvalidArgumentException;
use Sytzez\DataObjectTester\Factories\ClassExpectationFactory;

final class ClassExpectation
{
    /**
     * @param string $fqn
     * @param array<PropertyExpectation> $propertyExpectations
     */
    public function __construct(
        private string $fqn,
        private array $propertyExpectations,
    ) {
        if (! class_exists($this->fqn)) {
            throw new InvalidArgumentException('Class does not exist');
        }

        $this->validateDefaultProperties();
    }

    public function getFqn(): string
    {
        return $this->fqn;
    }

    /**
     * @return array<PropertyExpectation>
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

    protected function validateDefaultProperties(): void
    {
        $hasSeenDefault = false;

        foreach($this->propertyExpectations as $propertyExpectation) {
            if ($propertyExpectation->getDefaultCase()) {
                $hasSeenDefault = true;
            } else if ($hasSeenDefault) {
                throw new InvalidArgumentException(
                    $this->fqn . '::' . $propertyExpectation->getGetterName() . '() '
                    . 'has no default case'
                );
            }
        }
    }
}
