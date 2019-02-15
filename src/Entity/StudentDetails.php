<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentDetailsRepository")
 * @UniqueEntity("regNo")
 */
class StudentDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="studentDetails")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Semesters", inversedBy="studentDetails")
     * @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     */
    private $semester;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sections", inversedBy="studentDetails")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    private $section;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departments", inversedBy="studentDetails")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    private $department;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Programs", inversedBy="studentDetails")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     */
    private $program;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $regNo;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $facebookProfile;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $twitterProfile;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $googleProfile;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $siteUrl;

    /**
     * @ORM\Column(type="smallint")
     */
    private $isPhoneAvailable = 0;

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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
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
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $departmentId
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullname($fullname): void
    {
        $this->fullname = $fullname;
    }


    /**
     * @return mixed
     */
    public function getRegNo()
    {
        return $this->regNo;
    }

    /**
     * @param mixed $regNo
     */
    public function setRegNo($regNo): void
    {
        $this->regNo = $regNo;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param mixed $bio
     */
    public function setBio($bio): void
    {
        $this->bio = $bio;
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
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getFacebookProfile()
    {
        return $this->facebookProfile;
    }

    /**
     * @param mixed $facebookProfile
     */
    public function setFacebookProfile($facebookProfile): void
    {
        $this->facebookProfile = $facebookProfile;
    }

    /**
     * @return mixed
     */
    public function getTwitterProfile()
    {
        return $this->twitterProfile;
    }

    /**
     * @param mixed $twitterProfile
     */
    public function setTwitterProfile($twitterProfile): void
    {
        $this->twitterProfile = $twitterProfile;
    }

    /**
     * @return mixed
     */
    public function getGoogleProfile()
    {
        return $this->googleProfile;
    }

    /**
     * @param mixed $googleProfile
     */
    public function setGoogleProfile($googleProfile): void
    {
        $this->googleProfile = $googleProfile;
    }

    /**
     * @return mixed
     */
    public function getIsPhoneAvailable()
    {
        return $this->isPhoneAvailable;
    }

    /**
     * @param mixed $isPhoneAvailable
     */
    public function setIsPhoneAvailable($isPhoneAvailable): void
    {
        $this->isPhoneAvailable = $isPhoneAvailable;
    }

    /**
     * @return mixed
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * @param mixed $siteUrl
     */
    public function setSiteUrl($siteUrl): void
    {
        $this->siteUrl = $siteUrl;
    }



}
