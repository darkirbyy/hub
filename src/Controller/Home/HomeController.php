<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Enum\AppliStatusEnum;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
     * @param CategoryRepository $categoryRepo $shortcutRepo the repository managing the categories
     *
     * @return Response the rendered homepage
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findAndSort();
        $allowedClientsId = [];

        // Determine in which applis the connected user has at least one role
        try {
            if ($this->isGranted('ROLE_USER')) {
                $userArray = $this->getUser()->toArray();
                if (array_key_exists('clients_roles', $userArray)) {
                    $allowedClientsId = array_diff(array_keys($userArray['clients_roles']), ['account']);
                }
            }
        } catch (AuthenticationException $e) {
            $request->getSession()->invalidate();
        }

        // Loop through categories and remove applis the user is not authorized for
        foreach ($categories as $key => $category) {
            foreach ($category->getApplis() as $appli) {
                if (
                    AppliStatusEnum::PRIVATE === $appli->getStatus()
                    || (AppliStatusEnum::USER_ONLY === $appli->getStatus() && !in_array($appli->getClientId(), $allowedClientsId))
                ) {
                    $category->removeAppli($appli);
                }
            }
            if (0 == count($category->getApplis())) {
                unset($categories[$key]);
            }
        }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
