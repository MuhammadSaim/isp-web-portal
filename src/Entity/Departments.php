<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentsRepository")
 * @UniqueEntity("slug")
 */
class Departments
{
    public function __construct()
    {
        $this->cources = new ArrayCollection();
        $this->studentDetails = new ArrayCollection();
        $this->staffDetails = new ArrayCollection();
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
     * @ORM\OneToMany(targetEntity="App\Entity\StaffDetails", mappedBy="department", fetch="EXTRA_LAZY")
     */
    private $staffDetails;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Courses", mappedBy="department", fetch="EXTRA_LAZY")
     */
    private $cources;

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
    public function getCources()
    {
        return $this->cources;
    }

    /**
     * @param mixed $cources
     */
    public function setCources($cources): void
    {
        $this->cources = $cources;
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

    public function addCourse(Courses $course)
    {
        if(!$this->cources->contains($course)){
            $this->cources[] = $course;
            $course->setDepartment($this);
        }

        return $this;
    }

    public function removeCourse(Courses $course)
    {
        if($this->cources->contains($course)){
            $this->cources->removeElement($course);
            if($this->getDepartment() === $this){
                $course->setDepartment(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        return $this->department;
    }


}
