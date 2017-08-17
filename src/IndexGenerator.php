<?php

namespace ClassroomTechTools\WordpressBulkCreation;

use ClassroomTechTools\WordpressBulkCreation\Models\Student;

class IndexGenerator
{
    /**
     * @var Student[][]|string[][]
     */
    private $createdFor;

    /**
     * @var string
     */
    private $outputCsv;

    /**
     * @var string
     */
    private $outputHtml;

    public function __construct(Config $config)
    {
        $this->outputCsv = $config->getOutputIndexCsv();
        $this->outputHtml = $config->getOutputIndexHtml();
    }

    /**
     * @param Models\Student[][] $createdFor
     */
    public function setCreatedFor($createdFor)
    {
        $this->createdFor = $createdFor;
    }

    /**
     * @return string
     */
    public function generateHtml()
    {
        $index = '<html><head><title>Blog Index</title></head><body><ul>';
        foreach ($this->createdFor as $created) {
            /** @var Student $student */
            $student = $created['student'];

            $index .= '<li><a href="'.$created['url'].'">'
                .$student->getFirstName()
                .' '
                .$student->getLastName()
                .' ('.$student->getPowerSchoolId().')'
                .' ('.$student->getHomeRoom().')';

            $index .= '</a></li>';
        }
        $index .= '</ul></body></html>';

        return $index;
    }

    /**
     * @return string
     */
    public function generateCsv()
    {
        $csv = '';

        $headers = [
            'Powerschool ID',
            'First Name',
            'Last Name',
            'Grade',
            'Homeroom',
            'Blog URL',
        ];


        $csv .= implode(',', $headers).PHP_EOL;

        foreach ($this->createdFor as $created) {
            /** @var Student $student */
            $student = $created['student'];

            $line = [
                $student->getPowerSchoolId(),
                $student->getFirstName(),
                $student->getLastName(),
                $student->getGradeLevel(),
                $student->getHomeRoom(),
                $created['url']
            ];

            $csv .= implode(',', $line).PHP_EOL;
        }

        return $csv;
    }

}
