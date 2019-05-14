<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssignmentSubmissionsRepository")
 */
class AssignmentSubmissions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Assignments", inversedBy="assignmentSubmissions")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $assignment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="assignmentSubmissions")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $student;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $obtainedMarks;

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
    private $fileSize;

    /**
     * @ORM\Column(type="string")
     */
    private $fileExtenstion;

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
    public function getAssignment()
    {
        return $this->assignment;
    }

    /**
     * @param mixed $assignment
     */
    public function setAssignment($assignment): void
    {
        $this->assignment = $assignment;
    }

    /**
     * @return mixed
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param mixed $student
     */
    public function setStudent($student): void
    {
        $this->student = $student;
    }

    /**
     * @return mixed
     */
    public function getObtainedMarks()
    {
        return $this->obtainedMarks;
    }

    /**
     * @param mixed $obtainedMarks
     */
    public function setObtainedMarks($obtainedMarks): void
    {
        $this->obtainedMarks = $obtainedMarks;
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
    public function getFileExtenstion()
    {
        return $this->fileExtenstion;
    }

    /**
     * @param mixed $fileExtenstion
     */
    public function setFileExtenstion($fileExtenstion): void
    {
        $this->fileExtenstion = $fileExtenstion;
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
}
