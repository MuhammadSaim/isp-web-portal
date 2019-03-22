<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LecturesRepository")
 */
class Lectures
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lectures")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departments", inversedBy="lectures")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Programs", inversedBy="lectures")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     */
    private $program;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Courses", inversedBy="lectures")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Semesters", inversedBy="lectures")
     * @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     */
    private $semester;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sections", inversedBy="lectures")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    private $section;

    /**
     * @ORM\Column(type="string")
     */
    private $fileServerName;

    /**
     * @ORM\Column(type="string")
     */
    private $fileClientName;

    /**
     * @ORM\Column(type="string")
     */
    private $fileExtension;

    /**
     * @ORM\Column(type="string")
     */
    private $fileSize;

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
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @param mixed $fileExtension
     */
    public function setFileExtension($fileExtension): void
    {
        $this->fileExtension = $fileExtension;
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

}
