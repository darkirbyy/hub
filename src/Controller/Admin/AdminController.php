<?php

namespace App\Controller\Admin;

use App\Repository\Other\ShortcutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for the main admin pages.
 */
#[Route('/admin', name: 'admin_')]
final class AdminController extends AbstractController
{
    /**
     * Handles the index admin page, with link to all the crud and the customizable shortcuts.
     *
     * @param ShortcutRepository $shortcutRepo the repository managing the shortcuts
     *
     * @return Response the rendered main admin page
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ShortcutRepository $shortcutRepo): Response
    {
        $shortcutsByType = $shortcutRepo->findAndSortAndGroup();

        return $this->render('admin/index.html.twig', [
            'shortcuts_by_type' => $shortcutsByType,
        ]);
    }
}
