<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnouncementsRepository")
 */
class Announcements
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="announcements")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departments", inversedBy="announcements")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Programs", inversedBy="announcements")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $program;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Courses", inversedBy="announcements")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Semesters", inversedBy="announcements")
     * @ORM\JoinColumn(name="semester_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $semester;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sections", inversedBy="announcements")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $section;

    /**
     * @ORM\Column(type="string")
     */
    private $announcementTitle;

    /**
     * @ORM\Column(type="string")
     */
    private $announcementSlug;

    /**
     * @ORM\Column(type="text")
     */
    private $announcementDescription;

    /**
     * @ORM\Column(type="string")
     */
    private $announcementType;

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
    public function getAnnouncementTitle()
    {
        return $this->announcementTitle;
    }

    /**
     * @param mixed $announcementTitle
     */
    public function setAnnouncementTitle($announcementTitle): void
    {
        $this->announcementTitle = $announcementTitle;
    }

    /**
     * @return mixed
     */
    public function getAnnouncementDescription()
    {
        return $this->announcementDescription;
    }

    /**
     * @param mixed $announcementDescription
     */
    public function setAnnouncementDescription($announcementDescription): void
    {
        $this->announcementDescription = $announcementDescription;
    }

    /**
     * @return mixed
     */
    public function getAnnouncementType()
    {
        return $this->announcementType;
    }

    /**
     * @param mixed $announcementType
     */
    public function setAnnouncementType($announcementType): void
    {
        $this->announcementType = $announcementType;
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
    public function getAnnouncementSlug()
    {
        return $this->announcementSlug;
    }

    /**
     * @param mixed $announcementSlug
     */
    public function setAnnouncementSlug($announcementSlug): void
    {
        $this->announcementSlug = $announcementSlug;
    }

}
