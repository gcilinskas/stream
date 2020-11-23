<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Comment
 *
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
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
     * @Groups({"api_comment"})
     */
    private $text;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @Groups({"api_comment"})
     */
    private $user;

    /**
     * @var Movie|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="comments", cascade={"remove"})
     */
    private $movie;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Groups({"api_comment"})
     */
    private $createdAt;

    /**
     * Comment constructor.
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
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     *
     * @return Comment
     */
    public function setText(?string $text): Comment
    {
        $this->text = $text;

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
     * @return Comment
     */
    public function setUser(User $user): Comment
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
     * @param Movie $movie
     *
     * @return Comment
     */
    public function setMovie(Movie $movie): Comment
    {
        $this->movie = $movie;

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
     * @return Comment
     */
    public function setCreatedAt(DateTime $createdAt): Comment
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @Groups({"api_comment"})
     * @return int
     */
    public function getCreatedAtTimestamp()
    {
        return $this->getCreatedAt()->getTimestamp();
    }
}
