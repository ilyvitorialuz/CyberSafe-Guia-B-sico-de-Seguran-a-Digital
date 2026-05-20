<?php

require_once __DIR__ . '/app/bootstrap.php';

use App\Migration;

$migration = new Migration();
$migration->run();
