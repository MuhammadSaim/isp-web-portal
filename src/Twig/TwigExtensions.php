<?php

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtensions extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('shortEmail', [$this, 'shortEmail']),
            new TwigFilter('shortText', [$this, 'shortText']),
        ];
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

}