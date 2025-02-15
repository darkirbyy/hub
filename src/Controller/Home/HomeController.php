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
        if ($this->isGranted('IS_AUTHENTICATED')) {
            $allowedApplis = array_unique(
                $this->getUser()
                    ->getMetaRole()
                    ->getRoles()
                    ->map(function ($role) {
                        return $role->getAppli();
                    })
                    ->toArray(),
            );
        }

        foreach ($categories as $category) {
            foreach ($category->getApplis() as $appli) {
                if (!$appli->isPublic() && !in_array($appli, $allowedApplis)) {
                    $category->removeAppli($appli);
                }
            }
        }

        // foreach($categories as $category){
        //     $category-> $category->getApplis()->filter(function($appli) use ($allowedApplis){
        //         return $appli->isPublic() || in_array($appli, $allowedApplis);
        //     });
        // }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'server_base_url' => $serverBaseUrl,
        ]);
    }
}
