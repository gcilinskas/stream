<?php

namespace App\Service;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class MovieService
 */
class MovieService extends BaseService
{
    /**
     * @var MovieRepository
     */
    protected $repository;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * MovieService constructor.
     *
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher
    ) {
        parent::__construct($em, $dispatcher);
    }

    /**
     * @required
     * @param FileService $fileService
     */
    public function setFileService(FileService $fileService): void
    {
        $this->fileService = $fileService;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Movie::class;
    }

    /**
     * @return Movie[]|null
     */
    public function getAllOrdered(): ?array
    {
        return $this->repository->findAllOrdered();
    }

    /**
     * @param Movie $movie
     *
     * @throws Exception
     */
    public function delete(Movie $movie)
    {
        $this->fileService->removeMovieFiles($movie);

        parent::remove($movie);
    }

    /**
     * @param Movie $movie
     *
     * @throws Exception
     */
    public function softDelete(Movie $movie)
    {
        $this->fileService->removeMovieFiles($movie);
        $movie->setDeletedAt(new DateTime());

        $this->update($movie);
    }

    /**
     * @return Movie|null
     */
    public function getNewestMovie(): ?Movie
    {
        $movies = $this->getAllOrdered();

        return reset($movies) ? reset($movies) : null;
    }

    /**
     * @return Movie[]|null
     */
    public function getNewestMovies(): ?array
    {
        return $this->repository->findNewestMovies();
    }

    /**
     * @return array
     */
    public function getMoviesWithCategories(): array
    {
        $moviesWithCategories = [];
        foreach ($this->repository->findWithCategories() as $movieWithCategory) {
            $moviesWithCategories[$movieWithCategory['categoryId']]['movies'][] = $movieWithCategory['movie'];
            $moviesWithCategories[$movieWithCategory['categoryId']]['category'] = $movieWithCategory['categoryTitle'];
        }

        return $moviesWithCategories;
    }

    /**
     * @return Movie[]|array|null
     */
    public function getAllNotDeleted(): ?array
    {
        return $this->repository->findAllNotDeleted();
    }
}
