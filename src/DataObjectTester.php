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

    public function run(): void
    {
        $this->assertGettersExist();
        $this->testObjectCases();
    }

    private function assertGettersExist(): void
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
        $object = $this->instantiateObject($objectCase);

        // Expected exception was caught
        if ($object === false) {
            return;
        }

        foreach ($objectCase->getPropertyCases() as $propertyCase) {
            $propertyCase->makeAssertion($this->testCase, $object);
        }
    }

    private function instantiateObject(ObjectCase $objectCase): object | false
    {
        $fqn = $this->dataClassExpectation->getFqn();

        $arguments = $objectCase->getConstructorArguments();
        $expectedExceptionMessages = $objectCase->getConstructorExceptions();

        try {
            $object = new $fqn(...$arguments);
        } catch (Exception | Error $e) {
            $class = $e::class;
            $message = $e->getMessage();

            foreach($expectedExceptionMessages as $expectedExceptionMessage) {
                if ($message === $expectedExceptionMessage) {
                    return false;
                }
            }

            $this->testCase::fail("$class caught while instantiating $fqn: '$message'");
        }

        foreach($expectedExceptionMessages as $expectedExceptionMessage) {
            $this->testCase::fail("No exception thrown in $fqn::__construct(), expected exception with message '$expectedExceptionMessage'");
        }

        return $object;
    }
}
