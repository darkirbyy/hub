<?php

namespace App\Controller\Admin;

use App\Repository\Param\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
final class MainController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(StatusRepository $statusRepo): Response
    {
        $statuses = $statusRepo->findByOrder();

        return $this->render('admin/index.html.twig', [
            'statuses' => $statuses,
        ]);
    }
}
