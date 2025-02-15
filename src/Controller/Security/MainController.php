<?php

namespace App\Controller\Security;

use App\Form\Account\ConnectUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/security', name: 'security_')]
class MainController extends AbstractController
{
    #[Route(path: '', name: 'index')]
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED')) {
            return $this->redirectToRoute('security_profile');
        }

        // get the login error if there is one and last username entered by the user
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // create the form and pre fill the username value
        $form = $this->createForm(ConnectUserType::class);
        $form->get('username')->setData($lastUsername);

        if (!empty($error)) {
            $this->addFlash('danger', ['message' => $error->getMessageKey(), 'params' => $error->getMessageData(), 'domain' => 'security']);
        }

        return $this->render('security/login.html.twig', [
            'form' => $form,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/profile', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('security/profile.html.twig', []);
    }
}
