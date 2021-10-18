<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Error;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

abstract class AbstractSuccessfulPropertyCase extends AbstractPropertyCase
{
    protected $expectedOutput;

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
        } catch (Exception $e) {
            $message = $e->getMessage();
            $testCase::fail("Exception caught while calling $fqn::$this->getterName(): '$message'");
        } catch (Error $e) {
            $message = $e->getMessage();
            $testCase::fail("Error caught while calling $fqn::$this->getterName(): '$message'");
        }

        if ($output instanceof Generator) {
            $output = iterator_to_array($output);
        }

        $testCase::assertEquals(
            $this->expectedOutput,
            $output,
            "$fqn::$this->getterName() returned an unexpected value",
        );
    }
}
