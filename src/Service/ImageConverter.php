<?php

namespace App\Service;

class ImageConverter
{
    public function __construct()
    {
        if (!in_array('gd', get_loaded_extensions())) {
            throw new \Exception('Requires "gd" extension.');
        }
    }

    public function pngToJpg(string $in, string $out, int $quality = 100): void
    {
        $image = imagecreatefrompng($in);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, true);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        imagejpeg($bg, $out, $quality);
        imagedestroy($bg);
    }
}
