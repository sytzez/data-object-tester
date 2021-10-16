<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester;

use Error;
use Exception;
use PHPUnit\Framework\Assert;
use Sytzez\DataObjectTester\Contracts\Strategies\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\Strategies\CaseGenerators\MinimalCaseGenerator;

final class DataObjectTester
{
    private CaseGeneratorStrategy $caseGenerator;

    public function __construct(
        private Assert $assert,
        private ClassExpectation $dataClassExpectation,
        ?CaseGeneratorStrategy $caseGenerator = null,
    ) {
        if ($caseGenerator) {
            $this->caseGenerator = $caseGenerator;
        } else {
            // @codeCoverageIgnoreStart
            $this->caseGenerator = new MinimalCaseGenerator();
            // @codeCoverageIgnoreEnd
        }
    }

    public function test(): void
    {
        $this->testGettersExist();
        $this->testObjectCases();
    }

    private function testGettersExist(): void
    {
        $fqn = $this->dataClassExpectation->getFqn();

        $propertyExpectations = $this->dataClassExpectation->getPropertyExpectations();

        foreach ($propertyExpectations as $propertyExpectation) {
            $methodName = $propertyExpectation->getGetterName();

            $this->assert::assertTrue(
                method_exists($fqn, $methodName),
                "Method $fqn::$methodName() does not exist"
            );
        }
    }

    private function testObjectCases(): void
    {
        $objectCases = $this->caseGenerator->generate($this->dataClassExpectation);

        foreach ($objectCases as $objectCase) {
            $this->testObjectCase($objectCase);
        }
    }

    private function testObjectCase(ObjectCase $objectCase): void
    {
        $fqn = $this->dataClassExpectation->getFqn();

        $object = $this->instantiateObject($objectCase);

        foreach ($objectCase->getPropertyCases() as $propertyCase) {
            $getterName = $propertyCase->getExpectation()->getGetterName();

            try {
                $output = $object->{$getterName}();
            } catch (Exception $e) {
                $message = $e->getMessage();
                $this->assert::fail("Exception caught while calling $fqn::$getterName(): '$message'");
            } catch (Error $e) {
                $message = $e->getMessage();
                $this->assert::fail("Error caught while calling $fqn::$getterName(): '$message'");
            }

            $this->assert::assertEquals(
                $propertyCase->getExpectedOutput(),
                $output,
                "$fqn::$getterName() returned an unexpected value",
            );
        }
    }

    private function instantiateObject(ObjectCase $objectCase): object
    {
        $fqn = $this->dataClassExpectation->getFqn();

        $arguments = $objectCase->getConstructorArguments();

        try {
            return new $fqn(...$arguments);
        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->assert::fail("Exception caught while instantiating $fqn: '$message'");
        } catch (Error $e) {
            $message = $e->getMessage();
            $this->assert::fail("Error caught while instantiating $fqn: '$message'");
        }
        // @codeCoverageIgnoreStart
    }
}
