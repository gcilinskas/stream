<?php

namespace App\Service;

use App\Entity\Movie;
use App\Repository\MovieRepository;
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
    public function getAllOrdered()
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

        try {
            parent::remove($movie);

        } catch (Exception $e)  {
            var_dump($e->getMessage());
            die();
        }
    }
}
