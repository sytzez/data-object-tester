<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

use Generator;

final class GeneratorToArray
{
    private function __construct()
    {
    }

    /**
     * @param Generator<mixed> $generator
     * @return array<mixed>
     */
    public static function convert(Generator $generator): array {
        $array = [];

        foreach ($generator as $generated) {
            $array[] = $generated;
        }

        return $array;
    }
}
