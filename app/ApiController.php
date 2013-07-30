<?php

use Foo\Bar;

class ApiController
{
    public function homeAction(Bar $bar)
    {
        return $bar->hello();
    }
}