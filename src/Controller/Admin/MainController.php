<?php

namespace App\Controller\Admin;

use App\Repository\Param\ToolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
final class MainController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(ToolRepository $toolRepo): Response
    {
        $toolsByType = $toolRepo->findAndGroup();

        return $this->render('admin/index.html.twig', [
            'tools_by_type' => $toolsByType,
        ]);
    }
}
