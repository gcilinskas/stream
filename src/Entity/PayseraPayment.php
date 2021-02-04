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
    public const STATUS_CANCELED = 'STATUS_CANCELED';
    public const STATUS_NOT_PAID = 'STATUS_NOT_PAID';
    public const STATUS_PAID = 'STATUS_PAID';
    public const TYPE_TICKET = 'TYPE_TICKET';
    public const TYPE_SUBSCRIPTION = 'TYPE_SUBSCRIPTION';

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
     * @var Price|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Price", inversedBy="payseraPayments")
     */
    private $price;

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
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $token;

    /**
     * @var Ticket|null
     * @ORM\OneToOne(targetEntity="App\Entity\Ticket", inversedBy="payseraPayment")
     */
    private $ticket;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $payseraStatus;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $payseraOrderId;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $payseraError;

    /**
     * @var Subscription|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Subscription", inversedBy="payseraPayments")
     */
    private $subscription;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

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
     * @return Price|null
     */
    public function getPrice(): ?Price
    {
        return $this->price;
    }

    /**
     * @param Price|null $price
     *
     * @return PayseraPayment
     */
    public function setPrice(?Price $price): PayseraPayment
    {
        $this->price = $price;

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
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     *
     * @return PayseraPayment
     */
    public function setToken(?string $token): PayseraPayment
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Ticket|null
     */
    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    /**
     * @param Ticket|null $ticket
     *
     * @return PayseraPayment
     */
    public function setTicket(?Ticket $ticket): PayseraPayment
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPayseraStatus(): ?string
    {
        return $this->payseraStatus;
    }

    /**
     * @param string|null $payseraStatus
     *
     * @return PayseraPayment
     */
    public function setPayseraStatus(?string $payseraStatus): PayseraPayment
    {
        $this->payseraStatus = $payseraStatus;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPayseraOrderId(): ?string
    {
        return $this->payseraOrderId;
    }

    /**
     * @param string|null $payseraOrderId
     *
     * @return PayseraPayment
     */
    public function setPayseraOrderId(?string $payseraOrderId): PayseraPayment
    {
        $this->payseraOrderId = $payseraOrderId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPayseraError(): ?string
    {
        return $this->payseraError;
    }

    /**
     * @param string|null $payseraError
     *
     * @return PayseraPayment
     */
    public function setPayseraError(?string $payseraError): PayseraPayment
    {
        $this->payseraError = $payseraError;

        return $this;
    }

    /**
     * @return Subscription|null
     */
    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    /**
     * @param Subscription|null $subscription
     *
     * @return PayseraPayment
     */
    public function setSubscription(?Subscription $subscription): PayseraPayment
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return PayseraPayment
     */
    public function setType(?string $type): PayseraPayment
    {
        $this->type = $type;

        return $this;
    }
}
