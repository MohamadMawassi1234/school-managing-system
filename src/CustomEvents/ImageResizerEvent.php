<?php

namespace App\CustomEvents;

use App\Entity\Student;
use Symfony\Contracts\EventDispatcher\Event;

class ImageResizerEvent extends Event {
    public const NAME = 'image_resizer';
    protected $student;
    public function __construct(Student $student, $width, $height, $text)
    {
        $this->student = $student;
        $this->width = $width;
        $this->height = $height;
        $this->text = $text;
    }
    public function getStudent()
    {
        return $this->student;
    }
    public function getWidth()
    {
        return $this->width;
    }
    public function getHeight()
    {
        return $this->height;
    }
    public function getText()
    {
        return $this->text;
    }
}