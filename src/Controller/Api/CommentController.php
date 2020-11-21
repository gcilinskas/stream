<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Form\Api\Comment\CreateType;
use App\Service\CommentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CommentController
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * CommentController constructor.
     *
     * @param CommentService $commentService
     * @param SerializerInterface $serializer
     */
    public function __construct(CommentService $commentService, SerializerInterface $serializer)
    {
        $this->commentService = $commentService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/new")
     * @param Request $request
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function new(Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CreateType::class, $comment);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $this->commentService->update($comment);

            $json = $this->serializer->serialize($comment, 'json', ['groups' => ['api_comment', 'api_user']]);

            return $this->json($json);
        }


        return $this->json(['errors' => $form->getErrors(true)], 400);
    }

    /**
     * @Route("/movie/{movie}")
     * @param Movie $movie
     *
     * @return JsonResponse
     */
    public function movieComments(Movie $movie)
    {
        return $this->json($this->commentService->getBy(['movie' => $movie]));
    }
}
