<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Repository\Hub\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'home_')]
class MainController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepo, Request $request): Response
    {
        $categories = $categoryRepo->findAndSort();
        $serverBaseUrl = $request->getSchemeAndHttpHost();

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'server_base_url' => $serverBaseUrl,
        ]);
    }
}
