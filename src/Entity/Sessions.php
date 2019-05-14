<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SessionsRepository")
 */
class Sessions
{


    public function __construct()
    {
        $this->exams = new ArrayCollection();
        $this->teacherCourseMapping = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\Column(type="string")
     */
    private $session;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Examination", mappedBy="session")
     */
    private $exams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeacherCourseMapping", mappedBy="session")
     */
    private $teacherCourseMapping;

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
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session): void
    {
        $this->session = $session;
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
    public function getExams()
    {
        return $this->exams;
    }

    /**
     * @param mixed $exams
     */
    public function setExams($exams): void
    {
        $this->exams[] = $exams;
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
    public function getTeacherCourseMapping()
    {
        return $this->teacherCourseMapping;
    }

    /**
     * @param mixed $teacherCourseMapping
     */
    public function setTeacherCourseMapping($teacherCourseMapping): void
    {
        $this->teacherCourseMapping[] = $teacherCourseMapping;
    }

}
