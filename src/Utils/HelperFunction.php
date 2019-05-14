<?php

namespace App\Utils;

use FilesystemIterator;

class HelperFunction
{
    public function randomPasswordGenerator($length = 10)
    {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
    }


    public function getRandomBoyImage($dir)
    {
        $totalPics = $this->fileCountDir($dir);
        $picName = rand(1, $totalPics);
        return 'boys/'.$picName.'.png';
    }

    public function getRandomGirlImage($dir)
    {
        $totalPics = $this->fileCountDir($dir);
        $picName = rand(1, $totalPics);
        return 'girls/'.$picName.'.png';
    }


    public function fileCountDir($dir){
        $files = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
        return iterator_count($files);
    }


    public function slugify($text)
    {
        $separator = 'dash';
        $lowercase = TRUE;
        if ($separator === 'dash')
        {
            $separator = '-';
        }
        elseif ($separator === 'underscore')
        {
            $separator = '_';
        }
        $q_separator = preg_quote($separator, '#');
        $trans = array(
            '&.+?;'			=> '',
            '[^\w\d _-]'		=> '',
            '\s+'			=> $separator,
            '('.$q_separator.')+'	=> $separator
        );
        $text = strip_tags($text);
        foreach ($trans as $key => $val)
        {
            $text = preg_replace('#'.$key.'#i'.(TRUE ? 'u' : ''), $val, $text);
        }
        if ($lowercase === TRUE)
        {
            $text = strtolower($text);
        }
        return trim(trim($text, $separator));
    }


    public function convert_filesize($bytes, $decimals = 2){
        $size = array(' B',' kB',' MB',' GB',' TB',' PB',' EB',' ZB',' YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }


    public function getUiqueName($length = 10)
    {
        $string = md5(uniqid());
        return substr($string, 0, $length);
    }


    public function getUniqueFileName()
    {
        return md5(uniqid());
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

    public function getGrade($number)
    {
        if($number >= 50 && $number <= 54){
            return 'D';
        }else if($number >= 55 && $number <= 59){
            return 'D+';
        }else if($number >= 60 && $number <= 64){
            return 'C';
        }else if($number >= 65 && $number <= 69){
            return 'C+';
        }else if($number >= 70 && $number <= 74){
            return 'B';
        }else if($number >= 75 && $number <= 79){
            return 'B+';
        }else if($number >= 80 && $number <= 84){
            return 'A-';
        }else if($number >= 85 && $number <= 100){
            return 'A';
        }else{
            return 'F';
        }
    }



}