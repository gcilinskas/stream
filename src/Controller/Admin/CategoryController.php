<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Admin\Category\CreateType;
use App\Service\CategoryService;
use App\Service\MovieService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService
     * @param MovieService $movieService
     */
    public function __construct(CategoryService $categoryService, MovieService $movieService)
    {
        $this->categoryService = $categoryService;
        $this->movieService = $movieService;
    }

    /**
     * @Route("/add", name="admin_category_add")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CreateType::class, $category);

        if ($request->getMethod() === "POST") {
            $form->submit($request->request->all());
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $this->categoryService->create($category);

                    return $this->redirectToRoute('admin_category_list');
                } catch (Exception $e) {
                    $errors = $e->getMessage();
                }
            } else {
                $errors = $form->getErrors(true);
            }
        }

        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
            'errors' => isset($errors) ? $errors : []
        ]);
    }

    /**
     * @Route("/edit/{category}", name="admin_category_edit")
     * @param Request $request
     * @param Category $category
     *
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Category $category)
    {
        $form = $this->createForm(CreateType::class, $category);

        if ($request->getMethod() === "POST") {
            $form->submit($request->request->all());
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $this->categoryService->create($category);

                    return $this->redirectToRoute('admin_category_list');
                } catch (Exception $e) {
                    $errors = $e->getMessage();
                }
            } else {
                $errors = $form->getErrors(true);
            }
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
            'errors' => isset($errors) ? $errors : [],
            'category' => $category
        ]);
    }

    /**
     * @Route("/delete/{category}", name="admin_category_delete")
     * @param Category $category
     *
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function delete(Category $category)
    {
        $this->categoryService->delete($category);

        return $this->redirectToRoute('admin_category_list');
    }

    /**
     * @Route("/list", name="admin_category_list")
     */
    public function list()
    {
        return $this->render('admin/category/list.html.twig', [
            'categories' => $this->categoryService->getAll(),
        ]);
    }
}
