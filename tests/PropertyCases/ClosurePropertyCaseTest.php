<?php

namespace Sytzez\DataObjectTester\Tests\PropertyCases;

use Exception;
use PHPUnit\Framework\AssertionFailedError;
use Sytzez\DataObjectTester\PropertyCases\ClosurePropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\GeneratorToArray;

class ClosurePropertyCaseTest extends PropertyCaseTestCase
{
    /**
     * @test
     */
    public function it_does_not_fail_if_the_closure_succeeds(): void
    {
        $case = new ClosurePropertyCase('input', function (string $input): bool {
            static::assertEquals('output', $input);
            return true;
        });

        $case->setGetterName('getSomething');

        static::assertEquals([], GeneratorToArray::convert($case->getConstructorExceptions()));
        static::assertEquals(['input'], GeneratorToArray::convert($case->getConstructorArguments()));

        $object = new class {
            public function getSomething(): string
            {
                return 'output';
            }
        };

        $case->makeAssertion($this->testCaseMock, $object);
    }

    /**
     * @test
     */
    public function it_fails_if_the_closure_fails(): void
    {
        $case = new ClosurePropertyCase('input', function (string $input): bool {
            static::assertEquals('output', $input);
            return false;
        });

        $case->setGetterName('getSomething');

        static::assertEquals([], GeneratorToArray::convert($case->getConstructorExceptions()));
        static::assertEquals(['input'], GeneratorToArray::convert($case->getConstructorArguments()));

        $object = new class {
            public function getSomething(): string
            {
                return 'output';
            }
        };

        $this->testCaseMock->expects('fail')
            ->withArgs([$object::class . '::getSomething() returned an unexpected value'])
            ->once()
            ->andThrow(new AssertionFailedError());

        static::expectException(AssertionFailedError::class);

        $case->makeAssertion($this->testCaseMock, $object);
    }

    /**
     * @test
     */
    public function it_fails_if_the_getter_throws_something(): void
    {
        $case = new ClosurePropertyCase('input', function (): bool {
            static::fail('The closure must not be called');
        });

        $case->setGetterName('getSomething');

        static::assertEquals([], GeneratorToArray::convert($case->getConstructorExceptions()));
        static::assertEquals(['input'], GeneratorToArray::convert($case->getConstructorArguments()));

        $object = new class {
            public function getSomething(): string
            {
                throw new Exception('Something went wrong');
            }
        };

        $this->testCaseMock->expects('fail')
            ->withArgs([Exception::class . ' caught while calling ' . $object::class . "::getSomething(): 'Something went wrong'"])
            ->once()
            ->andThrow(new AssertionFailedError());

        static::expectException(AssertionFailedError::class);

        $case->makeAssertion($this->testCaseMock, $object);
    }
}
