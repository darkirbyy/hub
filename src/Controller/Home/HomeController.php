<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Entity\Account\Role;
use App\Enum\AppliStatusEnum;
use App\Repository\Hub\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for all the homepage of the server, that any visitor can see.
 */
#[Route('/', name: 'home_')]
class HomeController extends AbstractController
{
    /**
     * Displays the homepage with public applis for any visitor,
     * adapting the visible applis for an authenticated user depending on its roles.
     *
     * @param Request            $request      the HTTP request instance
     * @param CategoryRepository $categoryRepo $shortcutRepo the repository managing the categories
     *
     * @return Response the rendered homepage
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findAndSort();
        $serverBaseUrl = $request->getSchemeAndHttpHost();

        // Determines in which applis the user has at least one role
        $allowedApplis = [];
        if ($this->isGranted('ROLE_USER')) {
            // Check if the user has a metaRole and if it contains roles
            $metaRole = $this->getUser()->getMetaRole();
            if ($metaRole && $metaRole->getRoles()->count() > 0) {
                $allowedApplis = array_unique($metaRole->getRoles()->map(fn (Role $role) => $role->getAppli())->toArray());
            }
        }

        // Loop through categories and remove applis the user is not authorized for
        foreach ($categories as $category) {
            foreach ($category->getApplis() as $appli) {
                if (AppliStatusEnum::PRIVATE === $appli->getStatus() || (AppliStatusEnum::USERONLY === $appli->getStatus() && !in_array($appli, $allowedApplis))) {
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
