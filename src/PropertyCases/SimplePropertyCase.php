<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Generator;

final class SimplePropertyCase extends AbstractSuccessfulPropertyCase
{
    public function __construct(
        private $value,
    ) {
        $this->expectedOutput = $value;
    }

    public function getConstructorArguments(): Generator
    {
        yield $this->value;
    }
}
