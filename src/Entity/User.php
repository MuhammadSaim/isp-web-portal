<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("regNo")
 */
class User implements UserInterface, \Serializable
{

    public function __construct()
    {
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
     * @ORM\OneToOne(targetEntity="App\Entity\StudentDetails", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $studentDetails;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\StaffDetails", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $staffDetails;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $gender;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $rememberToken;

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
     * @param mixed $studentDetails
     */
    public function setStudentDetails(StudentDetails $studentDetails): void
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
     * @param mixed $teacherDetails
     */
    public function setStaffDetails($staffDetails): void
    {
        $this->staffDetails[] = $staffDetails;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_STUDENT';

        return array_unique($roles);
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * @param mixed $rememberToken
     */
    public function setRememberToken($rememberToken): void
    {
        $this->rememberToken = $rememberToken;
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

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }


    /**
     * String representation of object
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password
        ));
    }

    /**
     * Constructs the object
     * @return void
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized);
    }


    public function addStudentDetails(StudentDetails $studentDetails){
        if(!$this->studentDetails->contains($studentDetails)){
            $this->studentDetails = $studentDetails;
            $studentDetails->setAvatar($this);
            $studentDetails->setRegNo($this);
            $studentDetails->setDepartment($this);
            $studentDetails->setCourse($this);
            $studentDetails->setCreatedAt(new \DateTime());
            $studentDetails->setModifiedAt(new \DateTime());
        }
        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getUsername();
    }


}
