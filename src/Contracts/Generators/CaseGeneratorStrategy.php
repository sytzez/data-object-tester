<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Contracts\Generators;

use Generator;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;

interface CaseGeneratorStrategy
{
    /**
     * @param ClassExpectation $classExpectation
     * @return Generator<ObjectCase>
     */
    public function generate(ClassExpectation $classExpectation): Generator;
}
