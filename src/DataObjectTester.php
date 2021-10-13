<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester;

use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Contracts\Strategies\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;

final class DataObjectTester
{
    public function __construct(
        private ClassDescription $dataClassDescription,
        private CaseGeneratorStrategy $caseGenerator,
    ) {
    }

    public function test(): void
    {
        $this->testGettersExist();

        $objectCases = $this->caseGenerator->generate($this->dataClassDescription);

        foreach ($objectCases as $objectCase) {
            $this->testObjectCase($objectCase);
        }
    }

    private function testGettersExist(): void
    {
        $fqn = $this->dataClassDescription->getFqn();

        $propertyDescriptions = $this->dataClassDescription->getPropertyDescriptions();

        foreach ($propertyDescriptions as $propertyDescription) {
            $methodName = $propertyDescription->getGetterName();

            TestCase::assertTrue(
                method_exists($fqn, $methodName),
                "Method $fqn::$methodName() does not exist"
            );
        }
    }

    private function testObjectCase(ObjectCase $objectCase): void
    {
        $fqn =  $this->dataClassDescription->getFqn();

        $arguments = $objectCase->getConstructorArguments();

        $object = new $fqn(...$arguments);

        foreach ($objectCase->getPropertyCases() as $propertyCase) {
            $getterName = $propertyCase->getDescription()->getGetterName();

            TestCase::assertEquals(
                $propertyCase->getValue(),
                $object->{$getterName}(),
                "$fqn::$getterName() returned an unexpected value",
            );
        }
    }
}
