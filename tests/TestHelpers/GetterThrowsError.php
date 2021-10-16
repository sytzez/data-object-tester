<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

use Error;

final class GetterThrowsError
{
    public const MESSAGE = 'This is the message';

    public function __construct(
        int $number,
    ) {
    }

    /**
     * @throws Error
     */
    public function getNumber(): int
    {
        throw new Error(self::MESSAGE);
    }
}
