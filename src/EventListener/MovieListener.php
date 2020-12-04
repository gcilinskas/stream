<?php

namespace App\EventListener;

use App\Entity\Movie;
use App\Factory\PriceFactory;
use App\Service\FileService;
use App\Service\PriceService;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MovieListener
 */
class MovieListener implements EventSubscriberInterface
{
    /**
     * @var PriceService
     */
    private $priceService;

    /**
     * @var PriceFactory
     */
    private $priceFactory;

    /**
     * @var int|null
     */
    private $regularPrice;

    /**
     * @var int|null
     */
    private $clubPrice;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * MovieListener constructor.
     *
     * @param PriceService $priceService
     * @param PriceFactory $priceFactory
     * @param FileService $fileService
     */
    public function __construct(PriceService $priceService, PriceFactory $priceFactory, FileService $fileService)
    {
        $this->priceService = $priceService;
        $this->priceFactory = $priceFactory;
        $this->fileService = $fileService;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'postSubmit',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        if (isset($event->getData()['price'])) {
            $this->regularPrice = $event->getData()['price'];
        }

        if (isset($event->getData()['clubPrice'])) {
            $this->clubPrice = $event->getData()['clubPrice'];
        }
    }

    /**
     * @param FormEvent $event
     *
     * @throws Exception
     */
    public function postSubmit(FormEvent $event)
    {
        /** @var Movie $movie */
        $movie = $event->getData();

        if (!empty($this->regularPrice)) {
            $regularPriceInCents = (int)(round($this->regularPrice * 100));
            $this->priceFactory->createRegularPrice($movie, $regularPriceInCents);
        }

        if (!empty($this->clubPrice)) {
            $clubPriceInCents = (int)(round($this->clubPrice * 100));
            $this->priceFactory->createClubPrice($movie, $clubPriceInCents);
        }

        if ($movie->getMovieFile()) {
            $movieFilename = $this->fileService->upload($movie->getMovieFile());
            $movie->setMovie($movieFilename);
        }

        if ($movie->getImageFile()) {
            $movieImageFilename = $this->fileService->uploadImage($movie->getImageFile());
            $movie->setImage($movieImageFilename);
        }
    }
}
