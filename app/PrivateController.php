<?php

use G\SecureArea;

class PrivateController implements SecureArea
{
    public function homeAction()
    {
        return "PrivateController::home";
    }
}
