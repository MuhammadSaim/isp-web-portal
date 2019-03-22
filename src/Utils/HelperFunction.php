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



}