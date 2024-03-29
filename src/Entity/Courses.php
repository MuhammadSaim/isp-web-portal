<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoursesRepository")
 * @UniqueEntity("slug")
 * @UniqueEntity("courseCode")
 */
class Courses
{

    public function __construct()
    {
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
     * @ORM\OneToMany(targetEntity="App\Entity\TeacherCourseMapping", mappedBy="course", fetch="EAGER")
     */
    private $teacherCourseMap;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lectures", mappedBy="course", fetch="EXTRA_LAZY")
     */
    private $lectures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Assignments", mappedBy="course", fetch="EXTRA_LAZY")
     */
    private $assignments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Announcements", mappedBy="course", fetch="EXTRA_LAZY")
     */
    private $announcements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quizzes", mappedBy="course", fetch="EXTRA_LAZY")
     */
    private $quizzes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Examination", mappedBy="course", fetch="EXTRA_LAZY")
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attendence", mappedBy="course", fetch="EXTRA_LAZY")
     */
    private $attendence;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $slug;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $course;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", unique=true)
     */
    private $courseCode;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    private $creditHours;

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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param mixed $course
     */
    public function setCourse($course): void
    {
        $this->course = $course;
    }

    /**
     * @return mixed
     */
    public function getCourseCode()
    {
        return $this->courseCode;
    }

    /**
     * @param mixed $courseCode
     */
    public function setCourseCode($courseCode): void
    {
        $this->courseCode = $courseCode;
    }

    /**
     * @return mixed
     */
    public function getCreditHours()
    {
        return $this->creditHours;
    }

    /**
     * @param mixed $creditHours
     */
    public function setCreditHours($creditHours): void
    {
        $this->creditHours = $creditHours;
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



    public function __toString()
    {
        return $this->course.' ('.$this->courseCode.')';
    }

    /**
     * @return mixed
     */
    public function getLectures()
    {
        return $this->lectures;
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
     * @param mixed $lectures
     */
    public function setLectures($lectures): void
    {
        $this->lectures = $lectures;
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
