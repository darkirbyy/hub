<?php

namespace App\Controller\Admin;

use App\Repository\Other\ShortcutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
final class MainController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ShortcutRepository $shortcutRepo): Response
    {
        $shortcutsByType = $shortcutRepo->findAndSortAndGroup();

        return $this->render('admin/index.html.twig', [
            'shortcuts_by_type' => $shortcutsByType,
        ]);
    }
}
