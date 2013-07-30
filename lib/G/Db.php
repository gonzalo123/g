<?php

namespace G;

use Doctrine\DBAL\DriverManager;

class Db
{
    const DEFAULT_DATABASE_KEY = 'defaultDatabase';

    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    public function getConnection($dbId = null)
    {
        $dbId = array_merge([self::DEFAULT_DATABASE_KEY => $dbId], $this->conf)[self::DEFAULT_DATABASE_KEY];

        return DriverManager::getConnection($this->conf[$dbId]);
    }
}