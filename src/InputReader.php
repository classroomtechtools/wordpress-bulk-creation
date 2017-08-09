<?php

namespace ClassroomTechTools\WordpressBulkCreation;

use ClassroomTechTools\WordpressBulkCreation\Models\Student;
use Exception;

class InputReader
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Student[]
     *
     * @throws Exception
     */
    public function load()
    {
        $srcFile = $this->config->getSrcFile();

        if (!is_readable($srcFile)) {
            throw new Exception("srcFile is not readable.");
        }

        $lines = file($srcFile);

        $students = [];
        foreach ($lines as $line) {
            $line = str_getcsv($line);

            $students[] = Student::fromCsvData($line);
        }

        return $students;
    }
}
