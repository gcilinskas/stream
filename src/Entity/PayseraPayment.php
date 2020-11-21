<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PayseraPaymentRepository;

/**
 * Class PayseraPayment
 * @ORM\Entity(repositoryClass=App\Repository\PayseraPaymentRepository::class)
 */
class PayseraPayment
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $status;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $currency;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $locale;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="payseraPayments")
     */
    private $user;

    /**
     * @var Movie|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="payseraPayments")
     */
    private $movie;

    /**
     * @var PaymentAddress|null
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentAddress", inversedBy="payseraPayments")
     */
    private $paymentAddress;

    /**
     * PayseraPayment constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updateAt = new DateTime();
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
     * @return PayseraPayment
     */
    public function setId(int $id): PayseraPayment
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     *
     * @return PayseraPayment
     */
    public function setStatus(?string $status): PayseraPayment
    {
        $this->status = $status;

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
     * @param int|null $price
     *
     * @return PayseraPayment
     */
    public function setPrice(?int $price): PayseraPayment
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     *
     * @return PayseraPayment
     */
    public function setCurrency(?string $currency): PayseraPayment
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param string|null $locale
     *
     * @return PayseraPayment
     */
    public function setLocale(?string $locale): PayseraPayment
    {
        $this->locale = $locale;

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
     * @return PayseraPayment
     */
    public function setCreatedAt(DateTime $createdAt): PayseraPayment
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdateAt(): DateTime
    {
        return $this->updateAt;
    }

    /**
     * @param DateTime $updateAt
     *
     * @return PayseraPayment
     */
    public function setUpdateAt(DateTime $updateAt): PayseraPayment
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return PayseraPayment
     */
    public function setUser(?User $user): PayseraPayment
    {
        $this->user = $user;

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
     * @return PayseraPayment
     */
    public function setMovie(?Movie $movie): PayseraPayment
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * @return PaymentAddress|null
     */
    public function getPaymentAddress(): ?PaymentAddress
    {
        return $this->paymentAddress;
    }

    /**
     * @param PaymentAddress|null $paymentAddress
     *
     * @return PayseraPayment
     */
    public function setPaymentAddress(?PaymentAddress $paymentAddress): PayseraPayment
    {
        $this->paymentAddress = $paymentAddress;

        return $this;
    }
}
