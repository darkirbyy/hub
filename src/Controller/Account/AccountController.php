<?php

namespace App\Controller\Account;

use App\Form\Account\ConnectUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/account', name: 'account_')]
class AccountController extends AbstractController
{
    #[Route(path: '', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED')) {
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/profile.html.twig', []);
    }

    #[Route(path: '/login', name: 'login',  methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED')) {
            return $this->redirectToRoute('account_index');
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

        return $this->render('account/login.html.twig', [
            'form' => $form,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'logout', methods: ['GET', 'POST'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
