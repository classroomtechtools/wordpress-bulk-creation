<?php

use ClassroomTechTools\WordpressBulkCreation\Config;
use ClassroomTechTools\WordpressBulkCreation\InputReader;
use ClassroomTechTools\WordpressBulkCreation\ScriptGenerator;

require __DIR__.'/vendor/autoload.php';

$configData = require __DIR__.'/config.php';
$config = new Config($configData);
$input = new InputReader($config);
$generator = new ScriptGenerator($config);

$students = $input->load();

echo $generator->generateWordpressScriptForStudents($students);
