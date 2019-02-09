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




}