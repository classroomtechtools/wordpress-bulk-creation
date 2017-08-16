<?php

use ClassroomTechTools\WordpressBulkCreation\Config;
use ClassroomTechTools\WordpressBulkCreation\HomeRoomCalculator;
use ClassroomTechTools\WordpressBulkCreation\InputReader;
use ClassroomTechTools\WordpressBulkCreation\ScriptGenerator;

require __DIR__.'/vendor/autoload.php';

$configData = require __DIR__.'/config.php';
$config = new Config($configData);
$input = new InputReader($config);
$homeRoomCalculator = new HomeRoomCalculator();
$generator = new ScriptGenerator($config, $homeRoomCalculator);

$students = $input->loadStudents();
$staff = $input->loadStaff();
$elementarySchedule = $input->loadElementarySchedule();

$homeRoomCalculator->setStaff($staff);
$homeRoomCalculator->setElementarySchedule($elementarySchedule);

echo "#!/bin/bash".PHP_EOL;
echo "set -x".PHP_EOL;
echo $generator->generateWordpressScriptForStudents($students);
echo "rm -rf /var/www/portfolios/wp-content/cache/supercache/portfolios.ssis-suzhou.net".PHP_EOL;
