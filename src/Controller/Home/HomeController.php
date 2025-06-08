<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Entity\Hub\Right;
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
     * adapting the visible applis for an authenticated user depending on its rights.
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

        // Determine in which applis the connected user has at least one right
        $allowedApplis = [];
        if ($this->isGranted('ROLE_HUB_USER')) {
            /** @var \App\Entity\Account\User $user */
            $user = $this->getUser();
            $allowedApplis = array_unique(array_map(fn (Right $r) => $r->getAppli(), $user->getRights()->toArray()));
        }

        // Loop through categories and remove applis the user is not authorized for
        foreach ($categories as $key => $category) {
            foreach ($category->getApplis() as $appli) {
                if (AppliStatusEnum::PRIVATE === $appli->getStatus() || (AppliStatusEnum::USERONLY === $appli->getStatus() && !in_array($appli, $allowedApplis))) {
                    $category->removeAppli($appli);
                }
            }
            if (0 == count($category->getApplis())) {
                unset($categories[$key]);
            }
        }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'server_base_url' => $serverBaseUrl,
        ]);
    }
}
