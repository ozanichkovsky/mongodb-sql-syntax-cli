<?php

namespace MongoSql\Tests;

use Silex\WebTestCase;

class Base extends WebTestCase {

    public function createApplication() {

        $app = require __DIR__ . "/../app/app.php";
        require __DIR__ . "/../config/params.php";
        require __DIR__ . "/../app/services.php";

        return $app;
    }

}