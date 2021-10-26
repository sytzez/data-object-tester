<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\PropertyCases;

use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

abstract class PropertyCaseTestCase extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * @var TestCase&Mockery\MockInterface
     */
    protected TestCase|Mockery\MockInterface $testCaseMock;

    /**
     * @var stdClass&Mockery\MockInterface
     */
    protected stdClass|Mockery\MockInterface $objectMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testCaseMock = Mockery::mock(TestCase::class);
        $this->objectMock   = Mockery::mock(stdClass::class);
    }
}
