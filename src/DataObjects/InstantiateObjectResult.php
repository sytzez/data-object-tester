<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\DataObjects;

final class InstantiateObjectResult
{
    public function __construct(
        private ?object $object = null,
        private bool $exceptionWasCaught = false,
    ) {
    }

    public function getObject(): ?object
    {
        return $this->object;
    }

    public function exceptionWasCaught(): bool
    {
        return $this->exceptionWasCaught;
    }
}
