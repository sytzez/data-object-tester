<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\PropertyCases;

use Exception;
use PHPUnit\Framework\AssertionFailedError;
use Sytzez\DataObjectTester\PropertyCases\TransformativePropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\GeneratorToArray;

class TransformativePropertyCaseTest extends PropertyCaseTestCase
{
    /**
     * @test
     */
    public function it_does_not_fail_if_the_output_matches_the_input(): void
    {
        $case = new TransformativePropertyCase('in', 'out');

        $case->setGetterName('getSomething');

        static::assertEquals([], GeneratorToArray::convert($case->getConstructorExceptions()));
        static::assertEquals(['in'], GeneratorToArray::convert($case->getConstructorArguments()));

        $object = new class {
            public function getSomething(): string
            {
                return 'out';
            }
        };

        $this->testCaseMock->expects('assertEquals')
            ->withArgs([
                'out',
                'out',
                $object::class . '::getSomething() returned an unexpected value',
            ])
            ->once();

        $case->makeAssertion($this->testCaseMock, $object);
    }

    /**
     * @test
     */
    public function it_fails_if_the_output_does_not_match_the_input(): void
    {
        $case = new TransformativePropertyCase('in', 'out');

        $case->setGetterName('getSomething');

        static::assertEquals([], GeneratorToArray::convert($case->getConstructorExceptions()));
        static::assertEquals(['in'], GeneratorToArray::convert($case->getConstructorArguments()));

        $object = new class {
            public function getSomething(): string
            {
                return 'something else';
            }
        };

        $this->testCaseMock->expects('assertEquals')
            ->withArgs([
                'out',
                'something else',
                $object::class . '::getSomething() returned an unexpected value',
            ])
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
        $case = new TransformativePropertyCase('in', 'out');

        $case->setGetterName('getSomething');

        static::assertEquals([], GeneratorToArray::convert($case->getConstructorExceptions()));
        static::assertEquals(['in'], GeneratorToArray::convert($case->getConstructorArguments()));

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
