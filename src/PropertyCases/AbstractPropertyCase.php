<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\PropertyCases;

use Sytzez\DataObjectTester\Contracts\PropertyCaseContract;

abstract class AbstractPropertyCase implements PropertyCaseContract
{
    protected string $getterName;

    public function setGetterName(string $getterName): static
    {
        $this->getterName = $getterName;

        return $this;
    }
}
