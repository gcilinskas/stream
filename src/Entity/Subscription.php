<?php

namespace App\Entity;

use App\Entity\Traits\TimeEntityTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Subscription
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    use TimeEntityTrait;

    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User|null
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="subscription", cascade={"persist"})
     */
    private $user;

    /**
     * @var PayseraPayment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PayseraPayment", mappedBy="subscription")
     */
    private $payseraPayments;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $validFrom;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $validTo;

    /**
     * Subscription constructor.
     */
    public function __construct()
    {
        $this->updatedAt = new DateTime();
        $this->createdAt = new DateTime();
        $this->payseraPayments = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return Subscription
     */
    public function setId(?int $id): Subscription
    {
        $this->id = $id;

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
     * @return Subscription
     */
    public function setUser(?User $user): Subscription
    {
        $this->user = $user;

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
     * @return Subscription
     */
    public function setPayseraPayments($payseraPayments)
    {
        $this->payseraPayments = $payseraPayments;

        return $this;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return Subscription
     */
    public function addPayseraPayment(PayseraPayment $payseraPayment): Subscription
    {
        if (!$this->payseraPayments->contains($payseraPayment)) {
            $this->payseraPayments->add($payseraPayment);
        }

        return $this;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return Subscription
     */
    public function removePayseraPayment(PayseraPayment $payseraPayment): Subscription
    {
        if ($this->payseraPayments->contains($payseraPayment)) {
            $this->payseraPayments->remove($payseraPayment);
        }

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getValidFrom(): ?DateTime
    {
        return $this->validFrom;
    }

    /**
     * @param DateTime $validFrom
     *
     * @return Subscription
     */
    public function setValidFrom(DateTime $validFrom): Subscription
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getValidTo(): ?DateTime
    {
        return $this->validTo;
    }

    /**
     * @param DateTime $validTo
     *
     * @return Subscription
     */
    public function setValidTo(DateTime $validTo): Subscription
    {
        $this->validTo = $validTo;

        return $this;
    }
}
