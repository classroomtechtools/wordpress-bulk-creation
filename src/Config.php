<?php

namespace ClassroomTechTools\WordpressBulkCreation;

use Exception;

class Config
{
    /**
     * @var string
     */
    private $srcFile;

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
            'srcFile'
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
    public function getSrcFile()
    {
        return $this->srcFile;
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
