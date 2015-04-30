<?php

namespace FR3D\Psr3MessagesAssertionsTest\Fixture;

class ObjectWithToString
{
    public function __toString()
    {
        return 'ObjectWithToString';
    }
}
