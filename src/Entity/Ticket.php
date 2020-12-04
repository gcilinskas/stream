<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Ticket
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    const STATUS_UNUSED = "Nepanaudotas";
    const STATUS_USED = "Panaudotas";

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ajax_ticket_movie"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Groups({"ajax_ticket_movie"})
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"ajax_ticket_movie"})
     */
    private $status;

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tickets")
     * @Groups({"ajax_ticket_movie"})
     */
    private $user;

    /**
     * @var Movie|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="tickets")
     * @Groups({"ajax_tickets", "ticket_movie"})
     */
    private $movie;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $seen = false;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var PayseraPayment|null
     * @ORM\OneToOne(targetEntity="App\Entity\PayseraPayment", inversedBy="ticket")
     */
    private $payseraPayment;

    /**
     * Ticket constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return int|null
     * @Groups({"ajax_ticket_movie"})
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Ticket
     */
    public function setId(int $id): Ticket
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Ticket
     */
    public function setCode(string $code): Ticket
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Ticket
     */
    public function setStatus(string $status): Ticket
    {
        $this->status = $status;

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
     * @return Ticket
     */
    public function setUser(?User $user): Ticket
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
     * @return Ticket
     */
    public function setMovie(?Movie $movie): Ticket
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSeen(): bool
    {
        return $this->seen;
    }

    /**
     * @param bool $seen
     *
     * @return Ticket
     */
    public function setSeen(bool $seen): Ticket
    {
        $this->seen = $seen;

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
     * @return Ticket
     */
    public function setCreatedAt(DateTime $createdAt): Ticket
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return PayseraPayment|null
     */
    public function getPayseraPayment(): ?PayseraPayment
    {
        return $this->payseraPayment;
    }

    /**
     * @param PayseraPayment|null $payseraPayment
     *
     * @return Ticket
     */
    public function setPayseraPayment(?PayseraPayment $payseraPayment): Ticket
    {
        $this->payseraPayment = $payseraPayment;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return $this->getPayseraPayment() && ($this->getPayseraPayment()->getStatus() === PayseraPayment::STATUS_PAID);

    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        return $this->getStatus() === Ticket::STATUS_USED;
    }
}
