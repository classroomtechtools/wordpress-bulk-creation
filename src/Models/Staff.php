<?php

namespace ClassroomTechTools\WordpressBulkCreation\Models;

class Staff extends AbstractPerson
{
    /**
     * Student constructor.
     *
     * @param $powerSchoolId
     * @param $firstName
     * @param $lastName
     * @param $email
     */
    public function __construct($powerSchoolId, $firstName, $lastName, $email)
    {
        $this->powerSchoolId = $powerSchoolId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    /**
     * @param array $data
     *
     * @return Staff
     */
    public static function fromCsvData(array $data)
    {
        return new static(
            $data[0],
            $data[1],
            $data[2],
            $data[3]
        );
    }
}
