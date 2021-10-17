<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Error;
use Exception;
use Generator;
use PHPUnit\Framework\Assert;

abstract class AbstractSuccessfulPropertyCase extends AbstractPropertyCase
{
    protected $expectedOutput;

    public function makeInstantiationAssertion(Assert $assert): void
    {
        // no assertion
    }

    public function makeAssertion(Assert $assert, object $object): void
    {
        $fqn = $object::class;

        try {
            $output = $object->{$this->getterName}();
        } catch (Exception $e) {
            $message = $e->getMessage();
            $assert::fail("Exception caught while calling $fqn::$this->getterName(): '$message'");
        } catch (Error $e) {
            $message = $e->getMessage();
            $assert::fail("Error caught while calling $fqn::$this->getterName(): '$message'");
        }

        if ($output instanceof Generator) {
            $output = iterator_to_array($output);
        }

        $assert::assertEquals(
            $this->expectedOutput,
            $output,
            "$fqn::$this->getterName() returned an unexpected value",
        );
    }
}
