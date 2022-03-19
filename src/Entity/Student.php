<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Student extends User
{

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="date_of_birth", type="string", length=255, nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="thumbnail", type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @Vich\UploadableField(mapping="student_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @var datetime_immutable
     *
     * @ORM\Column(name="updated_at", type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Classes::class, inversedBy="students")
     */
    private $class;

    /**
     * @ORM\OneToMany(targetEntity=FinalGrade::class, mappedBy="student")
     */
    private $finalGrades;

    public function __construct()
    {
        $this->class = new ArrayCollection();
        $this->finalGrades = new ArrayCollection();
    }


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
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

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Classes>
     */
    public function getClass(): Collection
    {
        return $this->class;
    }

    public function addClass(Classes $class): self
    {
        if (!$this->class->contains($class)) {
            $this->class[] = $class;
        }

        return $this;
    }

    public function removeClass(Classes $class): self
    {
        $this->class->removeElement($class);

        return $this;
    }

    /**
     * @return Collection<int, FinalGrade>
     */
    public function getFinalGrades(): Collection
    {
        return $this->finalGrades;
    }

    public function addFinalGrade(FinalGrade $finalGrade): self
    {
        if (!$this->finalGrades->contains($finalGrade)) {
            $this->finalGrades[] = $finalGrade;
            $finalGrade->setStudent($this);
        }

        return $this;
    }

    public function removeFinalGrade(FinalGrade $finalGrade): self
    {
        if ($this->finalGrades->removeElement($finalGrade)) {
            // set the owning side to null (unless already changed)
            if ($finalGrade->getStudent() === $this) {
                $finalGrade->setStudent(null);
            }
        }

        return $this;
    }


}
