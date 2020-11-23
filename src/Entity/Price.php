<?php

namespace App\Entity;

use App\Repository\PriceRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Price
 * @ORM\Entity(repositoryClass=PriceRepository::class)
 */
class Price
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Price in CENTS
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amount;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Movie|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="prices", cascade={"persist"})
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $movie;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var string
     * @ORM\Column(type="string", length=3)
     */
    private $currency = 'EUR';

    /**
     * Price constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int|null $amount
     *
     * @return Price
     */
    public function setAmount(?int $amount): Price
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return Price
     */
    public function setCreatedAt(DateTime $createdAt): Price
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Movie|null
     */
    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    /**
     * @param Movie|null $movie
     *
     * @return Price
     */
    public function setMovie(?Movie $movie): Price
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return Price
     */
    public function setActive(bool $active): Price
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return Price
     */
    public function setCurrency(string $currency): Price
    {
        $this->currency = $currency;

        return $this;
    }
}
