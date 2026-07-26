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
#[Route('', name: 'home_')]
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
    #[Route('/', name: 'index', methods: ['GET'])]
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

    /**
     * Check if the user is connected and has the role described in the parameter.
     *
     * @return Response a http code (403, 401, 400 or 200)
     */
    #[Route('/check', name: 'check', methods: ['GET'])]
    public function check(Request $request): Response
    {
        // User MUST be connected
        $user = $this->getUser();
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        // Parameters MUST be there and MUST be valids
        $clientParam = $request->query->get('c');
        $roleParam = $request->query->get('r');

        if (!$clientParam || !preg_match('/^[a-zA-Z0-9-]+$/', $clientParam)) {
            return new Response('Client parameter (c) is missing or invalid', Response::HTTP_BAD_REQUEST);
        }

        if (!$roleParam || !preg_match('/^[a-zA-Z0-9-]+$/', $roleParam)) {
            return new Response('Role parameter (r) is missing or invalid', Response::HTTP_BAD_REQUEST);
        }

        // User MUST have clients_roles scope in the token
        $userArray = $user->toArray();
        if (!array_key_exists('clients_roles', $userArray)) {
            return new Response('Unauthorized', Response::HTTP_FORBIDDEN);
        }

        // User MUST have the given role for the given client
        $userClientsRoles = $userArray['clients_roles'];
        if (!array_key_exists($clientParam, $userClientsRoles) || !in_array($roleParam, $userClientsRoles[$clientParam])) {
            return new Response('Forbidden', Response::HTTP_FORBIDDEN);
        }

        return new Response('OK', Response::HTTP_OK);
    }
}
