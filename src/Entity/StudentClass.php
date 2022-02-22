<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentClass
 *
 * @ORM\Table(name="student_class")
 * @ORM\Entity
 */
class StudentClass
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="studentId", type="integer", nullable=true)
     */
    private $studentid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="classId", type="integer", nullable=true)
     */
    private $classid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentid(): ?int
    {
        return $this->studentid;
    }

    public function setStudentid(?int $studentid): self
    {
        $this->studentid = $studentid;

        return $this;
    }

    public function getClassid(): ?int
    {
        return $this->classid;
    }

    public function setClassid(?int $classid): self
    {
        $this->classid = $classid;

        return $this;
    }


}
