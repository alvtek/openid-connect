<?php

chdir(dirname(__FILE__, 2));
echo getcwd();
define('TEST_ASSETS', 'test' . DIRECTORY_SEPARATOR . 'assets');
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';