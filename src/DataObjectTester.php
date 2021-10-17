<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester;

use Error;
use Exception;
use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Contracts\Generators\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\Generators\MinimalCaseGenerator;

final class DataObjectTester
{
    private CaseGeneratorStrategy $caseGenerator;

    public function __construct(
        private TestCase $testCase,
        private ClassExpectation $dataClassExpectation,
        ?CaseGeneratorStrategy $caseGenerator = null,
    ) {
        $this->caseGenerator = $caseGenerator ?: new MinimalCaseGenerator();
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

            $this->testCase::assertTrue(
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
            $propertyCase->makeAssertion($this->testCase, $object);
        }
    }

    private function instantiateObject(ObjectCase $objectCase): object
    {
        $fqn = $this->dataClassExpectation->getFqn();

        $objectCase->makeInstantiationAssertions($this->testCase);

        $arguments = $objectCase->getConstructorArguments();

        try {
            return new $fqn(...$arguments);
        } catch (Exception $e) { // TODO: don't fail if assertions made
            $message = $e->getMessage();
            $this->testCase::fail("Exception caught while instantiating $fqn: '$message'");
        } catch (Error $e) {
            $message = $e->getMessage();
            $this->testCase::fail("Error caught while instantiating $fqn: '$message'");
        }
        // @codeCoverageIgnoreStart
    }
}
