<?php

declare(strict_types=1);

namespace App\Controller\Default;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findAll();

        return $this->render('default/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
