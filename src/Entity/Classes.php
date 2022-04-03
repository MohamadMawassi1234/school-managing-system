<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Classes
 *
 * @ORM\Table(name="classes")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Classes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"class"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", length=255, nullable=true)
     * @Groups({"student", "grade", "class"})
     */
    private $time;

    /**
     * @var string|null
     *
     * @ORM\Column(name="section", type="string", length=255, nullable=true)
     * @Groups({"class"})
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="classes")
     * @Groups({"student", "grade", "class"})
     */
    private $course;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Ignore()
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Ignore()
     */
    private $thumbnail;

    /**
     * @Ignore()
     * @Vich\UploadableField(mapping="class_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Ignore()
     */
    private $updatedAt;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->finalGrades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(?string $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTimeImmutable('now');
        }
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
