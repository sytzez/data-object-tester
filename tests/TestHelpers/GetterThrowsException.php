<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

use Exception;

final class GetterThrowsException
{
    public const MESSAGE = 'This is the message';

    /** @phpstan-ignore-next-line */
    public function __construct(
        int $number,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getNumber(): int
    {
        throw new Exception(self::MESSAGE);
    }
}
