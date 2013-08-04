<?php

use Symfony\Component\HttpFoundation\Request;
use G\Exception\SecurityException;

class Security
{
    public function __construct(Request $request)
    {
        throw new SecurityException("111");
    }
}