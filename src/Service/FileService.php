<?php

namespace App\Service;

use App\Entity\Movie;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class FileService
 */
class FileService
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * @var string
     */
    private $moviesImagesDir;

    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * FileService constructor.
     *
     * @param string $targetDirectory
     * @param string $moviesImagesDir
     * @param SluggerInterface $slugger
     * @param MovieService $movieService
     */
    public function __construct(
        string $targetDirectory,
        string $moviesImagesDir,
        SluggerInterface $slugger,
        MovieService $movieService
    ) {
        $this->targetDirectory = $targetDirectory;
        $this->moviesImagesDir = $moviesImagesDir;
        $this->slugger = $slugger;
        $this->movieService = $movieService;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function uploadImage(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $file->move($this->moviesImagesDir, $fileName);

        return $fileName;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$ext;
        $file->move($this->targetDirectory, $fileName);

        return $fileName;
    }

    public function uploadSubtitles(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $ext;
        $file->move($this->targetDirectory, $fileName);

        return $fileName;
    }

    /**
     * @param Movie $movie
     * @param bool $flush
     *
     * @return Movie
     * @throws Exception
     */
    public function removeMovieFiles(Movie $movie, bool $flush = true): Movie
    {
        $item = $this->removeMovieFile($movie, $flush);
        $item = $this->removeMovieImage($movie, $flush);
        $item = $this->removeSubtitleFile($movie, $flush);

        return $item;
    }

    /**
     * @param Movie $movie
     * @param bool $flush
     *
     * @return Movie
     * @throws Exception
     */
    public function removeMovieFile(Movie $movie, bool $flush = true): Movie
    {
        $filesystem = new Filesystem();

        if ($movie->getMovie()) {
            $filesystem->remove($this->targetDirectory. '/'. $movie->getMovie());
            $movie->setMovie(null);
        }

        return $this->movieService->update($movie, $flush);
    }

    /**
     * @param Movie $movie
     * @param bool $flush
     *
     * @return Movie
     * @throws Exception
     */
    public function removeMovieImage(Movie $movie, bool $flush = true): Movie
    {
        $filesystem = new Filesystem();

        if ($movie->getImage()) {
            $filesystem->remove($this->moviesImagesDir . '/' . $movie->getImage());
            $movie->setImage(null);
        }

        return $this->movieService->update($movie, $flush);
    }

    /**
     * @param Movie $movie
     * @param bool $flush
     *
     * @return Movie
     * @throws Exception
     */
    public function removeSubtitleFile(Movie $movie, bool $flush = true): Movie
    {
        $filesystem = new Filesystem();

        if ($movie->getSubtitles()) {
            $filesystem->remove($this->targetDirectory. '/'. $movie->getSubtitles());
            $movie->setSubtitles(null);
        }

        return $this->movieService->update($movie, $flush);
    }
}
