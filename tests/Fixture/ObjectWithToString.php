<?php

namespace FR3D\Psr3MessagesAssertions\Fixture;

class ObjectWithToString
{
    public function __toString()
    {
        return 'ObjectWithToString';
    }
}
