<?php

namespace ClassroomTechTools\WordpressBulkCreation;

use ClassroomTechTools\WordpressBulkCreation\Models\Staff;
use ClassroomTechTools\WordpressBulkCreation\Models\Student;

class HomeRoomCalculator
{
    /**
     * @var Staff[]
     */
    private $staff;

    /**
     * @var array[]
     */
    private $elementarySchedule;

    /**
     * @var Staff[]
     */
    private $homeRoomTeacherCache = [];

    /**
     * @param Staff[] $staff
     */
    public function setStaff(array $staff)
    {
        $this->staff = [];
        foreach ($staff as $staffMember) {
            $this->staff[$staffMember->getPowerSchoolId()] = $staffMember;
        }
    }

    /**
     * @param array[] $elementarySchedule
     */
    public function setElementarySchedule($elementarySchedule)
    {
        $this->elementarySchedule = $elementarySchedule;
    }

    /**
     * @param Student $student
     *
     * @return Staff|null
     * @throws \Exception
     */
    public function getHomeRoomTeacherForStudent(Student $student)
    {
        $homeRoom = $student->getHomeRoom();

        // Check if we already know the teacher for this homeroom.
        if (isset($this->homeRoomTeacherCache[$homeRoom])) {
            return $this->homeRoomTeacherCache[$homeRoom];
        }

        // Need to look it up from the schedule (by the student ID not by homeroom)
        $teacherId = $this->getHomeRoomTeacherIdFromSchedule($student->getGradeLevel(), $student->getPowerSchoolId());
        if (empty($teacherId)) {
            return null;
        }

        if (empty($this->staff[$teacherId])) {
            throw new \Exception(
                "Unknown teacher ID '{$teacherId}' "
                ." found for home room '{$student->getGradeLevel()}' for student '{$student->getPowerSchoolId()}'"
            );
        }

        $teacher = $this->staff[$teacherId];
        $this->homeRoomTeacherCache[$homeRoom] = $teacher;

        return $teacher;
    }

    /**
     * @param string $grade
     * @param string $studentId
     *
     * @return string|null
     */
    private function getHomeRoomTeacherIdFromSchedule($grade, $studentId)
    {
        $className = 'HROOM_'.str_pad($grade, 2, '0', STR_PAD_LEFT);

        foreach ($this->elementarySchedule as $class) {
            if ($class[0] !== $className) {
                continue;
            }

            if ($class[4] !== $studentId) {
                continue;
            }

            return $class[3];
        }

        return null;
    }
}
