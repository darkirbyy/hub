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
        $categories = $categoryRepo->findAndSort();
        $serverBaseUrl = $request->getSchemeAndHttpHost();

        $allowedApplis = [];
        if ($this->isGranted('ROLE_USER')) {
            // Check if the user has a metaRole and if it contains roles
            $metaRole = $this->getUser()->getMetaRole();
            if ($metaRole && $metaRole->getRoles()->count() > 0) {
                // Map the roles to the associated applications
                $allowedApplis = array_unique(
                    $metaRole
                        ->getRoles()
                        ->map(function ($role) {
                            return $role->getAppli();
                        })
                        ->toArray(),
                );
            }
        }

        // Loop through categories and remove apps the user is not authorized for
        foreach ($categories as $category) {
            foreach ($category->getApplis() as $appli) {
                if (!$appli->isPublic() && !in_array($appli, $allowedApplis)) {
                    $category->removeAppli($appli);
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'server_base_url' => $serverBaseUrl,
        ]);
    }
}
