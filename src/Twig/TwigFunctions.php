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
        ];
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
}