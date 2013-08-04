<?php

use Symfony\Component\HttpFoundation\Request;

class LoginController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function homeAction()
    {
        return "LoginController::home";
    }
}