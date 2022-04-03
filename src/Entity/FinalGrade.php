<?php

namespace App\Entity;

use App\Repository\FinalGradeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FinalGradeRepository::class)
 */
class FinalGrade
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grade"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grade"})
     */
    private $grade;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="finalGrades")
     * @Groups({"grade"})
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=Classes::class, inversedBy="finalGrades")
     * @Groups({"grade"})
     */
    private $class;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getClass(): ?Classes
    {
        return $this->class;
    }

    public function setClass(?Classes $class): self
    {
        $this->class = $class;

        return $this;
    }
}
