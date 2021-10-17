<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;
use PHPUnit\Framework\TestCase;

final class ConstructorExceptionPropertyCase extends AbstractPropertyCase
{
    public function __construct(
        private $input,
        private $message,
    ) {
    }

    public function getConstructorArguments(): Generator
    {
        yield $this->input;
    }

    public function makeInstantiationAssertion(TestCase $testCase): void
    {
        $testCase->expectExceptionMessage($this->message);
    }

    public function makeAssertion(TestCase $testCase, object $object): void
    {
        // no assertion
    }
}
