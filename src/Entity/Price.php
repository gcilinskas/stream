<?php

namespace App\Entity;

use App\Repository\PriceRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class Price
 * @ORM\Entity(repositoryClass=PriceRepository::class)
 * @Table(name="price", uniqueConstraints={
 *     @UniqueConstraint(name="unique_active_is_true_movie_price", columns={"active", "movie_id"})
 * })
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="prices")
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
    private $currency = 'LT';

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
     * @param int $id
     *
     * @return Price
     */
    public function setId(int $id): Price
    {
        $this->id = $id;

        return $this;
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
