<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Closure;
use Error;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

class ClosurePropertyCase extends AbstractPropertyCase
{
    public function __construct(
        private $input,
        private Closure $closure
    ) {
    }

    /**
     * @return Generator<string>
     */
    public function getConstructorExceptions(): Generator
    {
        yield from [];
    }

    public function getConstructorArguments(): Generator
    {
        yield $this->input;
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

        if (! ($this->closure)($output)) {
            $testCase::fail("$fqn::$this->getterName() returned an unexpected value");
        }
    }
}
