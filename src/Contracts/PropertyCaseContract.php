<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Contracts;

use Generator;
use PHPUnit\Framework\TestCase;

interface PropertyCaseContract
{
    public function setGetterName(string $getterName): static;

    /**
     * @return Generator<mixed>
     */
    public function getConstructorArguments(): Generator;

    /**
     * @return Generator<string>
     */
    public function getConstructorExceptions(): Generator;

    public function makeAssertion(TestCase $testCase, object $object): void;
}
