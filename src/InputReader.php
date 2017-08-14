<?php

namespace ClassroomTechTools\WordpressBulkCreation;

use ClassroomTechTools\WordpressBulkCreation\Models\AbstractPerson;
use ClassroomTechTools\WordpressBulkCreation\Models\Staff;
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

    public function loadElementarySchedule()
    {
        $src = $this->config->getElementaryScheduleInputFile();

        if (!is_readable($src)) {
            throw new Exception("'{$src}' is not readable.");
        }

        $lines = file($src);

        $schedule = [];
        foreach ($lines as $line) {
            // Schedule is tab separated because commas are used in the period field.
            $line = str_getcsv($line, "\t");
            $schedule[] = $line;
        }

        return $schedule;
    }

    /**
     * @return Student[]
     *
     * @throws Exception
     */
    public function loadStudents()
    {
        $src = $this->config->getStudentsInputFile();

        return $this->createPeopleFromFile($src, Student::class);
    }

    /**
     * @return Staff[]
     *
     * @throws Exception
     */
    public function loadStaff()
    {
        $src = $this->config->getStaffInputFile();

        return $this->createPeopleFromFile($src, Staff::class);
    }

    /**
     * @param $src
     * @param AbstractPerson|string $modelClass
     *
     * @return array
     * @throws Exception
     */
    private function createPeopleFromFile($src, $modelClass)
    {
        if (!is_readable($src)) {
            throw new Exception("'{$src}' is not readable.");
        }

        $lines = file($src);

        $models = [];
        foreach ($lines as $line) {
            $line = str_getcsv($line);
            $model = $modelClass::fromCsvData($line);
            $models[$model->getPowerSchoolId()] = $model;
        }

        return $models;
    }
}
