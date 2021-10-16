<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\TestHelpers;

use Exception;

final class ConstructorThrowsException
{
    public const MESSAGE = 'This is the message';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        throw new Exception(self::MESSAGE);
    }
}
