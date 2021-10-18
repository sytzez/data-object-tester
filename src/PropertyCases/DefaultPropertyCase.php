<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;

final class DefaultPropertyCase extends AbstractSuccessfulPropertyCase
{
    public function __construct(
        $expectedOutput,
    ) {
        $this->expectedOutput = $expectedOutput;
    }

    public function getConstructorArguments(): Generator
    {
        yield from [];
    }
}
