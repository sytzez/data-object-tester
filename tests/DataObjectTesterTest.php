<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Contracts\Generators\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjectTester;
use Sytzez\DataObjectTester\PropertyCases\TransformativePropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\ConstructorThrowsError;
use Sytzez\DataObjectTester\Tests\TestHelpers\ConstructorThrowsException;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\GetterThrowsError;
use Sytzez\DataObjectTester\Tests\TestHelpers\GetterThrowsException;

class DataObjectTesterTest extends MockeryTestCase
{
    protected Mockery\LegacyMockInterface|Mockery\MockInterface|TestCase $testCaseMock;

    protected CaseGeneratorStrategy|Mockery\LegacyMockInterface|Mockery\MockInterface $caseGeneratorMock;

    protected function setUp(): void
    {
        $this->testCaseMock = Mockery::mock(TestCase::class);

        $this->caseGeneratorMock = Mockery::mock(CaseGeneratorStrategy::class);
    }

    /**
     * @test
     */
    public function it_asserts_that_all_methods_exist(): void
    {
        $fqn = DataClass::class;

        $expectation = ClassExpectation::create($fqn, [
            'getString' => ['a'],
            'getInt'    => [1],
            'getArray'  => [[]],
        ]);

        $this->assertAssertMethodsExists($fqn, ['getString', 'getInt', 'getArray']);

        $this->caseGeneratorMock->expects('generate')
            ->with($expectation)
            ->once()
            ->andYield();

        $this->test($expectation);
    }

    /**
     * @test
     */
    public function it_asserts_that_all_object_cases_return_the_right_values(): void
    {
        $fqn = DataClass::class;

        $expectation = ClassExpectation::create($fqn, [
            'getString' => ['a', 'b'],
            'getInt'    => [1, 2],
            'getArray'  => [[]],
        ]);

        $this->assertAssertMethodsExists($fqn, ['getString', 'getInt', 'getArray']);

        $this->caseGeneratorMock->expects('generate')
            ->with($expectation)
            ->once()
            ->andYield(
                ObjectCase::create($expectation, [
                    'getString' => 'a',
                    'getInt'    => 1,
                    'getArray'  => [],
                ]),
                ObjectCase::create($expectation, [
                    'getString' => new TransformativePropertyCase('c', 'b'),
                    'getInt'    => new TransformativePropertyCase(3, 2),
                    'getArray'  => [],
                ]),
            );

        $this->assertAssertGetterReturns($fqn, 'getString', 'a', 'a');
        $this->assertAssertGetterReturns($fqn, 'getInt', 1, 1);
        $this->assertAssertGetterReturns($fqn, 'getArray', [], []);

        $this->assertAssertGetterReturns($fqn, 'getString', 'b', 'c');
        $this->assertAssertGetterReturns($fqn, 'getInt', 2, 3);
        $this->assertAssertGetterReturns($fqn, 'getArray', [], []);

        $this->test($expectation);
    }

    /**
     * @test
     */
    public function it_fails_if_instantiating_throws_an_exception(): void
    {
        $fqn = ConstructorThrowsException::class;

        $expectation = ClassExpectation::create($fqn, []);

        $this->caseGeneratorMock->expects('generate')
            ->with($expectation)
            ->once()
            ->andYield(ObjectCase::create($expectation, []));

        $this->testCaseMock->expects('fail')
            ->with("Exception caught while instantiating $fqn: '" . ConstructorThrowsException::MESSAGE . "'")
            ->once()
            ->andThrow(new AssertionFailedError());

        static::expectException(AssertionFailedError::class);

        $this->test($expectation);
    }

    /**
     * @test
     */
    public function it_fails_if_instantiating_throws_an_error(): void
    {
        $fqn = ConstructorThrowsError::class;

        $expectation = ClassExpectation::create($fqn, []);

        $this->caseGeneratorMock->expects('generate')
            ->with($expectation)
            ->once()
            ->andYield(ObjectCase::create($expectation, []));

        $this->testCaseMock->expects('fail')
            ->with("Error caught while instantiating $fqn: '" . ConstructorThrowsError::MESSAGE . "'")
            ->once()
            ->andThrow(new AssertionFailedError());

        static::expectException(AssertionFailedError::class);

        $this->test($expectation);
    }

    /**
     * @test
     */
    public function it_fails_if_a_getter_throws_an_exception(): void
    {
        $fqn = GetterThrowsException::class;

        $expectation = ClassExpectation::create($fqn, [
            'getNumber' => [1]
        ]);

        $this->assertAssertMethodsExists($fqn, ['getNumber']);

        $this->caseGeneratorMock->expects('generate')
            ->with($expectation)
            ->once()
            ->andYield(
                ObjectCase::create($expectation, [
                    'getNumber' => 1,
                ]),
            );

        $this->testCaseMock->expects('fail')
            ->with("Exception caught while calling $fqn::getNumber(): '" . GetterThrowsException::MESSAGE . "'")
            ->once()
            ->andThrow(new AssertionFailedError());

        static::expectException(AssertionFailedError::class);

        $this->test($expectation);
    }

    /**
     * @test
     */
    public function it_fails_if_a_getter_throws_an_error(): void
    {
        $fqn = GetterThrowsError::class;

        $expectation = ClassExpectation::create($fqn, [
            'getNumber' => [1]
        ]);

        $this->assertAssertMethodsExists($fqn, ['getNumber']);

        $this->caseGeneratorMock->expects('generate')
            ->with($expectation)
            ->once()
            ->andYield(
                ObjectCase::create($expectation, [
                    'getNumber' => 1,
                ]),
            );

        $this->testCaseMock->expects('fail')
            ->with("Error caught while calling $fqn::getNumber(): '" . GetterThrowsError::MESSAGE . "'")
            ->once()
            ->andThrow(new AssertionFailedError());

        static::expectException(AssertionFailedError::class);

        $this->test($expectation);
    }

    protected function test(ClassExpectation $classExpectation): void
    {
        $tester = new DataObjectTester(
            $this->testCaseMock,
            $classExpectation,
            $this->caseGeneratorMock,
        );

        $tester->run();
    }

    protected function assertAssertMethodsExists(string $fqn, array $methodNames): void
    {
        foreach ($methodNames as $methodName) {
            $this->testCaseMock->expects('assertTrue')
                ->with(true, "Method $fqn::$methodName() does not exist")
                ->once();
        }
    }

    protected function assertAssertGetterReturns(string $fqn, string $getterName, $expectedOutput, $output): void
    {
        $this->testCaseMock->expects('assertEquals')
            ->with($expectedOutput, $output, "$fqn::$getterName() returned an unexpected value")
            ->once();
    }

}
