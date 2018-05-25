<?php

declare(strict_types=1);

namespace FR3D\Psr3MessagesAssertions\Fixture;

class ObjectWithToString
{
    public function __toString()
    {
        return 'ObjectWithToString';
    }
}
