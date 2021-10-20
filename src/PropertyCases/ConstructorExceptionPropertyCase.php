<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;
use PHPUnit\Framework\TestCase;

final class ConstructorExceptionPropertyCase extends AbstractPropertyCase
{
    public function __construct(
        private mixed $input,
        private string $message,
    ) {
    }

    /**
     * @return Generator<mixed>
     */
    public function getConstructorArguments(): Generator
    {
        yield $this->input;
    }

    /**
     * @return Generator<string>
     */
    public function getConstructorExceptions(): Generator
    {
        yield $this->message;
    }

    public function makeAssertion(TestCase $testCase, object $object): void
    {
        // no assertion
    }
}
