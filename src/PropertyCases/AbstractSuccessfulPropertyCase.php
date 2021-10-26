<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Error;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

abstract class AbstractSuccessfulPropertyCase extends AbstractPropertyCase
{
    abstract protected function getExpectedOutput(): mixed;

    /**
     * @return Generator<string>
     */
    public function getConstructorExceptions(): Generator
    {
        yield from [];
    }

    public function makeAssertion(TestCase $testCase, object $object): void
    {
        $fqn = $object::class;

        try {
            $output = $object->{$this->getterName}();
        } catch (Exception | Error $e) {
            $class = $e::class;
            $message = $e->getMessage();
            $testCase::fail("$class caught while calling $fqn::$this->getterName(): '$message'");
        }

        $testCase::assertEquals(
            $this->getExpectedOutput(),
            $output,
            "$fqn::$this->getterName() returned an unexpected value",
        );
    }
}
