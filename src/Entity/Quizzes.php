<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizzesRepository")
 */
class Quizzes
{

    public function __construct()
    {
        $this->quizeEvaluation = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="quizzes")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departments", inversedBy="quizzes")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Programs", inversedBy="quizzes")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     */
    private $program;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Courses", inversedBy="quizzes")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Semesters", inversedBy="quizzes")
     * @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     */
    private $semester;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sections", inversedBy="quizzes")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizzesEvaluation", mappedBy="quiz")
     */
    private $quizeEvaluation;

    /**
     * @ORM\Column(type="string")
     */
    private $quizTitle;

    /**
     * @ORM\Column(type="string")
     */
    private $quizSlug;

    /**
     * @ORM\Column(type="text")
     */
    private $quizDescription;

    /**
     * @ORM\Column(type="date")
     */
    private $quizDate;

    /**
     * @ORM\Column(type="smallint")
     */
    private $totalMarks;

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
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param mixed $teacher
     */
    public function setTeacher($teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * @param mixed $program
     */
    public function setProgram($program): void
    {
        $this->program = $program;
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
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     */
    public function setSection($section): void
    {
        $this->section = $section;
    }

    /**
     * @return mixed
     */
    public function getQuizTitle()
    {
        return $this->quizTitle;
    }

    /**
     * @param mixed $quizTitle
     */
    public function setQuizTitle($quizTitle): void
    {
        $this->quizTitle = $quizTitle;
    }

    /**
     * @return mixed
     */
    public function getQuizSlug()
    {
        return $this->quizSlug;
    }

    /**
     * @param mixed $quizSlug
     */
    public function setQuizSlug($quizSlug): void
    {
        $this->quizSlug = $quizSlug;
    }

    /**
     * @return mixed
     */
    public function getQuizDescription()
    {
        return $this->quizDescription;
    }

    /**
     * @param mixed $quizDescription
     */
    public function setQuizDescription($quizDescription): void
    {
        $this->quizDescription = $quizDescription;
    }

    /**
     * @return mixed
     */
    public function getTotalMarks()
    {
        return $this->totalMarks;
    }

    /**
     * @param mixed $totalMarks
     */
    public function setTotalMarks($totalMarks): void
    {
        $this->totalMarks = $totalMarks;
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
    public function getQuizeEvaluation()
    {
        return $this->quizeEvaluation;
    }

    /**
     * @param mixed $quizeEvaluation
     */
    public function setQuizeEvaluation($quizeEvaluation): void
    {
        $this->quizeEvaluatio[] = $quizeEvaluation;
    }

    /**
     * @return mixed
     */
    public function getQuizDate()
    {
        return $this->quizDate;
    }

    /**
     * @param mixed $quizDate
     */
    public function setQuizDate($quizDate): void
    {
        $this->quizDate = $quizDate;
    }

}
