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
        ];
    }

    public function shortEmail($email)
    {
        return strstr($email, '@', true);
    }

}