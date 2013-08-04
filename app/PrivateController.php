<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use G\SecureArea;

class PrivateController implements SecureArea
{
    public function homeAction()
    {
        return "PrivateController::home";
    }
}