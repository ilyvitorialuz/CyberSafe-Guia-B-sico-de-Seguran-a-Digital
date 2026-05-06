<?php

require_once __DIR__ . '/src/bootstrap.php';

use App\Migration;

$migration = new Migration();
$migration->run();
