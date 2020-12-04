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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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
     * CommentController constructor.
     *
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
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

            return $this->json(
                ['movie' => $comment->getMovie(), 'comment' => $comment],
                Response::HTTP_OK,
                [],
                [ObjectNormalizer::GROUPS => ['api_comment'],
            ]);
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
        $comments = $this->commentService->getBy(['movie' => $movie], ['createdAt' => 'ASC']);

        return $this->json($comments, Response::HTTP_OK, [], [ObjectNormalizer::GROUPS => ['api_comment']]);
    }
}
