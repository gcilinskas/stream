<?php

namespace App\Entity;

use App\Repository\PaymentAddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PaymentAddress
 * @ORM\Entity(repositoryClass=App\Repository\PaymentAddressRepository::class)
 */
class PaymentAddress
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="paymentAddresses")
     */
    private $user;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $personCode;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $country;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $zipCode;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $iban;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $surname;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $termsAccepted;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $gdprAccepted;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $bank;

    /**
     * @var PayseraPayment[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PayseraPayment", mappedBy="paymentAddress")
     */
    private $payseraPayments;

    /**
     * PaymentAddress constructor.
     */
    public function __construct()
    {
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
     * @return PaymentAddress
     */
    public function setId(?int $id): PaymentAddress
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return PaymentAddress
     */
    public function setUser(User $user): PaymentAddress
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPersonCode(): ?int
    {
        return $this->personCode;
    }

    /**
     * @param int|null $personCode
     *
     * @return PaymentAddress
     */
    public function setPersonCode(?int $personCode): PaymentAddress
    {
        $this->personCode = $personCode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPhone(): ?int
    {
        return $this->phone;
    }

    /**
     * @param int|null $phone
     *
     * @return PaymentAddress
     */
    public function setPhone(?int $phone): PaymentAddress
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     *
     * @return PaymentAddress
     */
    public function setCountry(?string $country): PaymentAddress
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     *
     * @return PaymentAddress
     */
    public function setCity(?string $city): PaymentAddress
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string|null $zipCode
     *
     * @return PaymentAddress
     */
    public function setZipCode(?string $zipCode): PaymentAddress
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

    /**
     * @param string|null $iban
     *
     * @return PaymentAddress
     */
    public function setIban(?string $iban): PaymentAddress
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return PaymentAddress
     */
    public function setName(?string $name): PaymentAddress
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string|null $surname
     *
     * @return PaymentAddress
     */
    public function setSurname(?string $surname): PaymentAddress
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTermsAccepted(): bool
    {
        return $this->termsAccepted;
    }

    /**
     * @param bool $termsAccepted
     *
     * @return PaymentAddress
     */
    public function setTermsAccepted(bool $termsAccepted): PaymentAddress
    {
        $this->termsAccepted = $termsAccepted;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGdprAccepted(): bool
    {
        return $this->gdprAccepted;
    }

    /**
     * @param bool $gdprAccepted
     *
     * @return PaymentAddress
     */
    public function setGdprAccepted(bool $gdprAccepted): PaymentAddress
    {
        $this->gdprAccepted = $gdprAccepted;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBank(): ?string
    {
        return $this->bank;
    }

    /**
     * @param string|null $bank
     *
     * @return PaymentAddress
     */
    public function setBank(?string $bank): PaymentAddress
    {
        $this->bank = $bank;

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
     * @return PaymentAddress
     */
    public function setPayseraPayments($payseraPayments)
    {
        $this->payseraPayments = $payseraPayments;

        return $this;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return PaymentAddress
     */
    public function addPayseraPayment (PayseraPayment $payseraPayment): PaymentAddress
    {
        if (!$this->payseraPayments->contains($payseraPayment)) {
            $this->payseraPayments->add($payseraPayment);
        }

        return $this;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return PaymentAddress
     */
    public function removePayseraPayment (PayseraPayment $payseraPayment): PaymentAddress
    {
        if ($this->payseraPayments->contains($payseraPayment)) {
            $this->payseraPayments->remove($payseraPayment);
        }

        return $this;
    }
}
