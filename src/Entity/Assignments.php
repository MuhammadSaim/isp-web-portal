<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssignmentsRepository")
 * @UniqueEntity("slug")
 */
class Assignments
{

    public function __construct()
    {
        $this->assignmentSubmissions = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="assignments")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departments", inversedBy="assignments")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Programs", inversedBy="assignments")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     */
    private $program;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Courses", inversedBy="assignments")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Semesters", inversedBy="assignments")
     * @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     */
    private $semester;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sections", inversedBy="assignments")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AssignmentSubmissions", mappedBy="assignment", fetch="EAGER")
     */
    private $assignmentSubmissions;

    /**
     * @ORM\Column(type="string")
     */
    private $assignmentTitle;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $assignmentDescription;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileServerName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileClientName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileSize;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileExtension;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private $submissionDate;

    /**
     * @ORM\Column(type="integer")
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
    public function getAssignmentTitle()
    {
        return $this->assignmentTitle;
    }

    /**
     * @param mixed $assignmentTitle
     */
    public function setAssignmentTitle($assignmentTitle): void
    {
        $this->assignmentTitle = $assignmentTitle;
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
    public function getAssignmentDescription()
    {
        return $this->assignmentDescription;
    }

    /**
     * @param mixed $assignmentDescription
     */
    public function setAssignmentDescription($assignmentDescription): void
    {
        $this->assignmentDescription = $assignmentDescription;
    }

    /**
     * @return mixed
     */
    public function getFileServerName()
    {
        return $this->fileServerName;
    }

    /**
     * @param mixed $fileServerName
     */
    public function setFileServerName($fileServerName): void
    {
        $this->fileServerName = $fileServerName;
    }

    /**
     * @return mixed
     */
    public function getFileClientName()
    {
        return $this->fileClientName;
    }

    /**
     * @param mixed $fileClientName
     */
    public function setFileClientName($fileClientName): void
    {
        $this->fileClientName = $fileClientName;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @param mixed $fileSize
     */
    public function setFileSize($fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return mixed
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @param mixed $fileExtenstion
     */
    public function setFileExtension($fileExtension): void
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getSubmissionDate()
    {
        return $this->submissionDate;
    }

    /**
     * @param mixed $submissionDate
     */
    public function setSubmissionDate($submissionDate): void
    {
        $this->submissionDate = $submissionDate;
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
    public function getAssignmentSubmissions()
    {
        return $this->assignmentSubmissions;
    }

    /**
     * @param mixed $assignmentSubmissions
     */
    public function setAssignmentSubmissions($assignmentSubmissions): void
    {
        $this->assignmentSubmissions = $assignmentSubmissions;
    }

}
