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

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_user"})
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
     * @var PaymentAddress[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PaymentAddress", mappedBy="user")
     */
    private $paymentAddresses;

    /**
     * @var PayseraPayment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PayseraPayment", mappedBy="user")
     */
    private $payseraPayments;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->paymentAddresses = new ArrayCollection();
        $this->payseraPayments = new ArrayCollection();
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
     * @return PaymentAddress[]|ArrayCollection
     */
    public function getPaymentAddresses()
    {
        return $this->paymentAddresses;
    }

    /**
     * @param PaymentAddress[]|ArrayCollection $paymentAddresses
     *
     * @return User
     */
    public function setPaymentAddresses($paymentAddresses)
    {
        $this->paymentAddresses = $paymentAddresses;

        return $this;
    }

    /**
     * @param PaymentAddress $paymentAddress
     *
     * @return User
     */
    public function addPaymentAddress (PaymentAddress $paymentAddress): User
    {
        if (!$this->paymentAddresses->contains($paymentAddress)) {
            $this->paymentAddresses->add($paymentAddress);
        }

        return $this;
    }

    /**
     * @param PaymentAddress $paymentAddress
     *
     * @return User
     */
    public function removePaymentAddress (PaymentAddress $paymentAddress): User
    {
        if ($this->paymentAddresses->contains($paymentAddress)) {
            $this->paymentAddresses->remove($paymentAddress);
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
}
