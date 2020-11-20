<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Movie
 *
 * @ORM\Entity(repositoryClass=App\Repository\MovieRepository::class)
 */
class Movie
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var File
     */
    private $movieFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $movie;

    /**
     * @var File|null
     */
    private $imageFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $image;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime|null
     */
    private $updatedAt;

    /**
     * @var Category|null
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="movies")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $category;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @var DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param File|null $movieFile
     *
     * @return $this
     */
    public function setMovieFile(File $movieFile = null)
    {
        $this->movieFile = $movieFile;
        if ($movieFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getMovieFile()
    {
        return $this->movieFile;
    }

    /**
     * @return string|null
     */
    public function getMovie(): ?string
    {
        return $this->movie;
    }

    /**
     * @param string|null $movie
     *
     * @return $this
     */
    public function setMovie(?string $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     *
     * @return Movie
     */
    public function setImageFile(?File $imageFile = null): Movie
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     *
     * @return Movie
     */
    public function setImage(?string $image): Movie
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     *
     * @return Movie
     */
    public function setUpdatedAt(?DateTime $updatedAt): Movie
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     *
     * @return Movie
     */
    public function setCategory(?Category $category): Movie
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getFormattedPrice()
    {
        if ($this->getPrice()) {
            return number_format((float)$this->price, 2, '.', '');
        }

        return null;
    }

    /**
     * @param int|null $price
     *
     * @return Movie
     */
    public function setPrice(?int $price): Movie
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface|null $date
     *
     * @return Movie
     */
    public function setDate(?DateTimeInterface $date): Movie
    {
        $this->date = $date;

        return $this;
    }
}
