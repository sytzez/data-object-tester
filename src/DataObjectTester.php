<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester;

use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Contracts\Strategies\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\Strategies\CaseGenerators\MinimalCaseGenerator;

final class DataObjectTester
{
    private CaseGeneratorStrategy $caseGenerator;

    public function __construct(
        private ClassDescription $dataClassDescription,
        ?CaseGeneratorStrategy $caseGenerator = null,
    ) {
        if ($caseGenerator) {
            $this->caseGenerator = $caseGenerator;
        } else {
            $this->caseGenerator = new MinimalCaseGenerator();
        }
    }

    public function test(): void
    {
        $this->testGettersExist();
        $this->testObjectCases();
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

    private function testObjectCases(): void
    {
        $objectCases = $this->caseGenerator->generate($this->dataClassDescription);

        foreach ($objectCases as $objectCase) {
            $this->testObjectCase($objectCase);
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
