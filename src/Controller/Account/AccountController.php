<?php

namespace App\Controller\Account;

use App\Form\Account\AvatarUserType;
use App\Form\Account\ConnectUserType;
use App\Form\Account\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/account', name: 'account_')]
class AccountController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        if ($request->query->getBoolean('flash', false)) {
            $this->addFlash('success', ['message' => 'account.flash.avatarUpdated']);
        }

        return $this->render('account/index.html.twig', []);
    }

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/avatar', name: 'avatar', methods: ['GET', 'POST'])]
    public function avatar(Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\Account\User $user */
        $user = $this->getUser();

        $form = $this->createForm(AvatarUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            // Don't work, why ? use a query param instead...
            // $this->addFlash('success', ['message' => 'account.flash.avatarUpdated']);

            return $this->redirectToRoute('account_index', ['flash' => true], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/avatar.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/password', name: 'password', methods: ['GET', 'POST'])]
    public function password(Request $request, EntityManagerInterface $em, ?UserPasswordHasherInterface $userPasswordHasher = null): Response
    {
        /** @var \App\Entity\Account\User $user */
        $user = $this->getUser();

        $form = $this->createForm(PasswordUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', ['message' => 'account.flash.passwordUpdated']);

            return $this->redirectToRoute('account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/password.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
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
