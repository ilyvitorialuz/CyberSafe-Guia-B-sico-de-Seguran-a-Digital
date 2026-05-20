<?php

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../autoload.php';

$migration = new Migration();
$migration->run();
