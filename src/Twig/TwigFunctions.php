<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigFunctions extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getProgressBar', [$this, 'getProgressBar']),
            new TwigFunction('getGPA', [$this, 'getGPA']),
            new TwigFunction('number_format', [$this, 'number_format']),
        ];
    }

    public function number_format($number, $decimal, $point, $thousend)
    {
        return number_format((float)($number), $decimal, $point, $thousend);
    }

    public function getProgressBar($startDate, $endDate)
    {
        $start = strtotime($startDate);
        $end = strtotime($endDate);
        $current = strtotime(date('Y-m-d'));
        $completed = round((($current - $start) / ($end - $start)) * 100);
        if($completed > 100){
            return '<div class="progress wd-100p">
                         <div class="progress-bar progress-bar-animated progress-bar-striped bg-danger" role="progressbar" style="width: 100%"></div>
                    </div>';
        }
//        else{
//        }
    }

    public function getGPA($number)
    {
        if($number >= 50 && $number <= 54){
            return 1.00;
        }else if($number >= 55 && $number <= 59){
            return 1.50;
        }else if($number >= 60 && $number <= 64){
            return 2.00;
        }else if($number >= 65 && $number <= 69){
            return 2.50;
        }else if($number >= 70 && $number <= 74){
            return 3.00;
        }else if($number >= 75 && $number <= 79){
            return 3.50;
        }else if($number >= 80 && $number <= 84){
            return 3.75;
        }else if($number >= 85 && $number <= 100){
            return 4.00;
        }else{
            return 0.00;
        }
    }
}