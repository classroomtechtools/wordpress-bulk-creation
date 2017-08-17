<?php

namespace ClassroomTechTools\WordpressBulkCreation;

use Exception;

class Config
{
    /**
     * @var string
     */
    private $staffInputFile;

    /**
     * @var string
     */
    private $studentsInputFile;

    /**
     * @var string
     */
    private $elementaryScheduleInputFile;

    /**
     * @var string
     */
    private $outputIndexCsv;

    /**
     * @var string
     */
    private $outputIndexHtml;

    /**
     * @var string
     */
    private $outputScript;

    /**
     * @var string
     */
    private $wordpressCliBin = '/usr/local/bin/wp';

    /**
     * @var string
     */
    private $wordpressPath;

    /**
     * @var string
     */
    private $wordpressUrl;

    /**
     * @param array $config
     *
     * @throws Exception
     */
    public function __construct(array $config)
    {
        // Ensure required values exist.
        $requiredValues = [
            'studentsInputFile',
            'staffInputFile',
            'elementaryScheduleInputFile',
            'outputScript',
        ];

        foreach ($requiredValues as $requiredValue) {
            if (empty($config[$requiredValue])) {
                throw new Exception("Please set the {$requiredValue} in the config file.");
            }
        }

        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getStaffInputFile()
    {
        return $this->staffInputFile;
    }

    /**
     * @return string
     */
    public function getStudentsInputFile()
    {
        return $this->studentsInputFile;
    }

    /**
     * @return string
     */
    public function getElementaryScheduleInputFile()
    {
        return $this->elementaryScheduleInputFile;
    }

    /**
     * @return string
     */
    public function getOutputIndexCsv()
    {
        return $this->outputIndexCsv;
    }

    /**
     * @return string
     */
    public function getOutputIndexHtml()
    {
        return $this->outputIndexHtml;
    }

    /**
     * @return string
     */
    public function getOutputScript()
    {
        return $this->outputScript;
    }

    /**
     * @return string
     */
    public function getWordpressCliBin()
    {
        return $this->wordpressCliBin;
    }

    /**
     * @return string
     */
    public function getWordpressPath()
    {
        return $this->wordpressPath;
    }

    /**
     * @return string
     */
    public function getWordpressUrl()
    {
        return $this->wordpressUrl;
    }
}
