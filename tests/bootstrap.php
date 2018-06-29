<?php
define("APP_PATH", realpath(dirname(__FILE__) . '/../'));

require_once __DIR__ . '/../vendor/autoload.php';

Hamcrest\Util::registerGlobalFunctions();