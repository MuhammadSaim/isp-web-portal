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
     * @param mixed $lectures
     */
    public function setLectures($lectures): void
    {
        $this->lectures = $lectures;
    }

}
