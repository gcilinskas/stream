<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';
    const ROLE_KLUBO_NARYS = 'ROLE_KLUBO_NARYS';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_user", "ajax_tickets"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"api_user", "api_comment"})
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $role;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var Comment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @var PayseraPayment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PayseraPayment", mappedBy="user")
     */
    private $payseraPayments;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var Ticket[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="user")
     */
    private $tickets;

    /**
     * @var string|null
     */
    private $plainPassword;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $clubRequest = false;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->payseraPayments = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getRole() === self::ROLE_ADMIN;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
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
     * @return User
     */
    public function setComments($comments): User
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return User
     */
    public function addComment(Comment $comment): User
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return User
     */
    public function removeComment(Comment $comment): User
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
     * @return User
     */
    public function setPayseraPayments($payseraPayments)
    {
        $this->payseraPayments = $payseraPayments;

        return $this;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return User
     */
    public function addPayseraPayment (PayseraPayment $payseraPayment): User
    {
        if (!$this->payseraPayments->contains($payseraPayment)) {
            $this->payseraPayments->add($payseraPayment);
        }

        return $this;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return User
     */
    public function removePayseraPayment (PayseraPayment $payseraPayment): User
    {
        if ($this->payseraPayments->contains($payseraPayment)) {
            $this->payseraPayments->remove($payseraPayment);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     *
     * @return User
     */
    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     *
     * @return User
     */
    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Ticket[]|ArrayCollection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param Ticket[]|ArrayCollection $tickets
     *
     * @return User
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;

        return $this;
    }

    /**
     * @param Ticket $ticket
     *
     * @return User
     */
    public function addTicket(Ticket $ticket): User
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
        }

        return $this;
    }

    /**
     * @param Ticket $ticket
     *
     * @return User
     */
    public function removeTicket(Ticket $ticket): User
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->remove($ticket);
        }

        return $this;
    }

    /**
     * @param $movie
     *
     * @return bool
     */
    public function canWatch($movie)
    {
        foreach ($this->getTickets() as $ticket) {
            if ($ticket->getMovie() === $movie && $ticket->isPaid()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Movie $movie
     *
     * @return bool
     */
    public function hasUsedTicket(Movie $movie)
    {
        foreach ($this->getTickets() as $ticket) {
            if ($ticket->getMovie() === $movie && $ticket->isUsed()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isClubRequest(): bool
    {
        return $this->clubRequest;
    }

    /**
     * @param bool $clubRequest
     *
     * @return User
     */
    public function setClubRequest(bool $clubRequest): User
    {
        $this->clubRequest = $clubRequest;

        return $this;
    }

    /**
     * @return bool
     */
    public function isClubUser(): bool
    {
        return $this->getRole() === User::ROLE_KLUBO_NARYS;
    }

    /**
     * @return bool
     */
    public function isClubOrAdmin(): bool
    {
        return in_array($this->getRole(), [User::ROLE_KLUBO_NARYS, User::ROLE_ADMIN]);
    }

    /**
     * @return bool
     */
    public function isRegularUser(): bool
    {
        return $this->getRole() === User::ROLE_USER;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
