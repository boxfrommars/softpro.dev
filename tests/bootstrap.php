<?php
echo "softpro tests running\n\n";
require_once __DIR__ . '/../../Silex/autoload.php';
require_once __DIR__ . '/../application/Softpro/DbWebTestCase.php';
require_once __DIR__ . '/../application/Softpro/ArrayDataSet.php';

define('PATH_TO_APP', __DIR__ . '/../application/');