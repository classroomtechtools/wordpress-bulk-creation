<?php

namespace ClassroomTechTools\WordpressBulkCreation\Models;

abstract class AbstractPerson
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $powerSchoolId;

    /**
     * @param array $line
     *
     * @return static
     */
    public static function fromCsvData(array $line)
    {
        // PHP does not allow abstract static functions. So just override this.
    }

    /**
     * @return string
     */
    public function getPowerSchoolId()
    {
        return $this->powerSchoolId;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
