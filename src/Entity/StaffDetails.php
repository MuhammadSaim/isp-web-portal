<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StaffDetailsRepository")
 */
class StaffDetails
{


    public function __construct()
    {
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="staffDetails")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departments", inversedBy="staffDetails")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Designations", inversedBy="staffDetails")
     * @ORM\JoinColumn(name="designation_id", referencedColumnName="id")
     */
    private $designation;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fullname;

    /**
     * @ORM\Column(type="string")
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
     * @param mixed $userId
     */
    public function setUser($user): void
    {
        $this->user = $user;
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
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * @param mixed $designation
     */
    public function setDesignation($designation): void
    {
        $this->designation = $designation;
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
    public function getisPhoneAvailable()
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
