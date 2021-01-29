<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Log
 *
 * @ORM\Entity(repositoryClass=App\Repository\LogRepository::class)
 */
class Log
{
    const STATUS_OK = 'STATUS_OK';
    const STATUS_NOK = 'STATUS_NOK';

    const TYPE_PAYSERA_CALLBACK = 'TYPE_PAYSERA_CALLBACK';
    const TYPE_PAYSERA_SUCCESS = 'TYPE_PAYSERA_SUCCESS';
    const TYPE_PAYSERA_CANCEL = 'TYPE_PAYSERA_CANCEL';
    const TYPE_PAYSERA_NEW = 'TYPE_PAYSERA_NEW';
    const TYPE_EMAIL_RESET_PASSWORD = 'TYPE_EMAIL_RESET_PASSWORD';

    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime|null
     */
    private $createdAt;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $status;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $request;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $response;

    /**
     * Log constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
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
    public function getInfo(): ?string
    {
        return $this->info;
    }

    /**
     * @param string|null $info
     *
     * @return Log
     */
    public function setInfo(?string $info): Log
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     *
     * @return Log
     */
    public function setCreatedAt(?DateTime $createdAt): Log
    {
        $this->createdAt = $createdAt;

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
     * @return Log
     */
    public function setType(?string $type): Log
    {
        $this->type = $type;

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
     * @return Log
     */
    public function setStatus(?string $status): Log
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequest(): ?string
    {
        return $this->request;
    }

    /**
     * @param string|null $request
     *
     * @return Log
     */
    public function setRequest(?string $request): Log
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }

    /**
     * @param string|null $response
     *
     * @return Log
     */
    public function setResponse(?string $response): Log
    {
        $this->response = $response;

        return $this;
    }
}
