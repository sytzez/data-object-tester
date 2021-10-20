<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;

final class DefaultPropertyCase extends AbstractSuccessfulPropertyCase
{
    public function __construct(
        mixed $expectedOutput,
    ) {
        $this->expectedOutput = $expectedOutput;
    }

    /**
     * @return Generator<mixed>
     */
    public function getConstructorArguments(): Generator
    {
        yield from [];
    }
}
