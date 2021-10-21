<?php

namespace Sytzez\DataObjectTester\Tests\Builders;

use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Builders\PropertyExpectationBuilder;
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;
use Sytzez\DataObjectTester\PropertyCases\SimplePropertyCase;

class PropertyExpectationBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_on_an_empty_property_expectation(): void
    {
        static::expectExceptionMessage('PropertyExpectation must contain at least one PropertyCase');

        (new PropertyExpectationBuilder('getInt'))
            ->getResult();
    }

    /**
     * @test
     */
    public function it_can_create_a_property_expectation_with_property_cases(): void
    {
        $propertyCases = [
            (new SimplePropertyCase(1))->setGetterName('getInt'),
            (new SimplePropertyCase(2))->setGetterName('getInt'),
            (new SimplePropertyCase(3))->setGetterName('getInt'),
        ];

        $result = (new PropertyExpectationBuilder('getInt'))
            ->addCase($propertyCases[0])
            ->addCase($propertyCases[1])
            ->addCase($propertyCases[2])
            ->getResult();

        static::assertEquals('getInt', $result->getGetterName());
        static::assertCount(3, $result->getCases());
        static::assertEquals($propertyCases[0], $result->getCases()[0]);
        static::assertEquals($propertyCases[1], $result->getCases()[1]);
        static::assertEquals($propertyCases[2], $result->getCases()[2]);
    }

    /**
     * @test
     */
    public function it_can_get_the_default_case(): void
    {
        $propertyCases = [
            (new SimplePropertyCase(1))->setGetterName('getInt'),
            (new SimplePropertyCase(2))->setGetterName('getInt'),
            (new SimplePropertyCase(3))->setGetterName('getInt'),
        ];

        $defaultCase = new DefaultPropertyCase(0);

        $result = (new PropertyExpectationBuilder('getInt'))
            ->addCase($propertyCases[0])
            ->addCase($propertyCases[1])
            ->addCase($defaultCase)
            ->addCase($propertyCases[2])
            ->getResult();

        static::assertCount(4, $result->getCases());
        static::assertEquals($defaultCase, $result->getDefaultCase());
    }

    /**
     * @test
     */
    public function it_returns_null_if_there_is_no_default_case(): void
    {
        $propertyCases = [
            (new SimplePropertyCase(1))->setGetterName('getInt'),
            (new SimplePropertyCase(2))->setGetterName('getInt'),
            (new SimplePropertyCase(3))->setGetterName('getInt'),
        ];

        $result = (new PropertyExpectationBuilder('getInt'))
            ->addCase($propertyCases[0])
            ->addCase($propertyCases[1])
            ->addCase($propertyCases[2])
            ->getResult();

        static::assertNull($result->getDefaultCase());
    }
}
