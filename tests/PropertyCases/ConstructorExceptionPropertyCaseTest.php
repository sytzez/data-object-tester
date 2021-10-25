<?php

namespace Sytzez\DataObjectTester\Tests\PropertyCases;

use Sytzez\DataObjectTester\PropertyCases\ConstructorExceptionPropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\GeneratorToArray;

class ConstructorExceptionPropertyCaseTest extends PropertyCaseTestCase
{
    /**
     * @test
     */
    public function it_makes_no_assertions_and_expects_an_exception(): void
    {
        $case = new ConstructorExceptionPropertyCase('input', 'Exception message');

        static::assertEquals(['Exception message'], GeneratorToArray::convert($case->getConstructorExceptions()));
        static::assertEquals(['input'], GeneratorToArray::convert($case->getConstructorArguments()));

        $case->makeAssertion($this->testCaseMock, new EmptyClass());
    }
}
