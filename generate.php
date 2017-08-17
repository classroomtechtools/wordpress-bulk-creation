<?php

use ClassroomTechTools\WordpressBulkCreation\Config;
use ClassroomTechTools\WordpressBulkCreation\HomeRoomCalculator;
use ClassroomTechTools\WordpressBulkCreation\IndexGenerator;
use ClassroomTechTools\WordpressBulkCreation\InputReader;
use ClassroomTechTools\WordpressBulkCreation\ScriptGenerator;

require __DIR__.'/vendor/autoload.php';

$configData = require __DIR__.'/config.php';
$config = new Config($configData);
$input = new InputReader($config);
$homeRoomCalculator = new HomeRoomCalculator();
$scriptGenerator = new ScriptGenerator($config, $homeRoomCalculator);
$indexGenerator = new IndexGenerator($config);

$students = $input->loadStudents();
$staff = $input->loadStaff();
$elementarySchedule = $input->loadElementarySchedule();

$homeRoomCalculator->setStaff($staff);
$homeRoomCalculator->setElementarySchedule($elementarySchedule);

// Create blog creation script.
$outputScript = "#!/bin/bash".PHP_EOL;
$outputScript .= "set -x".PHP_EOL;
$outputScript .= $scriptGenerator->generateWordpressScriptForStudents($students);
$outputScript .= "rm -rf /var/www/portfolios/wp-content/cache/supercache/portfolios.ssis-suzhou.net".PHP_EOL;
file_put_contents($config->getOutputScript(), $outputScript);
echo "Created script {$config->getOutputScript()}".PHP_EOL;

$indexGenerator->setCreatedFor($scriptGenerator->getCreatedFor());

// Create a page with links to all the blogs.
if ($outputIndexHtml = $config->getOutputIndexHtml()) {
    $html = $indexGenerator->generateHtml();
    file_put_contents($outputIndexHtml, $html);
    echo "Created HTML index {$outputIndexHtml}".PHP_EOL;
}

// Create a csv list of all the blogs.
if ($outputIndexCsv = $config->getOutputIndexCsv()) {
    $csv = $indexGenerator->generateCsv();
    file_put_contents($outputIndexCsv, $csv);
    echo "Created CSV index {$outputIndexCsv}".PHP_EOL;
}
