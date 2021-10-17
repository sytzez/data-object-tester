<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Contracts;

use Generator;
use PHPUnit\Framework\TestCase;

interface PropertyCaseContract
{
    public function setGetterName(string $getterName): static;

    public function getConstructorArguments(): Generator;

    public function makeInstantiationAssertion(TestCase $testCase): void;

    public function makeAssertion(TestCase $testCase, object $object): void;
}
