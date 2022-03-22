<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Course
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    // /**
    //  * @ORM\OneToMany(targetEntity=Classes::class, mappedBy="course")
    //  */
    // private $classes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @Vich\UploadableField(mapping="course_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
    }

    // /**
    //  * @var \Classes
    //  *
    //  * @ORM\ManyToOne(targetEntity="Classes")
    //  * @ORM\JoinColumns({
    //  *   @ORM\JoinColumn(name="class_id", referencedColumnName="id", onDelete="SET NULL")
    //  * })
    //  */
    // private $class;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    // public function getClass(): ?Classes
    // {
    //     return $this->class;
    // }

    // public function setClass(?Classes $class): self
    // {
    //     $this->class = $class;

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Classes>
    //  */
    // public function getClasses(): Collection
    // {
    //     return $this->classes;
    // }

    // public function addClass(Classes $class): self
    // {
    //     if (!$this->classes->contains($class)) {
    //         $this->classes[] = $class;
    //         $class->setCourse($this);
    //     }

    //     return $this;
    // }

    // public function removeClass(Classes $class): self
    // {
    //     if ($this->classes->removeElement($class)) {
    //         // set the owning side to null (unless already changed)
    //         if ($class->getCourse() === $this) {
    //             $class->setCourse(null);
    //         }
    //     }

    //     return $this;
    // }

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
