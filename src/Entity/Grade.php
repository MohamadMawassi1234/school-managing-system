<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grade
 *
 * @ORM\Table(name="grade")
 * @ORM\Entity
 */
class Grade
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
     * @var string|null
     *
     * @ORM\Column(name="grade", type="string", length=255, nullable=true)
     */
    private $grade;

    /**
     * @var int|null
     *
     * @ORM\Column(name="studentId", type="integer", nullable=true)
     */
    private $studentid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="courseId", type="integer", nullable=true)
     */
    private $courseid;

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

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): self
    {
        $this->grade = $grade;

        return $this;
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

    public function getCourseid(): ?int
    {
        return $this->courseid;
    }

    public function setCourseid(?int $courseid): self
    {
        $this->courseid = $courseid;

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
