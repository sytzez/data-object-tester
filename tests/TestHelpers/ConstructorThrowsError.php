<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

use Error;

final class ConstructorThrowsError
{
    public const MESSAGE = 'This is the message';

    /**
     * @throws Error
     */
    public function __construct()
    {
        throw new Error(self::MESSAGE);
    }
}
