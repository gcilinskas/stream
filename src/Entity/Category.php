<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 *
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var ArrayCollection|Movie[]
     * @ORM\OneToMany(targetEntity="App\Entity\Movie", mappedBy="category")
     */
    private $movies;

    /**
     * Category constructor
     */
    public function __construct()
    {
        $this->movies = new ArrayCollection();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return Category
     */
    public function setTitle(?string $title): Category
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Movie[]|ArrayCollection
     */
    public function getMovies()
    {
        return $this->movies;
    }

    /**
     * @param Movie[]|ArrayCollection $movies
     *
     * @return Category
     */
    public function setMovies($movies): Category
    {
        $this->movies = $movies;

        return $this;
    }

    /**
     * @param Movie $movie
     *
     * @return $this
     */
    public function addMovie(Movie $movie): Category
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
        }

        return $this;
    }

    /**
     * @param Movie $movie
     *
     * @return Category
     */
    public function removeMovie(Movie $movie): Category
    {
        if ($this->movies->contains($movie)) {
            $this->movies->remove($movie);
        }

        return $this;
    }
}
