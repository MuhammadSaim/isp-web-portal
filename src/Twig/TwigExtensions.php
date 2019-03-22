<?php

namespace App\Twig;


use App\Entity\Courses;
use App\Entity\Departments;
use App\Entity\Programs;
use App\Entity\Sections;
use App\Entity\Semesters;
use Carbon\Carbon;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtensions extends AbstractExtension
{

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('shortEmail', [$this, 'shortEmail']),
            new TwigFilter('shortText', [$this, 'shortText']),
            new TwigFilter('humanReadableDate', [$this, 'humanReadableDate']),
            new TwigFilter('getCourse', [$this, 'getCourse']),
            new TwigFilter('getDepartment', [$this, 'getDepartment']),
            new TwigFilter('getProgram', [$this, 'getProgram']),
            new TwigFilter('getSemester', [$this, 'getSemester']),
            new TwigFilter('getSection', [$this, 'getSection']),
            new TwigFilter('getCourseSlug', [$this, 'getCourseSlug']),
        ];
    }

    public function humanReadableDate($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public function shortEmail($email)
    {
        return strstr($email, '@', true);
    }

    public function shortText($title, $length = 16)
    {
        if(strlen($title) < $length){
            return $title;
        }else{
            return substr($title, 0, $length).'...';
        }
    }

    public function getCourse($courseId)
    {
        $course = $this->doctrine->getRepository(Courses::class)->find($courseId);
        return $course->getCourse().' ('.$course->getCourseCode().')';
    }

    public function getCourseSlug($courseId)
    {
        $course = $this->doctrine->getRepository(Courses::class)->find($courseId);
        return $course->getSlug();
    }

    public function getDepartment($departmentId)
    {
        $course = $this->doctrine->getRepository(Departments::class)->find($departmentId);
        return $course->getDepartment();
    }

    public function getProgram($programId)
    {
        $course = $this->doctrine->getRepository(Programs::class)->find($programId);
        return $course->getProgram();
    }

    public function getSemester($semesterId)
    {
        $course = $this->doctrine->getRepository(Semesters::class)->find($semesterId);
        return $course->getSemester();
    }

    public function getSection($sectionId)
    {
        $course = $this->doctrine->getRepository(Sections::class)->find($sectionId);
        return $course->getSection();
    }

}