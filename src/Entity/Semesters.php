<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SemestersRepository")
 */
class Semesters
{

    public function __construct()
    {
        $this->studentDetails = new ArrayCollection();
        $this->lectures = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->teacherCourseMap = new ArrayCollection();
        $this->assignments = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->quizzes = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->attendence = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeacherCourseMapping", mappedBy="semester", fetch="EXTRA_LAZY")
     */
    private $teacherCourseMap;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudentDetails", mappedBy="semester", fetch="EXTRA_LAZY")
     */
    private $studentDetails;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lectures", mappedBy="semester", fetch="EXTRA_LAZY")
     */
    private $lectures;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SemesterCourseMapping", mappedBy="semester", fetch="EXTRA_LAZY")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Assignments", mappedBy="semester", fetch="EXTRA_LAZY")
     */
    private $assignments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Announcements", mappedBy="semester", fetch="EXTRA_LAZY")
     */
    private $announcements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quizzes", mappedBy="semester", fetch="EXTRA_LAZY")
     */
    private $quizzes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Examination", mappedBy="semester", fetch="EXTRA_LAZY")
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attendence", mappedBy="semester", fetch="EXTRA_LAZY")
     */
    private $attendence;

    /**
     * @ORM\Column(type="string")
     */
    private $semester;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modifiedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getStudentDetails()
    {
        return $this->studentDetails;
    }

    /**
     * @param mixed $studentDetails
     */
    public function setStudentDetails($studentDetails): void
    {
        $this->studentDetails[] = $studentDetails;
    }

    /**
     * @return mixed
     */
    public function getLectures()
    {
        return $this->lectures;
    }

    /**
     * @param mixed $lectures
     */
    public function setLectures($lectures): void
    {
        $this->lectures[] = $lectures;
    }

    /**
     * @return mixed
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * @param mixed $semester
     */
    public function setSemester($semester): void
    {
        $this->semester = $semester;
    }

    /**
     * @return mixed
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * @param mixed $courses
     */
    public function setCourses($courses): void
    {
        $this->courses[] = $courses;
    }


    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @param mixed $modifiedAt
     */
    public function setModifiedAt($modifiedAt): void
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @return mixed
     */
    public function getTeacherCourseMap()
    {
        return $this->teacherCourseMap;
    }

    /**
     * @param mixed $teacherCourseMap
     */
    public function setTeacherCourseMap($teacherCourseMap): void
    {
        $this->teacherCourseMap[] = $teacherCourseMap;
    }

    /**
     * @return mixed
     */
    public function getAssignments()
    {
        return $this->assignments;
    }

    /**
     * @param mixed $assignments
     */
    public function setAssignments($assignments): void
    {
        $this->assignments[] = $assignments;
    }

    /**
     * @return mixed
     */
    public function getAnnouncements()
    {
        return $this->announcements;
    }

    /**
     * @param mixed $announcements
     */
    public function setAnnouncements($announcements): void
    {
        $this->announcements[] = $announcements;
    }

    /**
     * @return mixed
     */
    public function getQuizzes()
    {
        return $this->quizzes;
    }

    /**
     * @param mixed $quizzes
     */
    public function setQuizzes($quizzes): void
    {
        $this->quizzes[] = $quizzes;
    }


    public function __toString()
    {
        return $this->semester;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     */
    public function setResults($results): void
    {
        $this->results[] = $results;
    }

    /**
     * @return mixed
     */
    public function getAttendence()
    {
        return $this->attendence;
    }

    /**
     * @param mixed $attendence
     */
    public function setAttendence($attendence): void
    {
        $this->attendence[] = $attendence;
    }
}
