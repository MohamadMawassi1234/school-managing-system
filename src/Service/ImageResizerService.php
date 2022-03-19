<?php

namespace App\Service;

class ImageResizerService
{
    public function __construct($thumbnail_height, $resized_height)
    {
        $this->resized_height = $resized_height;
        $this->thumbnail_height = $thumbnail_height;
    }

    public function getResizedHeight()
    {
        return $this->resized_height;
    }

    public function getThumbnailHeight()
    {
        return $this->thumbnail_height;
    }
}