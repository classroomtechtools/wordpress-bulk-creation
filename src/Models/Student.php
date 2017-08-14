<?php

namespace ClassroomTechTools\WordpressBulkCreation\Models;

class Student extends AbstractPerson
{
    /**
     * @var string
     */
    private $dob;

    /**
     * @var string
     */
    private $gradeLevel;

    /**
     * @var string
     */
    private $homeRoom;

    /**
     * @var string
     */
    private $ltisUsername;

    /**
     * Student constructor.
     *
     * @param $powerSchoolId
     * @param $gradeLevel
     * @param $homeRoom
     * @param $lastName
     * @param $firstName
     * @param $dob
     * @param $ltisUsername
     * @param $email
     */
    public function __construct($powerSchoolId, $gradeLevel, $homeRoom, $lastName, $firstName, $dob, $ltisUsername, $email)
    {
        $this->powerSchoolId = $powerSchoolId;
        $this->gradeLevel = $gradeLevel;
        $this->homeRoom = $homeRoom;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->dob = $dob;
        $this->ltisUsername = $ltisUsername;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @return string
     */
    public function getGradeLevel()
    {
        return $this->gradeLevel;
    }

    /**
     * @return string
     */
    public function getHomeRoom()
    {
        return $this->homeRoom;
    }

    /**
     * @return string
     */
    public function getLtisUsername()
    {
        return $this->ltisUsername;
    }

    /**
     * @param array $data
     *
     * @return Student
     */
    public static function fromCsvData(array $data)
    {
        return new static(
            $data[0],
            $data[1],
            $data[2],
            $data[3],
            $data[4],
            $data[5],
            $data[6],
            $data[7]
        );
    }
}
