<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\DependencyInjection\Tests\A;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentsRepository")
 * @UniqueEntity("slug")
 */
class Departments
{
    public function __construct()
    {
        $this->studentDetails = new ArrayCollection();
        $this->staffDetails = new ArrayCollection();
        $this->lectures = new ArrayCollection();
        $this->programs = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->teacherCourseMap = new ArrayCollection();
        $this->assignments = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudentDetails", mappedBy="department", fetch="EXTRA_LAZY")
     */
    private $studentDetails;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeacherCourseMapping", mappedBy="department", fetch="EXTRA_LAZY")
     */
    private $teacherCourseMap;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StaffDetails", mappedBy="department", fetch="EXTRA_LAZY")
     */
    private $staffDetails;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lectures", mappedBy="department", fetch="EXTRA_LAZY")
     */
    private $lectures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Programs", mappedBy="department", fetch="EXTRA_LAZY")
     */
    private $programs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SemesterCourseMapping", mappedBy="department", fetch="EXTRA_LAZY")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Assignments", mappedBy="department", fetch="EXTRA_LAZY")
     */
    private $assignments;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string")
     */
    private $department;

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
     * @param $studentDetails
     */
    public function setStudentDetails($studentDetails): void
    {
        $this->studentDetails[] = $studentDetails;
    }

    /**
     * @return mixed
     */
    public function getStaffDetails()
    {
        return $this->staffDetails;
    }

    /**
     * @param mixed $staffDetails
     */
    public function setStaffDetails($staffDetails): void
    {
        $this->staffDetails[] = $staffDetails;
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
    public function getPrograms()
    {
        return $this->programs;
    }

    /**
     * @param mixed $programs
     */
    public function setPrograms($programs): void
    {
        $this->programs[] = $programs;
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


    public function __toString()
    {
        return $this->department;
    }


}
