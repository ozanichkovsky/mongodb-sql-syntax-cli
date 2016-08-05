<?php

namespace MongoSql\Tests;

use Silex\WebTestCase;

class Base extends WebTestCase {

    public function createApplication() {

        $app = require_once  __DIR__ . "/../app/app.php";
        require_once __DIR__ . "/../config/params.php";
        require_once __DIR__ . "/../app/services.php";

        return $app;
    }

}