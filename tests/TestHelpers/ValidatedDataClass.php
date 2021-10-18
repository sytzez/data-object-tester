<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

use InvalidArgumentException;

final class ValidatedDataClass
{
    public const NEGATIVE_INT = 'Int must be non-negative';
    public const STRING_TOO_LONG = 'String must be 3 or less characters';

    public function __construct(
        private string $string,
        private int $int,
    ) {
        if (strlen($this->string) > 3) {
            throw new InvalidArgumentException(self::STRING_TOO_LONG);
        }

        if ($int < 0) {
            throw new InvalidArgumentException(self::NEGATIVE_INT);
        }
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function getInt(): int
    {
        return $this->int;
    }
}
