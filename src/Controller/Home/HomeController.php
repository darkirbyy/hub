<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Repository\Hub\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'home_')]
class HomeController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepo, Request $request): Response
    {
        $categories = $categoryRepo->findAll();
        $serverUri = $request->getUri();

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'server_uri' => $serverUri,
        ]);
    }
}
