<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="text", nullable=true)
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
     * @var DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var Comment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="movie")
     */
    private $comments;

    /**
     * @var PayseraPayment[]|ArrayCollection
     * @ORM\ManyToOne(targetEntity="App\Entity\PayseraPayment", inversedBy="movie")
     */
    private $payseraPayments;

    /**
     * @var Price[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Price", mappedBy="movie")
     */
    private $prices;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->payseraPayments = new ArrayCollection();
        $this->prices = new ArrayCollection();
    }

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

    /**
     * @return Comment[]|ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment[]|ArrayCollection $comments
     *
     * @return Movie
     */
    public function setComments($comments): Movie
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return Movie
     */
    public function addComment(Comment $comment): Movie
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return Movie
     */
    public function removeComment(Comment $comment): Movie
    {
        if ($this->comments->contains($comment)) {
            $this->comments->remove($comment);
        }

        return $this;
    }

    /**
     * @return PayseraPayment[]|ArrayCollection
     */
    public function getPayseraPayments()
    {
        return $this->payseraPayments;
    }

    /**
     * @param PayseraPayment[]|ArrayCollection $payseraPayments
     *
     * @return Movie
     */
    public function setPayseraPayments($payseraPayments)
    {
        $this->payseraPayments = $payseraPayments;

        return $this;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return Movie
     */
    public function addPayseraPayment(PayseraPayment $payseraPayment): Movie
    {
        if (!$this->payseraPayments->contains($payseraPayment)) {
            $this->payseraPayments->add($payseraPayment);
        }

        return $this;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return Movie
     */
    public function removePayseraPayment(PayseraPayment $payseraPayment): Movie
    {
        if ($this->payseraPayments->contains($payseraPayment)) {
            $this->payseraPayments->remove($payseraPayment);
        }

        return $this;
    }

    /**
     * @return Price[]|ArrayCollection
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param Price[]|ArrayCollection $prices
     *
     * @return Movie
     */
    public function setPrices($prices)
    {
        $this->prices = $prices;

        return $this;
    }

    /**
     * @param Price $price
     *
     * @return Movie
     */
    public function addPrice(Price $price): Movie
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
        }

        return $this;
    }

    /**
     * @param Price $price
     *
     * @return Movie
     */
    public function removePrice(Price $price): Movie
    {
        if ($this->prices->contains($price)) {
            $this->prices->remove($price);
        }

        return $this;
    }

    /**
     * @return Price|null
     */
    public function getActivePrice(): ?Price
    {
        foreach ($this->prices as $price) {
            if ($price->isActive()) {
                return $price;
            }
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getFormattedActivePrice()
    {
        if (!$this->getActivePrice()) {
            return null;

        }

        return number_format((float)$this->getActivePrice()->getAmount(), 2, '.', '');
    }

    /**
     * @return bool
     */
    public function isValidForPurchase(): bool
    {
        return $this->getActivePrice() && $this->getDate() && ($this->getDate()->getTimestamp() > time());
    }
}
